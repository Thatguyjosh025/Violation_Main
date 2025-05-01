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
                                <img src="{{ asset('./Photos/avatar.png') }}" alt="Profile Picture" class="profile-img">
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
                            <div id="notif-container" class="notif-scrollable">
                            @if ($notif->where('role', 'faculty')->where('is_read', 0)->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    You have no notifications.
                                </div>
                            @else
                                @foreach ($notif->where('role', 'facu   lty')->where('is_read', 0) as $notifdata)
                                    <div class="notification-card d-flex align-items-start mb-3 p-3 rounded shadow-sm bg-light position-relative" data-notif-id="{{ $notifdata->id }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $notifdata->title }}</h6>
                                            <p class="mb-1 text-muted small">{{ $notifdata->message }}</p>
                                            <small class="text-muted">{{ $notifdata->created_time }}</small>
                                            <small class="text-muted">{{ $notifdata->date_created }}</small>
                                        </div>
                                        <button class="btn-close ms-2 mt-1" aria-label="Close"></button>
                                    </div>
                                @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Event delegation for dynamic .btn-close elements inside #notif-container
    $('#notif-container').on('click', '.btn-close', function() {
        var self = this;
        var notifId = $(self).closest('.notification-card').data('notif-id');

        $.ajax({
            url: '/update_notification_status',  // Laravel route that handles the DB update
            type: 'POST',
            data: {
                notification_id: notifId,
                _token: '{{ csrf_token() }}' // CSRF protection
            },
            success: function(response) {
                if (response.success) {
                    console.log('Notification marked as read');

                    // Smoothly remove the notification card from DOM
                    $(self).closest('.notification-card').fadeOut(300, function() {
                        $(this).remove();
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