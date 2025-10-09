{{-- resources/views/admin/reports.blade.php --}}
<x-admin-layout>
    <x-slot name="title">
        Reports - Admin Dashboard
    </x-slot>

    <style>
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.2s;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .trend-up { color: #198754; }
        .trend-down { color: #dc3545; }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="position: relative; left: -120px;">
            
            <!-- Export & Filter -->
            <div class="d-flex justify-content-end ms-auto mb-3">
                <button class="btn btn-primary me-2" onclick="exportReport()">
                    <i class="bi bi-download me-1"></i> Export Report
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @php
                            $rangeLabels = [
                                '7days' => 'Last 7 Days',
                                '30days' => 'Last 30 Days', 
                                '90days' => 'Last 90 Days',
                                'year' => 'This Year'
                            ];
                        @endphp
                        {{ $rangeLabels[$timeRange] ?? 'Last 30 Days' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="timeRangeDropdown">
                        <li><a class="dropdown-item" href="?time_range=7days">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="?time_range=30days">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="?time_range=90days">Last 90 Days</a></li>
                        <li><a class="dropdown-item" href="?time_range=year">This Year</a></li>
                    </ul>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Requests</h6>
                                    <h2 class="fw-bold mb-0">{{ $totalRequests }}</h2>
                                    @if(isset($previousPeriodData['totalRequests']))
                                        @php
                                            $previousTotal = $previousPeriodData['totalRequests'];
                                            $trend = $previousTotal > 0 ? (($totalRequests - $previousTotal) / $previousTotal) * 100 : 0;
                                        @endphp
                                        <small class="{{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                                            <i class="bi bi-arrow-{{ $trend >= 0 ? 'up' : 'down' }}-circle"></i>
                                            {{ abs(round($trend, 1)) }}% from previous period
                                        </small>
                                    @endif
                                </div>
                                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">Real-time data</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Completed Requests</h6>
                                    <h2 class="fw-bold text-success mb-0">{{ $completedRequests }}</h2>
                                    @if(isset($previousPeriodData['completedRequests']))
                                        @php
                                            $previousCompleted = $previousPeriodData['completedRequests'];
                                            $trend = $previousCompleted > 0 ? (($completedRequests - $previousCompleted) / $previousCompleted) * 100 : 0;
                                        @endphp
                                        <small class="{{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                                            <i class="bi bi-arrow-{{ $trend >= 0 ? 'up' : 'down' }}-circle"></i>
                                            {{ abs(round($trend, 1)) }}% from previous period
                                        </small>
                                    @endif
                                </div>
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    @php
                                        $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0;
                                    @endphp
                                    {{ $completionRate }}% completion rate
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Revenue Generated</h6>
                                    <h2 class="fw-bold text-warning mb-0">₱{{ number_format($revenueGenerated, 2) }}</h2>
                                    @if(isset($previousPeriodData['revenueGenerated']))
                                        @php
                                            $previousRevenue = $previousPeriodData['revenueGenerated'];
                                            $trend = $previousRevenue > 0 ? (($revenueGenerated - $previousRevenue) / $previousRevenue) * 100 : 0;
                                        @endphp
                                        <small class="{{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                                            <i class="bi bi-arrow-{{ $trend >= 0 ? 'up' : 'down' }}-circle"></i>
                                            {{ abs(round($trend, 1)) }}% from previous period
                                        </small>
                                    @endif
                                </div>
                                <i class="bi bi-currency-exchange text-warning fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    @php
                                        $paidRequests = $documentTypeStats->sum('revenue') > 0 ? $documentTypeStats->where('revenue', '>', 0)->sum('total_requests') : 0;
                                    @endphp
                                    From {{ $paidRequests }} paid requests
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">New Residents</h6>
                                    <h2 class="fw-bold text-info mb-0">{{ $newResidents }}</h2>
                                    @if(isset($previousPeriodData['newResidents']))
                                        @php
                                            $previousResidents = $previousPeriodData['newResidents'];
                                            $trend = $previousResidents > 0 ? (($newResidents - $previousResidents) / $previousResidents) * 100 : 0;
                                        @endphp
                                        <small class="{{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                                            <i class="bi bi-arrow-{{ $trend >= 0 ? 'up' : 'down' }}-circle"></i>
                                            {{ abs(round($trend, 1)) }}% from previous period
                                        </small>
                                    @endif
                                </div>
                                <i class="bi bi-person-plus text-info fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">Registered since {{ $startDate->format('M j') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Requests Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="requestsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Document Type Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="documentTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Reports Table -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Request Statistics by Type</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Type</th>
                                    <th>Total Requests</th>
                                    <th>Completed</th>
                                    <th>In Progress</th>
                                    <th>Pending</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentTypeStats as $stat)
                                <tr>
                                    <th>{{ $stat->request_type ?? $stat->document_type ?? 'N/A' }}</th>
                                    <td>{{ $stat->total_requests }}</td>
                                    <td>{{ $stat->completed }}</td>
                                    <td>{{ $stat->processing ?? $stat->in_progress ?? 0 }}</td>
                                    <td>{{ $stat->pending }}</td>
                                    <td>₱{{ number_format($stat->revenue ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                                @if($documentTypeStats->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No data available for the selected time period
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $documentTypeStats->sum('total_requests') }}</th>
                                    <th>{{ $documentTypeStats->sum('completed') }}</th>
                                    <th>{{ $documentTypeStats->sum('processing') ?? $documentTypeStats->sum('in_progress') ?? 0 }}</th>
                                    <th>{{ $documentTypeStats->sum('pending') }}</th>
                                    <th>₱{{ number_format($documentTypeStats->sum('revenue'), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- @push('scripts') --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.href.includes('reports')) link.classList.add('active');
            });
            console.log("Requests over time:", @json($requestsOverTime));

            // Requests Chart - Dynamic Data
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            const requestsChart = new Chart(requestsCtx, {
                type: 'line',
                data: {
                    labels: @json($requestsOverTime->pluck('label')),
                    datasets: [
                        { 
                            label: 'Total Requests', 
                            data: @json($requestsOverTime->pluck('total')), 
                            borderColor: '#0d6efd', 
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true, // Keep fill for this dataset only
                            borderWidth: 3
                        },
                        { 
                            label: 'Completed Requests', 
                            data: @json($requestsOverTime->pluck('completed')), 
                            borderColor: '#198754', 
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            tension: 0.4,
                            fill: false, // Remove fill from this dataset
                            borderWidth: 3
                        }
                    ]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Request Trends Over Time',
                            font: { size: 16 }
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            // stacked: true,
                            title: {
                                display: true,
                                text: 'Number of Requests'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time Period'
                            }
                        }
                    }
                }
            });

            // Document Type Chart - Dynamic Data
            const documentTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
            const documentTypeChart = new Chart(documentTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($documentTypeStats->pluck('request_type')->map(function($type) {
                        return $type ?? 'Unknown';
                    })),
                    datasets: [{
                        data: @json($documentTypeStats->pluck('total_requests')),
                        backgroundColor: [
                            '#0d6efd', '#6f42c1', '#d63384', '#fd7e14', '#20c997', 
                            '#ffc107', '#dc3545', '#6610f2', '#e83e8c', '#6c757d'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            text: 'Requests by Document Type',
                            font: { size: 16 }
                        }
                    },
                    cutout: '50%'
                }
            });
        });

        function exportReport() {
            const timeRange = '{{ $timeRange }}';
            window.open(`/admin/reports/export?time_range=${timeRange}`, '_blank');
        }
    </script>
    {{-- @endpush --}}
</x-admin-layout>