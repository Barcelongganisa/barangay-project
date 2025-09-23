<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Barangay Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password" id="loginPassword">
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
                                <form id="ajaxRegisterForm">
                                    @csrf

                                    <!-- Hidden role input -->
                                    <input type="hidden" name="role" value="resident">

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" required placeholder="Your Name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required placeholder="Create a password">
                                    </div>
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
    </body>
    <script>
$(document).ready(function() {
    $('#ajaxRegisterForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('register') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                $('#registerMessage').html('<div class="alert alert-success">Registration successful! Please login.</div>');
                // Optionally switch to login form
                showForm('loginForm');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                errorHtml += '</ul></div>';
                $('#registerMessage').html(errorHtml);
            }
        });
    });
});
</script>

</html>