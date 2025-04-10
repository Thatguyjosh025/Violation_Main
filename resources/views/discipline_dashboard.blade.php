<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>
</head>
<body>

    <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1 class="mb-0 sidebar-title" style="color: #004581;">Central</h1>
                <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
            </div>
            <nav class="nav flex-column mt-3 flex-grow-1">
                <a class="nav-link active" href={{ route('discipline_dashboard') }}><i class="bi bi-columns-gap"></i> <span>Dashboard</span></a>
                <a class="nav-link" href="{{ route('violation_manage')}}"><i class="bi bi-person-exclamation"></i> <span>Violation Management</span></a>
                <a class="nav-link" href="{{ route('incident_report') }}"><i class='bx bxs-report'></i> <span>Incident Report</span></a>
                <a class="nav-link" href="{{ route('violation_records') }}"><i class="bi bi-people"></i> <span>Violation Record</span></a>
                <a class="nav-link" href="#"><i class="bi bi-clipboard-data-fill"></i> <span>Reports and Analytics</span></a>
                <a class="nav-link" href="#"><i class="bi bi-book-half"></i> <span>Student Handbook</span></a>
            </nav>
            <div class="logout-container">
                <a class="logout-link" href="#" onclick="document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-left"></i> <span>Log Out</span>
                </a>

                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
    </div>


        
    <div class="dashboard-content w-90">
        @include('components.discipline_components.' . $views)
    </div>


<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./js/discipline_js/AdminDashboard.js') }}"></script>
</body>
</html>