<style>
    .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    background-color: #0d3b8a;
    z-index: 1000;
    transition: all 0.3s;
    overflow-y: auto;
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
            background-color: #3d85c6;
            color: white !important;
            font-weight: bold;
        }
        .nav-link:hover:not(.active) {
            background-color: rgba(61, 133, 198, 0.5);
            color: white !important;
        }

.sidebar.show {
    transform: translateX(0);
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0 !important;
    }
}

.hamburger-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1001;
    background: #0d3b8a;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 10px;
}
</style>
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
    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
    <a href="{{ route('admin.manage.requests') }}" class="nav-link text-white">
        <i class="bi bi-list-check me-2"></i> Manage Requests
    </a>
    <a href="{{ route('admin.manage.residents') }}" class="nav-link text-white">
        <i class="bi bi-people me-2"></i> Manage Residents
    </a>
    <a href="{{ route('admin.reports') }}" class="nav-link text-white">
        <i class="bi bi-bar-chart me-2"></i> Reports
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
