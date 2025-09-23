<x-resident-layout>
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
                            <option value="under-review">Under Review</option>
                            <option value="waiting-payment">Waiting for Payment</option>
                            <option value="processing">Processing</option>
                            <option value="complete">Complete</option>
                            <option value="declined">Declined</option>
                        </select>
                        
                        <select class="form-select form-select-sm" id="serviceFilter" style="width: auto;">
                            <option value="all">All Services</option>
                            <option value="clearance">Barangay Clearance</option>
                            <option value="residency">Certificate of Residency</option>
                            <option value="indigency">Certificate of Indigency</option>
                            <option value="business">Business Permit</option>
                            <option value="id">Barangay ID</option>
                            <option value="other">Other</option>
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
                                <th>Reference #</th>
                                <th>Service Type</th>
                                <th>Purpose</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-request-id="BRGY-2023-00865" data-status="waiting-payment">
                                <td>BRGY-2023-00865</td>
                                <td>Barangay Clearance</td>
                                <td>For employment requirements at ABC Corporation</td>
                                <td>Oct 15, 2023</td>
                                <td><span class="status-badge status-waiting-payment">Waiting for Payment</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#requestDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr data-request-id="BRGY-2023-00872" data-status="processing">
                                <td>BRGY-2023-00872</td>
                                <td>Certificate of Residency</td>
                                <td>For scholarship application at State University</td>
                                <td>Oct 18, 2023</td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#requestDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr data-request-id="BRGY-2023-00875" data-status="under-review">
                                <td>BRGY-2023-00875</td>
                                <td>Business Permit</td>
                                <td>For small sari-sari store at 123 Main Street</td>
                                <td>Oct 20, 2023</td>
                                <td><span class="status-badge status-under-review">Under Review</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#requestDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelRequestModal">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </td>
                            </tr>
                            <tr data-request-id="BRGY-2023-00835" data-status="complete">
                                <td>BRGY-2023-00835</td>
                                <td>Certificate of Indigency</td>
                                <td>For educational assistance program</td>
                                <td>Oct 5, 2023</td>
                                <td><span class="status-badge status-complete">Complete</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#requestDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr data-request-id="BRGY-2023-00840" data-status="declined">
                                <td>BRGY-2023-00840</td>
                                <td>Barangay ID</td>
                                <td>For identification purposes</td>
                                <td>Oct 8, 2023</td>
                                <td><span class="status-badge status-declined">Declined</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#requestDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Request pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details: <span id="modal-request-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="requestDetailsContent">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Reference Number:</strong> <span id="modal-reference-number"></span></p>
                            <p><strong>Service Type:</strong> <span id="modal-service-type"></span></p>
                            <p><strong>Purpose:</strong> <span id="modal-purpose"></span></p>
                            <p><strong>Submitted On:</strong> <span id="modal-date-submitted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span id="modal-status-badge"></span></p>
                            <p><strong>Processing Fee:</strong> <span id="modal-processing-fee"></span></p>  
                            <p><strong>Last Updated:</strong> <span id="modal-last-updated"></span></p>
                        </div>
                    </div>
                    <hr>
                    <h6>Admin Comments</h6>
                    <div id="modal-admin-comments"></div>
                    <hr>
                    <h6>Progress History</h6>
                    <ul class="list-group" id="modal-progress-history">
                        <!-- Progress history will be dynamically inserted here -->
                    </ul>
                    <hr>
                    <h6>Required Documents</h6>
                    <ul class="list-group" id="modal-required-documents">
                        <!-- Required documents will be dynamically inserted here -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printRequest">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Request Modal -->
    <div class="modal fade" id="cancelRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-center">Are you sure you want to cancel this request?</h5>
                    <p class="text-muted text-center">Request: <strong>Business Permit (BRGY-2023-00875)</strong></p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for cancellation</label>
                        <textarea class="form-control" id="cancelReason" rows="3" placeholder="Please provide a reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                    <button type="button" class="btn btn-danger" id="confirmCancel">Cancel Request</button>
                </div>
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

                // In a real application, this would filter the requests
                alert(`Filters applied:\nStatus: ${status}\nService: ${service}\nDate Range: ${dateRange}`);
            });

            document.getElementById('resetFilters').addEventListener('click', function() {
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('serviceFilter').value = 'all';
                document.getElementById('dateFilter').value = 'month';

                // In a real application, this would reset the filters and show all requests
                alert('Filters reset');
            });

            // Print Request
            document.getElementById('printRequest').addEventListener('click', function() {
                alert('Printing request details...');
                // In a real application, this would print the request details
            });

            // Cancel Request
            document.getElementById('confirmCancel').addEventListener('click', function() {
                const reason = document.getElementById('cancelReason').value;

                if (!reason) {
                    alert('Please provide a reason for cancellation.');
                    return;
                }

                // Show loading state
                const cancelBtn = document.getElementById('confirmCancel');
                const originalText = cancelBtn.innerHTML;
                cancelBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cancelling...';
                cancelBtn.disabled = true;

                // Simulate cancellation process
                setTimeout(() => {
                    alert('Request cancelled successfully.');
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('cancelRequestModal'));
                    modal.hide();

                    // Reset button
                    cancelBtn.innerHTML = originalText;
                    cancelBtn.disabled = false;

                    // In a real application, this would update the UI to reflect the cancelled request
                }, 1500);
            });

            // Handle View Details button click
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const requestId = row.dataset.requestId;
                    const status = row.dataset.status;

                    // Set basic details
                    document.getElementById('modal-request-id').textContent = requestId;
                    document.getElementById('modal-reference-number').textContent = row.cells[0].textContent;
                    document.getElementById('modal-service-type').textContent = row.cells[1].textContent;
                    document.getElementById('modal-purpose').textContent = row.cells[2].textContent;
                    document.getElementById('modal-date-submitted').textContent = row.cells[3].textContent;
                    
                    // Set status badge
                    const statusBadge = document.getElementById('modal-status-badge');
                    statusBadge.innerHTML = row.cells[4].innerHTML;
                    
                    // Set processing fee
                    document.getElementById('modal-processing-fee').textContent = "₱100.00";
                    document.getElementById('modal-last-updated').textContent = "October 25, 2023";
                    
                    // Set admin comments based on status
                    const adminComments = document.getElementById('modal-admin-comments');
                    adminComments.innerHTML = '';
                    
                    if (status === 'under-review') {
                        adminComments.innerHTML = `
                            <div class="alert alert-warning" role="alert">
                                <strong>Admin Comment:</strong> Not yet reviewed.
                            </div>
                        `;
                    } else if (status === 'waiting-payment') {
                        adminComments.innerHTML = `
                            <div class="alert alert-secondary" role="alert">
                                <strong>Admin Comment:</strong> The request has been approved. Please proceed with payment.
                                <p class="mb-0 mt-2"><strong>Fee:</strong> ₱50.00</p>
                            </div>
                        `;
                    } else if (status === 'processing') {
                        adminComments.innerHTML = `
                            <div class="alert alert-info" role="alert">
                                <strong>Admin Comment:</strong> The request is currently being processed.
                            </div>
                        `;
                    } else if (status === 'declined') {
                        adminComments.innerHTML = `
                            <div class="alert alert-danger" role="alert">
                                <strong>Admin Comment:</strong> This request has been declined.
                                <p class="mb-0 mt-2"><strong>Reason:</strong> The uploaded document is invalid. Please submit a new request with the correct file.</p>
                            </div>
                        `;
                    } else if (status === 'complete') {
                        adminComments.innerHTML = `
                            <div class="alert alert-success" role="alert">
                                <strong>Admin Comment:</strong> This request has been completed.
                                <p class="mb-0 mt-2"><strong>Comment:</strong> The document is ready for pickup at the Barangay Hall.</p>
                            </div>
                        `;
                    }
                    
                    // Set progress history
                    const progressHistory = document.getElementById('modal-progress-history');
                    progressHistory.innerHTML = `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-check-circle text-success"></i> Request submitted
                                <p class="text-muted mb-0 small">${row.cells[3].textContent} - 10:23 AM</p>
                            </div>
                        </li>
                    `;
                    
                    if (status !== 'under-review') {
                        progressHistory.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle text-success"></i> Under review
                                    <p class="text-muted mb-0 small">${getNextDay(row.cells[3].textContent)} - 09:15 AM</p>
                                </div>
                            </li>
                        `;
                    }
                    
                    if (status === 'waiting-payment' || status === 'processing' || status === 'complete') {
                        progressHistory.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle text-success"></i> Request approved
                                    <p class="text-muted mb-0 small">${getNextDay(row.cells[3].textContent, 2)} - 02:45 PM</p>
                                </div>
                            </li>
                        `;
                    }
                    
                    if (status === 'complete') {
                        progressHistory.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle text-success"></i> Ready for pickup
                                    <p class="text-muted mb-0 small">${getNextDay(row.cells[3].textContent, 5)} - 10:30 AM</p>
                                </div>
                            </li>
                        `;
                    }
                    
                    // Set required documents
                    const requiredDocuments = document.getElementById('modal-required-documents');
                    requiredDocuments.innerHTML = `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Valid ID
                            <span class="badge bg-success rounded-pill"><i class="bi bi-check"></i> Submitted</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Proof of Residence
                            <span class="badge bg-success rounded-pill"><i class="bi bi-check"></i> Submitted</span>
                        </li>
                    `;
                });
            });
            
            // Helper function to calculate next day
            function getNextDay(dateString, daysToAdd = 1) {
                const months = {
                    'Jan': 'January', 'Feb': 'February', 'Mar': 'March', 'Apr': 'April',
                    'May': 'May', 'Jun': 'June', 'Jul': 'July', 'Aug': 'August',
                    'Sep': 'September', 'Oct': 'October', 'Nov': 'November', 'Dec': 'December'
                };
                
                const parts = dateString.split(' ');
                const month = months[parts[0]];
                const day = parseInt(parts[1].replace(',', ''));
                const year = parseInt(parts[2]);
                
                // This is a simplified calculation - in a real app you'd use Date objects
                const newDay = day + daysToAdd;
                return `${parts[0]} ${newDay}, ${year}`;
            }
        });
    </script>
</x-resident-layout>