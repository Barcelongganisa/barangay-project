<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayResident;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageResidentsController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Get all residents
        $residents = BarangayResident::orderBy('created_at', 'desc')->get();

        return view('admin.manage-residents', compact('residents'));
    }

    public function getResidentDetails($id)
    {
        try {
            $resident = BarangayResident::findOrFail($id);

            return response()->json([
                'success' => true,
                'resident' => $resident
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resident not found'
            ], 404);
        }
    }

public function getResidentHistory($id)
{
    try {
        // Confirm resident exists
        $resident = \App\Models\BarangayResident::findOrFail($id);

        // Get all service requests for that resident
        $history = \DB::table('service_requests as sr')
            ->where('sr.resident_id', $id)
            ->select(
                'sr.request_id',
                'sr.request_type', // ✅ use request_type as Document Type
                'sr.request_date',
                'sr.status',
                'sr.remarks'
            )
            ->orderBy('sr.request_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'requests' => $history
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching resident history: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error loading history: ' . $e->getMessage()
        ], 500);
    }
}



    public function removeResident($id)
    {
        try {
            $resident = BarangayResident::findOrFail($id);
            
            // Check if resident has any pending requests
            $pendingRequests = ServiceRequest::where('resident_id', $id)
                ->whereIn('status', ['pending', 'under-review', 'waiting-payment', 'processing'])
                ->exists();

            if ($pendingRequests) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove resident with pending requests'
                ], 400);
            }

            $resident->delete();

            return response()->json([
                'success' => true,
                'message' => 'Resident removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing resident: ' . $e->getMessage()
            ], 500);
        }
    }
}