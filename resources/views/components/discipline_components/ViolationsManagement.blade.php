<link rel="stylesheet" href="{{ asset('./css/discipline_css/ViolationManagement.css') }}"> 
@php
 use App\Models\users;
 use App\Models\violation;
 use App\Models\referals;
 use App\Models\penalties;

 $accounts = users::get();
 $violate = violation::get();
 $ref = referals::get();
 $pen = penalties::get();
@endphp
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation Management</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <!-- Violation Management Section -->
            <div class="container container-custom mt-4">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-custom mt-3 p-4">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                <img src="/Photos/UserPic.jpg" class="profile-img" alt="Profile Picture">
                                <div class="studentinfo w-100">
                                    <input type="text" class="form-control mb-2" placeholder="Student No." readonly>
                                    <input type="text" class="form-control mb-2" placeholder="Student Name" readonly>
                                    <input type="text" class="form-control mb-2" placeholder="Course and Section" readonly>
                                    <input type="email" class="form-control mb-2" placeholder="Student School Email" readonly>
                                </div>
                            </div>
                            <div class="createviolation mt-1">
                                <button class="btn create-violation-btn" data-bs-toggle="modal" data-bs-target="#violationModal">+ Create Violation</button>
                            </div>
                        </div>

                        <div class="recent-violation">
                            <h5 class="mt-4 pb-2">Recent Violations</h5>
                            <div class="violation-card">
                                <div class="top-content">
                                    <p>Today - Monday, March 17, 2025</p>
                                    <p class="cheating-text fw-bold fs-5">Cheating</p>
                                </div>
                                <p class="bottom-content">12:45 PM</p>
                            </div>
                            
                            <div class="violation-card">
                                <div class="top-content">
                                    <p>Today - Monday, March 17, 2025</p>
                                    <p class="cheating-text fw-bold fs-5">Cheating</p>
                                </div>
                                <p class="bottom-content">12:45 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student List Section -->
                    <div class="col-lg-4">
                        <div class="card card-custom p-3 mt-3">
                            <input type="text" class="form-control mb-3" placeholder="Search Student">
                            <div class="student-list">
                                @foreach ($accounts as $data )
                                <div class="student-item">
                                    <div class="d-flex align-items-center">
                                        <img src="/Photos/UserPic.jpg" alt="Student">
                                        <div class="ms-2">
                                            <p class="mb-0 fw-bold">Student No.</p>
                                            <small>{{ $data -> student_no}}</small>
                                            <p>{{ $data->firstname }} {{ $data->lastname }}</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm">View</button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Violation Process Form Modal -->
<div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="background-color: #2c698d; color: white; padding: 20px; border-radius: 10px;">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold" id="violationModalLabel">Violation Process</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf

                    <div class="mb-3">
                        <!-- <label for="student_no" class="form-label">Student No.</label> -->
                        <input type="hidden" name="student_no" id="student_no" class="form-control form-control-sm" required>
                    </div> 

                    <div class="mb-3">
                        <!-- <label for="student_name" class="form-label">Student Name</label> -->
                        <input type="hidden" name="student_name" id="student_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <!-- <label for="course_and_section" class="form-label">Course and Section</label> -->
                        <input type="hidden" name="course_and_section" id="course_and_section" class="form-control" required>
                    </div> 

                    <div class="mb-3">
                        <!-- <label for="school_email" class="form-label">School Email</label> -->
                        <input type="hidden" name="school_email" id="schoolEmail" class="form-control" required>    
                    </div>

                    <div class="mb-3">  
                        <label for="violation_type" class="form-label">Violation Type</label>
                        <select name="violation_type" id="violation_type" class="form-select">
                            <option value="">Select</option>
                            @foreach ($violate as $test)
                                <option value="{{ $test->violation_id }}">{{ $test->violations }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rule:</label>
                        <p id="ruleName"></p>
                        <input type="hidden" id="ruleNameInput" name="rule_Name"> 
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <p id="descriptionName"></p> 
                        <input type="hidden" id="descriptionNameInput" name="description_Name"> 
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Severity:</label>
                        <p id="severityName"></p> 
                        <input type="hidden" id="severityNameInput" name="severity_Name"> 
                    </div>   

                    <div class="mb-3">  
                        <label for="referal_type" class="form-label">Action Taken Prior to Referral</label>
                        <select name="referal_type" id="referal_type" class="form-select">
                            <option value="">Select</option>
                            @foreach ($ref as $refdata)
                                <option value="{{ $refdata->referal_id }}">{{ $refdata->referals }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">  
                        <label for="penalty_type" class="form-label">Penalty Type</label>
                        <select name="penalty_type" id="penalty_type" class="form-select">
                            <option value="">Select</option>
                            @foreach ($pen as $pendata)
                                <option value="{{ $pendata->penalties_id }}">{{ $pendata->penalties }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <h5>Faculty Involvement</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="faculty_yes" name="faculty_involvement" value="yes">
                            <label class="form-check-label" for="faculty_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="faculty_no" name="faculty_involvement" value="no" checked>
                            <label class="form-check-label" for="faculty_no">No</label>
                        </div>
                        <label for="" id="facultyLabel" style="display: none;"></label>
                        <input type="text" name="faculty_name" id="facultyName" class="form-control mt-2" style="display: none;">
                    </div>

                    <div class="mb-3">
                        <h5>Counseling Required</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="counseling_yes" name="counseling_required" value="yes">
                            <label class="form-check-label" for="counseling_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="counseling_no" name="counseling_required" value="no">
                            <label class="form-check-label" for="counseling_no">No</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="uploadEvidence" class="form-label">Upload Evidence</label>
                        <input type="file" class="form-control" id="uploadEvidence" name="upload_evidence">
                    </div>

                    <div class="modal-footer border-0 d-flex justify-content-end px-0">
                        <button type="submit" id="submit_violation" class="btn btn-light">Create Violation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
