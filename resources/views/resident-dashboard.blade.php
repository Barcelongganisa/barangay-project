<x-resident-layout>
    <!-- Summary Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Requests</h6>
                                <h2 class="fw-bold mb-0">5</h2>
                            </div>
                            <div>
                                <i class="bi bi-list-check text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1"> Completed</h6>
                                <h2 class="fw-bold text-success mb-0">3</h2>
                            </div>
                            <div>
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h2 class="fw-bold text-warning mb-0">1</h2>
                            </div>
                            <div>
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Processing</h6>
                                <h2 class="fw-bold text-primary mb-0">1</h2>
                            </div>
                            <div>
                                <i class="bi bi-gear text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="container-fluid p-4">
        <h2 class="h4 fw-bold mb-4">Recent Requests</h2>
        <div class="row g-4">
            <!-- Request 1 -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-semibold mb-0">Barangay Clearance</h5>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <p class="text-muted small mb-2">Requested on: October 15, 2023</p>
                        <p class="card-text mb-3">For employment requirements</p>
                        <button class="btn btn-outline-primary btn-sm">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Request 2 -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-semibold mb-0">Certificate of Residency</h5>
                            <span class="badge bg-primary">Processing</span>
                        </div>
                        <p class="text-muted small mb-2">Requested on: October 18, 2023</p>
                        <p class="card-text mb-3">For scholarship application</p>
                        <button class="btn btn-outline-primary btn-sm">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Request 3 -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-semibold mb-0">Business Permit</h5>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </div>
                        <p class="text-muted small mb-2">Requested on: October 20, 2023</p>
                        <p class="card-text mb-3">For small sari-sari store</p>
                        <button class="btn btn-outline-primary btn-sm">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Request 4 -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-semibold mb-0">Indigency Certificate</h5>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <p class="text-muted small mb-2">Requested on: October 5, 2023</p>
                        <p class="card-text mb-3">For educational assistance</p>
                        <button class="btn btn-outline-primary btn-sm">View Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-resident-layout>