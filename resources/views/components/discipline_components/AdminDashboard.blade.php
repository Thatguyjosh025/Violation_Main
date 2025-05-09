<link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
@php
    use App\Models\notifications;
    use App\Models\postviolation;

    $notif = notifications::get();
    $dataviolators = postviolation::get();

    function countMinor($collection) {
        return $collection->filter(function($item) {
            return str_contains($item->severity_Name, 'Minor');
        })->count();
    }
    function countMajor($collection) {
        return $collection->filter(function($item) {
            return str_contains($item->severity_Name, 'Major');
        })->count();
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
                                    <span class="badge badge-custom">Disciplinary Officer</span>
                                    <h4 class="mt-2 mb-1">Name:{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} {{ Auth::user()->middlename}}</h4>
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
                    <!-- Progress Cards and Notifications Below -->
                    <div class="col-lg-7 mb-3">
                        <div class="d-flex gap-3 flex-wrap">
                            <!-- Minor Card -->
                            <div class="custom-card flex-fill">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Total Recorded Minor Violation</div>
                                    <i class="bi bi-exclamation-circle-fill text-warning fs-3"></i>
                                </div>
                                <div class="card-number">{{ countMinor($dataviolators) }}</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                
                            <!-- Major Card -->
                            <div class="custom-card flex-fill">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Total Recorded Major Violation</div>
                                    <i class="bi bi-exclamation-circle-fill text-danger fs-3"></i>
                                </div>
                                <div class="card-number">{{ countMajor($dataviolators) }}</div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 45%;" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Notification Card -->
                    <div class="col-lg-5">
                    <div class="card shadow-sm notif" id="notif-container">
                        <h6 class="mb-3 sticky-top bg-white" style="z-index: 1;">Notifications</h6>
                        <div class="notif-scrollable">
                            <!-- notif cards -->
                            @if ($notif->where('role', 'admin')->where('is_read', 0)->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    You have no notifications.
                                </div>
                            @else
                                @foreach ($notif->where('role', 'admin')->where('is_read', 0) as $notifdata)
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
    $('#notif-container').on('click', '.btn-close', function() {
        var self = this;
        var notifId = $(self).closest('.notification-card').data('notif-id');

        $.ajax({
            url: '/update_notification_status',
            type: 'POST',
            data: {
                notification_id: notifId,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.success) {
                    console.log('Notification marked as read');

                    // Fade out and remove the notification card
                    $(self).closest('.notification-card').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Error updating notification status:', error);
            }
        });
    });
});

    //  setInterval(function() {
    //     $("#notif-container").load(window.location.href + " #notif-container > *");
    // }, 10000); //10s
</script>
