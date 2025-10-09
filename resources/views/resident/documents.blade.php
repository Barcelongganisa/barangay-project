<x-resident-layout>
    @php
        // Get the current resident's ID
        $residentId = auth()->id();
        
        // Get completed requests (documents) for this resident
        $documents = DB::table('service_requests')
            ->where('resident_id', $residentId)
            ->where('status', 'completed')
            ->orderBy('request_date', 'desc')
            ->get();
        
        // Document type mapping
        $documentMapping = [
            'clearance' => 'Barangay Clearance',
            'residency' => 'Certificate of Residency',
            'indigency' => 'Certificate of Indigency', 
            'business' => 'Business Permit',
            'id' => 'Barangay ID',
            'other' => 'Other Documents'
        ];

        // Function to format request ID as BRGY-YEAR-00000
        function formatRequestId($id) {
            $currentYear = date('Y');
            $paddedId = str_pad($id, 5, '0', STR_PAD_LEFT);
            return "BRGY-{$currentYear}-{$paddedId}";
        }

        // Function to calculate validity date (30 days from issue)
        function calculateValidUntil($issueDate) {
            $issue = \Carbon\Carbon::parse($issueDate);
            return $issue->addDays(30)->format('M j, Y');
        }

        // Function to check document status
        function getDocumentStatus($issueDate) {
            $issue = \Carbon\Carbon::parse($issueDate);
            $validUntil = $issue->addDays(30);
            $now = \Carbon\Carbon::now();
            
            if ($now->gt($validUntil)) {
                return ['status' => 'expired', 'class' => 'bg-danger', 'text' => 'Expired'];
            } elseif ($validUntil->diffInDays($now) <= 7) {
                return ['status' => 'expiring', 'class' => 'bg-warning', 'text' => 'Expiring Soon'];
            } else {
                return ['status' => 'valid', 'class' => 'bg-success', 'text' => 'Valid'];
            }
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
        <!-- Filter and Documents Card -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <h5 class="card-title mb-3 mb-md-0">My Documents</h5>
                    
                    <!-- Filter Controls -->
                    <div class="d-flex flex-wrap gap-2">
                        <select class="form-select form-select-sm" id="documentType" style="width: auto;">
                            <option value="all">All Documents</option>
                            @foreach($documentMapping as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        
                        <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                            <option value="all">All Statuses</option>
                            <option value="valid">Valid</option>
                            <option value="expiring">Expiring Soon</option>
                            <option value="expired">Expired</option>
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
                
                <!-- Desktop Documents Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Reference No.</th>
                                <th>Issue Date</th>
                                <th>Valid Until</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                @php
                                    $docStatus = getDocumentStatus($document->request_date);
                                    $validUntil = calculateValidUntil($document->request_date);
                                @endphp
                                <tr data-document-id="{{ $document->request_id }}" 
                                    data-document-type="{{ $document->request_type }}"
                                    data-request-date="{{ $document->request_date }}"
                                    data-valid-until="{{ $validUntil }}"
                                    data-status="{{ $docStatus['status'] }}"
                                    data-remarks="{{ $document->remarks }}"
                                    data-updated-at="{{ $document->updated_at }}">
                                    <td>{{ $documentMapping[$document->request_type] ?? $document->request_type }}</td>
                                    <td>{{ formatRequestId($document->request_id) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($document->request_date)->format('M j, Y') }}</td>
                                    <td>{{ $validUntil }}</td>
                                    <td>
                                        <span class="badge {{ $docStatus['class'] }}">
                                            {{ $docStatus['text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1 view-details-btn" 
                                                data-document-id="{{ $document->request_id }}">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        
                                        <!-- ALLOW DOWNLOAD FOR VALID AND EXPIRING, BLOCK ONLY EXPIRED -->
                                        @if($docStatus['status'] !== 'expired')
                                            <button class="btn btn-sm btn-outline-success download-doc-btn" 
                                                    data-document-id="{{ $document->request_id }}">
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if($documents->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                        <h4 class="text-muted">No Documents Yet</h4>
                                        <p class="text-muted">You don't have any completed documents yet.</p>
                                        <a href="{{ route('resident.new-request') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Request a Document
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($documents->count() > 0)
                <nav aria-label="Document pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>

    <!-- Document Details Modal -->
    <div class="modal fade" id="documentDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Details: <span id="modal-doc-ref"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="documentDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading document details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printDocument">Print</button>
                    <button type="button" class="btn btn-success" id="modalDownloadBtn" style="display: none;">
                        <i class="bi bi-download me-2"></i>Download Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Use a self-invoking function to avoid variable conflicts
        (function() {
            'use strict';
            
            // Function to format request ID for display
            function formatRequestId(id) {
                const currentYear = new Date().getFullYear();
                const paddedId = String(id).padStart(5, '0');
                return `BRGY-${currentYear}-${paddedId}`;
            }

            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function() {
                const docType = document.getElementById('documentType').value;
                const status = document.getElementById('statusFilter').value;
                const dateRange = document.getElementById('dateFilter').value;

                // Filter table rows
                const rows = document.querySelectorAll('tbody tr[data-document-id]');
                rows.forEach(row => {
                    let showRow = true;
                    
                    // Document type filter
                    if (docType !== 'all' && row.dataset.documentType !== docType) {
                        showRow = false;
                    }
                    
                    // Status filter
                    if (status !== 'all' && row.dataset.status !== status) {
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
                document.getElementById('documentType').value = 'all';
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('dateFilter').value = 'month';

                // Show all rows
                document.querySelectorAll('tbody tr[data-document-id]').forEach(row => {
                    row.style.display = '';
                });
            });

            // Print Document
            document.getElementById('printDocument').addEventListener('click', function() {
                window.print();
            });

            // Download functionality - ALLOW FOR VALID AND EXPIRING, BLOCK ONLY EXPIRED
            document.querySelectorAll('.download-doc-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const documentId = this.getAttribute('data-document-id');
                    const button = this;
                    
                    // Show loading
                    const originalText = button.innerHTML;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    button.disabled = true;

                    // Trigger download - this will call your PDF generation route
                    window.location.href = `/resident/requests/${documentId}/download`;
                    
                    // Reset button after download starts
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 3000);
                });
            });

            // Modal download button - FIXED
            document.getElementById('modalDownloadBtn').addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                if (!documentId) {
                    console.error('No document ID found for download');
                    return;
                }
                
                const button = this;
                
                // Show loading
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Downloading...';
                button.disabled = true;

                // Trigger download
                window.location.href = `/resident/requests/${documentId}/download`;
                
                // Reset button
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });

            // View Details functionality
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const documentId = this.getAttribute('data-document-id');
                    loadDocumentDetails(documentId);
                });
            });

            function loadDocumentDetails(documentId) {
                const modal = new bootstrap.Modal(document.getElementById('documentDetailsModal'));
                const modalTitle = document.getElementById('modal-doc-ref');
                const modalContent = document.getElementById('documentDetailsContent');
                const modalDownloadBtn = document.getElementById('modalDownloadBtn');

                modalTitle.textContent = formatRequestId(documentId);
                modalContent.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading document details...</p>
                    </div>
                `;
                
                modal.show();

                // Get the row data
                const row = document.querySelector(`tr[data-document-id="${documentId}"]`);
                if (!row) return;

                const docData = {
                    id: documentId,
                    type: row.dataset.documentType,
                    status: row.dataset.status,
                    date: row.dataset.requestDate,
                    updated: row.dataset.updatedAt,
                    remarks: row.dataset.remarks,
                    validUntil: row.dataset.validUntil
                };

                // Display immediately without fetching
                displayDocumentDetails(docData);
            }

            function displayDocumentDetails(docData) {
                const modalContentEl = document.getElementById('documentDetailsContent');
                const modalDownloadBtnEl = document.getElementById('modalDownloadBtn');
                
                const statusBadgeClass = {
                    'valid': 'bg-success',
                    'expiring': 'bg-warning',
                    'expired': 'bg-danger'
                }[docData.status] || 'bg-secondary';

                const statusDisplay = {
                    'valid': 'Valid',
                    'expiring': 'Expiring Soon', 
                    'expired': 'Expired'
                }[docData.status] || 'Unknown';

                const formattedDate = new Date(docData.date + ' UTC').toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const formattedUpdated = new Date(docData.updated + ' UTC').toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long', 
                    day: 'numeric'
                });

                const documentTypeMapping = {
                    'clearance': 'Barangay Clearance',
                    'residency': 'Certificate of Residency',
                    'indigency': 'Certificate of Indigency',
                    'business': 'Business Permit', 
                    'id': 'Barangay ID',
                    'other': 'Other Document'
                };

                const detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Document Type:</strong> ${documentTypeMapping[docData.type] || docData.type}</p>
                            <p><strong>Reference Number:</strong> ${formatRequestId(docData.id)}</p>
                            <p><strong>Purpose:</strong> ${docData.remarks}</p>
                            <p><strong>Issue Date:</strong> ${formattedDate}</p>
                            <p><strong>Valid Until:</strong> ${docData.validUntil}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge ${statusBadgeClass}">${statusDisplay}</span></p>
                            <p><strong>Processing Fee:</strong> ₱100.00</p>  
                            <p><strong>Last Updated:</strong> ${formattedUpdated}</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Current Status</h6>
                    <div id="modal-admin-comments">
                        ${getStatusMessage(docData.status)}
                    </div>
                    <hr>
                    <h6>Document Preview</h6>
                    <div class="text-center">
                        <div class="border rounded p-4 bg-light">
                            <i class="bi bi-file-earmark-pdf display-1 text-danger"></i>
                            <h5 class="mt-3">${documentTypeMapping[docData.type] || docData.type}</h5>
                            <p class="text-muted">Reference: ${formatRequestId(docData.id)}</p>
                            <p class="text-muted">Valid until: ${docData.validUntil}</p>
                        </div>
                    </div>
                `;
                
                modalContentEl.innerHTML = detailsHtml;
                
                // Show download button only for valid and expiring documents (not expired)
                if (docData.status !== 'expired') {
                    modalDownloadBtnEl.style.display = 'block';
                    // Set the document ID directly on the button - FIXED
                    modalDownloadBtnEl.setAttribute('data-document-id', docData.id);
                } else {
                    modalDownloadBtnEl.style.display = 'none';
                    // Remove the data attribute when hidden
                    modalDownloadBtnEl.removeAttribute('data-document-id');
                }
            }

            function getStatusMessage(status) {
                const messages = {
                    'valid': `<div class="alert alert-success" role="alert">
                        <strong>Current Status:</strong> This document is valid and can be downloaded.
                    </div>`,
                    'expiring': `<div class="alert alert-warning" role="alert">
                        <strong>Current Status:</strong> This document is expiring soon. Please download it before it expires.
                    </div>`,
                    'expired': `<div class="alert alert-danger" role="alert">
                        <strong>Current Status:</strong> This document has expired and can no longer be downloaded.
                    </div>`
                };
                return messages[status] || messages.valid;
            }
        })();
    </script>
</x-resident-layout>