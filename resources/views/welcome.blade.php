<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Barangay Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                /* Tailwind CSS would be here but shortened for brevity */
            </style>
        @endif
        
        <style>
            .branding-panel {
                background: url('images/bg.jpg') no-repeat center center;
                background-size: cover;
                color: #fff;
                position: relative;
            }

            /* Add to your CSS */
            .preview-tooltip {
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: white;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 8px;
                min-width: 200px;
                z-index: 1000;
                display: none;
            }

            #hoverPreview:hover .preview-tooltip {
                display: block;
            }

            .cursor-pointer {
                cursor: pointer;
            }
            
            @media (max-width: 991.98px) {
                .branding-panel {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1030;
                    height: auto;
                }
                body { padding-top: 150px; }
            }
            
            @media (min-width: 992px) {
                .branding-panel {
                    position: sticky;
                    top: 0;
                    height: 100vh;
                }
            }
            
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="container-fluid position-relative" style="min-height:100vh;">
            <div class="row min-vh-100">
                <!-- Branding Panel -->
                <div class="col-12 col-lg-7 d-flex flex-column justify-content-center align-items-center text-center branding-panel pt-3 pt-lg-0">
                    <img src="images/seal.png" alt="Barangay Logo" class="mb-3 d-none d-lg-block" style="width: 100px; height: auto;">

                    <h3 class="fw-bold mx-lg-5 px-lg-3">BARANGAY SERVICES AND REQUEST OF DOCUMENTS SYSTEM</h3>   
                    <p class="mt-2">Where community needs meet modern solutions.</p>

                    <p class="position-absolute bottom-0 w-100 text-center mb-3 d-none d-lg-block fs-6">
                        A centralized platform for requesting and managing all your barangay documents.
                    </p>
                </div>

                <!-- Forms -->
                <div class="col-lg-5 d-flex align-items-start align-items-lg-center justify-content-center py-5 order-2 order-lg-2">
                    <div class="w-100" style="max-width:420px;">
                        <div class="bg-white rounded-3 shadow p-4">
                            <!-- Display Laravel session messages -->
                            @if(session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <div id="loginForm" class="{{ Request::is('login') || !Request::is('register') ? '' : 'd-none' }}">
                                <h4 class="mb-3 fw-bold">Sign In</h4>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus placeholder="Enter your email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off"  placeholder="Enter your password" id="loginPassword">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('loginPassword', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <a href="#" class="fs-6" onclick="showForm('forgotForm')"><small>Forgot Password?</small></a>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
                                </form>
                                <div class="mt-3">
                                    <small>
                                        Don't have an account? <a href="#" onclick="showForm('registerForm'); return false;">Register</a>
                                    </small>
                                </div>
                            </div>

                            <!-- Register Form -->
                            <div id="registerForm" class="{{ Request::is('register') ? '' : 'd-none' }}">
                                <h4 class="mb-3 fw-bold">Create Your Account</h4>
                                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Hidden role input -->
                                    <input type="hidden" name="role" value="resident">

                                    <!-- Full Name -->
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" required placeholder="Your full name" autocomplete="off">
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required placeholder="Enter your email" autocomplete="off">
                                    </div>

                                    <!-- Address -->
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" required placeholder="Enter your address" autocomplete="off">
                                    </div>

                                    <!-- Birthday -->
                                    <div class="mb-3">
                                        <label class="form-label">Birthday</label>
                                        <input type="date" name="birthday" class="form-control" required>
                                    </div>

                                    <!-- Gender -->
                                    <div class="mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="" disabled selected>Select your gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <!-- Years of Residency -->
                                    <div class="mb-3">
                                        <label class="form-label">Years of Residency</label>
                                        <input type="number" name="years_of_residency" class="form-control" placeholder="Enter number of years" required>
                                    </div>

                                    <!-- Valid ID Upload -->
                                    <div class="mb-3">
                                        <label class="form-label">Valid ID (for verification)</label>
                                        <input type="file" name="valid_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                    </div>

                                    <!-- Add this after the file input -->
                                    <div class="mt-2 mb-2">
                                        <div id="hoverPreview" class="position-relative d-inline-block">
                                            <span class="badge bg-primary cursor-pointer">
                                                <i class="bi bi-eye me-1"></i>Preview
                                            </span>
                                            <div id="previewTooltip" class="preview-tooltip">
                                                <div id="tooltipContent" class="p-2 text-center">
                                                    <p class="small text-muted mb-0">Select a file to preview</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required placeholder="Create a password">
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm your password">
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Register</button>
                                </form>

                                <div id="registerMessage" class="mt-2"></div>

                                <div class="mt-3">
                                    Already have an account? <a href="#" onclick="showForm('loginForm'); return false;">Login</a>
                                </div>
                            </div>


                            <!-- Forgot Password Form -->
                            <div id="forgotForm" class="d-none">
                                <h4 class="mb-3 fw-bold">Forgot Password</h4>
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Enter your email to reset password</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" id="forgotEmail">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Send Reset Link</button>
                                </form>
                                <div class="mt-3">
                                    <small><a href="#" onclick="showForm('loginForm')">Back to Login</a></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function showForm(formId) {
                $("#loginForm, #registerForm, #forgotForm").addClass("d-none");
                $("#" + formId).removeClass("d-none");
            }

            function togglePassword(fieldId, button) {
                const input = document.getElementById(fieldId);
                const icon = button.querySelector("i");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("bi-eye", "bi-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("bi-eye-slash", "bi-eye");
                }
            }

            // Show appropriate form based on current URL
            @if(Request::is('register'))
                showForm('registerForm');
            @elseif(Request::is('password/reset*'))
                showForm('forgotForm');
            @else
                showForm('loginForm');
            @endif
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

                <!-- File Preview Functionality -->
        <script>
            // File preview functionality
            document.addEventListener('DOMContentLoaded', function() {
                const validIdInput = document.querySelector('input[name="valid_id"]');
                const tooltipContent = document.getElementById('tooltipContent');
                
                if (validIdInput) {
                    validIdInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        
                        if (file) {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    tooltipContent.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;">`;
                                };
                                reader.readAsDataURL(file);
                            } else if (file.type === 'application/pdf') {
                                tooltipContent.innerHTML = `
                                    <i class="bi bi-file-earmark-pdf display-6 text-danger"></i>
                                    <p class="small mt-2 mb-0">${file.name}</p>
                                `;
                            }
                        } else {
                            tooltipContent.innerHTML = '<p class="small text-muted mb-0">Select a file to preview</p>';
                        }
                    });
                }
            });
        </script>

        <!-- Add your AJAX script here, AFTER Bootstrap -->
        <script>
            $(document).ready(function() {
                $('#ajaxRegisterForm').submit(function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('register') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response, status, xhr) {
                            // Show beautiful success message
                            $('#registerMessage').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                '<i class="bi bi-check-circle-fill me-2"></i>' +
                                '<strong>Registration Successful!</strong> You can now login with your credentials.' +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>');
                            
                            // Clear the form
                            $('#ajaxRegisterForm')[0].reset();
                            
                            // Optional: Switch to login form after 3 seconds
                            setTimeout(function() {
                                showForm('loginForm');
                                $('#registerMessage').html(''); // Clear success message
                            }, 3000);
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                '<i class="bi bi-exclamation-triangle-fill me-2"></i>' +
                                '<strong>Registration Failed!</strong> Please check the following:' +
                                '<ul class="mb-0 mt-2">';
                            
                            $.each(errors, function(key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                            });
                            
                            errorHtml += '</ul>' +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>';
                            
                            $('#registerMessage').html(errorHtml);
                        }
                    });
                });
            });

            function showForm(formId) {
                $("#loginForm, #registerForm, #forgotForm").addClass("d-none");
                $("#" + formId).removeClass("d-none");
            }

            function togglePassword(fieldId, button) {
                const input = document.getElementById(fieldId);
                const icon = button.querySelector("i");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("bi-eye", "bi-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("bi-eye-slash", "bi-eye");
                }
            }

            // Show appropriate form based on current URL
            @if(Request::is('register'))
                showForm('registerForm');
            @elseif(Request::is('password/reset*'))
                showForm('forgotForm');
            @else
                showForm('loginForm');
            @endif
        </script>
    </body>
</html>

</html>