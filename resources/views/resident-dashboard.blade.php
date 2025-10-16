<x-resident-layout>
    @php
        // Function to format request ID as BRGY-YEAR-00000
        function formatRequestId($id) {
            $currentYear = date('Y');
            $paddedId = str_pad($id, 5, '0', STR_PAD_LEFT);
            return "BRGY-{$currentYear}-{$paddedId}";
        }
    @endphp

    <!-- Summary Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <!-- Total Requests -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Requests</h6>
                                <h2 class="fw-bold mb-0">{{ $totalRequests }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-list-check text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Completed -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Completed</h6>
                                <h2 class="fw-bold text-success mb-0">{{ $completedRequests }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pending -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h2 class="fw-bold text-warning mb-0">{{ $pendingRequests }}</h2>
                            </div>
                            <div>
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Processing -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Processing</h6>
                                <h2 class="fw-bold text-primary mb-0">{{ $processingRequests }}</h2>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0">Recent Requests</h2>
            <a href="{{ route('resident.requests') }}" class="btn btn-outline-primary btn-sm">
                View All Requests
            </a>
        </div>
        
        @if($recentRequests->count() > 0)
            <div class="row g-4">
                @foreach($recentRequests as $request)
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow card-hover h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-semibold mb-0">{{ $request->request_type }}</h5>
                                    <span class="badge 
                                        @if($request->status == 'completed') bg-success
                                        @elseif($request->status == 'processing') bg-primary
                                        @elseif($request->status == 'pending') bg-warning text-dark
                                        @else bg-secondary @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-2">
                                    Requested on: {{ \Carbon\Carbon::parse($request->request_date)->format('F j, Y') }}
                                </p>
                                <p class="card-text mb-3">
                                    {{ Illuminate\Support\Str::limit($request->remarks, 100) }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm view-details-btn" 
                                            data-request-id="{{ $request->request_id }}"
                                            data-request-type="{{ $request->request_type }}"
                                            data-status="{{ $request->status }}"
                                            data-request-date="{{ $request->request_date }}"
                                            data-updated-at="{{ $request->updated_at }}"
                                            data-remarks="{{ $request->remarks }}"
                                            data-formatted-id="{{ formatRequestId($request->request_id) }}">
                                        View Details
                                    </button>
                                    @if($request->status == 'completed')
                                        <button class="btn btn-outline-success btn-sm download-doc-btn" 
                                                data-request-id="{{ $request->request_id }}">
                                            <i class="bi bi-download"></i> Download
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Add empty state -->
            <div class="card border-0 shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No Requests Yet</h4>
                    <p class="text-muted mb-4">You haven't made any service requests yet.</p>
                    <a href="{{ route('resident.new-request') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Make Your First Request
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- View Details Modal - Updated to match document.blade.php style -->
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
                    <button type="button" class="btn btn-primary" id="printRequest">Print</button>
                    <button type="button" class="btn btn-success" id="modalDownloadBtn" style="display: none;">
                        <i class="bi bi-download me-2"></i>Download Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for View Details - Updated to match document.blade.php -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
        const modalTitle = document.getElementById('modal-request-ref');
        const modalContent = document.getElementById('requestDetailsContent');
        const modalDownloadBtn = document.getElementById('modalDownloadBtn');
        const printBtn = document.getElementById('printRequest');

        // Print functionality
        printBtn.addEventListener('click', function() {
            window.print();
        });

        // Download functionality for cards
        document.querySelectorAll('.download-doc-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                const button = this;
                
                // Show loading
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                button.disabled = true;

                // Trigger download
                window.location.href = `/resident/requests/${requestId}/download`;
                
                // Reset button after download starts
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        });

        // Modal download button
        modalDownloadBtn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            if (!requestId) {
                console.error('No request ID found for download');
                return;
            }
            
            const button = this;
            
            // Show loading
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Downloading...';
            button.disabled = true;

            // Trigger download
            window.location.href = `/resident/requests/${requestId}/download`;
            
            // Reset button
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });

        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get all data from the button's data attributes
                const requestId = this.getAttribute('data-request-id');
                const requestType = this.getAttribute('data-request-type');
                const status = this.getAttribute('data-status');
                const requestDate = this.getAttribute('data-request-date');
                const updatedAt = this.getAttribute('data-updated-at');
                const remarks = this.getAttribute('data-remarks');
                const formattedId = this.getAttribute('data-formatted-id');
                
                showRequestDetails(requestId, requestType, status, requestDate, updatedAt, remarks, formattedId);
            });
        });

        function showRequestDetails(requestId, requestType, status, requestDate, updatedAt, remarks, formattedId) {
            modalTitle.textContent = formattedId;
            
            // Format dates properly
            const formattedRequestDate = requestDate ? new Date(requestDate + ' UTC').toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }) : 'Not specified';
            
            const formattedUpdatedDate = updatedAt ? new Date(updatedAt + ' UTC').toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
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
            
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Request Type:</strong> ${displayRequestType}</p>
                        <p><strong>Reference Number:</strong> ${formattedId}</p>
                        <p><strong>Purpose:</strong> ${remarks || 'No specific remarks provided'}</p>
                        <p><strong>Request Date:</strong> ${formattedRequestDate}</p>
                        <p><strong>Last Updated:</strong> ${formattedUpdatedDate}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> <span class="badge ${getStatusBadgeClass(status)}">${getStatusDisplayText(status)}</span></p>
                        <p><strong>Processing Fee:</strong> ₱100.00</p>  
                    </div>
                </div>
                <hr>
                <h6>Current Status</h6>
                <div id="modal-admin-comments">
                    ${getStatusMessage(status)}
                </div>
                <hr>
                <h6>Document Preview</h6>
                <div class="text-center">
                    <div class="border rounded p-4 bg-light">
                        <i class="bi bi-file-earmark-pdf display-1 text-danger"></i>
                        <h5 class="mt-3">${displayRequestType}</h5>
                        <p class="text-muted">Reference: ${formattedId}</p>
                        <p class="text-muted">Status: ${getStatusDisplayText(status)}</p>
                    </div>
                </div>
            `;
            
            modalContent.innerHTML = detailsHtml;
            
            // Show download button only for completed requests
            if (status === 'completed') {
                modalDownloadBtn.style.display = 'block';
                modalDownloadBtn.setAttribute('data-request-id', requestId);
            } else {
                modalDownloadBtn.style.display = 'none';
                modalDownloadBtn.removeAttribute('data-request-id');
            }
            
            modal.show();
        }

        function getStatusBadgeClass(status) {
            if (!status) return 'bg-secondary';
            
            switch(status.toLowerCase()) {
                case 'completed': return 'bg-success';
                case 'processing': return 'bg-primary';
                case 'pending': return 'bg-warning text-dark';
                default: return 'bg-secondary';
            }
        }

        function getStatusDisplayText(status) {
            if (!status) return 'Unknown';
            
            switch(status.toLowerCase()) {
                case 'completed': return 'Completed';
                case 'processing': return 'Processing';
                case 'pending': return 'Pending';
                default: return status.charAt(0).toUpperCase() + status.slice(1);
            }
        }

        function getStatusMessage(status) {
            const messages = {
                'completed': `<div class="alert alert-success" role="alert">
                    <strong>Current Status:</strong> This request has been completed and the document is ready for download.
                </div>`,
                'processing': `<div class="alert alert-primary" role="alert">
                    <strong>Current Status:</strong> Your request is currently being processed by the barangay office.
                </div>`,
                'pending': `<div class="alert alert-warning" role="alert">
                    <strong>Current Status:</strong> Your request is pending review and approval.
                </div>`
            };
            return messages[status] || `<div class="alert alert-info" role="alert">
                <strong>Current Status:</strong> ${getStatusDisplayText(status)}
            </div>`;
        }
    });
    </script>
</x-resident-layout>