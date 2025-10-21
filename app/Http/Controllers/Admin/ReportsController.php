<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\BarangayResident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Get time range from request or default to 30 days
        $timeRange = request('time_range', '30days');
        $startDate = $this->getStartDate($timeRange);

        // Get dynamic data
        $data = [
            'totalRequests' => $this->getTotalRequests($startDate),
            'completedRequests' => $this->getCompletedRequests($startDate),
            'revenueGenerated' => $this->getRevenueGenerated($startDate),
            'newResidents' => $this->getNewResidents($startDate),
            'requestsOverTime' => $this->getRequestsOverTime($startDate),
            'documentTypeStats' => $this->getDocumentTypeStats($startDate),
            'timeRange' => $timeRange,
            'startDate' => $startDate,
        ];

        return view('admin.reports', $data);
    }

    private function getStartDate($timeRange)
    {
        return match($timeRange) {
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };
    }

    private function getTotalRequests($startDate)
    {
        return ServiceRequest::where('request_date', '>=', $startDate)->count();
    }

    private function getCompletedRequests($startDate)
    {
        return ServiceRequest::where('request_date', '>=', $startDate)
            ->where('status', 'completed')
            ->count();
    }

    private function getRevenueGenerated($startDate)
    {
        return DB::table('payments')
            ->where('created_at', '>=', $startDate)
            ->where('status', 'paid')
            ->sum('amount');
    }

    private function getNewResidents($startDate)
    {
        return BarangayResident::where('created_at', '>=', $startDate)->count();
    }

    private function getRequestsOverTime($startDate)
    {
        $timeRange = request('time_range', '30days');

        if (in_array($timeRange, ['7days', '30days', '90days'])) {
            // Group by DATE for short ranges
            $requests = ServiceRequest::select(
                    DB::raw('DATE(request_date) as date'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
                )
                ->where('request_date', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return $requests->map(function($item) {
                return [
                    'label' => Carbon::parse($item->date)->format('M j'), // e.g. "Oct 8"
                    'total' => (int) $item->total,
                    'completed' => (int) $item->completed,
                ];
            });
        }

        // Otherwise (year range): group by MONTH
        $requests = ServiceRequest::select(
                DB::raw('MONTH(request_date) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->where('request_date', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $requests->map(function($item) {
            return [
                'label' => Carbon::create()->month($item->month)->format('M'), // e.g. "Jan"
                'total' => (int) $item->total,
                'completed' => (int) $item->completed,
            ];
        });
    }

    private function getDocumentTypeStats($startDate)
    {
        return ServiceRequest::select(
                'request_type',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN service_requests.status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN service_requests.status IN ("pending", "under-review", "waiting-payment") THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN service_requests.status = "processing" THEN 1 ELSE 0 END) as processing'),
                DB::raw('COALESCE(SUM(CASE WHEN payments.status = "paid" THEN payments.amount ELSE 0 END), 0) as revenue')
            )
            ->leftJoin('payments', 'service_requests.request_id', '=', 'payments.request_id') // Use request_id instead of id
            ->where('service_requests.request_date', '>=', $startDate)
            ->groupBy('request_type')
            ->get();
    }

    public function export(Request $request)
    {
        // Simple export - you can enhance this later
        return response()->json(['message' => 'Export feature coming soon']);
    }
}