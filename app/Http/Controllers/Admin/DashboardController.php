<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\BarangayResident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Get service request counts
        $requestCounts = ServiceRequest::select(
            DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
            DB::raw("SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
            DB::raw("SUM(CASE WHEN status = 'pending' AND DATE(request_date) = CURDATE() THEN 1 ELSE 0 END) as pending_today"),
            DB::raw("SUM(CASE WHEN status = 'processing' AND DATE(request_date) = CURDATE() THEN 1 ELSE 0 END) as processing_today"),
            DB::raw("SUM(CASE WHEN status = 'completed' AND DATE(request_date) = CURDATE() THEN 1 ELSE 0 END) as completed_today")
        )->first();

        // Get resident counts - FIXED the SQL syntax
        $residentCounts = BarangayResident::select(
            DB::raw("COUNT(*) as total"),
            DB::raw("SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as this_week")
        )->first();

        // Get pending requests with resident info
        $pendingRequests = ServiceRequest::with('resident')
            ->where('status', 'pending')
            ->orderBy('request_date', 'desc')
            ->get();

        return view('admin.dashboard', compact('requestCounts', 'residentCounts', 'pendingRequests'));
    }
}