<link rel="stylesheet" href="{{ asset('./css/counseling_css/CounselingDashboard.css') }}">
@php
    use App\Models\notifications;
    use App\Models\counseling;
    $notif = notifications::get();
    $highPriority = counseling::where('priority_risk', 3)->get();


    function countCounseling() {
        return Counseling::whereIn('status', [2, 3, 4])->count();
    }

@endphp
        <!-- Dasboard Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Dashboard</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
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
                                    <span class="badge badge-custom">Guidance Counselor</span>
                                    <h4 class="mt-2 mb-1">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                                    <p class="mb-1">{{ Auth::user()->email }}</p>
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

                    <!-- Modal for calendar-->
                    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Today's Appointments</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="scheduleList"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row mt-4">
                    <!-- Sample Cards and Notifications Below -->
                    <div class="col-lg-7 mb-3">
                        <div class="row g-3">
                             <div class="col-12 col-md-6">
                                    <div class="custom-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Ongoing Session</div>
                                    <i class="bi bi-exclamation-circle-fill text-info fs-3"></i>
                                </div>
                                <div class="card-number">{{ countCounseling() }}</div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 30%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                             </div>

                             <div class="col-12 col-md-6">
                                <div class="custom-card">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="card-title">High Risk Priority List</div>
                                        <i class="bi bi-exclamation-octagon-fill text-danger fs-3"></i>
                                    </div>

                                    <div class="pending-incident-scrollable">
                                        @if($highPriority->isEmpty())
                                            <div class="text-center text-muted py-4">
                                                No high priority counseling cases.
                                            </div>
                                        @else
                                            @foreach ($highPriority as $case)
                                                <div class="incident-item mb-1">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-1 fw-semibold text-truncate me-2" style="max-width: 70%;">
                                                            {{ $case->student_name }}
                                                        </h6>
                                                        <span class="badge bg-danger text-light flex-shrink-0">High-risk</span>
                                                    </div>

                                                    <small class="text-muted">{{ $case->student_no }}</small><br>
                                                    <small class="text-muted">{{ $case->start_date }}</small>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                             </div>

                        </div>
                    </div>
                
                    <!-- NOTIF -->
                    <div class="col-lg-5 mb-3">
                        <div class="card shadow-sm notif">
                            <h6 class="mb-3 sticky-top bg-white" style="z-index: 1;">Notifications</h6>
                            <div class="notif-scrollable">
                                @if ($notif->where('role', 'counselor')->where('is_read', 0)->isEmpty())
                                    <div class="text-center py-4 text-muted">
                                        You have no notifications.
                                    </div>
                                @else
                                    @foreach ($notif->where('role', 'counselor')->where('is_read', 0) as $notifdata)
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