{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .main-content { margin-left: 250px; transition: all 0.3s; }
        .card-hover:hover { transform: translateY(-5px); transition: transform 0.2s; }
        .request-card { border-left: 4px solid #0d6efd; border-radius: 0.375rem; }
        .request-status { padding: 0.35em 0.65em; font-size: 0.75em; font-weight: 700; border-radius: 0.375rem; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-ready { background-color: #d4edda; color: #155724; }
        @media (max-width: 768px) { .main-content { margin-left: 0; } }
        .sidebar { width: 250px; height: 100vh; position: fixed; background-color: #0d3b8a; z-index: 1000; }
        .nav-link { padding: 1rem; display: block; text-decoration: none; color: white !important; margin: 5px 10px; border-radius: 5px; }
        .nav-link.active { background-color: #3d85c6; font-weight: bold; }
        .nav-link:hover:not(.active) { background-color: rgba(61, 133, 198, 0.5); color: white !important; }
        .hamburger-btn { position: fixed; top: 15px; left: 15px; z-index: 1100; background: #0d3b8a; color: white; border: none; border-radius: 90%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Mobile Hamburger Button -->
    <button class="hamburger-btn d-md-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar text-white" id="sidebar">
        <div class="p-3 border-bottom border-secondary">
            <h4 class="fw-bold mb-0">Admin Portal</h4>
        </div>
        <nav class="d-flex flex-column p-2 mt-3">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.requests') }}" class="nav-link {{ request()->routeIs('admin.requests') ? 'active' : '' }}">
                <i class="bi bi-list-check me-2"></i> Manage Requests
            </a>
            <a href="{{ route('admin.residents') }}" class="nav-link {{ request()->routeIs('admin.residents') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Manage Residents
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart me-2"></i> Reports
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-white text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header class="text-white p-3 sticky-top" style="background-color: #0d3b8a;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="h3 mb-2 mb-md-0 fw-bold">@yield('header', 'Dashboard')</h1>
                <div class="d-flex align-items-center">
                    <span class="text-white">Welcome, {{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html> --}}
