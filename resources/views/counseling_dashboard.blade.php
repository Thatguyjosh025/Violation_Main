<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Counselor Dashboard</title>
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('./css/counseling_css/CounselingDashboard.css') }}">
</head>
<body>
    <div class="d-flex">
        <!-- Side Bar Section -->   
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <a href="AdminDashboard.html" class="logo-link">
                    <img src="/Photos/central.png" alt="Central Logo" class="sidebar-logo full-logo">
                    <img src="/Photos/Central-Icon.png" alt="Central Icon" class="sidebar-logo icon-logo d-none">
                </a>
                <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
            </div>
            <nav class="nav flex-column mt-3 flex-grow-1">
                <a class="nav-link" href="{{ route('counseling_dashboard') }}"><i class="bi bi-columns-gap"></i> <span>Dashboard</span></a>
                <a class="nav-link "href="{{ route('referral_intake') }}"><i class="bi bi-person-lines-fill"></i></i> <span>Referral & Intake</span></a>
                <a class="nav-link" href="{{ route('session_management') }}"><i class='bi bi-people-fill'></i> <span>Session Management</span></a>
                <a class="nav-link" href="{{ route('student_counseling') }}"><i class="bi bi-clipboard-data-fill"></i> <span>Student Counseling<br>Profile</span></a>
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
        <!-- Dasboard Section -->
        <div class="dashboard-content w-90">
            @if(View::exists('components.counseling_components.' . $views))
                @include('components.counseling_components.' . $views)
            @elseif(View::exists('components.shared.' . $views))
                @include('components.shared.' . $views)
            @else
                <p>View not found.</p>
            @endif
    
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./js/counseling_js/CounselingDashboard.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/dataTables.min.js') }}"></script>
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

     $(document).ready(function () {
        // Get the current URL
        var currentUrl = window.location.href;

        // Handle click events on navigation links
        $('.nav-link').on('click', function (e) {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        });

        // Set the active link based on the current URL
        $('.nav-link').each(function() {
            if ($(this).attr('href') === currentUrl) {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            }
        });

        // Set the Dashboard link as the default active link if no match is found
        if (!$('.nav-link').hasClass('active')) {
            $('.nav-link').removeClass('active');
            $('.nav-link[href="' + "{{ url('counseling_dashboard') }}" + '"]').addClass('active');
        }
    });

</script>
</body>
</html>