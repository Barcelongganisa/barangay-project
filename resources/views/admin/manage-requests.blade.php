<x-admin-layout>
    @php
        // Function to format request ID as BRGY-YEAR-00000
        function formatRequestId($id) {
            $currentYear = date('Y');
            $paddedId = str_pad($id, 5, '0', STR_PAD_LEFT);
            return "BRGY-{$currentYear}-{$paddedId}";
        }
    @endphp

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
        
        .table-primary th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.075);
        }

        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="position: relative; left: -250px; width: calc(100% + 250px);"> 
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
                                    <th>Request ID</th>
                                    <th>Resident Name</th>
                                    <th>Document Type</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                                @forelse($requests as $request)
                                        <tr data-request-id="{{ $request->request_id }}" 
                                        data-status="{{ $request->status }}"
                                        data-submitted-date="{{ \Carbon\Carbon::parse($request->submitted_date ?? $request->request_date)->format('M j, Y g:i A') }}"
                                        data-approved-date="{{ $request->approved_date ? \Carbon\Carbon::parse($request->approved_date)->format('M j, Y g:i A') : '' }}"
                                        data-processing-date="{{ $request->processing_date ? \Carbon\Carbon::parse($request->processing_date)->format('M j, Y g:i A') : '' }}"
                                        data-completed-date="{{ $request->completed_date ? \Carbon\Carbon::parse($request->completed_date)->format('M j, Y g:i A') : '' }}"
                                        data-declined-date="{{ $request->declined_date ? \Carbon\Carbon::parse($request->declined_date)->format('M j, Y g:i A') : '' }}">
                                                                        <th scope="row">{{ formatRequestId($request->request_id) }}</th>
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
                                            <div class="d-flex align-items-center gap-2">
                                                @if(in_array($request->status, ['pending', 'under-review', 'approved', 'processing']))
                                                    <button class="btn btn-sm btn-primary btn-custom-size process-action-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#processModal" 
                                                            data-action-type="{{ $request->status }}"
                                                            data-request-id="{{ $request->request_id }}"
                                                            data-formatted-id="{{ formatRequestId($request->request_id) }}">
                                                        @if($request->status == 'approved')
                                                            Confirm Payment
                                                        @else
                                                            Process
                                                        @endif
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary btn-custom-size view-details-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#detailsModal"
                                                            data-request-id="{{ $request->request_id }}"
                                                            data-formatted-id="{{ formatRequestId($request->request_id) }}">
                                                        View
                                                    </button>
                                                @endif

                                                {{-- New History Button --}}
                                                <button class="btn btn-sm btn-info btn-custom-size text-white history-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#historyModal"
                                                        data-request-id="{{ $request->request_id }}"
                                                        data-formatted-id="{{ formatRequestId($request->request_id) }}">
                                                    History
                                                </button>
                                            </div>
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
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Request Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Request ID:</strong></td>
                                    <td id="modal-request-id-text"></td>
                                </tr>
                                <tr>
                                    <td><strong>Resident Name:</strong></td>
                                    <td id="modal-resident-name"></td>
                                </tr>
                                <tr>
                                    <td><strong>Document Type:</strong></td>
                                    <td id="modal-document-type"></td>
                                </tr>
                                <tr>
                                    <td><strong>Date Submitted:</strong></td>
                                    <td id="modal-date-submitted"></td>
                                </tr>
                                <tr>
                                    <td><strong>Current Status:</strong></td>
                                    <td id="modal-status"></td>
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
                    
                    <hr>
                    <h6>Purpose / Remarks</h6>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0" id="modal-remarks">No specific remarks provided</p>
                        </div>
                    </div>
                    
                    <div id="modal-documents-section" class="mt-4" style="display: none;">
                        <h6>Submitted Documents</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="30%">Document Type</th>
                                        <th width="35%">File Name</th>
                                        <th width="20%">Upload Date</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-documents-tbody">
                                    <!-- Documents will be loaded here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div id="modal-dynamic-content" class="mt-3"></div>
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

        {{-- History Modal --}}
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction History: <span id="history-modal-request-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="history-content" class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="history-tbody">
                                <tr><td colspan="4" class="text-center text-muted py-3">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        const formattedId = this.dataset.formattedId;
                        console.log('View details for:', requestId, formattedId);
                        
                        // Get basic info from table row
                        const row = this.closest('tr');
                        const residentName = row.cells[1].textContent;
                        const documentType = row.cells[2].textContent;
                        const dateSubmitted = row.cells[3].textContent;
                        const status = row.querySelector('.request-status').textContent;
                        
                        // Populate modal
                        document.getElementById('modal-request-id').textContent = formattedId;
                        document.getElementById('modal-request-id-text').textContent = formattedId;
                        document.getElementById('modal-resident-name').textContent = residentName;
                        document.getElementById('modal-document-type').textContent = documentType;
                        document.getElementById('modal-date-submitted').textContent = dateSubmitted;
                        document.getElementById('modal-status').textContent = status;
                        
                        // Load detailed information including documents
                        loadDetailedRequestInfo(requestId, formattedId);
                    });
                });

                // Process Modal
                document.querySelectorAll('.process-action-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const requestId = this.dataset.requestId;
                        const actionType = this.dataset.actionType;
                        const formattedId = this.dataset.formattedId;
                        console.log('Process request:', requestId, 'Action:', actionType);
                        
                        // Get basic info from table row
                        const row = this.closest('tr');
                        const residentName = row.cells[1].textContent;
                        const documentType = row.cells[2].textContent;
                        
                        // Populate basic info
                        document.getElementById('process-modal-request-id').textContent = formattedId;
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
                                <button type="button" class="btn btn-success btn-custom-size" onclick="updateRequestStatus(${requestId}, 'approved')">Approve</button>`;
                        } else if (actionType === 'approved') {
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

            // Function to load detailed request information
            async function loadDetailedRequestInfo(requestId, formattedId) {
                try {
                    const response = await fetch('/admin/requests/' + requestId + '/details');
                    
                    if (!response.ok) {
                        throw new Error('Failed to load request details');
                    }
                    
                    const data = await response.json();
                    console.log('Request details:', data);
                    
                    // Update remarks
                    if (data.request && data.request.remarks) {
                        document.getElementById('modal-remarks').textContent = data.request.remarks;
                    }
                    
                    // Load documents
                    if (data.success && data.documents && data.documents.length > 0) {
                        const documentsSection = document.getElementById('modal-documents-section');
                        const documentsTbody = document.getElementById('modal-documents-tbody');
                        
                        documentsSection.style.display = 'block';
                        documentsTbody.innerHTML = '';
                        
                        data.documents.forEach(doc => {
                            // FIX: Use the request_date from the service_requests table as upload date
                            const uploadDate = data.request && data.request.request_date 
                                ? new Date(data.request.request_date).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })
                                : 'Unknown';
                            
                            const documentRow = `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <span>${doc.document_type || 'Supporting Document'}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate" title="${doc.file_name || 'Document'}">
                                            ${doc.file_name || 'Document'}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">${uploadDate}</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-document-btn" 
                                                data-file-path="${doc.file_path}"
                                                title="View Document">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-outline-success download-document-btn ms-1" 
                                                data-file-path="${doc.file_path}"
                                                data-file-name="${doc.file_name || 'document'}"
                                                title="Download Document">
                                            <i class="bi bi-download"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            documentsTbody.innerHTML += documentRow;
                        });
                        
                        // Add event listeners for document viewing
                        documentsTbody.querySelectorAll('.view-document-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const filePath = this.getAttribute('data-file-path');
                                if (filePath) {
                                    window.open('/storage/' + filePath, '_blank');
                                } else {
                                    alert('Document path not available');
                                }
                            });
                        });

                        // Add event listeners for document download
                        documentsTbody.querySelectorAll('.download-document-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const filePath = this.getAttribute('data-file-path');
                                const fileName = this.getAttribute('data-file-name');
                                if (filePath) {
                                    downloadIndividualDocument(filePath, fileName);
                                } else {
                                    alert('Document path not available');
                                }
                            });
                        });
                    } else {
                        // Hide documents section if no documents
                        document.getElementById('modal-documents-section').style.display = 'none';
                    }
                    
                } catch (error) {
                    console.error('Error loading request details:', error);
                    // Hide documents section on error
                    document.getElementById('modal-documents-section').style.display = 'none';
                }
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
                                <div>
                                    <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-primary me-1">View</a>
                                    <button class="btn btn-sm btn-success download-document-btn" 
                                            data-file-path="${doc.file_path}"
                                            data-file-name="${doc.file_name || 'document'}">
                                        Download
                                    </button>
                                </div>
                            `;
                            documentsList.appendChild(docElement);
                        });

                        // Add event listeners for download buttons in process modal
                        documentsList.querySelectorAll('.download-document-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const filePath = this.getAttribute('data-file-path');
                                const fileName = this.getAttribute('data-file-name');
                                if (filePath) {
                                    downloadIndividualDocument(filePath, fileName);
                                } else {
                                    alert('Document path not available');
                                }
                            });
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

            // Function to download individual document
            function downloadIndividualDocument(filePath, fileName) {
                console.log('Downloading document:', filePath, fileName);
                
                // Create a temporary anchor element to trigger download
                const link = document.createElement('a');
                link.href = '/storage/' + filePath;
                link.download = fileName || 'document';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            // Global function to update request status
            window.updateRequestStatus = async function(requestId, newStatus) {
                try {
                    console.log('Updating status for request:', requestId, 'to:', newStatus);
                    
                    let remarks = '';
                    if (newStatus === 'declined') {
                        remarks = document.getElementById('adminComment')?.value || 'Request declined.';
                    } else if (newStatus === 'approved') {
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

            // Handle History button click - FOR ADMIN (TABLE VIEW)
            document.addEventListener('click', async function(e) {
                if (e.target.closest('.history-btn')) {
                    const button = e.target.closest('.history-btn');
                    const row = button.closest('tr');
                    const requestId = button.dataset.requestId;
                    const formattedId = button.dataset.formattedId;

                    // Get the date attributes from the table row
                    const submittedDate = row.getAttribute('data-submitted-date');
                    const approvedDate = row.getAttribute('data-approved-date');
                    const processingDate = row.getAttribute('data-processing-date');
                    const completedDate = row.getAttribute('data-completed-date');
                    const declinedDate = row.getAttribute('data-declined-date');
                    const currentStatus = row.getAttribute('data-status');

                    // Set modal header
                    document.getElementById('history-modal-request-id').textContent = formattedId;

                    // Show loading message
                    const tbody = document.getElementById('history-tbody');
                    tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">Loading...</td></tr>`;

                    try {
                        // Generate history using real dates
                        const history = generateRealHistory(
                            submittedDate, 
                            approvedDate,
                            processingDate, 
                            completedDate, 
                            declinedDate, 
                            currentStatus
                        );

                        tbody.innerHTML = '';
                        
                        if (history.length > 0) {
                            history.forEach(item => {
                                tbody.innerHTML += `
                                    <tr>
                                        <td>${item.date}</td>
                                        <td>${item.title}</td>
                                        <td>${item.remarks || '-'}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">No history found.</td></tr>`;
                        }
                    } catch (error) {
                        console.error(error);
                        tbody.innerHTML = `<tr><td colspan="3" class="text-center text-danger py-3">Failed to load history.</td></tr>`;
                    }
                }
            });

            // Real History Data Generation Function - FOR ADMIN (TABLE VIEW)
            function generateRealHistory(submittedDate, approvedDate, processingDate, completedDate, declinedDate, currentStatus) {
                const history = [];
                
                // Always show submitted
                history.push({
                    title: 'Submitted',
                    date: submittedDate,
                    remarks: 'Request submitted by resident'
                });

                // Show approved if date exists
                if (approvedDate) {
                    history.push({
                        title: 'Approved',
                        date: approvedDate,
                        remarks: 'Request approved for processing'
                    });
                }

                // Show processing if date exists
                if (processingDate) {
                    history.push({
                        title: 'Processing',
                        date: processingDate,
                        remarks: 'Request is being processed'
                    });
                }

                // Show completed if date exists
                if (completedDate) {
                    history.push({
                        title: 'Completed',
                        date: completedDate,
                        remarks: 'Request completed and ready'
                    });
                }

                // Show declined if date exists
                if (declinedDate) {
                    history.push({
                        title: 'Declined',
                        date: declinedDate,
                        remarks: 'Request was declined'
                    });
                }

                return history;
            }


            // Initial attachment of event listeners
            attachEventListeners();
        });
            </script>
</x-admin-layout>
