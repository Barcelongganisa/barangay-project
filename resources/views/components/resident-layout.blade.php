<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.2s;
        }
        
        /* Sidebar Styles */
        .hamburger-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: #113475ff;
            color: white;
            border: none;
            border-radius: 90%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .hamburger-btn.hide {
            opacity: 0;
            pointer-events: none;
        }
        .sidebar {
            width: 250px; 
            height: 100vh; 
            position: fixed; 
            background-color: #0b2351; 
            z-index: 1000;
        }
        .nav-link {
            padding: 1rem;
            display: block;
            text-decoration: none;
            color: white !important;
            margin: 5px 10px;
            border-radius: 5px;
        }
        .nav-link.active {
            background-color: #4a97f5ff;
            color: #0b2351 !important;
            font-weight: bold;
        }
        .nav-link:hover:not(.active) {
            background-color: rgba(52, 110, 181, 0.68);
            color: #0b2351 !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Hamburger Button -->
    <button class="hamburger-btn d-md-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar text-white" id="sidebar">
        <div class="p-3 border-bottom border-secondary">
            <h4 class="fw-bold mb-0">Resident Portal</h4>
        </div>
        <nav class="d-flex flex-column p-2 mt-3">
            <a href="{{ route('dashboard') }}" class="nav-link text-white">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
            <a href="{{ route('resident.new-request') }}" class="nav-link text-white">
                <i class="bi bi-plus-circle me-2"></i> New Request
            </a>
            <a href="{{ route('resident.requests') }}" class="nav-link text-white">
                <i class="bi bi-list-ul me-2"></i> Requests
            </a>
            <a href="{{ route('resident.documents') }}" class="nav-link text-white">
                <i class="bi bi-file-earmark-text me-2"></i> Documents
            </a>
            <a href="{{ route('resident.resident-profile') }}" class="nav-link text-white">
                <i class="bi bi-person me-2"></i> Profile
            </a>
            <a href="{{ route('logout') }}" class="nav-link text-white" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            
            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="text-white p-3 sticky-top" style="background-color: #0b2351;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center" style="height: 29px;">
                <h1 class="h3 mb-2 mb-md-0 fw-bold">Dashboard</h1>
                <div class="d-flex align-items-center">
                    <span class="text-white">Welcome, {{ Auth::user()->name ?? 'Resident User' }}</span>
                </div>
            </div>
        </header>

        <!-- Content Slot -->
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar JS -->
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                event.target !== toggleBtn && 
                !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Add active class to the current page link
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.href;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if(link.href === currentLocation) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>