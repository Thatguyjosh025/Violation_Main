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

                                    <label class="form-label fw-bold">Student name:</label>
                                    <p id="displaystudentname" class="mt-1 ms-1">-</p>
                                    <input type="hidden" class="form-control" id="incident_report_name" name="student_name"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                  
                                    <label class="form-label fw-bold">Student No.</label>
                                    <p id="displaystudentno" class="mt-1 ms-1">-</p>
                                    <input type="hidden" class="form-control" id="incident_report_studentno" name="student_no"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
                                
                                    <label class="form-label  fw-bold ">Course and section</label>
                                    <p id="displaycourse" class="mt-1 ms-1">-</p>
                                    <input type="hidden" class="form-control" id="incident_report_course" name="course_section"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>

                                    <label class="form-label  fw-bold ">School email</label>
                                    <p id="displayemail" class="mt-1 ms-1">-</p>
                                    <input type="email" class="form-control" id="incident_report_email" name="school_email"  pattern="[A-Za-z]+" title="Only letters are allowed" style="display: none;" readonly required>
                                    <p class="text-muted fw-bold">Kindly select a student from the list before submitting a violation.</p>

                                    <label class="form-label">Faculty Name</label>
                                    <input type="text" class="form-control" name="faculty_name" id="incident_report_facultyName" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}"  pattern="[A-Za-z ]+" title="Only letters are allowed" disabled>
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
                        <div class="card card-custom p-3">
                            <input type="text" id="search-student" class="form-control mb-3" placeholder="Search Student">
                            <div class="student-list">
                                <p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>
                            </div>
                        </div>
                    </div>

                     
                </div>
            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>

$(document).on("click", ".select-btn", function (e) {
    e.preventDefault();

    var studentId = $(this).data("id");
    var studentName = $(this).data("name");
    var email = $(this).data("email");
    var studentNo = $(this).data("student_no");
    var course = $(this).data("course");

    $('#displaystudentname').text(studentName);
    $('#displaystudentno').text(studentNo);
    $('#displaycourse').text(course);
    $('#displayemail').text(email);

    $("#incident_report_name").val(studentName).removeClass("is-invalid").next(".invalid-feedback").remove();
    $("#incident_report_email").val(email).removeClass("is-invalid").next(".invalid-feedback").remove();
    $("#incident_report_studentno").val(studentNo).removeClass("is-invalid").next(".invalid-feedback").remove();
    $("#incident_report_course").val(course).removeClass("is-invalid").next(".invalid-feedback").remove();
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

//Incident Report
$(document).ready(function () {

    // removes the errors when field have inputs
    $("#incident_report_name, #incident_report_studentno, #incident_report_course, #incident_report_email, #incident_report_violationType, #incident_report_remarks").on("input change", function () {
        if ($(this).val()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    $("#IncidentReportForm").on('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // Validate required fields
        if (!$("#incident_report_name").val()) {
            $("#incident_report_name").addClass("is-invalid");
            if ($("#incident_report_name").next(".invalid-feedback").length === 0) {
                $("#incident_report_name").after('<div class="invalid-feedback">Please enter the student name.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_name").removeClass("is-invalid");
            $("#incident_report_name").next(".invalid-feedback").remove();
        }

        if (!$("#incident_report_studentno").val()) {
            $("#incident_report_studentno").addClass("is-invalid");
            if ($("#incident_report_studentno").next(".invalid-feedback").length === 0) {
                $("#incident_report_studentno").after('<div class="invalid-feedback">Please enter the student number.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_studentno").removeClass("is-invalid");
            $("#incident_report_studentno").next(".invalid-feedback").remove();
        }

        if (!$("#incident_report_course").val()) {
            $("#incident_report_course").addClass("is-invalid");
            if ($("#incident_report_course").next(".invalid-feedback").length === 0) {
                $("#incident_report_course").after('<div class="invalid-feedback">Please enter the course and section.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_course").removeClass("is-invalid");
            $("#incident_report_course").next(".invalid-feedback").remove();
        }

        if (!$("#incident_report_email").val()) {
            $("#incident_report_email").addClass("is-invalid");
            if ($("#incident_report_email").next(".invalid-feedback").length === 0) {
                $("#incident_report_email").after('<div class="invalid-feedback">Please enter the school email.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_email").removeClass("is-invalid");
            $("#incident_report_email").next(".invalid-feedback").remove();
        }

        if (!$("#incident_report_violationType").val()) {
            $("#incident_report_violationType").addClass("is-invalid");
            if ($("#incident_report_violationType").next(".invalid-feedback").length === 0) {
                $("#incident_report_violationType").after('<div class="invalid-feedback">Please select a violation type.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_violationType").removeClass("is-invalid");
            $("#incident_report_violationType").next(".invalid-feedback").remove();
        }

        if (!$("#incident_report_remarks").val()) {
            $("#incident_report_remarks").addClass("is-invalid");
            if ($("#incident_report_remarks").next(".invalid-feedback").length === 0) {
                $("#incident_report_remarks").after('<div class="invalid-feedback">Please provide a detailed description.</div>');
            }
            isValid = false;
        } else {
            $("#incident_report_remarks").removeClass("is-invalid");
            $("#incident_report_remarks").next(".invalid-feedback").remove();
        }

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

                var violation_id = $("#incident_report_violationType").val();
                if (!violation_id) {
                    $("#ruleName").text("-");
                    $("#descriptionName").text("-");
                    $("#severityName").text("-");
                }

                $('#displaystudentname').text('-');
                $('#displaystudentno').text('-');
                $('#displaycourse').text('-');
                $('#displayemail').text('-');

                Swal.fire({
                    icon: "success",
                    text: "Incident report submitted successfully!",
                    timer: 5000
                });
            },
            error: function (xhr) {
                console.log("Error submitting form.");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Oops! It looks like some required fields are missing or incorrect. Please check your inputs.",
                });
                console.log(xhr.responseText);
            }
        });
    });

    $('#search-student').on('keypress', function(e) {
        const query = $(this).val().trim();

        if (e.which === 13 && query) {
            e.preventDefault(); 

            $.ajax({
                url: "/student_search",
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    if (response.length === 0) {
                        $('.student-list').html('<p class="text-muted text-center">No student found.</p>');
                    } else {
                        let html = '';
                        response.forEach(function(data) {
                            html += `
                                <div class="student-item">
                                    <div class="d-flex align-items-center">
                                        <img src="/Photos/avatar.png" alt="Student">
                                        <div class="ms-2">
                                            <p class="mb-0 fw-bold">Student No.</p>
                                            <small>${data.student_no}</small>
                                            <p>${data.firstname} ${data.lastname}</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm select-btn"
                                        data-id="${data.id}"
                                        data-name="${data.firstname} ${data.lastname}"
                                        data-email="${data.email}"
                                        data-student_no="${data.student_no}"
                                        data-course="${data.course_and_section}">
                                        Select
                                    </button>
                                </div>`;
                        });
                        $('.student-list').html(html);
                    }
                }
            });
        }
    });
});

</script>