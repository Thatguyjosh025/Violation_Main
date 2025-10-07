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

                                    <!-- <label for="course_section" class="fw-bold">Course and Section:</label>
                                    <p id="displaycourse">-</p> -->

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

    let dropArea = document.getElementById("dropArea");
    let fileInput = document.getElementById("uploadEvidence");
    let fileList = document.getElementById("fileList");
    let selectedFiles = []; 

    // -----------------------------
    // Drag & Drop Behaviors
    // -----------------------------
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.add('bg-light');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.remove('bg-light');
        }, false);
    });

    dropArea.addEventListener('drop', (e) => {
        let files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        let files = Array.from(e.target.files);
        handleFiles(files);
    });

    // -----------------------------
    // File Handling with Duplicate Rules
    // -----------------------------
    function handleFiles(files) {
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (files.length > 1) {
            let duplicates = files.filter(file => selectedFiles.find(f => f.name === file.name));
            if (duplicates.length > 0) {
                Swal.fire({ icon: "warning", text: `Multiple files have been already selected.` });
                return;
            }
        } else if (files.length === 1) {
            let file = files[0];
            let existingIndex = selectedFiles.findIndex(f => f.name === file.name);
            if (existingIndex !== -1) {
                // Replace old file
                selectedFiles[existingIndex] = file;
                Swal.fire({ icon: "info", text: `${file.name} have already selected.` });

                // Remove old entry
                [...fileList.children].forEach(li => {
                    if (li.firstChild.textContent.includes(file.name)) {
                        li.remove();
                    }
                });
            }
        }

        files.forEach(file => {
            if (file.size > maxSize) {
                Swal.fire({ icon: "error", text: `${file.name} exceeds 2MB limit.` });
                return;
            }

            if (!selectedFiles.find(f => f.name === file.name)) {
                selectedFiles.push(file);

                let li = document.createElement("li");
                li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                li.textContent = `${file.name} (${(file.size/1024).toFixed(1)} KB)`;

                let removeBtn = document.createElement("button");
                removeBtn.classList.add("btn", "btn-sm", "btn-danger");
                removeBtn.textContent = "x";
                removeBtn.onclick = () => {
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    li.remove();
                    syncInput();
                };

                li.appendChild(removeBtn);
                fileList.appendChild(li);
            }
        });

        syncInput();
        fileInput.value = ""; // allow reselection of same file
    }

    function syncInput() {
        let dataTransfer = new DataTransfer();
        selectedFiles.forEach(f => dataTransfer.items.add(f));
        fileInput.files = dataTransfer.files;
    }

    // -----------------------------
    // Reusable reset function
    // -----------------------------
    function resetViolationForm() {
        $('#postviolationForm').trigger('reset');

        ["#ruleName", "#descriptionName", "#severityName"].forEach(sel => $(sel).text("-"));

        $("#violation_type, #referal_type, #penalty_type, #remarks, input[name='faculty_involvement'], input[name='counseling_required'], #uploadEvidence, #facultyName").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        $("#facultyLabel").hide();
        $('#facultyName').hide().val('');

        $('#fileList').empty();
        selectedFiles = [];
        syncInput();

        $('#faculty_no').prop('checked', true);
        $('#counseling_no').prop('checked', true);
    }

    // -----------------------------
    // Modal Show / Close
    // -----------------------------
    $('#createViolationbtn').on('click', function(e){
        e.preventDefault();
        $('#violationModal').modal('show');
    });

    $('#close-btn').on('click', function(e){
        e.preventDefault();
        resetViolationForm();
    });

    $('#violationModal').on('hidden.bs.modal', function () {
        resetViolationForm();
    });

    // -----------------------------
    // View button functionality
    // -----------------------------
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

    // -----------------------------
    // Pre-fill modal fields
    // -----------------------------
    $("#createViolationbtn").on('click', function (e) {
        e.preventDefault();
        $('#modal_student_no').val($('#student_no').val());
        $('#modal_student_name').val($('#student_name').val());
        $('#modal_student_course').val($('#course_and_section').val());
        $('#modal_student_email').val($('#schoolEmail').val());
    });

    // -----------------------------
    // Validation clear on input
    // -----------------------------
    $("#student_no, #student_name, #course_and_section, #schoolEmail,#remarks").on("input change", function () {
        if ($(this).val()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // -----------------------------
    // Rule details
    // -----------------------------
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

    // -----------------------------
    // Faculty involvement toggle
    // -----------------------------
    $('input[name="faculty_involvement"]').change(function () {
        if ($('#faculty_yes').is(':checked')) {
            $('#facultyName').show();
            $("#facultyLabel").show().text('Enter Faculty Name:');
        } else {
            $("#facultyLabel").hide();
            $('#facultyName').hide().val('');
        }
    });

    // -----------------------------
    // Validation for selects/radios
    // -----------------------------
    $("#violation_type, #penalty_type, #referal_type").on("change", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    $("input[name='faculty_involvement'], input[name='counseling_required']").on("change", function () {
        if ($(this).is(':checked')) {
            $(this).removeClass("is-invalid");
            $(this).closest('.form-check').find(".invalid-feedback").remove();
        }
    });

    $("#facultyName").on("change", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // -----------------------------
    // Submit violation form
    // -----------------------------
    $("#submit_violation").click(function (e) {
        e.preventDefault();
        let isValid = true;

        $(".invalid-feedback").remove();
        $("#violation_type, #referal_type, #penalty_type, #facultyName, #remarks, input[name='faculty_involvement'], input[name='counseling_required']").removeClass("is-invalid");

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

        if ($("#facultyName").is(":visible") && !$("#facultyName").val()) {
            $("#facultyName").addClass("is-invalid").after('<div class="invalid-feedback">Please select a faculty name.</div>');
            isValid = false;
        }

        // if (selectedFiles.length === 0) {
        //     Swal.fire({ icon: "warning", text: "Please upload at least one evidence file." });
        //     isValid = false;
        // }

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

        selectedFiles.forEach(file => {
            formData.append('upload_evidence[]', file);
        });

        $.ajax({
            url: "/post_violation",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#violationModal').modal('hide');
                resetViolationForm();

                Swal.fire({ icon: "success", text: "Violation recorded successfully!", timer: 5000 });

                ["#ruleName", "#descriptionName", "#severityName"].forEach(sel => $(sel).text("-"));
                $('#displaystudentno').text("-");
                $('#displaystudentname').text("-");
                $('#displaycourse').text("-");
                $('#displayemail').text("-");

                $('#student_no').val('').hide();
                $('#student_name').val('').hide();
                $('#course_and_section').val('').hide();
                $('#schoolEmail').val('').hide();

                $('.recent-violation').html('<div class="violation-card-no" style="text-align: center;"><p>No selected student.</p></div>');
            },
            error: function (xhr) {
                Swal.fire({ icon: "error", title: "Oops...", text: "Oops! It looks like some required fields are missing or incorrect. Please check your inputs." });
                console.log(xhr.responseText);
            }
        });
    });

    // -----------------------------
    // Student search
    // -----------------------------
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
                                        data-course="${data.course_and_section}">View</button>
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
