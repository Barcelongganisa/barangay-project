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
        .btn-custom-size {
            width: 130px;
            white-space: nowrap;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .request-status {
            padding: 0.25em 0.6em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 0.375rem;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-under-review { background-color: #e2e3e5; color: #495057; }
        .status-waiting-payment { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-declined { background-color: #f8d7da; color: #721c24; }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid p-4" style="position: relative; left: -120px;">
            {{-- Search & Filter --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="searchBar" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchBar" placeholder="Search by name or address...">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100 me-2" id="applyFilters">Apply Filters</button>
                            <button class="btn btn-outline-secondary w-100" id="resetFilters">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Residents Table --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Resident ID</th>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Contact #</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="residentsTableBody">
                                @forelse($residents as $resident)
                                    <tr data-resident-id="{{ $resident->resident_id }}">
                                        <th scope="row">RES-{{ str_pad($resident->resident_id, 3, '0', STR_PAD_LEFT) }}</th>
                                        <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
                                        <td>{{ $resident->address ?? 'No address provided' }}</td>
                                        <td>{{ $resident->contact_number ?? 'No contact number' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-secondary view-details-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#residentDetailsModal"
                                                        data-resident-id="{{ $resident->resident_id }}">
                                                    View Details
                                                </button>
                                                <button class="btn btn-sm btn-info text-white history-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#residentHistoryModal"
                                                        data-resident-id="{{ $resident->resident_id }}"
                                                        data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                                                    History
                                                </button>
                                                <button class="btn btn-sm btn-danger remove-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#removeResidentModal"
                                                        data-resident-id="{{ $resident->resident_id }}"
                                                        data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">No residents found.</div>
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

    <!-- Resident Details Modal -->
    <div class="modal fade" id="residentDetailsModal" tabindex="-1" aria-labelledby="residentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="residentDetailsModalLabel">Resident Details: <span id="modal-resident-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Resident ID:</strong> <span id="modal-resident-id-display"></span></p>
                            <p><strong>Name:</strong> <span id="modal-name"></span></p>
                            <p><strong>Address:</strong> <span id="modal-address"></span></p>
                            <p><strong>Contact #:</strong> <span id="modal-contact"></span></p>
                            <p><strong>Email:</strong> <span id="modal-email"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> <span id="modal-dob"></span></p>
                            <p><strong>Gender:</strong> <span id="modal-gender"></span></p>
                            <p><strong>Civil Status:</strong> <span id="modal-civil-status"></span></p>
                            <p><strong>Occupation:</strong> <span id="modal-occupation"></span></p>
                            <p><strong>Registration Date:</strong> <span id="modal-registration-date"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resident History Modal -->
    <div class="modal fade" id="residentHistoryModal" tabindex="-1" aria-labelledby="residentHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="residentHistoryModalLabel">Request History for <span id="history-resident-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Document Type</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="no-history-message" class="text-center py-4" style="display: none;">
                        <div class="text-muted">No request history found for this resident.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Resident Modal -->
    <div class="modal fade" id="removeResidentModal" tabindex="-1" aria-labelledby="removeResidentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeResidentModalLabel">Remove Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove <strong id="remove-resident-name"></strong> from the resident list?</p>
                    <div class="alert alert-danger mt-3">
                        <strong>Warning:</strong> This action is permanent and cannot be undone. All resident data and history will be permanently deleted.
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmRemove">
                        <label class="form-check-label" for="confirmRemove">
                            I understand this action cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" disabled id="removeButton">Remove Resident</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentResidentId = null;

            // Search functionality
            const searchBar = document.getElementById('searchBar');
            const applyFiltersBtn = document.getElementById('applyFilters');
            const resetButton = document.getElementById('resetFilters');
            
            // Apply filters when button is clicked
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', function() {
                    const searchTerm = searchBar ? searchBar.value.toLowerCase() : '';
                    filterResidents(searchTerm);
                });
            }

            // Auto-search as user types (optional - you can remove this if you only want button click)
            if (searchBar) {
                searchBar.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    filterResidents(searchTerm);
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    if (searchBar) {
                        searchBar.value = '';
                    }
                    filterResidents('');
                });
            }

            function filterResidents(searchTerm) {
                const rows = document.querySelectorAll('#residentsTableBody tr');
                let hasVisibleRows = false;

                rows.forEach(row => {
                    if (row.cells.length < 4) return; // Skip empty rows
                    
                    const name = row.cells[1].textContent.toLowerCase();
                    const address = row.cells[2].textContent.toLowerCase();
                    const rowText = name + ' ' + address;
                    
                    if (searchTerm === '' || rowText.includes(searchTerm)) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show no results message if needed
                const noResultsRow = document.querySelector('#residentsTableBody .no-results');
                if (!hasVisibleRows && !noResultsRow) {
                    const tbody = document.getElementById('residentsTableBody');
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-results';
                    newRow.innerHTML = '<td colspan="5" class="text-center py-4"><div class="text-muted">No residents match your search.</div></td>';
                    tbody.appendChild(newRow);
                } else if (hasVisibleRows && noResultsRow) {
                    noResultsRow.remove();
                }
            }

            // View Details Modal
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const residentId = this.dataset.residentId;
                    currentResidentId = residentId;
                    loadResidentDetails(residentId);
                });
            });

            // History Modal
            document.querySelectorAll('.history-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const residentId = this.dataset.residentId;
                    const residentName = this.dataset.residentName;
                    currentResidentId = residentId;
                    
                    document.getElementById('history-resident-name').textContent = residentName;
                    loadResidentHistory(residentId);
                });
            });

            // Remove Modal
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const residentId = this.dataset.residentId;
                    const residentName = this.dataset.residentName;
                    currentResidentId = residentId;
                    
                    document.getElementById('remove-resident-name').textContent = residentName;
                    
                    // Reset confirmation checkbox
                    document.getElementById('confirmRemove').checked = false;
                    document.getElementById('removeButton').disabled = true;
                });
            });

            // Load resident details
            async function loadResidentDetails(residentId) {
                try {
                    const response = await fetch(`/admin/residents/${residentId}/details`);
                    const data = await response.json();
                    
                    if (data.success) {
                        const resident = data.resident;
                        
                        document.getElementById('modal-resident-id').textContent = 'RES-' + String(residentId).padStart(3, '0');
                        document.getElementById('modal-resident-id-display').textContent = 'RES-' + String(residentId).padStart(3, '0');
                        document.getElementById('modal-name').textContent = `${resident.first_name} ${resident.last_name}`;
                        document.getElementById('modal-address').textContent = resident.address || 'No address provided';
                        document.getElementById('modal-contact').textContent = resident.contact_number || 'No contact number';
                        document.getElementById('modal-email').textContent = resident.email || 'No email provided';
                        document.getElementById('modal-dob').textContent = resident.date_of_birth ? new Date(resident.date_of_birth).toLocaleDateString() : 'Not specified';
                        document.getElementById('modal-gender').textContent = resident.gender || 'Not specified';
                        document.getElementById('modal-civil-status').textContent = resident.civil_status || 'Not specified';
                        document.getElementById('modal-occupation').textContent = resident.occupation || 'Not specified';
                        document.getElementById('modal-registration-date').textContent = resident.created_at ? new Date(resident.created_at).toLocaleDateString() : 'Not specified';
                    }
                } catch (error) {
                    console.error('Error loading resident details:', error);
                }
            }

            // Load resident history - FIXED to show ALL service requests
            async function loadResidentHistory(residentId) {
                try {
                    console.log('Loading history for resident:', residentId);
                    const response = await fetch(`/admin/residents/${residentId}/history`);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('History data:', data);
                    
                    const historyTableBody = document.getElementById('historyTableBody');
                    const noHistoryMessage = document.getElementById('no-history-message');
                    
                    historyTableBody.innerHTML = '';
                    
                    if (data.success && data.requests && data.requests.length > 0) {
                        noHistoryMessage.style.display = 'none';
                        
                        data.requests.forEach(request => {
                            const row = document.createElement('tr');
                            const requestDate = new Date(request.request_date);
                            const statusClass = 'status-' + request.status.replace(' ', '-');
                            const statusDisplay = request.status.charAt(0).toUpperCase() + request.status.slice(1);
                            
                            row.innerHTML = `
                                <td>REQ-${String(request.request_id).padStart(3, '0')}</td>
                                <td>${request.request_type}</td>
                                <td>${requestDate.toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric', 
                                    year: 'numeric',
                                    hour: 'numeric',
                                    minute: '2-digit',
                                    hour12: true 
                                })}</td>
                                <td><span class="request-status ${statusClass}">${statusDisplay}</span></td>
                                <td>${request.remarks || 'No remarks'}</td>
                            `;
                            historyTableBody.appendChild(row);
                        });
                    } else {
                        noHistoryMessage.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error loading resident history:', error);
                    const noHistoryMessage = document.getElementById('no-history-message');
                    noHistoryMessage.innerHTML = '<div class="text-danger">Error loading history. Please try again.</div>';
                    noHistoryMessage.style.display = 'block';
                }
            }

            // Remove confirmation checkbox
            const confirmCheckbox = document.getElementById('confirmRemove');
            const removeButton = document.getElementById('removeButton');
            
            if (confirmCheckbox && removeButton) {
                confirmCheckbox.addEventListener('change', function() {
                    removeButton.disabled = !this.checked;
                });
            }

            // Remove resident
            if (removeButton) {
                removeButton.addEventListener('click', async function() {
                    if (!currentResidentId) return;
                    
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Removing...';
                    this.disabled = true;

                    try {
                        const response = await fetch(`/admin/residents/${currentResidentId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            // Close modal and reload page
                            const modal = bootstrap.Modal.getInstance(document.getElementById('removeResidentModal'));
                            modal.hide();
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                            // Reset button
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error removing resident:', error);
                        alert('Error removing resident. Please try again.');
                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });
            }
        });
    </script>
</x-admin-layout>