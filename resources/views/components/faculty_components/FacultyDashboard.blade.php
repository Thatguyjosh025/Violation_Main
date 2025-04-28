<link rel="stylesheet" href="{{ asset('./css/faculty_css/FacultyDashboard.css') }}">
@php
use App\Models\notifications;

$notif = notifications::get();
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
                                <img src="/Photos/UserPic.jpg" alt="Profile Picture" class="profile-img">
                                <div class="flex-grow-1">
                                    <span class="badge badge-custom">Faculty</span>
                                    <h4 class="mt-2 mb-1">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                                    <p class="mb-1">ID: {{Auth::user()->student_no }}</p>
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
                    <!-- Chart Card -->
                    <div class="col-lg-7">
                        <div class="card p-3 shadow-sm">
                            <h6>Overall Violation</h6>
                            <div class="chart-container">
                                <canvas id="violationChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Card -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm notif">
                            <h6 class="mb-3 sticky-top bg-white" style="z-index: 1;">Notifications</h6>
                            <div class="notif-scrollable">
                                @foreach ( $notif as $notifdata )
                                    @if ($notifdata->role === 'faculty' && $notifdata->is_read === 0)
                                        <div class="notification-card d-flex align-items-start mb-3 p-3 rounded shadow-sm bg-light position-relative" data-notif-id="{{$notifdata->id}}">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{$notifdata->title}}</h6>
                                                <p class="mb-1 text-muted small">{{$notifdata->message}}</p>
                                                <small class="text-muted">{{$notifdata->created_time}}</small>
                                                <small class="text-muted">{{$notifdata->date_created}}</small>
                                            </div>
                                            <button class="btn-close ms-2 mt-1" aria-label="Close"></button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.btn-close').on('click', function() {
        // Get the notification ID (you can pass it using a data attribute or through other means)
        var notifId = $(this).closest('.notification-card').data('notif-id');

        // Make an AJAX request to update the status
        $.ajax({
            url: '/update_notification_status',  
            type: 'POST',
            data: {
                notification_id: notifId,   
                _token: '{{ csrf_token() }}',  
            },
            success: function(response) {
                if (response.success) {
                    console.log('notif removed')
                    $(this).closest('.notification-card').fadeOut();
                    $("#notif-container").load(window.location.href + " #notif-container > *");
                }
            },
            error: function(xhr, status, error) {
                console.log('Error updating notification status:', error);
            }
        });
    });
});
</script>