<x-resident-layout>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Profile Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Profile Information</h5>
                        <p class="text-muted mb-4">Update your account's profile information and email address.</p>
                        
                        {{-- IMPORTANT: Use the resident profile update route --}}
                        <form method="post" action="{{ route('resident.resident-profile.update') }}">
                            @csrf
                            @method('patch')
                            
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                
                                @if (session('status') === 'profile-updated')
                                    <span class="text-success ms-3">Saved.</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Update Password</h5>
                        <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>
                        
                        {{-- For password updates, you can use the global route but handle redirect --}}
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                            
                            <input type="hidden" name="redirect_to" value="resident.profile">
                            
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" name="current_password" required>
                                @error('current_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password" required>
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                                
                                @if (session('status') === 'password-updated')
                                    <span class="text-success ms-3">Saved.</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="card shadow-sm border-danger">
                    <div class="card-body">
                        <h5 class="card-title mb-4 text-danger">Delete Account</h5>
                        <p class="text-muted mb-4">
                            Once your account is deleted, all of its resources and data will be permanently deleted. 
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Delete Account
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
                    
                    {{-- Use resident profile destroy route --}}
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

    <!-- Success Message Toast -->
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