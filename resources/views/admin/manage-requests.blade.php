<x-admin-layout>
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

        .request-status {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.375rem;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-under-review { background-color: #e2e3e5; color: #495057; }
        .status-waiting-payment { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-declined { background-color: #f8d7da; color: #721c24; }
        
        .btn-custom-size {
            width: 130px;
            white-space: nowrap;
        }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="position: relative; left: -120px;">
            {{-- Filter Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filterStatus" class="form-label">Filter by Status</label>
                            <select class="form-select" id="filterStatus">
                                <option value="all" selected>All</option>
                                <option value="pending">Pending</option>
                                <option value="under-review">Under Review</option>
                                <option value="waiting-payment">Waiting for Payment</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterType" class="form-label">Filter by Document Type</label>
                            <select class="form-select" id="filterType">
                                <option value="all" selected>All</option>
                                <option value="Barangay Clearance">Barangay Clearance</option>
                                <option value="Barangay Certificate of Residency">Barangay Residency</option>
                                <option value="Barangay Certificate of Indigency">Certificate of Indigency</option>
                                <option value="Barangay Business Clearance">Business Clearance</option>
                                <option value="Barangay ID">Barangay ID</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchBar" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchBar" placeholder="Search by name or request #...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100 me-2 btn-custom-size" id="applyFilters">Apply Filters</button>
                            <button class="btn btn-outline-secondary w-100 btn-custom-size" id="resetFilters">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Requests Table --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="requestsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Request #</th>
                                    <th>Resident Name</th>
                                    <th>Document Type</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                                @forelse($requests as $request)
                                    <tr data-request-id="{{ $request->request_id }}" data-status="{{ $request->status }}">
                                        <th scope="row">REQ-{{ str_pad($request->request_id, 3, '0', STR_PAD_LEFT) }}</th>
                                        <td>{{ $request->resident->first_name }} {{ $request->resident->last_name }}</td>
                                        <td>{{ $request->request_type }}</td>
                                        <td>
                                            @php
                                                $requestDate = \Carbon\Carbon::parse($request->request_date);
                                                echo $requestDate->format('M j, Y, g:i A');
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = 'status-' . str_replace(' ', '-', $request->status);
                                                $statusDisplay = ucfirst(str_replace('-', ' ', $request->status));
                                            @endphp
                                            <span class="request-status {{ $statusClass }}">{{ $statusDisplay }}</span>
                                        </td>
                                        <td>
                                            @if(in_array($request->status, ['pending', 'under-review', 'waiting-payment', 'processing']))
                                                <button class="btn btn-sm btn-primary btn-custom-size process-action-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#processModal" 
                                                        data-action-type="{{ $request->status }}"
                                                        data-request-id="{{ $request->request_id }}">
                                                    @if($request->status == 'waiting-payment')
                                                        Confirm Payment
                                                    @else
                                                        Process
                                                    @endif
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary btn-custom-size view-details-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailsModal"
                                                        data-request-id="{{ $request->request_id }}">
                                                    View
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">No requests found.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Request Details Modal --}}
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details: <span id="modal-request-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Resident Name:</strong> <span id="modal-resident-name"></span></p>
                    <p><strong>Document Type:</strong> <span id="modal-document-type"></span></p>
                    <p><strong>Date Submitted:</strong> <span id="modal-date-submitted"></span></p>
                    <p><strong>Current Status:</strong> <span id="modal-status"></span></p>
                    <hr>
                    <div id="modal-dynamic-content"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Process Modal --}}
    <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process Request: <span id="process-modal-request-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Request Details</h6>
                    <p><strong>Resident Name:</strong> <span id="process-modal-resident-name"></span></p>
                    <p><strong>Document Type:</strong> <span id="process-modal-document-type"></span></p>
                    <hr>
                    <h6>Uploaded Documents</h6>
                    <div id="process-modal-documents-list"></div>
                    <hr>
                    <div id="process-modal-dynamic-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <div id="process-modal-action-buttons"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- INLINE JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Manage Requests Page Loaded');

            // Store original table content for reset
            const originalTableContent = document.getElementById('requestsTableBody').innerHTML;

            // Filter functionality - ONLY when Apply Filters is clicked
            document.getElementById('applyFilters').addEventListener('click', applyFilters);
            document.getElementById('resetFilters').addEventListener('click', resetFilters);
            
            function applyFilters() {
                const statusFilter = document.getElementById('filterStatus').value;
                const typeFilter = document.getElementById('filterType').value;
                const searchTerm = document.getElementById('searchBar').value.toLowerCase();
                
                console.log('Applying filters:', { statusFilter, typeFilter, searchTerm });
                
                let hasVisibleRows = false;
                
                // Reset table to original state first
                document.getElementById('requestsTableBody').innerHTML = originalTableContent;
                
                // Now apply filters to all rows
                document.querySelectorAll('#requestsTableBody tr').forEach(row => {
                    if (row.cells.length < 5) {
                        // This is the "no results" row, hide it initially
                        row.style.display = 'none';
                        return;
                    }
                    
                    const status = row.dataset.status;
                    const documentType = row.cells[2].textContent;
                    const rowText = row.textContent.toLowerCase();
                    
                    const statusMatch = statusFilter === 'all' || status === statusFilter;
                    const typeMatch = typeFilter === 'all' || documentType.includes(typeFilter);
                    const searchMatch = searchTerm === '' || rowText.includes(searchTerm);
                    
                    if (statusMatch && typeMatch && searchMatch) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show message if no rows match filters
                const tbody = document.getElementById('requestsTableBody');
                if (!hasVisibleRows) {
                    // Check if we already have a no-results row
                    let noResultsRow = tbody.querySelector('.no-results');
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results';
                        noResultsRow.innerHTML = '<td colspan="6" class="text-center py-4"><div class="text-muted">No requests match your filters.</div></td>';
                        tbody.appendChild(noResultsRow);
                    } else {
                        noResultsRow.style.display = '';
                    }
                } else {
                    // Remove no-results row if it exists
                    const noResultsRow = tbody.querySelector('.no-results');
                    if (noResultsRow) {
                        noResultsRow.remove();
                    }
                }

                // Re-attach event listeners to buttons since we reset the table
                attachEventListeners();
            }

            function resetFilters() {
                document.getElementById('filterStatus').value = 'all';
                document.getElementById('filterType').value = 'all';
                document.getElementById('searchBar').value = '';
                
                // Reset table to original state
                document.getElementById('requestsTableBody').innerHTML = originalTableContent;
                
                // Re-attach event listeners
                attachEventListeners();
            }

            function attachEventListeners() {
                // View Details Modal
                document.querySelectorAll('.view-details-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const requestId = this.dataset.requestId;
                        console.log('View details for:', requestId);
                        
                        // Get basic info from table row
                        const row = this.closest('tr');
                        const residentName = row.cells[1].textContent;
                        const documentType = row.cells[2].textContent;
                        const dateSubmitted = row.cells[3].textContent;
                        const status = row.querySelector('.request-status').textContent;
                        
                        // Populate modal
                        document.getElementById('modal-request-id').textContent = 'REQ-' + String(requestId).padStart(3, '0');
                        document.getElementById('modal-resident-name').textContent = residentName;
                        document.getElementById('modal-document-type').textContent = documentType;
                        document.getElementById('modal-date-submitted').textContent = dateSubmitted;
                        document.getElementById('modal-status').textContent = status;
                        
                        // Add dynamic content based on status
                        const dynamicContent = document.getElementById('modal-dynamic-content');
                        const statusLower = status.toLowerCase();
                        
                        if (statusLower.includes('pending') || statusLower.includes('review')) {
                            dynamicContent.innerHTML = `
                                <h6>Admin Comment</h6>
                                <div class="alert alert-warning">
                                    This request is awaiting review by an administrator.
                                </div>`;
                        } else if (statusLower.includes('waiting')) {
                            dynamicContent.innerHTML = `
                                <h6>Admin Comment</h6>
                                <div class="alert alert-secondary">
                                    The request has been approved. Waiting for resident to complete payment.
                                    <p class="mb-0 mt-2"><strong>Fee:</strong> ₱50.00</p>
                                </div>`;
                        } else if (statusLower.includes('processing')) {
                            dynamicContent.innerHTML = `
                                <h6>Admin Comment</h6>
                                <div class="alert alert-info">
                                    The request is currently being processed.
                                </div>`;
                        } else if (statusLower.includes('declined')) {
                            dynamicContent.innerHTML = `
                                <h6>Admin Comment</h6>
                                <div class="alert alert-danger">
                                    This request has been declined.
                                    <p class="mb-0 mt-2"><strong>Reason:</strong> Please check the uploaded documents.</p>
                                </div>`;
                        } else if (statusLower.includes('complete')) {
                            dynamicContent.innerHTML = `
                                <h6>Admin Comment</h6>
                                <div class="alert alert-success">
                                    This request has been completed.
                                    <p class="mb-0 mt-2"><strong>Comment:</strong> Document ready for pickup.</p>
                                </div>
                                <hr>
                                <h6>Generated Document</h6>
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="bi bi-file-earmark-check-fill me-2 fs-4"></i>
                                    <div>
                                        <p class="mb-0"><strong>Completed_Document.pdf</strong></p>
                                        <p class="mb-0 text-muted">Ready for pickup at barangay hall.</p>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-success ms-auto">Download</a>
                                </div>`;
                        }
                    });
                });

                // Process Modal
                document.querySelectorAll('.process-action-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const requestId = this.dataset.requestId;
                        const actionType = this.dataset.actionType;
                        console.log('Process request:', requestId, 'Action:', actionType);
                        
                        // Get basic info from table row
                        const row = this.closest('tr');
                        const residentName = row.cells[1].textContent;
                        const documentType = row.cells[2].textContent;
                        
                        // Populate basic info
                        document.getElementById('process-modal-request-id').textContent = 'REQ-' + String(requestId).padStart(3, '0');
                        document.getElementById('process-modal-resident-name').textContent = residentName;
                        document.getElementById('process-modal-document-type').textContent = documentType;
                        
                        // Show loading for documents
                        const documentsList = document.getElementById('process-modal-documents-list');
                        documentsList.innerHTML = '<div class="alert alert-info">Loading documents...</div>';
                        
                        // Load dynamic content based on action type
                        const dynamicContent = document.getElementById('process-modal-dynamic-content');
                        const actionButtons = document.getElementById('process-modal-action-buttons');
                        
                        if (actionType === 'pending' || actionType === 'under-review') {
                            dynamicContent.innerHTML = `
                                <h6>Admin Action</h6>
                                <div class="mb-3">
                                    <label class="form-label">Admin Comment</label>
                                    <textarea class="form-control" id="adminComment" rows="3" placeholder="Add a comment..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Processing Fee</label>
                                    <input type="number" class="form-control" id="processingFee" placeholder="e.g., 50" value="50">
                                    <div class="form-text">Enter the fee for this document.</div>
                                </div>`;
                            actionButtons.innerHTML = `
                                <button type="button" class="btn btn-danger btn-custom-size" onclick="updateRequestStatus(${requestId}, 'declined')">Decline</button>
                                <button type="button" class="btn btn-success btn-custom-size" onclick="updateRequestStatus(${requestId}, 'waiting-payment')">Approve</button>`;
                        } else if (actionType === 'waiting-payment') {
                            dynamicContent.innerHTML = `
                                <p class="text-center">Are you sure you want to confirm payment for this request?</p>`;
                            actionButtons.innerHTML = `
                                <button type="button" class="btn btn-primary btn-custom-size" onclick="updateRequestStatus(${requestId}, 'processing')">Yes, Confirm</button>`;
                        } else if (actionType === 'processing') {
                            dynamicContent.innerHTML = `
                                <h6>Complete Processing</h6>
                                <div class="mb-3">
                                    <label class="form-label">Comment for Resident</label>
                                    <textarea class="form-control" id="processComment" rows="3" placeholder="e.g., Document ready for pickup..."></textarea>
                                </div>`;
                            actionButtons.innerHTML = `
                                <button type="button" class="btn btn-success btn-custom-size" onclick="updateRequestStatus(${requestId}, 'completed')">Mark as Complete</button>`;
                        }
                        
                        // Load actual documents from API
                        loadRequestDocuments(requestId);
                    });
                });
            }

// Function to load documents from API
async function loadRequestDocuments(requestId) {
    try {
        console.log('Loading documents for request:', requestId);
        const response = await fetch('/admin/requests/' + requestId + '/details');
        
        if (!response.ok) {
            throw new Error('Failed to load documents');
        }
        
        const data = await response.json();
        console.log('Documents data:', data);
        
        const documentsList = document.getElementById('process-modal-documents-list');
        documentsList.innerHTML = '';
        
        // Check if we have documents in the response
        if (data.success && data.documents && data.documents.length > 0) {
            console.log('Found documents:', data.documents);
            data.documents.forEach(doc => {
                const docElement = document.createElement('div');
                docElement.className = 'alert alert-info d-flex justify-content-between align-items-center mb-2';
                docElement.innerHTML = `
                    <div>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        ${doc.document_type}
                    </div>
                    <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-primary">View</a>
                `;
                documentsList.appendChild(docElement);
            });
        } else {
            console.log('No documents found in response');
            documentsList.innerHTML = '<div class="alert alert-warning">No documents uploaded for this request.</div>';
        }
    } catch (error) {
        console.error('Error loading documents:', error);
        const documentsList = document.getElementById('process-modal-documents-list');
        documentsList.innerHTML = '<div class="alert alert-danger">Error loading documents: ' + error.message + '</div>';
    }
}

            // Global function to update request status
            window.updateRequestStatus = async function(requestId, newStatus) {
                try {
                    console.log('Updating status for request:', requestId, 'to:', newStatus);
                    
                    let remarks = '';
                    if (newStatus === 'declined') {
                        remarks = document.getElementById('adminComment')?.value || 'Request declined.';
                    } else if (newStatus === 'waiting-payment') {
                        const fee = document.getElementById('processingFee')?.value || '50';
                        remarks = `Approved. Processing fee: ₱${fee}. Waiting for payment.`;
                    } else if (newStatus === 'completed') {
                        remarks = document.getElementById('processComment')?.value || 'Document completed and ready for pickup.';
                    }
                    
                    const response = await fetch('/admin/requests/' + requestId + '/status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus,
                            remarks: remarks
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Close the modal and reload the page
                        const modal = bootstrap.Modal.getInstance(document.getElementById('processModal'));
                        modal.hide();
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to update status'));
                    }
                } catch (error) {
                    console.error('Error updating status:', error);
                    alert('Error updating status. Please try again.');
                }
            };

            // Initial attachment of event listeners
            attachEventListeners();
        });
    </script>
</x-admin-layout>