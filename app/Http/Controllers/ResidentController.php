<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\ServiceRequest;
use App\Models\RequiredDocument;
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

            // Validate the request
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

            // 🔹 TEMPORARY FIX: Use resident_id = 1 since that's the only one that exists
            $residentId = 1;

            // Create Service Request
            $serviceRequest = ServiceRequest::create([
                'resident_id' => $residentId,
                'request_type' => $requestType,
                'remarks' => $request->purpose,
                'status' => 'pending',
                'request_date' => now(),
                'updated_at' => now(),
            ]);

            \Log::info('Service request created with ID: ' . $serviceRequest->request_id);

            // Save documents
            foreach ($request->documents as $index => $documentData) {
                if (isset($documentData['file']) && $documentData['file']->isValid()) {
                    $file = $documentData['file'];
                    $filePath = $file->store('required_documents', 'public');

                    \DB::table('required_documents')->insert([
                        'request_id' => $serviceRequest->request_id,
                        'document_type' => $documentData['document_type'],
                        'file_path' => $filePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Request submitted successfully!',
                'request_id' => $serviceRequest->request_id,
            ]);

        } catch (\Exception $e) {
            \Log::error('Submission error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

     public function downloadDocument($id)
    {
        try {
            $residentId = auth()->user()->resident_id ?? 1;
            
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
            $residentId = auth()->user()->resident_id ?? 1;
            
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
}
