<x-resident-layout>
    @php
        // Get the current resident's ID
        $residentId = auth()->user()->resident_id ?? 1;
        
        // Get requests for this resident
        $requests = DB::table('service_requests')
            ->where('resident_id', $residentId)
            ->orderBy('request_date', 'desc')
            ->get();
        
        // Map statuses to CSS classes
        $statusClasses = [
            'pending' => 'status-under-review',
            'processing' => 'status-processing', 
            'completed' => 'status-complete',
            'declined' => 'status-declined'
        ];
        
        // Map statuses to display names
        $statusDisplay = [
            'pending' => 'Under Review',
            'processing' => 'Processing',
            'completed' => 'Complete',
            'declined' => 'Declined'
        ];
        
        // Service type mapping
        $serviceMapping = [
            'clearance' => 'Barangay Clearance',
            'residency' => 'Certificate of Residency',
            'indigency' => 'Certificate of Indigency', 
            'business' => 'Business Permit',
            'id' => 'Barangay ID',
            'other' => 'Other Request'
        ];

        // Function to format request ID as BRGY-YEAR-00000
        function formatRequestId($id) {
            $currentYear = date('Y');
            $paddedId = str_pad($id, 5, '0', STR_PAD_LEFT);
            return "BRGY-{$currentYear}-{$paddedId}";
        }
    @endphp

    <style>
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.375rem;
        }
        .status-under-review {
            background-color: #e2e3e5;
            color: #495057;
        }
        .status-waiting-payment {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-declined {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-complete {
            background-color: #d4edda;
            color: #155724;
        }
        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid p-4">
        <!-- Filter and Requests Table Card -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <h5 class="card-title mb-3 mb-md-0">My Requests</h5>
                    
                    <!-- Filter Controls -->
                    <div class="d-flex flex-wrap gap-2">
                        <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                            <option value="all">All Statuses</option>
                            <option value="pending">Under Review</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Complete</option>
                            <option value="declined">Declined</option>
                        </select>
                        
                        <select class="form-select form-select-sm" id="serviceFilter" style="width: auto;">
                            <option value="all">All Services</option>
                            @foreach($serviceMapping as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        
                        <select class="form-select form-select-sm" id="dateFilter" style="width: auto;">
                            <option value="all">All Time</option>
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="quarter">Last 3 Months</option>
                            <option value="year">This Year</option>
                        </select>
                        
                        <div class="btn-group" role="group">
                            <button class="btn btn-primary btn-sm" id="applyFilters">
                                <i class="bi bi-filter"></i> Apply
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="resetFilters">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop Requests Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Service Type</th>
                                <th>Purpose</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr data-request-id="{{ $request->request_id }}" 
                                    data-status="{{ $request->status }}"
                                    data-request-type="{{ $request->request_type }}"
                                    data-request-date="{{ $request->request_date }}"
                                    data-updated-at="{{ $request->updated_at }}"
                                    data-remarks="{{ $request->remarks }}">
                                    <td>{{ formatRequestId($request->request_id) }}</td>
                                    <td>{{ $serviceMapping[$request->request_type] ?? $request->request_type }}</td>
                                    <td>{{ Illuminate\Support\Str::limit($request->remarks, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->request_date)->format('M j, Y') }}</td>
                                    <td>
                                        <span class="status-badge {{ $statusClasses[$request->status] ?? 'status-under-review' }}">
                                            {{ $statusDisplay[$request->status] ?? 'Under Review' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if($requests->count() == 0)
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                        <h4 class="text-muted">No Requests Yet</h4>
                                        <p class="text-muted">You haven't made any service requests yet.</p>
                                        <a href="{{ route('resident.new-request') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Make Your First Request
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination would go here for larger datasets -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function() {
                const status = document.getElementById('statusFilter').value;
                const service = document.getElementById('serviceFilter').value;
                const dateRange = document.getElementById('dateFilter').value;

                // Filter table rows
                const rows = document.querySelectorAll('tbody tr[data-request-id]');
                rows.forEach(row => {
                    let showRow = true;
                    
                    // Status filter
                    if (status !== 'all' && row.dataset.status !== status) {
                        showRow = false;
                    }
                    
                    // Service filter  
                    if (service !== 'all' && !row.dataset.requestType.toLowerCase().includes(service)) {
                        showRow = false;
                    }
                    
                    // Date filter (simplified - you'd need proper date comparison)
                    if (dateRange !== 'all') {
                        // Implement date filtering logic here
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
            });

            document.getElementById('resetFilters').addEventListener('click', function() {
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('serviceFilter').value = 'all';
                document.getElementById('dateFilter').value = 'month';

                // Show all rows
                document.querySelectorAll('tbody tr[data-request-id]').forEach(row => {
                    row.style.display = '';
                });
            });
        });
    </script>
</x-resident-layout>