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
            </div>

            <!-- Violation Management Section -->
            <div class="container container-custom mt-4">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-custom mt-3 p-4">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                <img src="{{ asset('./Photos/avatar.png') }}" class="profile-img" alt="Profile Picture">
                                <div class="studentinfo w-100">
                                    <label for="student_no" class="fw-bold">Student No:</label>
                                    <p id="displaystudentno">-</p>

                                    <label for="student_name" class="fw-bold">Student Name:</label>
                                    <p id="displaystudentname">-</p>

                                    <label for="course_section" class="fw-bold">Course and Section:</label>
                                    <p id="displaycourse">-</p>

                                    <label for="school_email" class="fw-bold">School Email:</label>
                                    <p id="displayemail">-</p>

                                    <small class="text-muted fw-bold">Please select a student from the list before creating a violation.</small>
                                    <input type="text" class="form-control" placeholder="Student No." id="student_no" style="display: none;" required readonly>
                                    <input type="text" class="form-control mt-1" placeholder="Student Name" id="student_name" style="display: none;" readonly required>
                                    <input type="text" class="form-control mt-1" placeholder="Course and Section" id="course_and_section" style="display: none;" readonly required>
                                    <input type="email" class="form-control mt-1" placeholder="Student School Email" id="schoolEmail" style="display: none;" readonly required>
                                </div>
                            </div>
                            <div class="createviolation mt-1">
                                <button class="btn create-violation-btn" id="createViolationbtn">+ Create Violation</button>
                            </div>
                        </div>
                        
                        <h5 class="mt-4 pb-2">Recent Violations</h5>
                        <div class="recent-violation" style="height: 22rem; max-height: 50%; overflow-y: auto;">
                            <!-- Violation of selected user will be generated here -->
                            <div class="violation-card-no" style="text-align: center;"><p>No selected student.</p></div>
                        </div>
                    </div>

                    <!-- Student List Section -->
                    <div class="col-lg-4">
                        <div class="card card-custom p-3 mt-3">
                            <input type="text" id="search-student" class="form-control mb-3" placeholder="Search Student">
                            <div class="student-list">
                                <p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>
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
    // Show violation modal
    $('#createViolationbtn').on('click', function(e){
        e.preventDefault();
        $('#violationModal').modal('show');
    });

    $('#close-btn').on('click', function(e){
        e.preventDefault();
        // Reset the form
        $('#postviolationForm').trigger('reset');

        // Reset display elements
        ["#ruleName", "#descriptionName", "#severityName"].forEach(sel => $(sel).text("-"));

        // Remove is-invalid classes and error messages from all form fields
        $("#violation_type, #referal_type, #penalty_type, #remarks, input[name='faculty_involvement'], input[name='counseling_required'], #uploadEvidence, #facultyName").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        $("#facultyLabel").hide();
        $('#facultyName').hide().val('');
    });


    //file upload validations
    $('#uploadEvidence').attr('accept', '.pdf,.jpg,.jpeg,.png,.docx');

     $('#uploadEvidence').change(function() {
        var file = this.files[0];
        var maxSize = 2 * 1024 * 1024; // 2MB in bytes

        // Remove any existing error message
        $(this).next('.invalid-feedback').remove();
        $(this).removeClass('is-invalid');

        if (file) {
            if (file.size > maxSize) {
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">File size must be less than 2MB.</div>');
            }
        } else {
            $(this).addClass('is-invalid');
            $(this).after('<div class="invalid-feedback">Please select a file.</div>');
        }
    });

    // View button functionality
    $(document).on("click", ".view-btn", function (e) {
        e.preventDefault();

        var studentId = $(this).data("id");
        var studentName = $(this).data("name");
        var email = $(this).data("email");
        var studentNo = $(this).data("student_no");
        var course = $(this).data("course");

        $('#displaystudentno').text(studentNo);
        $('#displaystudentname').text(studentName);
        $('#displaycourse').text(course);
        $('#displayemail').text(email);

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

    // Pre-fill modal fields on createViolation click
    $("#createViolationbtn").on('click', function (e) {
        e.preventDefault();
        $('#modal_student_no').val($('#student_no').val());
        $('#modal_student_name').val($('#student_name').val());
        $('#modal_student_course').val($('#course_and_section').val());
        $('#modal_student_email').val($('#schoolEmail').val());
    });

    // Remove error on input
    $("#student_no, #student_name, #course_and_section, #schoolEmail,#remarks").on("input change", function () {
        if ($(this).val()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // Update rule details
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

    // Faculty involvement toggle
    $('input[name="faculty_involvement"]').change(function () {
        if ($('#faculty_yes').is(':checked')) {
            $('#facultyName').show();
            $("#facultyLabel").show().text('Enter Faculty Name:');
        } else {
            $("#facultyLabel").hide();
            $('#facultyName').hide().val('');
        }
    });

    // Remove validation error for select and radio inputs
    $("#violation_type, #penalty_type, #referal_type").on("change", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // Remove validation error for radio inputs
    $("input[name='faculty_involvement'], input[name='counseling_required']").on("change", function () {
        if ($(this).is(':checked')) {
            $(this).removeClass("is-invalid");
            $(this).closest('.form-check').find(".invalid-feedback").remove();
        }
    });

    // Remove validation error for faculty name dropdown
    $("#facultyName").on("change", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });
    
    // Submit violation form
    $("#submit_violation").click(function (e) {
        e.preventDefault();
        let isValid = true;

       // Remove existing error messages
        $(".invalid-feedback").remove();
        $("#violation_type, #referal_type, #penalty_type, #facultyName, #remarks, input[name='faculty_involvement'], input[name='counseling_required']").removeClass("is-invalid");

        // Validate form fields
        const fieldsToValidate = [
            { id: "#violation_type", message: "Please select a violation." },
            { id: "#penalty_type", message: "Please select a penalty." },
            { id: "#referal_type", message: "Please select a referral action." },
            { id: "#remarks", message: "Please provide a remarks." }
        ];

        fieldsToValidate.forEach(field => {
            if (!$(field.id).val()) {
                $(field.id).addClass("is-invalid").after(`<div class="invalid-feedback">${field.message}</div>`);
                isValid = false;
            }
        });

        const radioFieldsToValidate = [
            { name: "faculty_involvement", message: "Please select faculty involvement." },
            { name: "counseling_required", message: "Please select counseling requirement." }
        ];

        radioFieldsToValidate.forEach(field => {
            if (!$(`input[name='${field.name}']:checked`).val()) {
                $(`input[name='${field.name}']`).addClass("is-invalid")
                    .first().parent().append(`<div class="invalid-feedback">${field.message}</div>`);
                isValid = false;
            }
        });


        // Validate the Faculty Name dropdown if it is visible
        if ($("#facultyName").is(":visible") && !$("#facultyName").val()) {
            $("#facultyName").addClass("is-invalid").after('<div class="invalid-feedback">Please select a faculty name.</div>');
            isValid = false;
        }

       // Validate file upload
        const fileUpload = $('#uploadEvidence')[0];
        if (fileUpload.files[0]) {
            var file = fileUpload.files[0];
            var maxSize = 2 * 1024 * 1024; // 2MB in bytes

            if (file.size > maxSize) {
                $('#uploadEvidence').addClass('is-invalid').after('<div class="invalid-feedback">File size must be less than 2MB.</div>');
                isValid = false;
            }
        }


        if (!isValid) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please fill out all required fields before submitting." });
            return;
        }

        let facultyName = $('#faculty_yes').is(':checked') ? $("#facultyName").val() : "N/A";

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
        if (fileInput.files[0]) {
            formData.append('upload_evidence', fileInput.files[0]);
        }

        $.ajax({
            url: "/post_violation",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#violationModal').modal('hide');

                $('#postviolationForm').trigger('reset');
                $("#facultyLabel").hide();
                $('#facultyName').hide().val('');

                //default to no after reset
                $('#faculty_no').prop('checked', true);
                $('#counseling_no').prop('checked', true);

                Swal.fire({ icon: "success", text: "Violation recorded successfully!", timer: 5000 });

                // Reset display elements
                ["#ruleName", "#descriptionName", "#severityName"].forEach(sel => $(sel).text("-"));
                $('#displaystudentno').text("-");
                $('#displaystudentname').text("-");
                $('#displaycourse').text("-");
                $('#displayemail').text("-");

                // Reset student info inputs
                $('#student_no').val('').hide();
                $('#student_name').val('').hide();
                $('#course_and_section').val('').hide();
                $('#schoolEmail').val('').hide();

                // Reset recent violations section
                $('.recent-violation').html('<div class="violation-card-no" style="text-align: center;"><p>No selected student.</p></div>');
            },
           error: function (xhr, message) {
                Swal.fire({ icon: "error", title: "Oops...", text: "Oops! It looks like some required fields are missing or incorrect. Please check your inputs." });
                console.log(xhr.responseText);
            }
        });
    });

    // Student search
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
                                    <button class="btn btn-outline-primary btn-sm view-btn"
                                        data-id="${data.id}"
                                        data-name="${data.firstname} ${data.lastname}"
                                        data-email="${data.email}"
                                        data-student_no="${data.student_no}"
                                        data-course="${data.course_and_section}">
                                        View
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
