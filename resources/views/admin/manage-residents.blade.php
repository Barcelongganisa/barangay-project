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
    </style>

    <div class="main-content" id="mainContent">
        

        <div class="container-fluid p-4" style="    position: relative;
    left: -120px;">
            {{-- Search & Reset --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="searchBar" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchBar" placeholder="Search by name or address...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100">Reset</button>                        </div>
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
                            <tbody>
                                {{-- Dummy Data Rows --}}
                                <tr>
                                    <th scope="row">RES-001</th>
                                    <td>Juan Dela Cruz</td>
                                    <td>Purok 1, Brgy. San Jose</td>
                                    <td>0917-123-4567</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#residentDetailsModal">View Details</button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#residentHistoryModal">History</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeResidentModal">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">RES-002</th>
                                    <td>Maria Santos</td>
                                    <td>Purok 2, Brgy. San Jose</td>
                                    <td>0920-987-6543</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#residentDetailsModal">View Details</button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#residentHistoryModal">History</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeResidentModal">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">RES-003</th>
                                    <td>Pedro Reyes</td>
                                    <td>Purok 3, Brgy. San Jose</td>
                                    <td>0918-555-1234</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#residentDetailsModal">View Details</button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#residentHistoryModal">History</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeResidentModal">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">RES-004</th>
                                    <td>Ana Lopez</td>
                                    <td>Purok 4, Brgy. San Jose</td>
                                    <td>0927-444-7890</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#residentDetailsModal">View Details</button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#residentHistoryModal">History</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeResidentModal">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">RES-005</th>
                                    <td>Miguel Garcia</td>
                                    <td>Purok 5, Brgy. San Jose</td>
                                    <td>0919-333-4567</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#residentDetailsModal">View Details</button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#residentHistoryModal">History</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeResidentModal">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resident Details Modal --}}
    {{-- <x-modal id="residentDetailsModal" title="Resident Details">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Resident ID:</strong> RES-001</p>
                    <p><strong>Name:</strong> Juan Dela Cruz</p>
                    <p><strong>Address:</strong> Purok 1, Brgy. San Jose</p>
                    <p><strong>Contact #:</strong> 0917-123-4567</p>
                    <p><strong>Email:</strong> juan.delacruz@example.com</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date of Birth:</strong> January 15, 1985</p>
                    <p><strong>Gender:</strong> Male</p>
                    <p><strong>Civil Status:</strong> Married</p>
                    <p><strong>Occupation:</strong> Engineer</p>
                    <p><strong>Registration Date:</strong> March 12, 2023</p>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </x-slot:footer>
    </x-modal> --}}

    {{-- Resident History Modal --}}
    {{-- <x-modal id="residentHistoryModal" title="Request History for Juan Dela Cruz">
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Document Type</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>REQ-2023-001</td>
                            <td>Barangay Clearance</td>
                            <td>Sep 20, 2023</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>REQ-2023-004</td>
                            <td>Barangay ID</td>
                            <td>Sep 17, 2023</td>
                            <td><span class="badge bg-warning">Processing</span></td>
                        </tr>
                        <tr>
                            <td>REQ-2023-007</td>
                            <td>Certificate of Residency</td>
                            <td>Aug 25, 2023</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>REQ-2023-012</td>
                            <td>Business Permit</td>
                            <td>Jul 15, 2023</td>
                            <td><span class="badge bg-info">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </x-slot:footer>
    </x-modal> --}}

    {{-- Remove Resident Modal --}}
    {{-- <x-modal id="removeResidentModal" title="Remove Resident">
        <div class="modal-body">
            <p>Are you sure you want to remove <strong>Juan Dela Cruz</strong> from the resident list?</p>
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
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" disabled id="removeButton">Remove Resident</button>
        </x-slot:footer>
    </x-modal> --}}

    <!-- Resident Details Modal -->
<div class="modal fade" id="residentDetailsModal" tabindex="-1" aria-labelledby="residentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="residentDetailsModalLabel">Resident Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Resident ID:</strong> RES-001</p>
            <p><strong>Name:</strong> Juan Dela Cruz</p>
            <p><strong>Address:</strong> Purok 1, Brgy. San Jose</p>
            <p><strong>Contact #:</strong> 0917-123-4567</p>
            <p><strong>Email:</strong> juan.delacruz@example.com</p>
          </div>
          <div class="col-md-6">
            <p><strong>Date of Birth:</strong> January 15, 1985</p>
            <p><strong>Gender:</strong> Male</p>
            <p><strong>Civil Status:</strong> Married</p>
            <p><strong>Occupation:</strong> Engineer</p>
            <p><strong>Registration Date:</strong> March 12, 2023</p>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="residentHistoryModalLabel">Request History for Juan Dela Cruz</h5>
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
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>REQ-2023-001</td>
                <td>Barangay Clearance</td>
                <td>Sep 20, 2023</td>
                <td><span class="badge bg-success">Completed</span></td>
              </tr>
              <tr>
                <td>REQ-2023-004</td>
                <td>Barangay ID</td>
                <td>Sep 17, 2023</td>
                <td><span class="badge bg-warning">Processing</span></td>
              </tr>
              <tr>
                <td>REQ-2023-007</td>
                <td>Certificate of Residency</td>
                <td>Aug 25, 2023</td>
                <td><span class="badge bg-success">Completed</span></td>
              </tr>
              <tr>
                <td>REQ-2023-012</td>
                <td>Business Permit</td>
                <td>Jul 15, 2023</td>
                <td><span class="badge bg-info">Pending</span></td>
              </tr>
            </tbody>
          </table>
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
        <p>Are you sure you want to remove <strong>Juan Dela Cruz</strong> from the resident list?</p>
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


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Simple search functionality (just for show)
                const searchBar = document.getElementById('searchBar');
                if (searchBar) {
                    searchBar.addEventListener('input', function() {
                        // Just show that it's working - no actual filtering
                        console.log('Searching for:', this.value);
                    });
                }

                // Remove confirmation checkbox
                const confirmCheckbox = document.getElementById('confirmRemove');
                const removeButton = document.getElementById('removeButton');
                
                if (confirmCheckbox && removeButton) {
                    confirmCheckbox.addEventListener('change', function() {
                        removeButton.disabled = !this.checked;
                    });
                }

                // Reset button functionality
                const resetButton = document.querySelector('.btn-outline-secondary');
                if (resetButton) {
                    resetButton.addEventListener('click', function() {
                        if (searchBar) {
                            searchBar.value = '';
                        }
                        console.log('Filters reset');
                    });
                }

                // Remove button confirmation
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        // Show loading state
                        const originalText = this.innerHTML;
                        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Removing...';
                        this.disabled = true;

                        // Simulate removal process
                        setTimeout(() => {
                            alert('Resident has been removed successfully.');
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('removeResidentModal'));
                            modal.hide();
                            
                            // Reset button and checkbox
                            this.innerHTML = originalText;
                            this.disabled = true;
                            if (confirmCheckbox) {
                                confirmCheckbox.checked = false;
                            }
                        }, 1500);
                    });
                }
            });
        </script>
    @endpush
</x-admin-layout>