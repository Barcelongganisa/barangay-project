<x-admin-layout>
    @php
        // Function to format ID as BRGY-YEAR-00000
        function formatId($id) {
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
        .btn-custom-size {
            width: 130px;
            white-space: nowrap;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: nowrap; /* dati wrap */
            justify-content: flex-start;
            align-items: center;
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
        .status-approved { background-color: #d4edda; color: #155724; }
    </style>

    <div class="main-content" id="mainContent">
       <div class="container-fluid p-4" style="position: relative; left: -250px; width: calc(100% + 250px);">
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
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
<tbody id="residentsTableBody">
    @forelse($residents as $resident)
        <tr data-resident-id="{{ $resident->resident_id }}">
            <th scope="row">{{ formatId($resident->resident_id) }}</th>
            <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
            <td>{{ $resident->address ?? 'No address provided' }}</td>
            <td>{{ $resident->contact_number ?? 'No contact number' }}</td>
            <td>
                @php
                    // Determine status based on user approval status
                    $user = \App\Models\User::find($resident->resident_id);
                    $status = $user ? $user->approval_status : 'unknown';
                    $statusClass = 'status-' . $status;
                    $statusText = ucfirst($status);
                @endphp
                <span class="request-status {{ $statusClass }}">{{ $statusText }}</span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-outline-secondary view-details-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#residentDetailsModal"
                            data-resident-id="{{ $resident->resident_id }}">
                        View
                    </button>
                    <button class="btn btn-sm btn-info text-white history-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#residentHistoryModal"
                            data-resident-id="{{ $resident->resident_id }}"
                            data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                        History
                    </button>
                    
                    @if($user && $user->isPending())
                        {{-- Show Approve/Decline buttons for pending users --}}
                        <button class="btn btn-sm btn-success action-modal-trigger"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmationModal"
                                data-action-type="approve"
                                data-resident-id="{{ $resident->resident_id }}"
                                data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                            Approve
                        </button>
                        <button class="btn btn-sm btn-danger action-modal-trigger"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmationModal"
                                data-action-type="decline"
                                data-resident-id="{{ $resident->resident_id }}"
                                data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                            Decline
                        </button>
                    @else
                        {{-- Show Remove button for approved/declined users --}}
                        <button class="btn btn-sm btn-danger remove-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#removeResidentModal"
                                data-resident-id="{{ $resident->resident_id }}"
                                data-resident-name="{{ $resident->first_name }} {{ $resident->last_name }}">
                            Remove
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center py-4">
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
    

{{-- RESIDENT DETAILS MODAL --}}
<div class="modal fade" id="residentDetailsModal" tabindex="-1" aria-labelledby="residentDetailsModalLabel" aria-hidden="true">
    {{-- ADDED: modal-dialog-centered class --}}
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
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
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mt-2">Valid ID for Verification:</h6>
                        <div id="modal-valid-id-image-container" class="border p-2 rounded text-center" style="min-height: 150px; background-color: #f8f9fa;">
                            <a id="modal-valid-id-link" href="#" target="_blank" style="display: none;">
                                <img id="modal-valid-id-image" src="" class="img-fluid rounded" style="max-height: 300px; max-width: 100%; object-fit: contain;" alt="Valid ID">
                            </a>
                            <div id="modal-valid-id-not-provided" class="text-muted py-5">
                                No Valid ID provided.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    </div>

    {{-- RESIDENT HISTORY MODAL --}}
    <div class="modal fade" id="residentHistoryModal" tabindex="-1" aria-labelledby="residentHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
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

    {{-- REMOVE RESIDENT MODAL (CONFIRMATION) --}}
    <div class="modal fade" id="removeResidentModal" tabindex="-1" aria-labelledby="removeResidentModalLabel" aria-hidden="true">
        {{-- ADDED: modal-dialog-centered class --}}
        <div class="modal-dialog modal-dialog-centered"> 
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
    
    {{-- APPROVE/DECLINE CONFIRMATION MODAL --}}
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        {{-- ADDED: modal-dialog-centered class --}}
        <div class="modal-dialog modal-dialog-centered"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmationMessage"></p>
                    <div class="mb-3" id="declineReasonGroup" style="display:none;">
                        <label for="declineReason" class="form-label">Reason for Declining (Required)</label>
                        <textarea class="form-control" id="declineReason" rows="3"></textarea>
                        <div class="text-danger mt-1" id="reasonError" style="display:none;">Reason is required to decline.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn" id="confirmActionButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentResidentId = null;
    let currentActionType = null;

    // Function to format ID as BRGY-YEAR-00000
    function formatIdClient(id) {
        const currentYear = new Date().getFullYear();
        const paddedId = String(id).padStart(5, '0');
        return "BRGY-"+currentYear+"-"+paddedId;
    }

    // Search & Filter functions
    const searchBar = document.getElementById('searchBar');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetButton = document.getElementById('resetFilters');
    
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const searchTerm = searchBar ? searchBar.value.toLowerCase() : '';
            filterResidents(searchTerm);
        });
    }

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
            if (row.cells.length < 4) return;
            
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

        const noResultsRow = document.querySelector('#residentsTableBody .no-results');
        if (!hasVisibleRows && !noResultsRow) {
            const tbody = document.getElementById('residentsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'no-results';
            newRow.innerHTML = '<td colspan="6" class="text-center py-4"><div class="text-muted">No residents match your search.</div></td>';
            tbody.appendChild(newRow);
        } else if (hasVisibleRows && noResultsRow) {
            noResultsRow.remove();
        }
    }
    
    // Logic for Approve/Decline Modal Trigger
    document.querySelectorAll('.action-modal-trigger').forEach(button => {
        button.addEventListener('click', function() {
            const residentId = this.dataset.residentId;
            const residentName = this.dataset.residentName;
            const actionType = this.dataset.actionType;
            const formattedId = formatIdClient(residentId);

            currentResidentId = residentId;
            currentActionType = actionType;
            
            const actionDisplay = actionType.charAt(0).toUpperCase() + actionType.slice(1);
            const confirmActionButton = document.getElementById('confirmActionButton');
            const confirmationMessage = document.getElementById('confirmationMessage');
            const declineReasonGroup = document.getElementById('declineReasonGroup');
            const declineReason = document.getElementById('declineReason');
            const reasonError = document.getElementById('reasonError');

            // Reset state
            declineReason.value = '';
            declineReasonGroup.style.display = 'none';
            reasonError.style.display = 'none';

            // Set modal content
            confirmationMessage.innerHTML = `Are you sure you want to <strong>${actionDisplay.toUpperCase()}</strong> the registration for <strong>${residentName}</strong> (${formattedId})?`;
            
            if (actionType === 'approve') {
                confirmActionButton.textContent = 'Confirm Approval';
                confirmActionButton.className = 'btn btn-success';
                confirmActionButton.disabled = false;
            } else if (actionType === 'decline') {
                confirmActionButton.textContent = 'Confirm Decline';
                confirmActionButton.className = 'btn btn-danger';
                declineReasonGroup.style.display = 'block';
                confirmActionButton.disabled = true; // Disable until reason is provided
            }
        });
    });

    // Logic for Decline reason check
    const declineReason = document.getElementById('declineReason');
    const confirmActionButton = document.getElementById('confirmActionButton');

    if (declineReason && confirmActionButton) {
        declineReason.addEventListener('input', function() {
            if (currentActionType === 'decline') {
                const isFilled = this.value.trim().length > 0;
                confirmActionButton.disabled = !isFilled;
                document.getElementById('reasonError').style.display = isFilled ? 'none' : 'block';
            }
        });
    }

    // Logic for Confirmation Action (User Approval)
    if (confirmActionButton) {
        confirmActionButton.addEventListener('click', async function() {
            const actionType = currentActionType;
            const residentId = currentResidentId;
            const declineReason = document.getElementById('declineReason');
            const reason = actionType === 'decline' ? declineReason.value.trim() : null;
            const reasonError = document.getElementById('reasonError');

            // Check for decline reason validity
            if (actionType === 'decline' && reason.length === 0) {
                reasonError.style.display = 'block';
                return;
            } else {
                reasonError.style.display = 'none';
            }

            // Show loading state
            const originalText = this.textContent;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            try {
                const url = actionType === 'approve' 
                    ? `/admin/users/${residentId}/approve`
                    : `/admin/users/${residentId}/decline`;
                
                const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                };

                if (actionType === 'decline') {
                    options.body = JSON.stringify({ reason: reason });
                }

                const response = await fetch(url, options);
                const data = await response.json();

                if (data.success) {
                    // Close modal and reload page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error processing request. Please try again.');
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
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

    // Remove Resident Modal
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
                
                const formattedId = 'BRGY-' + new Date().getFullYear() + '-' + String(residentId).padStart(5, '0');
                
                document.getElementById('modal-resident-id').textContent = formattedId;
                document.getElementById('modal-resident-id-display').textContent = formattedId;
                document.getElementById('modal-name').textContent = `${resident.first_name} ${resident.last_name}`;
                document.getElementById('modal-address').textContent = resident.address || 'No address provided';
                document.getElementById('modal-contact').textContent = resident.contact_number || 'No contact number';
                document.getElementById('modal-email').textContent = resident.email || 'No email provided';
                document.getElementById('modal-dob').textContent = resident.date_of_birth ? new Date(resident.date_of_birth).toLocaleDateString() : 'Not specified';
                document.getElementById('modal-gender').textContent = resident.gender || 'Not specified';
                document.getElementById('modal-civil-status').textContent = resident.civil_status || 'Not specified';
                document.getElementById('modal-occupation').textContent = resident.occupation || 'Not specified';
                document.getElementById('modal-registration-date').textContent = resident.created_at ? new Date(resident.created_at).toLocaleDateString() : 'Not specified';

                // Handle Valid ID display - FIXED: Use data.valid_id_path instead of validIdPath
                const validIdLink = document.getElementById('modal-valid-id-link');
                const validIdImage = document.getElementById('modal-valid-id-image');
                const validIdNotProvided = document.getElementById('modal-valid-id-not-provided');

                if (data.valid_id_path) { // FIX: Use data.valid_id_path
                    // Show the image/link
                    validIdLink.style.display = 'block';
                    validIdImage.src = `/storage/${data.valid_id_path}`; // FIX: Use data.valid_id_path
                    validIdLink.href = `/storage/${data.valid_id_path}`; // FIX: Use data.valid_id_path
                    validIdNotProvided.style.display = 'none';
                } else {
                    // Hide the image/link and show "not provided" message
                    validIdLink.style.display = 'none';
                    validIdImage.src = '';
                    validIdLink.href = '#';
                    validIdNotProvided.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error loading resident details:', error);
        }
    }

    // Load resident history
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
                    
                    const formattedRequestId = 'BRGY-' + new Date().getFullYear() + '-' + String(request.request_id).padStart(5, '0');
                    
                    row.innerHTML = `
                        <td>${formattedRequestId}</td>
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

    // Remove resident confirmation
    const confirmCheckbox = document.getElementById('confirmRemove');
    const removeButton = document.getElementById('removeButton');
    
    if (confirmCheckbox && removeButton) {
        confirmCheckbox.addEventListener('change', function() {
            removeButton.disabled = !this.checked;
        });
    }

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