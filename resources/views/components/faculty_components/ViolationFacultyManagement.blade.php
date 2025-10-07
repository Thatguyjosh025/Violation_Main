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

                    <!-- RuleName, Description, Severity auto populate -->
                    <label class="fw mt-2">Rule Name:</label>
                    <p id="ruleName">-</p>
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
                            <small class="text-muted">Click to select files or drag them here</small>
                        </label>
                        <input type="file" id="uploadEvidence" name="upload_evidence[]" multiple hidden>
                        <ul id="fileList" class="list-group mt-2" style="max-height: 150px; overflow-y: auto;"></ul>
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
    $('#displaystudentname').text(studentName);
    $('#displaystudentno').text(studentNo);
    $('#displayemail').text(email);
    $("#incident_report_name").val(studentName).removeClass("is-invalid").next(".invalid-feedback").remove();
    $("#incident_report_email").val(email).removeClass("is-invalid").next(".invalid-feedback").remove();
    $("#incident_report_studentno").val(studentNo).removeClass("is-invalid").next(".invalid-feedback").remove();
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
        $("#incident_report_desc").val(desc);
        $("#incident_report_severity").val(severity);
        $("#incident_report_ruleName").val(rule);
    }
});

$(document).ready(function () {
    let selectedFiles = [];
    const fileInput = document.getElementById("uploadEvidence");
    const fileList = document.getElementById("fileList");
    const dropArea = document.querySelector(".drop-area");

    function syncInput() {
        let dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function handleFiles(files) {
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
            }
        }
        syncInput();
    }

    fileInput.addEventListener("change", e => handleFiles([...e.target.files]));

    ["dragenter","dragover"].forEach(evt =>
        dropArea.addEventListener(evt, e => {
            e.preventDefault(); e.stopPropagation();
            dropArea.classList.add("bg-light");
        })
    );

    ["dragleave","drop"].forEach(evt =>
        dropArea.addEventListener(evt, e => {
            e.preventDefault(); e.stopPropagation();
            dropArea.classList.remove("bg-light");
        })
    );

    dropArea.addEventListener("drop", e => handleFiles([...e.dataTransfer.files]));

    // Search on Enter only
    $('#search-student').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length > 0) {
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
                                            data-student_no="${data.student_no}">
                                            Select
                                        </button>
                                    </div>`;
                            });
                            $('.student-list').html(html);
                        }
                    }
                });
            }
        }
    });

    // Form submission with explicit formData.append()
    $("#IncidentReportForm").on('submit', function (e) {
        e.preventDefault();
        let isValid = true;
        const requiredFields = [
            {id: "#incident_report_name", msg: "Please enter the student name."},
            {id: "#incident_report_studentno", msg: "Please enter the student number."},
            {id: "#incident_report_email", msg: "Please enter the school email."},
            {id: "#incident_report_violationType", msg: "Please select a violation type."},
            {id: "#incident_report_remarks", msg: "Please provide a detailed description."},
            {id: "#incident_report_ruleName", msg: "Rule name is required."},
            {id: "#incident_report_desc", msg: "Description is required."},
            {id: "#incident_report_severity", msg: "Severity is required."},
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

        // Create FormData and append all fields explicitly
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

        // Append files
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append("upload_evidence[]", fileInput.files[i]);
        }

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
                Swal.fire({
                    icon: "success",
                    text: "Incident report submitted successfully!",
                    timer: 5000
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: xhr.responseJSON?.message || "An error occurred. Please try again.",
                });
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
