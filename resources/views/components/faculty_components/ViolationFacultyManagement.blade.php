<link rel="stylesheet" href="{{ asset('./css/faculty_css/FViolationManagment.css') }}">
@php
use App\Models\users;
 use App\Models\violation;
 use App\Models\referals;
 use App\Models\penalties;

 $violate = violation::get();
 $accounts = users::get();

@endphp
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Incident Report</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <!-- Faculty Violation Managament Section -->
             
            <div class="container mt-4">
                <div class="row mt-3">
                    <!-- Main Content -->
                <div class="col-md-8">
                    <div class="container-box p-3">
                        <h5>Student Information</h5>
                            <form action="" id="IncidentReportForm">
                                <div class="student-info">
                                <div class="row mb-1">
                                    <label class="form-label">Student name</label>
                                    <input type="text" class="form-control" id="incident_report_name" name="student_name"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                </div>
                                    <div class="row mb-1">
                                        <label class="form-label">Student No.</label>
                                        <input type="text" class="form-control" id="incident_report_studentno" name="student_no"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="form-label">Course and section</label>
                                        <input type="text" class="form-control" id="incident_report_course" name="course_section"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="form-label">School email</label>
                                        <input type="email" class="form-control" id="incident_report_email" name="school_email"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                    </div>
                                </div>

                                <div class="violation-process mb-3">
                                    <label class="form-label">Faculty Name</label>
                                    <input type="text" class="form-control" name="faculty_name" id="incident_report_facultyName" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}"  pattern="[A-Za-z]+" title="Only letters are allowed" required>
                                </div>

                                <!-- Violation Dropdown Section -->
                                <div class="mb-3">
                                    <label class="form-label">Reason/s for Referral</label>
                                    <div class="dropdown" id="dropdown1">
                                        <select class="form-select" name="violation_type" id="incident_report_violationType">
                                            <option value="">Select Violation</option>
                                            @foreach ($violate as $data )
                                                <option value="{{ $data -> violation_id }}">{{ $data -> violations }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- End of Dropdown Section -->

                                <!-- RuleName, Description, Severity auto populate -->
                                    <label class="fw mt-2">Rule Name:</label>
                                    <p id="ruleName">-</p>
                                    <input type="hidden" id="incident_report_ruleNAme" name="rule_Name" style="display: none;" readonly required> 

                                    <label class="fw mt-2">Description: </label>
                                    <p id="descriptionName">-</p>
                                    <input type="hidden" id="incident_report_desc" name="description_Name" style="display: none;" readonly required>

                                    <div class="Severity mb-2">
                                        <label class="fw">Severity of Offense: </label>
                                        <p id="severityName">-</p> 
                                        <input type="hidden" id="incident_report_severity" name="severity_Name" style="display: none;" readonly required> 
                                    </div>
                                <!-- RuleName, Description, Severity auto populate END -->

                                <div class="form-group mt-3">
                                    <label for="floatingTextarea">Details</label>
                                    <textarea class="form-control" id="incident_report_remarks" style="height: 100px; resize: none; font-size: 14px;" maxlength="150"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="uploadEvidence" class="form-label mt-2">Upload Evidence</label>
                                    <input type="file" class="form-control form-control-sm" id="uploadEvidence" name="upload_evidence">
                                </div>

                                <button type="submit" class="btn btn-primary mt-2 btn-submit-incident">Submit</button>
                            </form>
                        </div>
                    </div>

                    <!-- Student List Section -->
                    <div class="col-md-4">
                        <div class="card card-custom p-3 mt-3">
                            <input type="text" class="form-control mb-3" placeholder="Search Student">
                            <div class="student-list">
                                @foreach ($accounts as $userdata )
                                    @if ($userdata -> role === 'student')
                                    <div class="student-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/Photos/UserPic.jpg" alt="Student">
                                            <div class="ms-2">
                                                <p class="mb-0 fw-bold">{{$userdata -> firstname}} {{$userdata -> lastname}}</p>
                                                <small>{{$userdata -> student_no}}</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm view-btn-incident"
                                        data-id="{{ $userdata->id }}"
                                        data-name="{{ $userdata->firstname }} {{ $userdata->lastname }}"
                                        data-email="{{ $userdata->email }}"
                                        data-student_no="{{ $userdata->student_no }}"
                                        data-course="{{ $userdata->course_and_section }}">
                                        View
                                        </button>
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

$(document).ready(function () {
        $(".view-btn-incident").on("click", function (e) {
        e.preventDefault();

        var studentId = $(this).data("id");
        var studentName = $(this).data("name");
        var email = $(this).data("email");
        var studentNo = $(this).data("student_no"); 
        var course = $(this).data("course");

        $("#incident_report_name").val(studentName);
        $("#incident_report_email").val(email);
        $("#incident_report_studentno").val(studentNo);
        $("#incident_report_course").val(course);
    });
});


$(document).on("change", "#incident_report_violationType", function (e) {
        e.preventDefault();
        
        var violation_id = $(this).val();

        if (!violation_id) {
            updateRuleDetails("-", "-", "-");
            return;
        }

        $.get("/get_rule/" + violation_id, function (response) {
            if (response.error) {
                updateRuleDetails("", "", "");
            } else {
                updateRuleDetails(response.rule_name, response.description, response.severity_name);
            }
        });

        function updateRuleDetails(rule, desc, severity) {
            $("#ruleName").text(rule);
            $("#descriptionName").text(desc);
            $("#severityName").text(severity);

            // Populate hidden inputs
            $("#incident_report_desc").val(desc);
            $("#incident_report_severity").val(severity);
            $("#incident_report_ruleNAme").val(rule);
        }
  
});

$(document).ready(function () {
    $("#IncidentReportForm").on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData();

        formData.append('_token', $('input[name="_token"]').val());
        formData.append('student_name', $('#incident_report_name').val());
        formData.append('student_no', $('#incident_report_studentno').val());
        formData.append('course_section', $('#incident_report_course').val());
        formData.append('school_email', $('#incident_report_email').val());
        formData.append('faculty_name', $('#incident_report_facultyName').val());
        formData.append('violation_type', $('#incident_report_violationType').val());
        formData.append('rule_name', $('#incident_report_ruleNAme').val());
        formData.append('description', $('#incident_report_desc').val());
        formData.append('severity', $('#incident_report_severity').val());
        formData.append('remarks', $('#incident_report_remarks').val());
        //formData.append('upload_evidence', $('#uploadEvidence')[0].files[0]);

        const fileInput = $('#uploadEvidence')[0];
        const file = fileInput.files[0];


        if (file) {
            formData.append('upload_evidence', file);
        }

        $.ajax({
            url: "/submit_incident_report", 
            type: "POST", 
            data: formData,
            contentType: false, 
            processData: false, 
            success: function (response) {
                console.log("Incident report submitted successfully!");
                $('#IncidentReportForm')[0].reset();

                
            },
            error: function (xhr) {
                console.log("Error submitting form.");
                console.log(xhr.responseText);
            }
        });
    });
});

</script>