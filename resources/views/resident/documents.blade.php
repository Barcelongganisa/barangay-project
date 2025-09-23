<x-resident-layout>
    <style>
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.375rem;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-ready {
            background-color: #d4edda;
            color: #155724;
        }
        .status-released {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .document-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }
        .badge-new {
            background-color: #d1fae5;
            color: #10b981;
        }
        .badge-downloaded {
            background-color: #dbeafe;
            color: #3b82f6;
        }
        .document-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .document-preview {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background-color: white;
            margin-bottom: 1rem;
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
                            <option value="clearance">Barangay Clearance</option>
                            <option value="residency">Certificate of Residency</option>
                            <option value="indigency">Certificate of Indigency</option>
                            <option value="business">Business Permit</option>
                            <option value="id">Barangay ID</option>
                            <option value="other">Other Documents</option>
                        </select>
                        
                        <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                            <option value="all">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="expired">Expired</option>
                            <option value="expiring">Expiring Soon</option>
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
                                <th>Issue Date</th>
                                <th>Valid Until</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Barangay Clearance</td>
                                <td>Oct 20, 2023</td>
                                <td>Jan 20, 2024</td>
                                <td><span class="badge bg-success">Valid</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Certificate of Residency</td>
                                <td>Sep 15, 2023</td>
                                <td>Dec 15, 2023</td>
                                <td><span class="badge bg-success">Valid</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Certificate of Indigency</td>
                                <td>Aug 5, 2023</td>
                                <td>Nov 5, 2023</td>
                                <td><span class="badge bg-warning">Expiring Soon</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Barangay ID</td>
                                <td>Jul 20, 2023</td>
                                <td>Jul 20, 2024</td>
                                <td><span class="badge bg-success">Valid</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Business Permit</td>
                                <td>Jun 10, 2023</td>
                                <td>Jun 10, 2024</td>
                                <td><span class="badge bg-success">Valid</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Health Certificate</td>
                                <td>May 5, 2023</td>
                                <td>Aug 5, 2023</td>
                                <td><span class="badge bg-danger">Expired</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#documentDetailsModal">
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
            </div>
        </div>
    </div>

    <!-- Document Details Modal -->
    <div class="modal fade" id="documentDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Document Type:</strong> Barangay Clearance</p>
                            <p><strong>Reference Number:</strong> BRGY-2023-00865</p>
                            <p><strong>Issue Date:</strong> October 20, 2023</p>
                            <p><strong>Valid Until:</strong> January 20, 2024</p>
                            <p><strong>Status:</strong> <span class="badge bg-success">Valid</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Purpose:</strong> Employment Requirements</p>
                            <p><strong>Processing Fee:</strong> ₱100.00</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Document Preview</h6>
                    <div class="text-center">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHlsZT0iYmFja2dyb3VuZC1jb2xvcjojZjJmMmYyO2JvcmRlci1yYWRpdXM6NHB4OyI+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtZmFtaWx5PSJtb25vc3BhY2UiIGZvbnQtc2l6ZT0iMTZweCIgZmlsbD0iIzY0NzQ4YiI+QkFSQU5HQVkgQ0xFQVJBTkNFIFBSRVZJRVc8L3RleHQ+PC9zdmc+" alt="Document preview" class="img-fluid rounded">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-download"></i> Download
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function() {
                const docType = document.getElementById('documentType').value;
                const status = document.getElementById('statusFilter').value;
                const dateRange = document.getElementById('dateFilter').value;

                // In a real application, this would filter the documents
                alert(`Filters applied:\nDocument Type: ${docType}\nStatus: ${status}\nDate Range: ${dateRange}`);
            });

            document.getElementById('resetFilters').addEventListener('click', function() {
                document.getElementById('documentType').value = 'all';
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('dateFilter').value = 'month';

                // In a real application, this would reset the filters and show all documents
                alert('Filters reset');
            });

            // Download buttons
            const downloadButtons = document.querySelectorAll('.btn-outline-success');
            downloadButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const docName = row.querySelector('td:first-child').textContent;

                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Downloading...';
                    this.disabled = true;

                    // Simulate download process
                    setTimeout(() => {
                        alert(`Downloading ${docName}`);
                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 1500);
                });
            });

            // View buttons - set up modal content based on document
            const viewButtons = document.querySelectorAll('[data-bs-target="#documentDetailsModal"]');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const docType = row.cells[0].textContent;
                    const issueDate = row.cells[1].textContent;
                    const validUntil = row.cells[2].textContent;
                    const status = row.cells[3].textContent;
                    
                    // In a real application, you would update the modal content
                    // based on the specific document data
                    console.log('Viewing document:', { docType, issueDate, validUntil, status });
                });
            });
        });
    </script>
</x-resident-layout>