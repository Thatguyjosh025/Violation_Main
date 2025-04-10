<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}">
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <h1 class="mb-0 sidebar-title" style="color: #004581;">Central</h1>
        <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
    </div>

    <nav class="nav flex-column mt-3 flex-grow-1">
        <!-- Link to Authorization -->
        <a class="nav-link" href="{{ route('super_dashboard') }}"><i class="bi bi-shield-exclamation"></i> <span>Authorization</span></a>

        <!-- Violation Management Links -->
        <a class="nav-link sub-link" href="{{ route('violation_management') }}" data-section="violation-type"><i class="bi bi-exclamation-triangle "></i><span>Violation Type</span></a>
        <a class="nav-link sub-link" href="{{ route('referal_management') }}" data-section="referral"><i class="bi bi-arrow-right-circle "></i><span>Referral</span></a>
        <a class="nav-link sub-link" href="{{ route('penalty_management') }}" data-section="penalty"><i class="bi bi-hammer "></i><span>Penalty</span></a>

        <!-- Link to Rule Management -->
        <a class="nav-link" href="{{ route('rule_management') }}"><i class="bi bi-journal-text"></i> <span>Rule Management</span></a>

        <!-- Link to Reports and Analytics -->
        <a class="nav-link" href="#"><i class="bi bi-clipboard-data-fill"></i> <span>Reports and Analytics</span></a>

        <!-- Link to Student Handbook -->
        <a class="nav-link" href="#"><i class="bi bi-book-half"></i> <span>Student Handbook</span></a>
    </nav>

    <div class="logout-container">
    <a class="logout-link" href="#" onclick="document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-left"></i><span>Log Out</span>
    </a>
    <div class="div">
        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <button onclick="document.getElementById('logout-form').submit();" class="btn btn-danger" style="display: none;">
            Logout
        </button>
    </div>
</div>
</div>



<!-- Dynamically include the correct component based on the 'view' variable -->
<div class="dashboard-content w-90">
    @include('components.super_admin_components.' . $view)
</div>

<!-- Scripts -->
<script src="{{ asset('vendor/jquery.min.js') }}"></script>
<script src="{{ asset('js/super_admin_js/Authorization.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
