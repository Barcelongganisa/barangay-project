<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\BarangayResident;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageRequestsController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Get all service requests with resident information
        $requests = ServiceRequest::with('resident')
            ->orderBy('request_date', 'desc')
            ->get();

        return view('admin.manage-requests', compact('requests'));
    }

public function updateStatus(Request $request, $id)
{
    try {
        \Log::info('=== UPDATE STATUS START ===');
        
        $validated = $request->validate([
            'status' => 'required|in:pending,under-review,waiting-payment,processing,completed,declined',
            'remarks' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0'
        ]);

        $serviceRequest = ServiceRequest::findOrFail($id);
        
        \Log::info('Service Request:', [
            'id' => $serviceRequest->id,
            'resident_id' => $serviceRequest->resident_id,
            'current_status' => $serviceRequest->status
        ]);

        // Update service request
        $serviceRequest->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? $serviceRequest->remarks
        ]);

        \Log::info('Service request updated to: ' . $validated['status']);

        // PAYMENT HANDLING - SIMPLE AND DIRECT
        if ($validated['status'] === 'waiting-payment') {
            $fee = $validated['fee'] ?? 100;
            
            \Log::info('Creating payment record...');
            
            // Simple direct creation
            $paymentData = [
                'resident_id' => $serviceRequest->resident_id,
                'request_id' => $serviceRequest->id,
                'fee' => $fee,
                'amount' => $fee,
                'status' => 'pending',
                'payment_method' => 'cash',
                'notes' => 'Processing fee for ' . $serviceRequest->request_type,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            \Log::info('Payment data:', $paymentData);
            
            // Use DB facade to avoid any model issues
            $paymentId = \DB::table('payments')->insertGetId($paymentData);
            
            \Log::info('Payment created with ID: ' . $paymentId);
        }

        // If status is 'processing', update payment status to paid
        if ($validated['status'] === 'processing') {
            \Log::info('Updating payment status to paid');
            
            $updated = \DB::table('payments')
                ->where('request_id', $serviceRequest->id)
                ->update(['status' => 'paid', 'updated_at' => now()]);
                
            \Log::info('Payments updated: ' . $updated);
        }

        \Log::info('=== UPDATE STATUS SUCCESS ===');

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully'
        ]);

    } catch (\Exception $e) {
        \Log::error('=== UPDATE STATUS FAILED ===');
        \Log::error('Error: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
        \Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    public function getRequestDetails($id)
    {
        $request = ServiceRequest::with(['resident', 'documents'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'request' => $request,
            'documents' => $request->documents
        ]);
    }

    // Add this new method for payment details
    public function getPaymentDetails($id)
    {
        $payment = Payment::where('request_id', $id)->first();
        
        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }
}