<x-resident-layout>
    <style>
        body {
        overflow-x: hidden;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

        <!-- Profile Header Card -->
        {{-- <center> --}}
        <div class="card border-0 shadow mb-4 mt-4 col-lg-10 col-xl-11 mx-auto">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-4 mb-md-0">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHlsZT0iYmFja2dyb3VuZC1jb2xvcjojZjJmMmYyO2JvcmRlci1yYWRpdXM6NTAlOyI+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtZmFtaWx5PSJtb25vc3BhY2UiIGZvbnQtc2l6ZT0iMjBweCIgZmlsbD0iIzY0NzQ4YiI+VVNFUjwvdGV4dD48L3N2Zz4="
                             alt="Profile" class="profile-img rounded-circle shadow">
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-camera me-1"></i> Change Photo
                            </button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-2">Joined since: {{ Auth::user()->created_at->format('F Y') }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-success">Resident</span>
                            <span class="badge bg-info">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </center> --}}

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-11">
                <!-- Personal Information -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('resident.resident-profile.update') }}">
                            @csrf
                            @method('patch')

                            <!-- Name Row -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" 
                                           value="{{ old('first_name', $residentData->first_name ?? '') }}" required>
                                    @error('first_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" 
                                           value="{{ old('middle_name', $residentData->middle_name ?? '') }}">
                                    @error('middle_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" 
                                           value="{{ old('last_name', $residentData->last_name ?? '') }}" required>
                                    @error('last_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ old('email', $residentData->email ?? Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="{{ old('phone', $residentData->contact_number ?? '') }}">
                                    @error('phone')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Personal Details Row -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="birthdate" 
                                           value="{{ old('birthdate', $residentData->date_of_birth ?? '') }}" required>
                                    @error('birthdate')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $residentData->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $residentData->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Others" {{ old('gender', $residentData->gender ?? '') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Civil Status</label>
                                    <select class="form-select" name="civil_status" required>
                                        <option value="">Select Status</option>
                                        <option value="Single" {{ old('civil_status', $residentData->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('civil_status', $residentData->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ old('civil_status', $residentData->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ old('civil_status', $residentData->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                    @error('civil_status')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Occupation & Address -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" class="form-control" name="occupation" 
                                           value="{{ old('occupation', $residentData->occupation ?? '') }}" required>
                                    @error('occupation')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barangay</label>
                                    <input type="text" class="form-control" name="barangay" 
                                           value="{{ old('barangay', $residentData->barangay_name ?? '') }}" required>
                                    @error('barangay')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label">Complete Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address', $residentData->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Save Changes
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <span class="text-success ms-3">Profile updated successfully!</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Update Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <input type="hidden" name="redirect_to" value="resident.profile">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                    @error('current_password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-shield-lock me-2"></i>Update Password
                                </button>

                                @if (session('status') === 'password-updated')
                                    <span class="text-success ms-3">Password updated successfully!</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="card border-0 shadow border-danger mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4 text-danger">Delete Account</h5>
                        <p class="text-muted mb-4">
                            Once your account is deleted, all of its resources and data will be permanently deleted. 
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account?</p>
                    <p class="text-danger"><small>This action cannot be undone. All your data will be permanently removed.</small></p>

                    <form method="post" action="{{ route('resident.resident-profile.destroy') }}" id="deleteAccountForm">
                        @csrf
                        @method('delete')

                        <div class="mb-3">
                            <label for="password" class="form-label">Enter your password to confirm:</label>
                            <input type="password" class="form-control" name="password" required>
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" form="deleteAccountForm">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    @if (session('status'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        @if(session('status') === 'profile-updated')
                            Profile updated successfully!
                        @elseif(session('status') === 'password-updated')
                            Password updated successfully!
                        @endif
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('successToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            });
        </script>
    @endif
</x-resident-layout>