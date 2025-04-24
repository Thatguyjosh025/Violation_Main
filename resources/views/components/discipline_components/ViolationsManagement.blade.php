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
                                    <input type="text" class="form-control" placeholder="Student No." id="student_no" required readonly>
                                    <input type="text" class="form-control mt-1" placeholder="Student Name" id="student_name" readonly required>
                                    <input type="text" class="form-control mt-1" placeholder="Course and Section" id="course_and_section" readonly required>
                                    <input type="email" class="form-control mt-1" placeholder="Student School Email" id="schoolEmail" readonly required>
                                </div>
                            </div>
                            <div class="createviolation mt-1">
                                <button class="btn create-violation-btn" id="createViolationbtn">+ Create Violation</button>
                            </div>
                        </div>
                        
                        <h5 class="mt-4 pb-2">Recent Violations</h5>
                        <div class="recent-violation">
                            
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

    $(document).ready(function () {
    // Populate fields from student list
    $(".view-btn").on("click", function (e) {
        e.preventDefault();

        var studentId = $(this).data("id");
        var studentName = $(this).data("name");
        var email = $(this).data("email");
        var studentNo = $(this).data("student_no");
        var course = $(this).data("course");

        $("#student_name").val(studentName).removeClass("is-invalid").next(".invalid-feedback").remove();
        $("#schoolEmail").val(email).removeClass("is-invalid").next(".invalid-feedback").remove();
        $("#student_no").val(studentNo).removeClass("is-invalid").next(".invalid-feedback").remove();
        $("#course_and_section").val(course).removeClass("is-invalid").next(".invalid-feedback").remove();

        // Fetch violations for the student
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

    // Pass populated data to modal
    $("#createViolationbtn").on('click', function (e) {
        e.preventDefault();

        let isValid = true;

        // Fields to validate
        const fields = [
            { id: "student_no", message: "Student No. is required." },
            { id: "student_name", message: "Student Name is required." },
            { id: "course_and_section", message: "Course and Section is required." },
            { id: "schoolEmail", message: "Student School Email is required." }
        ];

        fields.forEach(field => {
            const el = $(`#${field.id}`);
            if (!el.val()) {
                el.addClass("is-invalid");
                if (el.next(".invalid-feedback").length === 0) {
                    el.after(`<div class="invalid-feedback">${field.message}</div>`);
                }
                isValid = false;
            } else {
                el.removeClass("is-invalid");
                el.next(".invalid-feedback").remove();
            }
        });

        // Transfer values to modal
        $('#modal_student_no').val($('#student_no').val());
        $('#modal_student_name').val($('#student_name').val());
        $('#modal_student_course').val($('#course_and_section').val());
        $('#modal_student_email').val($('#schoolEmail').val());
    });

    // removes the error in the fields when they have input
    $("#student_no, #student_name, #course_and_section, #schoolEmail,#remarks").on("input change", function () {
        if ($(this).val()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // Violation rule/severity/description population
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

            $("#descriptionNameInput").val(desc);
            $("#severityNameInput").val(severity);
            $("#ruleNameInput").val(rule);
        }
    });
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

    // Toggle faculty name input
    $('input[name="faculty_involvement"]').change(function () {
        if ($('#faculty_yes').is(':checked')) {
            $('#facultyName').show();
            $("#facultyLabel").show().text('Enter Faculty Name:');
        } else {
            $("#facultyLabel").hide();
            $('#facultyName').hide().val('');
        }
    });

    // Auto remove error styling when user interacts with fields
    $("#violation_type, #penalty_type, #referal_type").on("change", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    $("input[name='faculty_involvement']").on("change", function () {
        if ($("input[name='faculty_involvement']:checked").val()) {
            $("input[name='faculty_involvement']").removeClass("is-invalid");
            $("input[name='faculty_involvement']").parent().find(".invalid-feedback").remove();
        }
    });

    $("input[name='counseling_required']").on("change", function () {
        if ($("input[name='counseling_required']:checked").val()) {
            $("input[name='counseling_required']").removeClass("is-invalid");
            $("input[name='counseling_required']").parent().find(".invalid-feedback").remove();
        }
    });

    $("#submit_violation").click(function (e) {
        e.preventDefault();

        let isValid = true;

        $("#violation_type, #referal_type, #penalty_type, #remarks").removeClass("is-invalid");
        $("input[name='faculty_involvement']").removeClass("is-invalid");
        $("input[name='counseling_required']").removeClass("is-invalid");

        // Validate dropdowns
        if (!$("#violation_type").val()) {
            $("#violation_type").addClass("is-invalid");
            if ($("#violation_type").next(".invalid-feedback").length === 0) {
                $("#violation_type").after('<div class="invalid-feedback">Please select a violation.</div>');
            }
            isValid = false;
        }

        if (!$("#penalty_type").val()) {
            $("#penalty_type").addClass("is-invalid");
            if ($("#penalty_type").next(".invalid-feedback").length === 0) {
                $("#penalty_type").after('<div class="invalid-feedback">Please select a penalty.</div>');
            }
            isValid = false;
        }
        if (!$("#referal_type").val()) {
            $("#referal_type").addClass("is-invalid");
            if ($("#referal_type").next(".invalid-feedback").length === 0) {
                $("#referal_type").after('<div class="invalid-feedback">Please select a referral action.</div>');
            }
            isValid = false;
        }

        // Validate radio buttons
        if (!$("input[name='faculty_involvement']:checked").val()) {
            $("input[name='faculty_involvement']").addClass("is-invalid");
            if ($("input[name='faculty_involvement']").parent().find(".invalid-feedback").length === 0) {
                $("input[name='faculty_involvement']").first().parent()
                    .append('<div class="invalid-feedback">Please select faculty involvement.</div>');
            }
            isValid = false;
        }

        if (!$("input[name='counseling_required']:checked").val()) {
            $("input[name='counseling_required']").addClass("is-invalid");
            if ($("input[name='counseling_required']").parent().find(".invalid-feedback").length === 0) {
                $("input[name='counseling_required']").first().parent()
                    .append('<div class="invalid-feedback">Please select counseling requirement.</div>');
            }
            isValid = false;
        }

        // Validate remarks
        if (!$("#remarks").val()) {
            $("#remarks").addClass("is-invalid");
            $("#remarks").after('<div class="invalid-feedback">Please provide a remarks.</div>');
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please fill out all required fields before submitting."
            });
            return;
        }

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
        formData.append('appeal', $("#appeal").val());
        formData.append('Remarks', $("#remarks").val());

        const fileInput = $('#uploadEvidence')[0];
        const file = fileInput.files[0];
        if (file) {
            formData.append('upload_evidence', file);
        }

        $.ajax({
            url: "/post_violation",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log("Violation recorded successfully!");
                $('#violationModal').modal('hide');
                $('#postviolationForm').trigger('reset');
                $("#facultyLabel").hide();
                $('#facultyName').hide().val('');

                Swal.fire({
                    icon: "success",
                    text: "Violation recorded successfully!",
                    timer: 5000
                });

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

                var violation_id = $("#violation_type").val();
                if (!violation_id) {
                    $("#ruleName").text("-");
                    $("#descriptionName").text("-");
                    $("#severityName").text("-");
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Oops! It looks like some required fields are missing or incorrect. Please check your inputs.",
                });
                console.log("Error submitting form. Please check the inputs.");
                console.log(xhr.responseText);
            }
        });
    });
});


</script>
