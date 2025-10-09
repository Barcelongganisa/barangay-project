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
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="position: relative; left: -120px;">
            
            <!-- Export & Filter -->
            <div class="d-flex justify-content-end ms-auto mb-3">
                <button class="btn btn-primary me-2">
                    <i class="bi bi-download me-1"></i> Export Report
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Last 30 Days
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="timeRangeDropdown">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                        <li><a class="dropdown-item" href="#">Custom Range</a></li>
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
                                    <h2 class="fw-bold mb-0">185</h2>
                                </div>
                                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">+12% from last month</span>
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
                                    <h2 class="fw-bold text-success mb-0">142</h2>
                                </div>
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">76.8% completion rate</span>
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
                                    <h2 class="fw-bold text-warning mb-0">₱7,250</h2>
                                </div>
                                <i class="bi bi-currency-exchange text-warning fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">+18% from last month</span>
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
                                    <h2 class="fw-bold text-info mb-0">15</h2>
                                </div>
                                <i class="bi bi-person-plus text-info fs-4"></i>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">+5 this month</span>
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
                                <tr><th>Barangay Clearance</th><td>68</td><td>52</td><td>8</td><td>8</td><td>₱3,400</td></tr>
                                <tr><th>Certificate of Indigency</th><td>45</td><td>38</td><td>4</td><td>3</td><td>₱0</td></tr>
                                <tr><th>Business Permit</th><td>32</td><td>24</td><td>5</td><td>3</td><td>₱3,200</td></tr>
                                <tr><th>Barangay ID</th><td>28</td><td>22</td><td>4</td><td>2</td><td>₱650</td></tr>
                                <tr><th>Certificate of Residency</th><td>12</td><td>6</td><td>3</td><td>3</td><td>₱0</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.href.includes('reports')) link.classList.add('active');
            });

            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            new Chart(requestsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep'],
                    datasets: [
                        { label: 'Total Requests', data: [120,135,140,152,148,160,172,165,185], borderColor:'#0d6efd', tension:0.1 },
                        { label: 'Completed Requests', data: [95,105,110,120,115,125,140,130,142], borderColor:'#198754', tension:0.1 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            const documentTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
            new Chart(documentTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Barangay Clearance','Certificate of Indigency','Business Permit','Barangay ID','Certificate of Residency'],
                    datasets: [{ data: [68,45,32,28,12], backgroundColor: ['#0d6efd','#6f42c1','#d63384','#fd7e14','#20c997'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        });
    </script>
    @endpush
</x-admin-layout>
