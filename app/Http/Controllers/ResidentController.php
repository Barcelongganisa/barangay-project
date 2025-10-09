<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\ServiceRequest;
use App\Models\RequiredDocument;
use App\Models\BarangayResident;
use App\Models\User; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    // ✅ Upload required document for an existing request
    public function uploadRequiredDocument(Request $request, $requestId)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $serviceRequest = ServiceRequest::findOrFail($requestId);

        $filePath = $request->file('file')->store('required_documents', 'public');

        $serviceRequest->requiredDocuments()->create([
            'document_type' => $request->document_type,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Document uploaded successfully!');
    }

    public function storeRequestWithDocuments(Request $request)
    {
        try {
            \Log::info('Received request data:', $request->all());

            // Validate
            $validated = $request->validate([
                'service_type' => 'required|string',
                'purpose' => 'required|string',
                'documents' => 'required|array|min:1',
                'documents.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'documents.*.document_type' => 'required|string|max:100',
            ]);

            // Service mapping
            $serviceMapping = [
                'clearance' => 'Barangay Clearance',
                'residency' => 'Barangay Certificate of Residency', 
                'indigency' => 'Barangay Certificate of Indigency',
                'business' => 'Barangay Business Clearance',
                'id' => 'Barangay ID',
                'other' => 'Other Request',
            ];

            $requestType = $serviceMapping[$request->service_type] ?? null;
            if (!$requestType) {
                return response()->json(['error' => 'Invalid service selected.'], 422);
            }

            // ✅ Get the logged-in resident
            $user = auth()->user();
            $resident = \App\Models\BarangayResident::where('email', $user->email)->first();
            
            if (!$resident) {
                return response()->json(['error' => 'Resident profile not found.'], 422);
            }

            $residentId = $resident->resident_id;

            // ✅ Use a transaction so both insertions succeed or fail together
            DB::transaction(function () use ($request, $validated, $residentId, $requestType) {

                // 1️⃣ Create the main service request
                $serviceRequest = ServiceRequest::create([
                    'resident_id' => $residentId,
                    'request_type' => $requestType,
                    'remarks' => $request->purpose,
                    'status' => 'pending',
                    'request_date' => now(),
                    'updated_at' => now(),
                ]);

                \Log::info('Created request ID: ' . $serviceRequest->request_id);

                // 2️⃣ Attach uploaded documents directly via relationship
                foreach ($request->documents as $docData) {
                    if (!isset($docData['file']) || !$docData['file']->isValid()) continue;

                    $path = $docData['file']->store('required_documents', 'public');

                    $serviceRequest->requiredDocuments()->create([
                        'document_type' => $docData['document_type'],
                        'file_path' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Request submitted successfully!',
            ]);

        } catch (\Exception $e) {
            \Log::error('Submission error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function downloadDocument($id)
    {
        try {
            // ✅ FIX: Get actual resident ID
            $user = auth()->user();
            $resident = \App\Models\BarangayResident::where('email', $user->email)->first();
            
            if (!$resident) {
                return response()->json(['error' => 'Resident profile not found'], 404);
            }

            $residentId = $resident->resident_id;
            
            // Verify the request belongs to the resident and is completed
            $request = DB::table('service_requests')
                ->where('request_id', $id)
                ->where('resident_id', $residentId)
                ->where('status', 'completed')
                ->first();
                
            if (!$request) {
                return response()->json(['error' => 'Request not found or not completed'], 404);
            }

            // Get resident information
            $resident = DB::table('barangay_residents')
                ->where('resident_id', $residentId)
                ->first();

            // Generate file name
            $fileName = str_replace(' ', '_', $request->request_type) . '_Certificate_' . $request->request_id . '.pdf';
            
            // Generate PDF
            $pdf = PDF::loadView('pdf.certificate', [
                'request' => $request,
                'resident' => $resident
            ])->setPaper('a4', 'portrait');
            
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            \Log::error('Download error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate certificate: ' . $e->getMessage()], 500);
        }
    }

    public function showRequestDetails($id)
    {
        try {
            // ✅ FIX: Get actual resident ID
            $user = auth()->user();
            $resident = \App\Models\BarangayResident::where('email', $user->email)->first();
            
            if (!$resident) {
                return response()->json(['error' => 'Resident profile not found'], 404);
            }

            $residentId = $resident->resident_id;
            
            $request = DB::table('service_requests')
                ->where('request_id', $id)
                ->where('resident_id', $residentId)
                ->first();
                
            if (!$request) {
                return response()->json(['error' => 'Request not found'], 404);
            }
            
            // Get uploaded documents
            $documents = DB::table('required_documents')
                ->where('request_id', $id)
                ->get();
                
            return response()->json([
                'success' => true,
                'request' => $request,
                'documents' => $documents
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load request details'], 500);
        }
    }

    public function dashboard()
    {
        $user = auth()->user();
        $resident = \App\Models\BarangayResident::where('email', $user->email)->first();

        if (!$resident) {
            abort(403, 'Resident profile not found');
        }

        $residentId = $resident->resident_id;

        // Fetch all requests
        $requests = DB::table('service_requests')
            ->where('resident_id', $residentId)
            ->orderBy('request_date', 'desc')
            ->get();

        // Stats
        $totalRequests = $requests->count();
        $completedRequests = $requests->where('status', 'completed')->count();
        $pendingRequests = $requests->where('status', 'pending')->count();
        $processingRequests = $requests->where('status', 'processing')->count();
        $recentRequests = $requests->take(4);

        return view('resident-dashboard', compact(
            'totalRequests',
            'completedRequests',
            'pendingRequests',
            'processingRequests',
            'recentRequests'
        ));
    }

}