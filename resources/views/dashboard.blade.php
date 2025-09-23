<x-admin-layout>
    <!-- Summary Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <!-- Pending Requests Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending Requests</h6>
                                <h2 class="fw-bold mb-0">12</h2>
                            </div>
                            <div>
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">+3 today</span>
                        </div>
                    </div>
                </div>
            </div>
<!-- Processing Card -->
<div class="col-12 col-sm-6 col-lg-3">
    <div class="card border-0 shadow card-hover h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Processing</h6>
                    <h2 class="fw-bold mb-0">8</h2>
                </div>
                <div>
                    <i class="bi bi-gear text-primary fs-4"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">+2 today</span>
            </div>
        </div>
    </div>
</div>

<!-- Completed Card -->
<div class="col-12 col-sm-6 col-lg-3">
    <div class="card border-0 shadow card-hover h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Completed</h6>
                    <h2 class="fw-bold mb-0">5</h2>
                </div>
                <div>
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">+1 today</span>
            </div>
        </div>
    </div>
</div>

<!-- Total Residents Card -->
<div class="col-12 col-sm-6 col-lg-3">
    <div class="card border-0 shadow card-hover h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Residents</h6>
                    <h2 class="fw-bold mb-0">254</h2>
                </div>
                <div>
                    <i class="bi bi-people text-info fs-4"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">+2 this week</span>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Pending Requests List -->
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Pending Requests</h5>
                    </div>
                    <div class="card-body">
                        <!-- Example Request Card -->
                        <div class="request-card card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Certificate of Indigency - Maria Santos</h6>
                                        <p class="text-muted mb-1">Submitted: Yesterday, 3:45 PM</p>
                                        <span class="badge bg-success">Indigency</span>
                                    </div>
                                    <div>
                                        <span class="request-status status-pending">Pending</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary">Process Request</button>
                                    <button class="btn btn-sm btn-outline-secondary ms-1">View Details</button>
                                </div>
                            </div>
                        </div>
                        <!-- Barangay Clearance Request Card -->
                        <div class="request-card card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Barangay Clearance - Juan Dela Cruz</h6>
                                        <p class="text-muted mb-1">Submitted: Today, 9:15 AM</p>
                                        <span class="badge bg-info">Clearance</span>
                                    </div>
                                    <div>
                                        <span class="request-status status-pending">Pending</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary">Process Request</button>
                                    <button class="btn btn-sm btn-outline-secondary ms-1">View Details</button>
                                </div>
                            </div>
                        </div>

                        <!-- Business Permit Request Card -->
                        <div class="request-card card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Business Permit - Carlos Lopez</h6>
                                        <p class="text-muted mb-1">Submitted: Today, 10:30 AM</p>
                                        <span class="badge bg-warning text-dark">Business</span>
                                    </div>
                                    <div>
                                        <span class="request-status status-pending">Pending</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary">Process Request</button>
                                    <button class="btn btn-sm btn-outline-secondary ms-1">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
