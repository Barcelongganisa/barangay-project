<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .body {
            overflow: hidden;
        }
        .main-content {
            margin-left: 250px;
            /* padding: 20px; */
            transition: all 0.3s;
            min-height: auto;
            background-color: #f8f9fa;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.2s;
        }
        .request-card {
            border-left: 4px solid #0d6efd;
            border-radius: 0.375rem;
        }
        .request-status {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.375rem;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-ready {
            background-color: #d4edda;
            color: #155724;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    @include('profile.partials.admin_sidebar')

    <!-- Main Content -->
    {{--width: 86.96vw;
        left: -20px;" 
        top: -20px;--}}
    <div class="main-content" id="mainContent">
        <header class="text-white p-3 sticky-top" style="background-color: #0d3b8a; position: -webkit-sticky;
            position: relative;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="h4 mb-2 mb-md-0 fw-bold">Admin Dashboard</h1>
                <div class="d-flex align-items-center">
                    <span class="text-white">Welcome, Admin</span>
                </div>
            </div>
        </header>

        {{ $slot }}
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
