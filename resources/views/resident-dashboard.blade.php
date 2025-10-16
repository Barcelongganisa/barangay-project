<x-resident-layout>
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
                                            data-remarks="{{ $request->remarks }}">
                                        View Details
                                    </button>
                                    @if($request->status == 'completed')
                                        <button class="btn btn-outline-success btn-sm">Download</button>
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

    <!-- View Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestDetailsTitle">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="requestDetailsContent">
                    <!-- Content will be loaded here via JavaScript -->
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading request details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="downloadCertificateBtn" style="display: none;">
                        <i class="bi bi-download me-2"></i>Download Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for View Details -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
        const modalTitle = document.getElementById('requestDetailsTitle');
        const modalContent = document.getElementById('requestDetailsContent');
        const downloadBtn = document.getElementById('downloadCertificateBtn');

        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get all data from the button's data attributes
                const requestId = this.getAttribute('data-request-id');
                const requestType = this.getAttribute('data-request-type');
                const status = this.getAttribute('data-status');
                const requestDate = this.getAttribute('data-request-date');
                const updatedAt = this.getAttribute('data-updated-at');
                const remarks = this.getAttribute('data-remarks');
                
                showRequestDetails(requestId, requestType, status, requestDate, updatedAt, remarks);
            });
        });

        function showRequestDetails(requestId, requestType, status, requestDate, updatedAt, remarks) {
            modalTitle.textContent = `Request Details - ${requestType}`;
            
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
            
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Request Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Request ID:</strong></td>
                                <td>#${requestId}</td>
                            </tr>
                            <tr>
                                <td><strong>Service Type:</strong></td>
                                <td>${requestType}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge ${getStatusBadgeClass(status)}">${status}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Request Date:</strong></td>
                                <td>${formattedRequestDate}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>${formattedUpdatedDate}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Document Status</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            ${status === 'completed' ? 'Documents processed and approved' : 'Documents uploaded and under review'}
                        </div>
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
                
                ${status === 'completed' ? `
                <div class="alert alert-success mt-3">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Request Completed!</strong> Your document is ready for download.
                </div>
                ` : ''}
                
                ${status === 'pending' ? `
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>Request Pending</strong> - Waiting for barangay approval.
                </div>
                ` : ''}
                
                ${status === 'processing' ? `
                <div class="alert alert-primary mt-3">
                    <i class="bi bi-gear me-2"></i>
                    <strong>Request in Progress</strong> - Currently being processed.
                </div>
                ` : ''}
            `;
            
            modalContent.innerHTML = detailsHtml;
            
            // Show/hide download button based on status
            if (status === 'completed') {
                downloadBtn.style.display = 'block';
                downloadBtn.onclick = function() {
                    alert('Download functionality will be implemented for request #' + requestId);
                    // You can implement actual download later:
                    // window.location.href = '/resident/requests/' + requestId + '/download';
                };
            } else {
                downloadBtn.style.display = 'none';
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
    });
    </script>
</x-resident-layout>