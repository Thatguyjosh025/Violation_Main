<link rel="stylesheet" href="{{ asset('./css/faculty_css/FViolationManagment.css') }}">
@php
use App\Models\users;
use App\Models\violation;
use App\Models\referals;
use App\Models\penalties;
$violate = violation::where('is_visible', 'active')->get();
$accounts = users::get();
@endphp

<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Incident Report</h3>
</div>

<div class="container mt-4">
    <div class="row mt-3">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="container-box p-3">
                <h5>Student Information</h5>
                <form action="" method="POST" id="IncidentReportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="student-info">
                        <label class="form-label fw-bold">Student name:</label>
                        <p id="displaystudentname" class="mt-1 ms-1">-</p>
                        <input type="text" class="form-control" id="incident_report_name" name="student_name" pattern="[A-Za-z ]+" title="Only letters and spaces are allowed" style="display: none;" readonly required>

                        <label class="form-label fw-bold">Student No.</label>
                        <p id="displaystudentno" class="mt-1 ms-1">-</p>
                        <input type="text" class="form-control" id="incident_report_studentno" name="student_no" style="display: none;" readonly required>

                        <label class="form-label fw-bold">School email</label>
                        <p id="displayemail" class="mt-1 ms-1">-</p>
                        <input type="email" class="form-control" id="incident_report_email" name="school_email" style="display: none;" readonly required>

                        <p class="text-muted fw-bold">Kindly select a student from the list before submitting a violation.</p>

                        <label class="form-label" style="display: none;">Faculty Name</label>
                        <input type="text" class="form-control" name="faculty_name" id="incident_report_facultyName" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}" pattern="[A-Za-z ]+" title="Only letters and spaces are allowed" disabled hidden>
                        <input type="hidden" name="faculty_id" id="incident_faculty_id" value="{{ Auth::user()->student_no }}">
                    </div>

                    <!-- Violation Dropdown Section -->
                    <div class="mb-3">
                        <label class="form-label">Reason/s for Referral</label>
                        <div class="dropdown" id="dropdown1">
                            <select class="form-select" name="violation_type" id="incident_report_violationType">
                                <option value="">Select Violation</option>
                                @foreach ($violate as $data)
                                    <option value="{{ $data->violation_id }}">{{ $data->violations }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw mt-2">Rule Name:</label>
                        <select id="incident_report_ruleDropdown" class="form-select" >
                            <option value="">Select Rule</option>
                        </select>
                    </div>

                    <!-- RuleName, Description, Severity auto populate -->
                    <!-- <label class="fw mt-2">Rule Name:</label> -->
                    <!-- <p id="ruleName">-</p> -->
                    <input type="hidden" id="incident_report_ruleName" name="rule_name" style="display: none;" readonly required>

                    <label class="fw mt-2">Description: </label>
                    <p id="descriptionName">-</p>
                    <input type="hidden" id="incident_report_desc" name="description" style="display: none;" readonly required>

                    <div class="Severity mb-2">
                        <label class="fw">Severity of Offense: </label>
                        <p id="severityName">-</p>
                        <input type="hidden" id="incident_report_severity" name="severity" style="display: none;" readonly required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="floatingTextarea">Details</label>
                        <textarea class="form-control" id="incident_report_remarks" name="remarks" style="height: 100px; resize: none; font-size: 14px;" maxlength="500" required></textarea>
                    </div>

                    <!-- UPLOAD FILES (Bulk Upload) -->
                    <div class="my-3">
                        <label for="uploadEvidence"
                               class="d-block w-100 p-5 border border-dark rounded text-center bg-white drop-area"
                               style="cursor: pointer; border-style: dashed;">
                            <div class="text-dark fw-medium">Upload Evidence</div>
                              <small class="text-muted">
                              Click to select files or drag them here<br>
                              Allowed formats: JPG, JPEG, PNG, PDF, DOCX (max 2MB)
                            </small>
                        </label>
                        <input type="file" id="uploadEvidence" name="upload_evidence[]" multiple hidden accept=".jpg,.jpeg,.png,.pdf,.docx">
                        <ul id="fileList" class="list-group mt-2" style="max-height: 150px; overflow-y: auto;"></ul>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2 btn-submit-incident" id="btnsubmitincident" >Submit</button>
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
$(document).ready(function () {
    let selectedFiles = [];
    const fileInput = document.getElementById("uploadEvidence");
    const fileList = document.getElementById("fileList");
    const dropArea = document.querySelector(".drop-area");

    // ---------- STUDENT SELECTION ----------
    $(document).on("click", ".select-btn", function (e) {
        e.preventDefault();
        const studentName = $(this).data("name");
        const studentNo = $(this).data("student_no");
        const email = $(this).data("email");

        $('#displaystudentname').text(studentName);
        $('#displaystudentno').text(studentNo);
        $('#displayemail').text(email);

        $("#incident_report_name").val(studentName).removeClass("is-invalid").next(".invalid-feedback").remove();
        $("#incident_report_studentno").val(studentNo).removeClass("is-invalid").next(".invalid-feedback").remove();
        $("#incident_report_email").val(email).removeClass("is-invalid").next(".invalid-feedback").remove();
    });

    // ---------- DROPDOWN LOADERS ----------
    function loadViolationDropdown(url, id, selectedValue = null, callback) {
        $.ajax({
            url: url,
            success: function(response) {
                const dropdown = $(id);
                dropdown.empty().append('<option value="">Select Violation</option>');

                response.violation_data.forEach(item => {
                    const isSelected = item.violation_id == selectedValue ? 'selected' : '';
                    dropdown.append(`<option value="${item.violation_id}" ${isSelected}>${item.violations}</option>`);
                });

                if (typeof callback === "function") callback();
            }
        });
    }

   // ---------- LOAD RULE DROPDOWN ----------
    function loadRuleDropdown(url) {
        $.get(url, function(response) {
            const dropdown = $("#incident_report_ruleDropdown");
            dropdown.empty().append('<option value="">Select Rule</option>');

            if (!response.rules || response.rules.length === 0) {
                dropdown.append('<option value="">No rules available</option>');
                updateRuleDetails("-", "-", "-");
                return;
            }

            response.rules.forEach(item => {
                dropdown.append(
                    `<option value="${item.id}" data-rule="${item.rule_name}" data-desc="${item.description}" data-severity="${item.severity ? item.severity.severity : '-'}">
                        ${item.rule_name}
                    </option>`
                );
            });

            // Reset rule details on new load
            updateRuleDetails("-", "-", "-");
        });
    }

    // ---------- UPDATE RULE DETAILS ----------
    function updateRuleDetails(rule, desc, severity) {
        $("#ruleName").text(rule);
        $("#descriptionName").text(desc);
        $("#severityName").text(severity);
        $("#incident_report_ruleName").val(rule);
        $("#incident_report_desc").val(desc);
        $("#incident_report_severity").val(severity);
    }

    $(document).on("change", "#incident_report_violationType", function () {
        const violation_id = $(this).val();
        if (!violation_id) {
            updateRuleDetails("-", "-", "-");
            $("#incident_report_ruleDropdown").empty().append('<option value="">Select Rule</option>');
            return;
        }
        loadRuleDropdown(`/get_rule/${violation_id}`);
    });

    $(document).on("change", "#incident_report_ruleDropdown", function () {
        const selected = $(this).find(":selected");
        updateRuleDetails(
            selected.data("rule") || "-",
            selected.data("desc") || "-",
            selected.data("severity") || "-"
        );
    });


    // ---------- FILE UPLOAD ----------
    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function handleFiles(files) {
        let duplicateFiles = []; // track duplicates

        for (const file of files) {
            if (!selectedFiles.find(f => f.name === file.name)) {
                selectedFiles.push(file);
                const li = document.createElement("li");
                li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                li.textContent = file.name;
                const removeBtn = document.createElement("button");
                removeBtn.classList.add("btn", "btn-sm", "btn-danger");
                removeBtn.textContent = "x";
                removeBtn.onclick = () => {
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    li.remove();
                    syncInput();
                };
                li.appendChild(removeBtn);
                fileList.appendChild(li);
            } else {    
                duplicateFiles.push(file.name); // add duplicates
            }
        }

        if (duplicateFiles.length > 0) {
            Swal.fire({
                icon: "info",
                text: duplicateFiles.length === 1
                    ? `${duplicateFiles[0]} is already selected.`
                    : `These files were already selected: ${duplicateFiles.join(", ")}`
            });
        }

        syncInput();
    }

    fileInput.addEventListener("change", e => handleFiles([...e.target.files]));
    ["dragenter","dragover"].forEach(evt => dropArea.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); dropArea.classList.add("bg-light"); }));
    ["dragleave","drop"].forEach(evt => dropArea.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); dropArea.classList.remove("bg-light"); }));
    dropArea.addEventListener("drop", e => handleFiles([...e.dataTransfer.files]));

    // ---------- STUDENT SEARCH ----------
    let typingTimer;
    const typingDelay = 0; // debounce (ms)
    function getInitials(first, last) {
        const f = first ? first.charAt(0).toUpperCase() : '';
        const l = last ? last.charAt(0).toUpperCase() : '';
        return f + l;
    }
    $('#search-student').on('keyup', function () {
        clearTimeout(typingTimer);

        const query = $(this).val().trim();

        // If search bar is empty → show placeholder and stop
        if (query === '') {
            $('.student-list').html('<p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>');
            return;
        }

        typingTimer = setTimeout(function () {

            $.ajax({
                url: "/student_search",
                method: 'GET',
                data: { query: query },
                success: function(response) {

                    const results = response.slice(0, 3); // Show only first 3

                    if (results.length === 0) {
                        $('.student-list').html('<p class="text-muted text-center">No student found.</p>');
                    } else {
                        let html = '';
                            results.forEach(function(data) {
                                html += `
                                    <div class="student-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="initial-icon">
                                                ${getInitials(data.firstname, data.lastname)}
                                            </div>
                                            <div class="ms-2">
                                                <p class="mb-0 fw-bold">${data.firstname} ${data.lastname}</p>
                                                <small>${data.student_no}</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm select-btn"
                                            data-id="${data.id}"
                                            data-name="${data.firstname} ${data.lastname}"
                                            data-email="${data.email}"
                                            data-student_no="${data.student_no}"
                                            data-first="${data.firstname}"
                                            data-last="${data.lastname}"
                                            data-course="${data.course_and_section}">
                                            Select
                                        </button>
                                    </div>`;
                            });
                        $('.student-list').html(html);
                    }
                }
            });

        }, 0);
    });

    // ---------- FORM SUBMISSION ----------
    $("#IncidentReportForm").on('submit', function (e) {
        e.preventDefault();
        let isValid = true;
        const requiredFields = [
            {id: "#incident_report_name", msg: "Please select student name in the list."},
            {id: "#incident_report_studentno", msg: "Please select student number in the list."},
            {id: "#incident_report_email", msg: "Please select school email in the list."},
            {id: "#incident_report_violationType", msg: "Please select a violation type."},
            {id: "#incident_report_remarks", msg: "Please provide a detailed description."},
            {id: "#incident_report_ruleDropdown", msg: "Please select a rule."},
            // {id: "#incident_report_desc", msg: "Description is required."},
            // {id: "#incident_report_severity", msg: "Severity is required."},
        ];

        requiredFields.forEach(field => {
            if (!$(field.id).val()) {
                $(field.id).addClass("is-invalid");
                if ($(field.id).next(".invalid-feedback").length === 0) {
                    $(field.id).after('<div class="invalid-feedback">'+field.msg+'</div>');
                }
                isValid = false;
            } else {
                $(field.id).removeClass("is-invalid");
                $(field.id).next(".invalid-feedback").remove();
            }
        });

        if (!isValid) return;

        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());
        formData.append('student_name', $("#incident_report_name").val());
        formData.append('student_no', $("#incident_report_studentno").val());
        formData.append('school_email', $("#incident_report_email").val());
        formData.append('faculty_name', $("#incident_report_facultyName").val());
        formData.append('faculty_id', $("#incident_faculty_id").val());
        formData.append('violation_type', $("#incident_report_violationType").val());
        formData.append('rule_name', $("#incident_report_ruleName").val());
        formData.append('description', $("#incident_report_desc").val());
        formData.append('severity', $("#incident_report_severity").val());
        formData.append('remarks', $("#incident_report_remarks").val());

        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append("upload_evidence[]", fileInput.files[i]);
        }
        
        $("#btnsubmitincident")
        .prop("disabled", true)
        .text("Submiting...");

        Swal.fire({
            title: 'Processing Incident Report',
            text: 'Please wait while submitting the incident report.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "/submit_incident_report",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#IncidentReportForm')[0].reset();
                $('#fileList').empty();
                selectedFiles = [];
                $("#ruleName, #descriptionName, #severityName").text("-");
                $('#displaystudentname, #displaystudentno, #displayemail').text('-');


                $("#btnsubmitincident")
                    .prop("disabled", false)
                    .text("Submit");

                Swal.fire({
                    icon: "success",
                    text: "Incident report submitted successfully!",
                    timer: 5000
                });
            },
            error: function (xhr) {
                
                 $("#btnsubmitincident")
                .prop("disabled", false)
                .text("Submit");

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong. Try to refresh the page and try again."
                });
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on("change", "#incident_report_violationType", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    // REMOVE ERROR ON INPUT FOR RULE DROPDOWN
    $(document).on("change", "#incident_report_ruleDropdown", function () {
        if ($(this).val() !== "") {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });
});
</script>
