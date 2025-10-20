<link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
@php
    use App\Models\notifications;
    use App\Models\postviolation;

    $notif = notifications::get();
    $dataviolators = postviolation::get();

    function countMinor() {
    return postviolation::where('is_active', 1)->where('severity_Name', 'like', '%Minor%') ->count();
    }

    function countMajor() {
        return postviolation::where('is_active', 1)->where('severity_Name', 'like', '%Major%')->count();
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
                             @php
                                $first = Auth::user()->firstname ?? '';
                                $last = Auth::user()->lastname ?? '';
                                $initials = strtoupper(substr($first, 0, 1) . substr($last, 0, 1));
                            @endphp
                            <div class="d-flex align-items-center mt-4 flex-md-nowrap flex-wrap">
                                <!-- <img src="{{ asset('./Photos/avatar.png') }}" alt="Profile Picture" class="profile-img"> -->
                                    <div class="profile-avatar me-3">
                                        {{ $initials }}
                                    </div>
                                    <div class="flex-grow-1">
                                    <span class="badge badge-custom">Disciplinary Officer</span>
                                    <h4 class="mt-2 mb-1">Name: {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                                    <p class="mb-1">email: {{ Auth::user()->email }}</p>
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
                             <!-- Minor Card -->
                            <div class="custom-card flex-fill">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Minor</div>
                                    <i class="bi bi-exclamation-circle-fill text-warning fs-3"></i>
                                </div>
                                <div class="card-number">{{ countMinor() }}</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                
                            <!-- Major Card -->
                            <div class="custom-card flex-fill">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Major</div>
                                    <i class="bi bi-exclamation-circle-fill text-danger fs-3"></i>
                                </div>
                                <div class="card-number">{{ countMajor() }}</div>
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
                                    <a href="{{ $notifdata->url }}" class="text-decoration-none text-dark">
                                        <div class="notification-card d-flex align-items-start mb-3 p-3 rounded shadow-sm bg-light position-relative" data-notif-id="{{ $notifdata->id }}">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $notifdata->title }}</h6>
                                                <p class="mb-1 text-muted small">{{ $notifdata->message }}</p>
                                                <small class="text-muted">{{ $notifdata->created_time }}</small>
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
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
$(document).ready(function() {
    $('#notif-container').on('click', '.btn-close', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var self = this;
            var $card = $(self).closest('.notification-card');
            var notifId = $card.data('notif-id');

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
                        $card.fadeOut(300, function() {
                            $(this).remove();

                            // Check if there are any remaining notification cards
                            if ($('#notif-container .notification-card').length === 0) {
                                $('#notif-container .notif-scrollable').html(`
                                    <div class="text-center py-4 text-muted">
                                        You have no notifications.
                                    </div>
                                `);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error updating notification status:', error);
                }
            });
        });
    });
});

    //  setInterval(function() {
    //     $("#notif-container").load(window.location.href + " #notif-container > *");
    // }, 10000); //10s
</script>
