 <link rel="stylesheet" href="{{ asset('./css/student_css/StudentDB.css') }}">
    <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Dashboard</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            
            <div class="container dashboard-container mt-4">
                <div class="row mt-3">
                    <div class="col-lg-7 mb-3 mb-lg-0">
                        <div class="card card-custom shadow-sm h-100">
                            <h4>Welcome</h4>
                                    <div class="d-flex align-items-center mt-4 flex-md-nowrap flex-wrap">
                                        <img src="/Photos/UserPic.jpg" alt="Profile Picture" class="profile-img">
                                        <div class="flex-grow-1">
                                            <span class="badge badge-custom">Student</span>
                                            <h4 class="mt-2 mb-1">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} {{ Auth::user() -> middlename }}</h4>
                                            <p class="mb-1">Bachelor of Science in Information Technology</p>
                                            <span>{{ Auth::user()->course_and_section }}</span>
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

                    <div class="row mt-3"> 
                        <div class="col-md-4 mb-3">
                            <div class="card p-3 text-center shadow" style="background-color: #FFEB3B; border-radius: 15px;">
                                <h5 class="fw-bold">Minor Offense</h5>
                                <h1 class="fw-bold">2</h1>
                                <p class="mb-1">Recent Violation</p>
                                <div class="bg-white p-2 rounded shadow-sm">
                                    <p class="mb-0">Improper Uniform</p>
                                    <a href="#" class="text-primary">View</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card p-3 text-center shadow" style="background-color: #F44336; border-radius: 15px; color: white;">
                                <h5 class="fw-bold">Major Offense</h5>
                                <h1 class="fw-bold">1</h1>
                                <p class="mb-1">Recent Violation</p>
                                <div class="bg-white p-2 rounded shadow-sm text-dark">
                                    <p class="mb-0">Gambling</p>
                                    <a href="#" class="text-primary">View</a>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-4 mb-3">
                            <div class="card good-moral-score p-3">
                                <h5>Good Moral Score</h5>
                                <div class="progress-circle">
                                    <canvas id="moralScoreChart"></canvas>
                                    <div class="progress-text fs-2">97%</div>
                                </div>
                                <p class="text-primary fw-bold mt-1">Excellent behavior</p>
                            </div>
                        </div>
                    </div>
                </div>  
<script src="{{ asset('./js/student_js/StudentDashboard.js') }}"></script>