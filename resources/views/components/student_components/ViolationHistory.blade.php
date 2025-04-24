<link rel="stylesheet" href="{{ asset('./css/student_css/ViolationHistory.css') }}">
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation History</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <div class="container mt-4">
                <!-- Violation Card Template -->
                <div class="violation-card d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <div class="fw-bold date-text">Today - Saturday, February 22, 2025</div>
                        <div class="violation-details">01:56 PM &nbsp;&nbsp; Violation: Improper Uniform</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold violation-title fs-5">Student Uniform</div>
                        <button class="btn view-btn" data-bs-toggle="modal" data-bs-target="#violationModal">View</button>
                    </div>
                </div>
                <!-- Add more cards here if needed -->
            </div>
            
            <!-- Modal Section -->
            <div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="violationModalLabel">Violation Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Offense/s:</strong> Improper Uniform</p>
                            <p><strong>Details:</strong> <a href="#">Student Uniform</a></p>
                            <p>Certain programs, courses, or activities require a different set of uniforms. Only STI issued or endorsed uniforms are allowed.</p>
                            <hr>
                            <p><strong>Severity:</strong> Minor Offense</p>
                            <p><strong>Penalty:</strong> Verbal/Oral Warning</p>
                            <p><strong>Action Taken:</strong> Consulted DO/GA</p>
                            <hr>
                            <p><strong>Message:</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor.</p>
                            <hr>
                            <p><strong>Apppeal</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor.</p>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>