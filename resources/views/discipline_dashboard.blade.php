<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Disciplinary Officer Dashboard</title>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="logo">
            <a href="{{ route('discipline_dashboard') }}" class="logo-link">
                <img src="/Photos/central.png" alt="Central Logo" class="sidebar-logo full-logo">
                <img src="/Photos/Central-Icon.png" alt="Central Icon" class="sidebar-logo icon-logo d-none">
            </a>
            <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
        </div>
        <nav class="nav flex-column mt-3 flex-grow-1">
            <a class="nav-link" href={{ route('discipline_dashboard') }}><i class="bi bi-columns-gap"></i> <span>Dashboard</span></a>
            <a class="nav-link" href="{{ route('violation_manage')}}"><i class="bi bi-person-exclamation"></i> <span>Violation Management</span></a>
            <a class="nav-link" href="{{ route('incident_report') }}"><i class='bx bxs-report'></i> <span>Incident Report</span></a>
            <a class="nav-link" href="{{ route('violation_records') }}"><i class="bi bi-people"></i> <span>Violation Record</span></a>
            <a class="nav-link" href="{{ route('report_analytics') }}"><i class="bi bi-clipboard-data-fill"></i> <span>Reports and Analytics</span></a>
            <a class="nav-link" href="{{ route('violation_handbook') }}"><i class="bi bi-book-half"></i> <span>Student Handbook</span></a>
        </nav>
        
        <div class="logout-container">
            <a id="logout-link" class="logout-link" href="#" onclick="logout();">
                <i class="bi bi-box-arrow-left"></i> <span>Log Out</span>
            </a>

            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="dashboard-content w-90">
        @if(View::exists('components.discipline_components.' . $views))
            @include('components.discipline_components.' . $views)
        @elseif(View::exists('components.shared.' . $views))
            @include('components.shared.' . $views)
        @else
            <p>View not found.</p>
        @endif
    </div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./js/discipline_js/AdminDashboard.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function logout() {
    Swal.fire({
        title: "Are you sure you want to logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $('#logout-link').addClass('disabled').css('pointer-events', 'none');

            $('#logout-form').submit();

            setTimeout(function() {
                window.location.href = "{{ url('login') }}"; 
            }, 2000); 
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const currentUrl = window.location.href;
    const navLinks = document.querySelectorAll('.nav-link');
    let matched = false;

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });

        if (link.href === currentUrl) {
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            matched = true;
        }
    });

    if (!matched) {
        navLinks.forEach(l => l.classList.remove('active'));
        const fallback = document.querySelector(".nav-link[href='{{ url('discipline_dashboard') }}']");        
        if (fallback) fallback.classList.add('active');
    }
});
</script>
</body>
<script src="{{ asset('./vendor/dataTables.min.js') }}"></script>


</html>
