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
        .status-under-review { background-color: #e2e3e5; color: #495057; }
        .status-waiting-payment { background-color: #fff3cd; color: #856404; }
        .status-declined { background-color: #f8d7da; color: #721c24; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-complete { background-color: #d4edda; color: #155724; }
        .btn-custom-size {
            width: 130px;
            white-space: nowrap;
        }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="    position: relative;
    left: -120px;">
            {{-- Filter Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filterStatus" class="form-label">Filter by Status</label>
                            <select class="form-select" id="filterStatus">
                                <option selected>All</option>
                                <option value="under-review">Under Review</option>
                                <option value="waiting-payment">Waiting for Payment</option>
                                <option value="processing">Processing</option>
                                <option value="complete">Complete</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterType" class="form-label">Filter by Document Type</label>
                            <select class="form-select" id="filterType">
                                <option selected>All</option>
                                <option value="residency">Barangay Residency</option>
                                <option value="indigency">Certificate of Indigency</option>
                                <option value="business">Business Clearance</option>
                                <option value="id">Barangay ID</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchBar" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchBar" placeholder="Search by name or request #...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100 me-2 btn-custom-size">Apply Filters</button>
                            <button class="btn btn-outline-secondary w-100 btn-custom-size">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Requests Table --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                            <tbody>
                                <tr data-request-id="REQ-001" data-status="under-review">
                                    <th scope="row">REQ-001</th>
                                    <td>Juan Dela Cruz</td>
                                    <td>Barangay Residency</td>
                                    <td>Sep 20, 2025</td>
                                    <td><span class="request-status status-under-review">Under Review</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-custom-size process-action-btn" data-bs-toggle="modal" data-bs-target="#processModal" data-action-type="review">Process</button>
                                    </td>
                                </tr>
                                <tr data-request-id="REQ-002" data-status="waiting-payment">
                                    <th scope="row">REQ-002</th>
                                    <td>Maria Santos</td>
                                    <td>Certificate of Indigency</td>
                                    <td>Sep 19, 2025</td>
                                    <td><span class="request-status status-waiting-payment">Waiting for Payment</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success btn-custom-size process-action-btn" data-bs-toggle="modal" data-bs-target="#processModal" data-action-type="confirm-payment">Confirm Payment</button>
                                    </td>
                                </tr>
                                <tr data-request-id="REQ-003" data-status="declined">
                                    <th scope="row">REQ-003</th>
                                    <td>Jose Rizal</td>
                                    <td>Business Clearance</td>
                                    <td>Sep 18, 2025</td>
                                    <td><span class="request-status status-declined">Declined</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary btn-custom-size view-details-btn" data-bs-toggle="modal" data-bs-target="#detailsModal">View</button>
                                    </td>
                                </tr>
                                <tr data-request-id="REQ-004" data-status="processing">
                                    <th scope="row">REQ-004</th>
                                    <td>Teresita Reyes</td>
                                    <td>Barangay ID</td>
                                    <td>Sep 17, 2025</td>
                                    <td><span class="request-status status-processing">Processing</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-custom-size process-action-btn" data-bs-toggle="modal" data-bs-target="#processModal" data-action-type="processing">Process</button>
                                    </td>
                                </tr>
                                <tr data-request-id="REQ-005" data-status="complete">
                                    <th scope="row">REQ-005</th>
                                    <td>John Doe</td>
                                    <td>Certificate of Indigency</td>
                                    <td>Sep 16, 2025</td>
                                    <td><span class="request-status status-complete">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary btn-custom-size view-details-btn" data-bs-toggle="modal" data-bs-target="#detailsModal">View</button>
                                    </td>
                                </tr>
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
                    <div class="alert alert-info">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Uploaded Documents (Barangay Clearance, etc.)
                        <a href="#" class="btn btn-sm btn-primary float-end">View</a>
                    </div>
                    <hr>
                    <div id="process-modal-dynamic-content"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <div id="process-modal-action-buttons"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Highlight sidebar link
                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.href.includes('manage_requests')) {
                        link.classList.add('active');
                    }
                });

                // View Details Modal
                document.querySelectorAll('.view-details-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const requestId = row.dataset.requestId;
                        const status = row.dataset.status;

                        document.getElementById('modal-request-id').textContent = requestId;
                        document.getElementById('modal-resident-name').textContent = row.cells[1].textContent;
                        document.getElementById('modal-document-type').textContent = row.cells[2].textContent;
                        document.getElementById('modal-date-submitted').textContent = row.cells[3].textContent;
                        document.getElementById('modal-status').textContent = row.cells[4].textContent;

                        const dynamicContent = document.getElementById('modal-dynamic-content');
                        dynamicContent.innerHTML = '';

                        if (status === 'under-review') {
                            dynamicContent.innerHTML = `<div class="alert alert-warning">Not yet reviewed.</div>`;
                        } else if (status === 'waiting-payment') {
                            dynamicContent.innerHTML = `<div class="alert alert-secondary">The request has been approved. The resident has been notified to proceed with payment.<p class="mb-0 mt-2"><strong>Fee:</strong> ₱50.00</p></div>`;
                        } else if (status === 'processing') {
                            dynamicContent.innerHTML = `<div class="alert alert-info">The request is currently being processed.</div>`;
                        } else if (status === 'declined') {
                            dynamicContent.innerHTML = `<div class="alert alert-danger">This request has been declined.<p class="mb-0 mt-2"><strong>Reason:</strong> Invalid uploaded document.</p></div>`;
                        } else if (status === 'complete') {
                            dynamicContent.innerHTML = `<div class="alert alert-success">This request has been completed.<p class="mb-0 mt-2"><strong>Comment:</strong> Document ready for pickup.</p></div>`;
                        }
                    });
                });

                // Process Modal
                document.querySelectorAll('.process-action-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const requestId = row.dataset.requestId;
                        const actionType = this.dataset.actionType;

                        document.getElementById('process-modal-request-id').textContent = requestId;
                        document.getElementById('process-modal-resident-name').textContent = row.cells[1].textContent;
                        document.getElementById('process-modal-document-type').textContent = row.cells[2].textContent;

                        const dynamicContent = document.getElementById('process-modal-dynamic-content');
                        const actionButtons = document.getElementById('process-modal-action-buttons');
                        dynamicContent.innerHTML = '';
                        actionButtons.innerHTML = '';

                        if (actionType === 'review') {
                            dynamicContent.innerHTML = `
                                <div class="mb-3">
                                    <label class="form-label">Admin Comment</label>
                                    <textarea class="form-control" rows="3" placeholder="Add a comment..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Processing Fee</label>
                                    <input type="number" class="form-control" placeholder="e.g., 50">
                                    <div class="form-text">Enter the fee for this document.</div>
                                </div>`;
                            actionButtons.innerHTML = `
                                <button class="btn btn-danger btn-custom-size">Decline</button>
                                <button class="btn btn-success btn-custom-size">Approve</button>`;
                        } else if (actionType === 'confirm-payment') {
                            dynamicContent.innerHTML = `<p class="text-center">Confirm payment for this request?</p>`;
                            actionButtons.innerHTML = `<button class="btn btn-primary btn-custom-size">Yes, Confirm</button>`;
                        } else if (actionType === 'processing') {
                            dynamicContent.innerHTML = `
                                <div class="mb-3">
                                    <label class="form-label">Upload Document</label>
                                    <input class="form-control" type="file">
                                    <div class="form-text">Accepted: PDF, Max: 5MB</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment for Resident</label>
                                    <textarea class="form-control" rows="3" placeholder="e.g., Document ready for pickup."></textarea>
                                </div>`;
                            actionButtons.innerHTML = `<button class="btn btn-success btn-custom-size">Mark as Complete</button>`;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>
