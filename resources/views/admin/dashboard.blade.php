<x-admin-layout>
    @php
        // Function to format request ID as BRGY-YEAR-00000
        function formatRequestId($id) {
            $currentYear = date('Y');
            $paddedId = str_pad($id, 4, '0', STR_PAD_LEFT);
            return "BRGY-{$currentYear}-{$paddedId}";
        }
    @endphp

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
                                                <strong>Request ID:</strong> {{ formatRequestId($request->request_id) }}
                                            </p>
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
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.manage.requests') }}" class="btn btn-sm btn-primary">Process Request</a>
                                        <button class="btn btn-sm btn-outline-secondary ms-1 view-details-btn" 
                                                data-request-id="{{ $request->request_id }}"
                                                data-request-type="{{ $request->request_type }}"
                                                data-resident-name="{{ $request->resident->first_name }} {{ $request->resident->last_name }}"
                                                data-request-date="{{ $request->request_date }}"
                                                data-remarks="{{ $request->remarks }}"
                                                data-documents="{{ json_encode($request->documents ?? []) }}"
                                                data-formatted-id="{{ formatRequestId($request->request_id) }}">
                                            View Details
                                        </button>
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

    <!-- View Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details: <span id="modal-request-ref"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="requestDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading request details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('admin.manage.requests') }}" class="btn btn-primary">Process Request</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for View Details -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
        const modalTitle = document.getElementById('modal-request-ref');
        const modalContent = document.getElementById('requestDetailsContent');

        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get all data from the button's data attributes
                const requestId = this.getAttribute('data-request-id');
                const requestType = this.getAttribute('data-request-type');
                const residentName = this.getAttribute('data-resident-name');
                const requestDate = this.getAttribute('data-request-date');
                const remarks = this.getAttribute('data-remarks');
                const documents = JSON.parse(this.getAttribute('data-documents') || '[]');
                const formattedId = this.getAttribute('data-formatted-id');
                
                showRequestDetails(requestId, requestType, residentName, requestDate, remarks, documents, formattedId);
            });
        });

        function showRequestDetails(requestId, requestType, residentName, requestDate, remarks, documents, formattedId) {
            modalTitle.textContent = formattedId;
            
            // Format dates properly
            const formattedRequestDate = requestDate ? new Date(requestDate + ' UTC').toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Not specified';

            // Document type mapping for better display
            const documentTypeMapping = {
                'clearance': 'Barangay Clearance',
                'residency': 'Certificate of Residency',
                'indigency': 'Certificate of Indigency',
                'business': 'Business Permit', 
                'id': 'Barangay ID',
                'other': 'Other Document'
            };

            const displayRequestType = documentTypeMapping[requestType] || requestType;
            
            // Generate documents list HTML
            let documentsHtml = '';
            if (documents && documents.length > 0) {
                documentsHtml = `
                    <h6 class="mt-4">Submitted Documents</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                documents.forEach(doc => {
                    const uploadDate = doc.upload_date ? new Date(doc.upload_date + ' UTC').toLocaleDateString('en-US') : 'Unknown';
                    documentsHtml += `
                        <tr>
                            <td>${doc.document_type || 'Supporting Document'}</td>
                            <td>${doc.file_name || 'Document'}</td>
                            <td>${uploadDate}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-document-btn" 
                                        data-file-path="${doc.file_path}">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                documentsHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                documentsHtml = `
                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No documents submitted for this request.
                    </div>
                `;
            }
            
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Request Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Request ID:</strong></td>
                                <td>${formattedId}</td>
                            </tr>
                            <tr>
                                <td><strong>Request Type:</strong></td>
                                <td>${displayRequestType}</td>
                            </tr>
                            <tr>
                                <td><strong>Resident Name:</strong></td>
                                <td>${residentName}</td>
                            </tr>
                            <tr>
                                <td><strong>Request Date:</strong></td>
                                <td>${formattedRequestDate}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Processing Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Processing Fee:</strong></td>
                                <td>₱100.00</td>
                            </tr>
                            <tr>
                                <td><strong>Estimated Time:</strong></td>
                                <td>2-3 Business Days</td>
                            </tr>
                            <tr>
                                <td><strong>Priority:</strong></td>
                                <td><span class="badge bg-info">Normal</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Purpose / Remarks</h6>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">${remarks || 'No specific remarks provided'}</p>
                        </div>
                    </div>
                </div>

                ${documentsHtml}

                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> This request is pending review. Click "Process Request" to manage this request in the full management interface.
                </div>
            `;
            
            modalContent.innerHTML = detailsHtml;
            
            // Add event listeners for document viewing
            modalContent.querySelectorAll('.view-document-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const filePath = this.getAttribute('data-file-path');
                    if (filePath) {
                        // Open document in new tab or implement document viewer
                        window.open('/storage/' + filePath, '_blank');
                    } else {
                        alert('Document path not available');
                    }
                });
            });
            
            modal.show();
        }
    });
    </script>
</x-admin-layout>