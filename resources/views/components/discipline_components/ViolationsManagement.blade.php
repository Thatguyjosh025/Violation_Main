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
                                    <input type="text" class="form-control mb-2" placeholder="Student No." id="student_no" required readonly>
                                    <input type="text" class="form-control mb-2" placeholder="Student Name" id="student_name" readonly required>
                                    <input type="text" class="form-control mb-2" placeholder="Course and Section" id="course_and_section" readonly required>
                                    <input type="email" class="form-control mb-2" placeholder="Student School Email" id="schoolEmail" readonly required>
                                </div>
                            </div>
                            <div class="createviolation mt-1">
                                <button class="btn create-violation-btn" id="createViolationbtn">+ Create Violation</button>
                            </div>
                        </div>

                        <div class="recent-violation">
                            <h5 class="mt-4 pb-2">Recent Violations</h5>
                                
                        </div>
                    </div>

                    <!-- Student List Section -->
                    <div class="col-lg-4">
                        <div class="card card-custom p-3 mt-3">
                            <input type="text" class="form-control mb-3" placeholder="Search Student">
                            <div class="student-list">
                                @foreach ($accounts as $data )
                                    @if ($data -> role === 'student')
                                    <div class="student-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/Photos/UserPic.jpg" alt="Student">
                                            <div class="ms-2">
                                                <p class="mb-0 fw-bold">Student No.</p>
                                                <small>{{ $data -> student_no}}</small>
                                                <p>{{ $data->firstname }} {{ $data->lastname }}</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm view-btn"
                                        data-id="{{ $data->id }}"
                                        data-name="{{ $data->firstname }} {{ $data->lastname }}"
                                        data-email="{{ $data->email }}"
                                        data-student_no="{{ $data->student_no }}"
                                        data-course="{{ $data->course_and_section }}">
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
        </div>
    </div>
    
    @include('components.discipline_components.modals.modal')

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    $(document).ready(function(){
        $('#createViolationbtn').on('click', function(e){
            e.preventDefault();
            $('#violationModal').modal('show');
        });
    });

    //populating the input from the student list
    $(document).ready(function () {
    $(".view-btn").on("click", function (e) {
        e.preventDefault();

        var studentId = $(this).data("id");
        var studentName = $(this).data("name");
        var email = $(this).data("email");
        var studentNo = $(this).data("student_no"); // Assume this field exists
        var course = $(this).data("course");

        // Fill in form fields
        $("#student_name").val(studentName);
        $("#schoolEmail").val(email);
        $("#student_no").val(studentNo);
        $("#course_and_section").val(course);

        // AJAX request to fetch violations for the student
        $.ajax({
            url: "/get_violators_history/" + encodeURIComponent(studentName) + "/" + studentNo,
            type: "GET",
            success: function (response) {
                $(".recent-violation").empty();

                if (response.length === 0) {
                    $(".recent-violation").append("<div class='violation-card'><p>No violations found.</p></div>");
                } else {
                    response.forEach(function (violation) {
                        var card = `
                            <div class="violation-card">
                                <div class="top-content">
                                    <p id="dateandtime">Date: ${violation.date}</p>
                                    <p class="cheating-text fw-bold fs-5" id="violationtype">${violation.type}</p>
                                </div>
                                <p class="bottom-content" id="penaltytype">Violated Rule: ${violation.violatedrule}</p>
                            </div>
                        `;
                        $(".recent-violation").append(card);
                    });
                }
            },
            error: function () {
                alert("Failed to fetch violations.");
            }
        });
    });
});

    //passing the populated data outside of the modal
    $(document).ready(function(){
        $("#createViolationbtn").on('click', function(e){
            e.preventDefault();

            var studentnum = $('#student_no').val();
            var studentname = $('#student_name').val();
            var course = $('#course_and_section').val();
            var schoolemail = $('#schoolEmail').val();

            $('#modal_student_no').val(studentnum);
            $('#modal_student_name').val(studentname);
            $('#modal_student_course').val(course);
            $('#modal_student_email').val(schoolemail);

        })
    });

    //Selecting violation populating the rule,desc,severity
    $(document).on("change", "#violation_type", function (e) {
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
            $("#descriptionNameInput").val(desc);
            $("#severityNameInput").val(severity);
            $("#ruleNameInput").val(rule);
        }
  
    });

    //faculty Inolvement
    $(document).ready(function () {
        $('input[name="faculty_involvement"]').change(function () {
            if ($('#faculty_yes').is(':checked')) {
                $('#facultyName').show();
                $("#facultyLabel").show().text('Enter Faculty Name:');
            } else {
                $("#facultyLabel").hide();
                $('#facultyName').hide().val('');
            }
        });
    });

    //submit form
    $(document).ready(function () {
        $("#submit_violation").click(function (e) {
            e.preventDefault();

            let facultyName = "N/A";

            if ($('#faculty_yes').is(':checked')) {
                facultyName = $("#facultyName").val();
            }

            let formData = new FormData();

            formData.append('_token', $('input[name="_token"]').val());
            formData.append('student_no', $("#modal_student_no").val());
            formData.append('student_name', $("#modal_student_name").val());
            formData.append('course', $("#modal_student_course").val());
            formData.append('school_email', $("#modal_student_email").val());
            formData.append('violation_type', $("#violation_type").val());
            formData.append('penalty_type', $("#penalty_type").val());
            formData.append('severity_Name', $("#severityNameInput").val());
            formData.append('rule_Name', $("#ruleNameInput").val());
            formData.append('description_Name', $("#descriptionNameInput").val());
            formData.append('faculty_involvement', $("input[name='faculty_involvement']:checked").val());
            formData.append('faculty_name', facultyName);
            formData.append('counseling_required', $("input[name='counseling_required']:checked").val());
            formData.append('referal_type', $("#referal_type").val());
            formData.append('Remarks', $("#remarks").val());
            formData.append('upload_evidence', $("#uploadEvidence")[0].files[0]);

            $.ajax({
                url: "/post_violation",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log("Violation recorded successfully!");

                    const clearallfield = [
                        "student_no",
                        "student_name",
                        "course_and_section",
                        "schoolEmail",
                        "violation_type",
                        "penalty_type",
                        "severityNameInput",
                        "ruleNameInput",
                        "descriptionNameInput",
                        "referal_type",
                        "remarks",
                        "facultyName",
                        "uploadEvidence"
                    ];

                    for (let field of clearallfield) {
                        $(`#${field}`).val("");
                    }

                    $('#postviolationForm').reset();

                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Oops! It looks like some required fields are missing or incorrect. Please check your inputs.",
                        footer: '<a href="#">Why do I have this issue?</a>'
                    });
                    console.log("Error submitting form. Please check the inputs.");
                    console.log(xhr.responseText);
                }
            });
    });
});


</script>
