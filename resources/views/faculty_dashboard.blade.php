<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Faculty Dashboard</title>
</head>
<body>
    <div class="sidebar" id="sidebar">
            <div class="logo">
                <h1 class="mb-0 sidebar-title" style="color: #004581;">Central</h1>
                <button class="close-btn" id="closeSidebar"><i class="bi bi-list"></i></button>
            </div>
            <nav class="nav flex-column mt-3 flex-grow-1">
                <a class="nav-link" href="{{ url('faculty_dashboard') }}"><i class="bi bi-columns-gap"></i> <span>Dashboard</span></a>
                <a class="nav-link" href="{{ url('faculty_violation') }}"><i class="bi bi-person-exclamation"></i> <span>Incident Report</span></a>
                <a class="nav-link" href="{{ url('faculty_incident') }}"><i class="bi bi-file-earmark-bar-graph-fill"></i><span>Incident Records</span></a>
                <a class="nav-link" href="#"><i class="bi bi-clipboard-data-fill"></i> <span>Reports and Analytics</span></a>
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
        </div>

        <div class="dashboard-content w-90">
            @if(View::exists('components.faculty_components.' . $views))
                @include('components.faculty_components.' . $views)
            @elseif(View::exists('components.shared.' . $views))
                @include('components.shared.' . $views)
            @else
                <p>View not found.</p>
            @endif
        </div>

</body>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./js/faculty_js/FacultyDashboard.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
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
            $('.nav-link[href="' + "{{ url('faculty_dashboard') }}" + '"]').addClass('active');
        }
    });
</script>

</html>