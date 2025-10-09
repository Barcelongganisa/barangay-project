<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\BarangayResident;
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
        $validated = $request->validate([
            'status' => 'required|in:pending,under-review,waiting-payment,processing,completed,declined',
            'remarks' => 'nullable|string'
        ]);

        $serviceRequest = ServiceRequest::findOrFail($id);
        $serviceRequest->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? $serviceRequest->remarks
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
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

         dd($request->documents);
    }
}