<x-resident-layout>
    @php
        // Get the current resident's ID
        $residentId = auth()->id() ;
        
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
            'pending' => 'Pending',
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
        
        /* NEW CSS FOR TIMELINE */
        .timeline {
            position: relative;
            padding: 10px 0 0 20px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 2px;
            background-color: #e9ecef; /* Light gray line */
        }
        .timeline-item {
            margin-bottom: 20px;
            position: relative;
            line-height: 1.4;
        }
        .timeline-item-icon {
            position: absolute;
            left: -28px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #0d6efd; /* Primary color */
            border: 4px solid white;
            z-index: 1;
        }
        .timeline-item.active .timeline-item-icon {
            background-color: #28a745; /* Success color for active */
        }
        .timeline-item-date {
            display: block;
            font-size: 0.85em;
            color: #6c757d;
            margin-bottom: 2px;
        }
        .timeline-item-title {
            font-weight: bold;
        }
    </style>

    <div class="container-fluid p-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <h5 class="card-title mb-3 mb-md-0">My Requests</h5>
                    
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
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Service Type</th>
                                <th>Purpose</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                    <td>
                                            <button class="btn btn-sm btn-info text-white history-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#historyModal"
                                                data-request-id="{{ formatRequestId($request->request_id) }}"
                                                data-submitted-date="{{ \Carbon\Carbon::parse($request->request_date)->format('M j, Y g:i A') }}"
                                                data-current-status="{{ $request->status }}">
                                             History
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if($requests->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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

                </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Transaction History: <span id="modal-request-id" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="timeline" id="historyTimeline">
                        <p class="text-center text-muted" id="timeline-loading-message">Loading history...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // --- Mock History Data Generation Function (Frontend Simulation) ---
        function generateMockHistory(submittedDate, currentStatus) {
            const history = [];
            // Map the user-friendly names to the database status keys
            const statuses = [
                { key: 'pending', title: 'Submitted', iconClass: 'bi-file-earmark-check', isFinal: false, date: submittedDate },
                { key: 'approved', title: 'Approved', iconClass: 'bi-check-circle', isFinal: false, date: null }, 
                { key: 'processing', title: 'Processing', iconClass: 'bi-gear', isFinal: false, date: null },
                { key: 'completed', title: 'Complete', iconClass: 'bi-trophy', isFinal: true, date: null },
            ];

            let mockDate = new Date(submittedDate); // Start from submitted date

            for (const statusItem of statuses) {
                // Determine if this status step has been reached
                let hasReached = false;
                
                if (statusItem.key === 'pending') {
                    hasReached = true;
                } else if (statusItem.key === 'approved') {
                    // Approved must happen before Processing or Completed
                    if (currentStatus !== 'pending' && currentStatus !== 'declined') {
                        hasReached = true;
                        mockDate.setMinutes(mockDate.getMinutes() + 15); // Mock 15 mins later
                        statusItem.date = mockDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                    }
                } else if (statusItem.key === 'processing' && (currentStatus === 'processing' || currentStatus === 'completed')) {
                    hasReached = true;
                    mockDate.setHours(mockDate.getHours() + 1); // Mock 1 hour later
                    statusItem.date = mockDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                } else if (statusItem.key === 'completed' && currentStatus === 'completed') {
                    hasReached = true;
                    mockDate.setHours(mockDate.getHours() + 2); // Mock 2 hours later
                    statusItem.date = mockDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                }

                if (hasReached) {
                    history.push({
                        title: statusItem.title,
                        date: statusItem.date,
                        isActive: statusItem.key === currentStatus || (statusItem.key === 'approved' && (currentStatus === 'processing' || currentStatus === 'completed')),
                        iconClass: statusItem.iconClass,
                        isDeclined: false
                    });
                }
                
                // If the current status is reached, stop the loop (unless it's 'approved', which is an intermediate step)
                if (statusItem.key === currentStatus) {
                    break;
                }
            }
            
            // Add 'Declined' if the current status is declined
            if (currentStatus === 'declined') {
                mockDate.setMinutes(new Date(submittedDate).getMinutes() + 15);
                history.push({
                    title: 'Submitted',
                    date: submittedDate,
                    isActive: false,
                    iconClass: 'bi-file-earmark-check',
                    isDeclined: false
                });
                history.push({
                    title: 'Declined',
                    date: mockDate.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }),
                    isActive: true,
                    iconClass: 'bi-x-circle',
                    isDeclined: true
                });
            }

            return history;
        }
        // --- End Mock History Data Generation ---


        document.addEventListener('DOMContentLoaded', function() {
            
            // Filter functionality (Keeping original logic)
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


            // History Modal Logic
            const historyModal = document.getElementById('historyModal');
            if (historyModal) {
                // Event listener for when the modal is about to be shown
                historyModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    
                    // Extract info from data-* attributes
                    const requestId = button.getAttribute('data-request-id');
                    const submittedDate = button.getAttribute('data-submitted-date');
                    const currentStatus = button.getAttribute('data-current-status');
                    
                    // Update the modal's title
                    const modalTitle = historyModal.querySelector('#modal-request-id');
                    modalTitle.textContent = requestId;

                    const timelineDiv = historyModal.querySelector('#historyTimeline');
                    timelineDiv.innerHTML = '<p class="text-center text-muted" id="timeline-loading-message"><i class="bi bi-arrow-clockwise"></i> Loading history...</p>';
                    
                    // Simulate API call delay (optional)
                    setTimeout(() => {
                        const history = generateMockHistory(submittedDate, currentStatus);
                        timelineDiv.innerHTML = ''; // Clear loading message

                        if (history.length === 0) {
                            timelineDiv.innerHTML = '<p class="text-center text-muted">No history found.</p>';
                            return;
                        }

                        history.forEach(item => {
                            const itemDiv = document.createElement('div');
                            // Determine the 'active' class based on the last status reached or the current status
                            const isActive = item.title.toLowerCase().includes(currentStatus) || 
                                             (item.title === 'Approved' && (currentStatus === 'processing' || currentStatus === 'completed'));
                            
                            itemDiv.className = `timeline-item ${item.isActive ? 'active' : ''}`;
                            
                            // Determine the icon and background color for the circle
                            let iconColor = item.isActive ? '#28a745' : '#0d6efd'; // Success for active, Primary for others
                            if (item.isDeclined) {
                                iconColor = '#dc3545'; // Danger for declined
                            }

                            itemDiv.innerHTML = `
                                <span class="timeline-item-icon" style="background-color: ${iconColor};"></span>
                                <span class="timeline-item-date">${item.date}</span>
                                <div class="timeline-item-title">${item.title}</div>
                            `;
                            timelineDiv.appendChild(itemDiv);
                        });
                        
                    }, 500); // 0.5 second delay
                });
            }
        });
    </script>
</x-resident-layout>