<link rel="stylesheet" href="{{ asset('./css/discipline_css/AdminDashboard.css') }}">
@php
    use App\Models\users;
@endphp
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
                            <div class="d-flex align-items-center mt-4 flex-md-nowrap flex-wrap">
                                <img src="{{ asset('./Photos/aiah.jpg') }}" alt="Profile Picture" class="profile-img">
                                <div class="flex-grow-1">
                                    <span class="badge badge-custom">{{ Auth::user()->role }}</span>
                                    <h4 class="mt-2 mb-1">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} {{ Auth::user() -> middlename }}</h4>
                                    <p class="mb-1">School ID: {{ Auth::user()-> student_no }}</p>
                                    <p class="mb-1">Bachelor of Science in Information Technology</p>
                                    <span>{{Auth::user()-> course_and_section}}</span>
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
                        <div class="card p-3 shadow-sm" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
