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

        // Get all service requests with resident information and payment
        $requests = ServiceRequest::with(['resident', 'payment'])
            ->orderBy('request_date', 'desc')
            ->get();

        \Log::info('Loaded requests count: ' . $requests->count());
        foreach ($requests as $request) {
            \Log::info("Request ID: {$request->request_id}, Status: {$request->status}, Payment: " . ($request->payment ? $request->payment->status : 'No payment'));
        }

        return view('admin.manage-requests', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            \Log::info('=== UPDATE STATUS START ===');
            \Log::info('Request ID: ' . $id);
            \Log::info('Request data:', $request->all());
            
            $validated = $request->validate([
                'status' => 'required|in:pending,under-review,waiting-payment,approved,processing,completed,declined',
                'remarks' => 'nullable|string',
                'fee' => 'nullable|numeric|min:0',
                'update_payment' => 'nullable|boolean'
            ]);

            // Find service request using request_id (not id)
            $serviceRequest = ServiceRequest::where('request_id', $id)->first();
            
            if (!$serviceRequest) {
                \Log::error('Service request not found with request_id: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Service request not found'
                ], 404);
            }

            \Log::info('Service Request Found:', [
                'request_id' => $serviceRequest->request_id,
                'id' => $serviceRequest->id,
                'resident_id' => $serviceRequest->resident_id,
                'current_status' => $serviceRequest->status,
                'new_status' => $validated['status']
            ]);

            // Update service request status AND DATE COLUMNS
            $oldStatus = $serviceRequest->status;
            $serviceRequest->status = $validated['status'];
            $serviceRequest->remarks = $validated['remarks'] ?? $serviceRequest->remarks;
            
            // UPDATE THE DATE COLUMNS BASED ON STATUS
            $now = now();
            switch ($validated['status']) {
                case 'approved': // Add this case
                    $serviceRequest->approved_date = $now;
                    break;
                case 'processing':
                    $serviceRequest->processing_date = $now;
                    break;
                case 'completed':
                    $serviceRequest->completed_date = $now;
                    break;
                case 'declined':
                    $serviceRequest->declined_date = $now;
                    break;
            }
                    
            $serviceRequest->save();

            \Log::info('Service request updated from "'.$oldStatus.'" to: ' . $validated['status']);

            // PAYMENT HANDLING LOGIC
            $this->handlePaymentUpdates($serviceRequest, $validated);

            \Log::info('=== UPDATE STATUS SUCCESS ===');

            return response()->json([
                'success' => true, 
                'message' => 'Status updated successfully',
                'new_status' => $validated['status']
            ]);

        } catch (\Exception $e) {
            \Log::error('=== UPDATE STATUS FAILED ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment creation and updates based on request status
     */
    private function handlePaymentUpdates($serviceRequest, $validated)
    {
        $status = $validated['status'];
        $fee = $validated['fee'] ?? 100; // Default fee

        \Log::info('Handling payment updates for status: ' . $status);

        // Check if payment already exists for this request
        $existingPayment = Payments::where('request_id', $serviceRequest->request_id)->first();

        switch ($status) {
            case 'waiting-payment':
                if (!$existingPayment) {
                    \Log::info('Creating new payment record for waiting-payment status');
                    
                    $paymentData = [
                        'resident_id' => $serviceRequest->resident_id,
                        'request_id' => $serviceRequest->request_id, // Use request_id, not id
                        'fee' => $fee,
                        'amount' => $fee,
                        'status' => 'pending',
                        'payment_method' => 'cash',
                        'notes' => 'Processing fee for ' . $serviceRequest->request_type,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    \Log::info('Payment data to create:', $paymentData);
                    
                    $paymentId = DB::table('payments')->insertGetId($paymentData);
                    \Log::info('New payment created with ID: ' . $paymentId);
                } else {
                    \Log::info('Payment already exists, updating status to pending');
                    $existingPayment->update([
                        'status' => 'pending',
                        'fee' => $fee,
                        'amount' => $fee,
                        'updated_at' => now()
                    ]);
                }
                break;

            case 'processing':
                \Log::info('Updating payment status to paid for processing status');
                if ($existingPayment) {
                    $existingPayment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'updated_at' => now()
                    ]);
                    \Log::info('Payment updated to paid for request: ' . $serviceRequest->request_id);
                } else {
                    \Log::warning('No payment found to update for processing status');
                    // Create payment if it doesn't exist but status is processing
                    $paymentData = [
                        'resident_id' => $serviceRequest->resident_id,
                        'request_id' => $serviceRequest->request_id,
                        'fee' => $fee,
                        'amount' => $fee,
                        'status' => 'paid',
                        'payment_method' => 'cash',
                        'paid_at' => now(),
                        'notes' => 'Processing fee for ' . $serviceRequest->request_type . ' - Auto created',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    $paymentId = DB::table('payments')->insertGetId($paymentData);
                    \Log::info('Auto-created paid payment with ID: ' . $paymentId);
                }
                break;

            case 'completed':
                \Log::info('Checking payment for completed status');
                if ($existingPayment) {
                    // If payment exists and is still pending, mark it as paid
                    if ($existingPayment->status === 'pending') {
                        $existingPayment->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'updated_at' => now()
                        ]);
                        \Log::info('Payment auto-updated to paid for completed request: ' . $serviceRequest->request_id);
                    }
                } else {
                    \Log::info('No payment record found for completed request');
                    // Optionally create a paid payment record for completed requests without payment
                    if ($validated['update_payment'] ?? false) {
                        $paymentData = [
                            'resident_id' => $serviceRequest->resident_id,
                            'request_id' => $serviceRequest->request_id,
                            'fee' => 0, // No fee for free services
                            'amount' => 0,
                            'status' => 'paid',
                            'payment_method' => 'free',
                            'paid_at' => now(),
                            'notes' => 'No fee required for ' . $serviceRequest->request_type,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        
                        $paymentId = DB::table('payments')->insertGetId($paymentData);
                        \Log::info('Created free payment record for completed request: ' . $paymentId);
                    }
                }
                break;

            case 'declined':
                \Log::info('Updating payment status for declined request');
                if ($existingPayment) {
                    $existingPayment->update([
                        'status' => 'cancelled',
                        'updated_at' => now()
                    ]);
                    \Log::info('Payment cancelled for declined request: ' . $serviceRequest->request_id);
                }
                break;

            case 'approved':
                \Log::info('Request approved, no payment action needed yet');
                // You can add payment creation here if needed, or leave it for waiting-payment
                break;

            default:
                \Log::info('No payment updates needed for status: ' . $status);
                break;
        }
    }

    public function getRequestDetails($id)
    {
        try {
            // Use request_id to find the request
            $request = ServiceRequest::with(['resident', 'documents', 'payment'])
                ->where('request_id', $id)
                ->firstOrFail();

            \Log::info('Request details loaded:', [
                'request_id' => $request->request_id,
                'status' => $request->status,
                'has_payment' => !is_null($request->payment),
                'payment_status' => $request->payment ? $request->payment->status : 'none'
            ]);

            return response()->json([
                'success' => true,
                'request' => $request,
                'documents' => $request->documents,
                'payment' => $request->payment
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting request details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading request details'
            ], 500);
        }
    }

    public function getPaymentDetails($id)
    {
        try {
            // Use request_id to find payment
            $payment = Payments::where('request_id', $id)->first();
            
            \Log::info('Payment details lookup:', [
                'request_id' => $id,
                'payment_found' => !is_null($payment),
                'payment_status' => $payment ? $payment->status : 'not found'
            ]);
            
            return response()->json([
                'success' => true,
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting payment details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading payment details'
            ], 500);
        }
    }
}