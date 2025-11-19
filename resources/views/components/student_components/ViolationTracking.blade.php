<link rel="stylesheet" href="{{ asset('css/student_css/ViolationTracking.css') }}">

@php
use App\Models\rules;
$ruleinfos = rules::get();
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Active Violation</h3>
</div>

<div class="container mt-4" id="activeViolationCards">
    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3" id="violation-cards">
        <!-- Violation cards will be loaded here via AJAX -->
    </div>
    <div id="pagination-container" class="mt-3 d-flex justify-content-center"></div>
</div>

<!-- Modal Section -->
<div class="modal fade" id="appealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Violation Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text text-dark"><strong>Offense/s:</strong> <span id="offense"></span></p>
                <p class="text text-dark"><strong>Rule:</strong> <span id="ruleLink"></span></p>
                <p class="text text-dark" id="detailsDescription"></p>
                <hr>
                <p class="text text-dark"><strong>Severity:</strong> <a href="#" id="severity"></a></p>
                <p class="text text-dark"><strong>Penalty:</strong> <span id="penalty"></span></p>
                <p class="text text-dark"><strong>Action Taken:</strong> <span id="actionTaken"></span></p>
                <p class="text text-dark"><strong>Status:</strong> <span id="status"></span></p>
                <hr>
                <p class="text text-dark"><strong>Message:</strong></p>
                <p class="text text-dark" id="message"></p>
                <hr>
                <p class="text text-dark"><strong>Uploaded Evidence:</strong></p>
                <ul id="evidenceList" class="list-group mb-3" style="max-height: 170px; overflow-y: auto;"></ul>
                <hr>

                <!-- Appeal Section -->
                <div id="appealSection">
                    <p class="text text-dark"><strong>Appeal your case?</strong></p>
                    <input type="radio" id="appealYes" name="appeal" value="Yes">
                    <label for="appealYes" class="text text-dark">Yes</label><br>
                    <input type="radio" id="appealNo" name="appeal" value="No">
                    <label for="appealNo" class="text text-dark">No</label>

                    <div id="appealFormContainer" style="display: none;">
                        <form id="appealform" enctype="multipart/form-data">
                            <textarea id="appealReason" class="form-control mt-2" maxlength="200"
                                style="height: 100px; resize: none;" placeholder="Enter your reason for appeal..."></textarea>

                            <!-- Upload Section -->
                            <div class="my-3" id="uploadSection" style="display: none;">
                                <label id="dropArea"
                                    for="uploadAppealEvidence"
                                    class="d-block w-100 p-5 border border-dark rounded text-center bg-white"
                                    style="cursor: pointer; border-style: dashed;">
                                    <div class="text-dark fw-medium">Upload Appeal Evidence</div>
                                    <small class="text-muted">Click to select files or drag them here</small>
                                </label>

                                <input type="file" id="uploadAppealEvidence" name="upload_appeal_evidence[]" multiple style="display:none;">

                                <div style="max-height: 150px; overflow-y: auto;">
                                    <ul id="fileList" class="list-group mt-2"></ul>
                                </div>
                            </div>

                            <button type="button" id="submitAppeal" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                </div>
                <!-- End Appeal Section -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    let selectedFiles = [];

    // -----------------------------
    // Load Violations (First Page)
    // -----------------------------
    loadViolations();

    function loadViolations(page = 1) {
        $.ajax({
            url: '/get_violations_records?page=' + page,
            method: 'GET',
            success: function (data) {
                renderViolations(data);
                buildPagination(data);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching violations:', error);
            }
        });
    }

    function renderViolations(data) {
        let cardsContainer = $('#violation-cards');
        cardsContainer.empty();

        const violations = data.data;

        if (violations.length === 0) {
              $('#activeViolationCards').append(`
                <div id="no-violations-msg" class="d-flex justify-content-center align-items-center" 
                     style="width: 100%;">
                    <p class="text-center mb-0">No active violations found.</p>
                </div>
            `);
        } else {
            $('#no-violations-msg').remove();
            violations.forEach(function (violation) {
                cardsContainer.append(`
                    <div class="col">
                        <div class="card p-3">
                            <h5>${violation.type}</h5>
                            <p><small>Status: ${violation.status}</small></p>
                            <p><small>Date: ${violation.date}</small></p>
                            <button class="btn btn-light view-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#appealModal"
                                    data-violation='${JSON.stringify(violation)}'>
                                    View
                            </button>
                        </div>
                    </div>
                `);
            });
        }
    }

    // -----------------------------
    // Pagination Click
    // -----------------------------
    $(document).on('click', '.page-btn', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        loadViolations(page);
    });

    // -----------------------------
    // View Button
    // -----------------------------
    $(document).on('click', '.view-btn', function () {
        let violation = $(this).data('violation');

        $('#offense').text(violation.type || 'N/A')
        $('#ruleLink').text(violation.rule_Name);
        $('#detailsDescription').html(violation.description_Name.replace(/\n/g, '<br>'));
        $('#severity').text(violation.severity_Name).attr('href', '/violation_handbook#' + violation.section_Id);
        $('#penalty').text(violation.penalties || 'N/A');
        $('#actionTaken').text(violation.referals || 'N/A');
        $('#message').text(violation.Remarks);
        $('#status').text(violation.status || 'N/A');
        $('#appealModal').data('studentId', violation.id);
        $('#appealModal').data('studentName', violation.student_name);

        $('#evidenceList').empty();
        if (violation.upload_evidence) {
            try {
                let files = JSON.parse(violation.upload_evidence);
                if (Array.isArray(files) && files.length > 0) {
                    files.forEach(filePath => {
                        let fileName = filePath.split('/').pop();
                        $('#evidenceList').append(`<li class="list-group-item"><a href="/storage/${filePath}" target="_blank">${fileName}</a></li>`);
                    });
                } else {
                    $('#evidenceList').append('<li class="text-muted">No uploaded evidence.</li>');
                }
            } catch {
                $('#evidenceList').append('<li class="text-muted">Invalid evidence data.</li>');
            }
        } else {
            $('#evidenceList').append('<li class="text-muted">No uploaded evidence.</li>');
        }

        if (violation.appeal === 'N/A' || violation.appeal === 'Warning') {
            $('#appealSection').show();
        } else {
            $('#appealSection').hide();
        }

        $('#appealFormContainer').hide();
        $('#appealReason').show();
        $('#submitAppeal').show();
        $('#uploadSection').hide();
        $('input[name="appeal"]').prop('checked', false);

        selectedFiles = [];
        $('#fileList').empty();
        $('#uploadAppealEvidence').val('');
    });

    // -----------------------------
    // Appeal Selection
    // -----------------------------
    $('input[name="appeal"]').change(function () {
        if ($('#appealYes').is(':checked')) {
            $('#appealFormContainer').show();
            $('#appealReason').show();
            $('#uploadSection').show();
            $('#appealReason').val('');
        } else if ($('#appealNo').is(':checked')) {
            $('#appealFormContainer').show();
            $('#appealReason').hide();
            $('#uploadSection').hide();
            $('#appealReason').val('No Objection');
        }
    });

    // -----------------------------
    // Drag & Drop Upload
    // -----------------------------
    let dropArea = document.getElementById("dropArea");
    let fileInput = document.getElementById("uploadAppealEvidence");
    let fileList = document.getElementById("fileList");

    ['dragenter','dragover','dragleave','drop'].forEach(e => {
        dropArea.addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); }, false);
    });
    ['dragenter','dragover'].forEach(e => { dropArea.addEventListener(e, () => dropArea.classList.add('bg-light'), false); });
    ['dragleave','drop'].forEach(e => { dropArea.addEventListener(e, () => dropArea.classList.remove('bg-light'), false); });

    dropArea.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
    // dropArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', e => handleFiles(e.target.files));

    function handleFiles(files) {
        const maxSize = 2 * 1024 * 1024; // 2MB

        Array.from(files).forEach(file => {
            if (file.size > maxSize) {
                Swal.fire({ icon: "error", text: `${file.name} exceeds 2MB limit.` });
                return;
            }
            if (!selectedFiles.some(f => f.name === file.name)) {
                selectedFiles.push(file);
                let li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.textContent = `${file.name} (${(file.size/1024).toFixed(1)} KB)`;
                let removeBtn = document.createElement("button");
                removeBtn.className = "btn btn-sm btn-danger";
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
    }

    function syncInput() {
        let dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    // -----------------------------
    // Submit Appeal
    // -----------------------------
    $(document).on('click', '#submitAppeal', function () {
        let appealReason = $('#appealReason').val();
        let studentId = $('#appealModal').data('studentId');
        let studentName = $('#appealModal').data('studentName');
        let formData = new FormData();

        formData.append('appealReason', appealReason);
        formData.append('studentId', studentId);
        formData.append('studentName', studentName);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        Array.from(fileInput.files).forEach(f => formData.append('upload_appeal_evidence[]', f));

        $.ajax({
            url: '/update_appeal_reason',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#appealform').trigger('reset');
                    $('#appealSection').hide();
                    $('#appealModal').modal('hide');
                    Swal.fire({ icon: "success", text: "Appeal submitted successfully!", timer: 3000 });
                } else {
                    Swal.fire({ icon: "error", text: response.message });
                }
            },
            error: function () {
                Swal.fire({ icon: "error", text: "Submission failed." });
            }
        });
    });

    // -----------------------------
    // Build Pagination (show only 3 pages at a time)
    // -----------------------------
    function buildPagination(data) {
        let container = $('#pagination-container');
        container.empty();

        if (data.last_page <= 1) return;

        let html = `<nav><ul class="pagination">`;

        if (data.current_page > 1) {
            html += `<li class="page-item"><a class="page-link page-btn" data-page="${data.current_page - 1}">Previous</a></li>`;
        }

        let start = Math.max(1, data.current_page - 1);
        let end = Math.min(data.last_page, start + 2);

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === data.current_page ? 'active' : ''}"><a class="page-link page-btn" data-page="${i}">${i}</a></li>`;
        }

        if (data.current_page < data.last_page) {
            html += `<li class="page-item" ><a class="page-link page-btn" data-page="${data.current_page + 1}">Next</a></li>`;
        }

        html += `</ul></nav>`;
        container.html(html);
    }
});
</script>
