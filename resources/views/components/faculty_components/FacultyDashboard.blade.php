<link rel="stylesheet" href="{{ asset('./css/faculty_css/FacultyDashboard.css') }}">

@php
use App\Models\incident;
use App\Models\notifications;
use App\Models\postviolation;

$reports = incident::get();
$notif = notifications::get();

$name = Auth::user()->firstname . " " . Auth::user()->lastname;
$id = Auth::user()->student_no;

$filteredReports = $reports->where('faculty_name', $name)->where('is_visible', '!=', 'reject');

$filterednotif = notifications::where('role', 'faculty')
    ->where('is_read', 0)
    ->where('student_no', Auth::user()->student_no)
    ->get();

function countIncident() {
    $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;

    return postviolation::where('is_active', 1)
        ->where('faculty_name', 'like', $name)
        ->count();
}
@endphp         

<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Dashboard</h3>
</div>

<div class="container dashboard-container mt-4">
    <div class="row mt-3">
        <!-- Profile Card -->
        <div class="col-lg-7 mb-3 mb-lg-0">
            <div class="card card-custom shadow-sm h-100">
                <h4>Welcome</h4>
                <div class="d-flex align-items-center mt-4 flex-md-nowrap flex-wrap">
                    <img src="{{ asset('./Photos/avatar.png') }}" alt="Profile Picture" class="profile-img">
                    <div class="flex-grow-1">
                        <span class="badge badge-custom">Faculty</span>
                        <h4 class="mt-2 mb-1">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                        <p class="mb-1">ID: {{ Auth::user()->student_no }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="col-lg-5">
            <div id="calendarView" class="calendar-view text-center">
                <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                    <h5 id="monthYear" class="mb-0 fw-bold"></h5>
                </div>
                <div class="calendar-days d-grid">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                <div id="calendarDates" class="calendar-dates d-grid mt-2"></div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
            <!-- Progress Cards -->
        <div class="col-lg-7 mb-3">
                <div class="row g-3">
                    <!-- Processed Violatiion Card -->
                        <div class="col-12 col-md-6">
                            <div class="custom-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title">Processed Violation</div>
                                <i class="bi bi-exclamation-circle-fill text-info fs-3"></i>
                            </div>
                            <div class="card-number">{{ countIncident() }}</div>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 30%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        </div>

                        <div class="col-12 col-md-6">
                        <div class="custom-card">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="card-title">Pending Incident</div>
                                <i class="bi bi-exclamation-octagon-fill text-info fs-3"></i>
                            </div>
                            <div class="pending-incident-scrollable">
                                <!-- Repeat this block for each incident -->
                                    @if ($filteredReports->isEmpty())
                                            <div class="text-center py-4 text-muted">
                                                No Pending Incident Reports.
                                            </div>
                                    @endif
                                    @foreach ( $filteredReports as $report)
                                        <div class="incident-item mb-1">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-1 fw-semibold text-truncate me-2" style="max-width: 70%;">{{ $report->violation->violations}}</h6>
                                                <span class="badge bg-warning text-dark flex-shrink-0">{{ $report->status }}</span>
                                            </div>
                                            <small class="text-muted">{{ $report->student_no }}</small><br>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">{{ $report->student_name }}</small>
                                                <small class="text-muted">{{ $report->Date_Created }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                            </div>
                        </div>
                        </div>
                </div>
            </div>

        <!-- Notifications -->
        <div class="col-lg-5">
            <div class="card shadow-sm notif">
                <h6 class="mb-3 sticky-top bg-white" style="z-index: 1;">Notifications</h6>
                <div id="notif-container" class="notif-scrollable">
                @if ($filterednotif->isEmpty())
                    <div class="text-center py-4 text-muted">
                        You have no notifications.
                    </div>
                @else
                    @foreach ($filterednotif as $notifdata)
                        <a href="{{ $notifdata->url }}" class="text-decoration-none text-dark">
                            <div class="notification-card d-flex align-items-start mb-3 p-3 rounded shadow-sm bg-light position-relative" data-notif-id="{{ $notifdata->id }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notifdata->title }}</h6>
                                    <p class="mb-1 text-muted small">{{ $notifdata->message }}</p>
                                    <small class="text-muted">{{ $notifdata->created_time }}</small><br>
                                    <small class="text-muted">{{ $notifdata->date_created }}</small>
                                </div>
                                <button class="btn-close ms-2 mt-1" aria-label="Close"></button>
                            </div>
                        </a>
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
        $('#notif-container').on('click', '.btn-close', function(event) {
        event.preventDefault();
        event.stopPropagation();

        var self = this;
        var $card = $(self).closest('.notification-card');
        var notifId = $card.data('notif-id');

        $.ajax({
            url: '/update_notification_status',
            type: 'POST',
            data: {
                notification_id: notifId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $card.fadeOut(300, function() {
                        $(this).remove();

                        // ✅ Show "no notifications" if list is empty
                        if ($('#notif-container .notification-card').length === 0) {
                            $('#notif-container').html(`
                                <div class="text-center py-4 text-muted">
                                    You have no notifications.
                                </div>
                            `);
                        }
                    });
                } else {
                    console.log('Failed to update notification status');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    });
});
</script>
