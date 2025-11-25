 <link rel="stylesheet" href="{{ asset('./css/student_css/StudentDB.css') }}">
 @php
  use App\Models\notifications;
  use App\Models\postviolation;
  use App\Models\counseling;

  $notif = notifications::get();

  $studentNotifs = $notif->where('student_no', Auth::user()->student_no)->where('is_read', 0);
  $studentCounseling = counseling::where('student_no', Auth::user()->student_no)->get();
  function countStudentMajor() {
        return postviolation::where('is_active', 1)
        ->where('severity_Name', 'like', '%Major%')
        ->where('student_no','like',Auth::user()->student_no)
        ->count();
  }
  function countStudentMinor() {
        return postviolation::where('is_active', 1)
        ->where('severity_Name', 'like', '%Minor%')
        ->where('student_no','like',Auth::user()->student_no)
        ->count();
  }
 @endphp
      <div class="d-flex align-items-center">
        <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <h3 class="mb-0">Dashboard</h3>
      </div>

      <div class="container dashboard-container mt-4">
        <div class="row mt-3">
          <div class="col-lg-7 d-flex flex-column gap-3">
            
            <!-- Welcome Card -->
            <div class="card card-custom shadow-sm h-100">
              <h4>Welcome</h4>
                @php
                    $first = Auth::user()->firstname ?? '';
                    $last = Auth::user()->lastname ?? '';
                    $initials = strtoupper(substr($first, 0, 1) . substr($last, 0, 1));
                @endphp
              <div class="d-flex align-items-center mt-4 flex-md-nowrap flex-wrap">
                  <div class="profile-avatar me-3">
                      {{ $initials }}
                  </div>
                <div class="flex-grow-1">
                  <span class="badge badge-custom">Student</span>
                  <h4 class="mt-2 mb-1">{{ Auth::user()-> firstname }} {{ Auth::user()-> lastname}} {{ Auth::user()-> middlename }}</h4>
                  <p class="mb-1">Student ID: {{ Auth::user()-> student_no }}</p>
                </div>
              </div>
            </div>
        
            <!-- Minor & Major & Good Moral Cards -->
            <div class="row g-3">
             <div class="col-md-4">
                <div class="card p-3 text-center shadow" style="background-color: #f5c423; color: white; border-radius: 7px;">
                  <h5 class="fw-bold">Minor Offense</h5>
                  <h1 class="fw-bold">{{ countStudentMinor() }}</h1>
                  <p class="mb-1">Recent Violation</p>
                  <div class="bg-white p-2 rounded shadow-sm">
                    <p class="mb-0">Improper Uniform</p>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="card p-3 text-center shadow" style="background-color: #F44336; border-radius: 7px; color: white;">
                  <h5 class="fw-bold">Major Offense</h5>
                  <h1 class="fw-bold">{{ countStudentMajor() }}</h1>
                  <p class="mb-1">Recent Violation</p>
                  <div class="bg-white p-2 rounded shadow-sm text-dark">
                    <p class="mb-0">Gambling</p>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                  <div class="card p-3 text-center shadow" style="background-color: white; border-radius: 7px; color: #2c698d;">
                      <h5 class="fw-bold mb-3 text-center">Guidance Counseling</h5>
                      <div class="guidanceCard">                 
                      @if($studentCounseling->isEmpty())
                          <div class="text-center py-4 text-muted">
                              You have no scheduled counseling.
                          </div>
                      @else
                          @foreach($studentCounseling as $counsel)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#CounselingReport" class="text-decoration-none">
                                  <div class="RequiredCounselingBox rounded d-flex justify-content-between align-items-start"
                                      style="padding: 16px 8px 16px 10px;">
                                      <div class="text-start">
                                          <p class="b-1 mb-1">{{ $counsel -> statusRelation -> session_status}}</p>
                                          <small>{{ $counsel-> start_date }}</small>
                                          <small>{{ \Carbon\Carbon::parse($counsel->start_time)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($counsel->end_time)->format('g:i A') }}</small>
                                      </div>
                                      <i class="bi bi-person-fill-exclamation fs-3"></i>
                                  </div>
                              </a>
                          @endforeach
                      @endif
                      </div>
                  </div>
              </div>

            </div>
          </div>
      
          <div class="col-lg-5 d-flex flex-column gap-3">
            
            <!-- Calendar -->
            <div id="calendarView" class="calendar-view text-center shadow-sm h-100">
              <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                <h5 id="monthYear" class="mb-0 fw-bold"></h5>
              </div>
              <div class="calendar-days d-grid">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
              </div>
              <div id="calendarDates" class="calendar-dates d-grid mt-2"></div>
            </div>
        
            <!-- Notifications -->
            <div class="card shadow-sm notif" id="notif-container">
              <h6 class="mb-3 sticky-top bg-white" style="z-index: 1;">Notifications</h6>
              <div class="notif-scrollable">
                  @if ($studentNotifs->isEmpty())
                      <div class="text-center py-4 text-muted">
                          You have no notifications.
                      </div>
                  @else
                      @foreach ($studentNotifs as $notifdata)
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
<script src="{{ asset('./js/student_js/StudentDashboard.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
     $('#notif-container').on('click', '.btn-close', function(event) {
        event.preventDefault();
        event.stopPropagation();

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

                        // Check if there are any notifications left
                        if ($('.notification-card').length === 0) {
                            $('.notif-scrollable').html('<div class="text-center py-4 text-muted">You have no notifications.</div>');
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
</script>