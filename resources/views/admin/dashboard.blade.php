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
                                <h2 class="fw-bold mb-0">{{ $requestCounts->pending ?? 0 }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                @if(($requestCounts->pending_today ?? 0) > 0)
                                    +{{ $requestCounts->pending_today }} today
                                @else
                                    No added today
                                @endif
                            </span>
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
                                <h2 class="fw-bold mb-0">{{ $requestCounts->processing ?? 0 }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-gear text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                @if(($requestCounts->processing_today ?? 0) > 0)
                                    +{{ $requestCounts->processing_today }} today
                                @else
                                    No added today
                                @endif
                            </span>
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
                                <h2 class="fw-bold mb-0">{{ $requestCounts->completed ?? 0 }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                @if(($requestCounts->completed_today ?? 0) > 0)
                                    +{{ $requestCounts->completed_today }} today
                                @else
                                    No added today
                                @endif
                            </span>
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
                                <h2 class="fw-bold mb-0">{{ $residentCounts->total ?? 0 }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-people text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                @if(($residentCounts->this_week ?? 0) > 0)
                                    +{{ $residentCounts->this_week }} this week
                                @else
                                    No added this week
                                @endif
                            </span>
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
                        @forelse($pendingRequests as $request)
                            <div class="request-card card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $request->request_type }} - {{ $request->resident->first_name }} {{ $request->resident->last_name }}</h6>
                                            <p class="text-muted mb-1">
                                                Submitted: 
                                                @php
                                                    $requestDate = \Carbon\Carbon::parse($request->request_date);
                                                    $now = \Carbon\Carbon::now();
                                                    
                                                    if($requestDate->isToday()) {
                                                        echo 'Today, ' . $requestDate->format('g:i A');
                                                    } elseif($requestDate->isYesterday()) {
                                                        echo 'Yesterday, ' . $requestDate->format('g:i A');
                                                    } else {
                                                        echo $requestDate->format('M j, Y, g:i A');
                                                    }
                                                @endphp
                                            </p>
                                            <span class="badge 
                                                @if(str_contains($request->request_type, 'Indigency')) bg-success
                                                @elseif(str_contains($request->request_type, 'Clearance')) bg-info
                                                @elseif(str_contains($request->request_type, 'Business')) bg-warning text-dark
                                                @else bg-secondary @endif">
                                                {{ last(explode(' ', $request->request_type)) }}
                                            </span>
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
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">No pending requests found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>