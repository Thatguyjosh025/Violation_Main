<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('./css/student_css/StudentDB.css') }}">
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Student Dashboard</title>
</head>
<body>
    <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1 class="mb-0 sidebar-title" style="color: #004581;">Central</h1>
                <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
            </div>
            <nav class="nav flex-column mt-3 flex-grow-1">
                <a class="nav-link" href="{{ url('student_dashboard') }}"><i class="bi bi-columns-gap"></i> <span>Dashboard</span></a>
                <a class="nav-link" href="{{ url('violation_tracking') }}"><i class="bi bi-person-exclamation"></i> <span>Violation Tracking</span></a>
                <a class="nav-link" href="{{ url('violation_history') }}"><i class="bi bi-clipboard-data-fill"></i> <span>Violation History</span></a>
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
            @if(View::exists('components.student_components.' . $views))
                @include('components.student_components.' . $views)
            @elseif(View::exists('components.shared.' . $views))
                @include('components.shared.' . $views)
            @else
                <p>View not found.</p>
            @endif
        </div>

</body>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./js/student_js/StudentDashboard.js') }}"></script>
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

            // Clear the active link from localStorage
            localStorage.removeItem('activeSidebarLink');

            $('#logout-form').submit();

            setTimeout(function() {
                window.location.href = "{{ url('login') }}";
            }, 2000);
        }
    });
}

$(document).ready(function () {
    // Get the current path (without query or hash)
    var currentUrl = window.location.pathname;

    // Handle click events on navigation links
    $('.nav-link').on('click', function (e) {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

    // Set the active link based on the current path
    $('.nav-link').each(function () {
        var linkPath = new URL($(this).attr('href'), window.location.origin).pathname;
        if (linkPath === currentUrl) {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        }
    });

    // Set Dashboard as fallback if no match found
    if (!$('.nav-link').hasClass('active')) {
        $('.nav-link').removeClass('active');
        $('.nav-link[href="' + "{{ url('student_dashboard') }}" + '"]').addClass('active');
    }
});
</script>
</html>
