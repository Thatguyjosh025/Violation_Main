<link rel="stylesheet" href="{{ asset('./css/counseling_css/SessionManagement.css') }}">
<link rel="stylesheet" href="{{ asset('./vendor/dataTables.dataTables.min.css') }}">
@php
    use App\Models\counseling;
    $counselingsessions = counseling::whereNotIn('status', [4, 5])->get();
@endphp
<!-- Counseling Section -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Session Management</h3>
</div>

<div class="mb-3" style="display: flex; justify-content: end;">
    <button class="btn btn-add-counseling btn-md" id="addSessionCounseling">+ Add Schedule</button>
</div>

<!-- Table Contents -->
<div class="table-container">
    <table id="violationTable" class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student No.</th>
                <th>Name</th>
                <th>Violation</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($counselingsessions as $currentsession)
            <tr>
                <td data-label="Student No.">{{ $currentsession->student_no }}</td>
                <td data-label="Name">{{ $currentsession->student_name }}</td>
                <td data-label="Violation">{{ $currentsession->violation ?? '-' }}</td>
                <td data-label="Status">
                    <span class="badge bg-warning text-dark">{{ $currentsession->statusRelation->session_status }}</span>
                </td>
                <td data-label="Start Time">{{ \Carbon\Carbon::parse($currentsession->start_time)->format('g:i A') }}</td>
                <td data-label="End Time">{{ \Carbon\Carbon::parse($currentsession->end_time)->format('g:i A') }}</td>
                <td data-label="Start Date">{{ $currentsession->start_date }}</td>
                <td data-label="End Date">{{ $currentsession->end_date ?? '-' }}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-primary me-1 btn-action-consistent edit-btn" data-id="{{ $currentsession->id }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-warning me-1 btn-action-consistent resched-btn" data-id="{{ $currentsession->id }}">
                            <i class="bi bi-calendar-event"></i> Reschedule
                        </button>
                        <button class="btn btn-sm btn-secondary btn-action-consistent follow-btn" data-id="{{ $currentsession->id }}">
                            <i class="bi bi-arrow-repeat"></i> Follow-up
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-custom modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0">
                <div class="report-card position-relative">
                    <!-- Close button -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    <form id="sessionForm" novalidate>
                        @csrf
                        <header class="report-title mb-3">
                            <h1>Session Report</h1>
                        </header>
                        <div class="section-title">General Info</div>
                        <div class="kv-row"><div class="kv-label">Name</div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Student No.</div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Email</div><div class="kv-value"><span class="badge-severity"></span></div></div>
                        <div class="kv-row"><div class="kv-label">Priority Level</div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Guidance Service</div><div class="kv-value"></div></div>
                        <hr class="my-3">
                        <div class="section-title">Time and Date</div>
                        <div class="kv-row"><div class="kv-label">Start Time: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">End Time: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Start Date: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">End Date: </div><div class="kv-value"></div></div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <div class="section-title required">Year level / Grade</div>
                            <!-- <input class="form-control" id="year_level_input" placeholder="First Year or Grade - 11"> -->
                             <select name="" id="year_level_input_select" class="form-select">
                                <option value="" hidden>Select Year/Grade Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="Grade - 11">Grade - 11</option>
                                <option value="Grade - 12">Grade - 12</option>
                            </select>
                            <div class="error-msg text-danger small mt-1" id="error_year_level"></div>
                        </div>
                        <div class="mb-3">
                            <div class="section-title required">Program</div>
                            <!-- <input class="form-control" id="program_input" placeholder="Bachelor of Science in Information Technology"> -->
                            <select name="" id="program_input" class="form-select">
                                <option value="">Select Year/Grade Level</option>
                                <option value="BS Information Technology">BS Information Technology</option>
                                <option value="BS Computer Science">BS Computer Science</option>
                                <option value="BS Business Administration">BS Business Administration</option>
                                <option value="BS Toursim Managemen">BS Toursim Management</option>
                                <option value="BS Hospitality Management">BS Hospitality Management</option>
                                <option value="HUMSS">HUMSS</option>
                                <option value="ITMAWD">ITMAWD</option>
                                <option value="ABM">ABM</option>
                            </select>
                            <div class="error-msg text-danger small mt-1" id="error_program"></div>
                        </div>
                        <div class="mb-3">
                            <div class="section-title">Session Notes</div>
                            <textarea class="form-control" id="session_notes_input" placeholder="Write session notes..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Emotional State</label>
                            <input class="form-control" id="emotional_state_input" placeholder="e.g., Calm, Agitated">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold section-title">Intervention Plans & Goals</label>
                            <textarea class="form-control" id="plans_goals_input" placeholder="Define interventions, steps, and goals"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="counselingstatus">
                                <!-- dropdown loader will appear in here -->
                            </select>
                        </div>
                        <div class="save-wrap">
                            <button type="submit" class="btn btn-save" id="btnupdate" >Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="reschedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <h5>Reschedule Counseling Session</h5>
            <form id="reschedForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label required">New Start Date</label>
                    <input type="date" id="resched_date" class="form-control">
                    <div class="new-error-msg text-danger small mt-1" id="error_new_start_date"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">New Start Time</label>
                    <input type="time" id="resched_start_time" class="form-control">
                    <div class="new-error-msg text-danger small mt-1" id="error_new_start_time"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">New End Time</label>
                    <input type="time" id="resched_end_time" class="form-control">
                    <div class="new-error-msg text-danger small mt-1" id="error_new_end_time"></div>
                </div>
                <button type="submit" class="btn btn-primary" id="btnconfirm" >Confirm Reschedule</button>
            </form>
        </div>
    </div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="followModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <h5>Create Follow-up Counseling Session</h5>
            <form id="followForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label required">Follow-up Date</label>
                    <input type="date" id="follow_date" class="form-control" >
                    <div class="follow-error-msg text-danger small mt-1" id="error_follow_start_date"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Start Time</label>
                    <input type="time" id="follow_start_time" class="form-control">
                    <div class="follow-error-msg text-danger small mt-1" id="error_follow_start_time"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">End Time</label>
                    <input type="time" id="follow_end_time" class="form-control">
                    <div class="follow-error-msg text-danger small mt-1" id="error_follow_end_time"></div>
                </div>
                <button type="submit" class="btn btn-success" id="btnfollowup" >Create Follow-up</button>
            </form>
        </div>
    </div>
</div>

<!-- ADD SESSION MODAL -->
<div class="modal fade" id="addSessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <h5 class="mb-3">Add Counseling Session</h5>
            <!-- First View -->
            <div id="searchView">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Search Student</label>
                    <input type="text" id="search-student" class="form-control mb-3" placeholder="Search Student">
                    <div class="student-list">
                        <p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>
                    </div>
                </div>
            </div>
            <!-- Second view -->
            <div id="formView" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="backToSearch">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSessionForm">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-bold">Student Number</label>
                        <br>
                        <label class="form-label" id="display_student_number"></label>
                        <input type="text" id="add_student_no" class="form-control" style="display: none;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Student Name</label>
                        <br>
                        <label class="form-label" id="display_student_name"></label>
                        <input type="text" id="add_student_name" class="form-control" style="display: none;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Email</label>
                        <br>
                        <label class="form-label" id="display_student_email"></label>
                        <input type="text" id="add_student_email" class="form-control" style="display: none;">
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label fw-bold required">Year level / Grade</label>
                        <!-- <input type="text" id="add_year_level" class="form-control" placeholder="First Year or Grade - 11"> -->
                        <select name="" id="add_year_level_select" class="form-select">
                            <option value="">Select Year/Grade Level</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="Grade - 11">Grade - 11</option>
                            <option value="Grade - 12">Grade - 12</option>
                        </select>
                        <div class="error-msg text-danger small mt-1" id="add_error_year_level"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold required">Program</label>
                        <!-- <input type="text" id="add_program" class="form-control" placeholder="Bachelor of Science in Information Technology"> -->
                          <select name="" id="add_program" class="form-select">
                            <option value="">Select Year/Grade Level</option>
                            <option value="BS Information Technology">BS Information Technology</option>
                            <option value="BS Computer Science">BS Computer Science</option>
                            <option value="BS Business Administration">BS Business Administration</option>
                            <option value="BS Toursim Managemen">BS Toursim Management</option>
                            <option value="BS Hospitality Management">BS Hospitality Management</option>
                            <option value="HUMSS">HUMSS</option>
                            <option value="ITMAWD">ITMAWD</option>
                            <option value="ABM">ABM</option>
                        </select>
                        <div class="error-msg text-danger small mt-1" id="add_error_program"></div>
                    </div>
                    <hr>
                    <div class="section-title">Time and Date</div>
                    <div class="mb-2">
                        <label class="required">Start Date</label>
                        <input type="date" id="add_start_date" class="form-control rounded-pill px-3 py-2">
                        <div class="error-msg text-danger small mt-1" id="error_start_date"></div>
                    </div>
                    <div class="mb-2">
                        <label class="required">Start Time</label>
                        <input type="time" id="add_start_time" class="form-control rounded-pill px-3 py-2">
                        <div class="error-msg text-danger small mt-1" id="error_start_time"></div>
                    </div>
                    <div class="mb-2">
                        <label class="required">End Time</label>
                        <input type="time" id="add_end_time" class="form-control rounded-pill px-3 py-2">
                        <div class="error-msg text-danger small mt-1" id="error_end_time"></div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="fw-bold required">Priority Level</label>
                        <select name="" id="add_priority_level_input" class="form-select">
                            <!-- Load hereeee -->
                        </select>
                        <div class="error-msg text-danger small mt-1" id="error_priority_level"></div>
                    </div>
                    <div class="mb-2">
                        <label class="fw-bold required">Guidance Service</label>
                        <select name="" id="add_guidance_service_input" class="form-select">
                            <!-- Load hereeee -->
                        </select>
                        <div class="error-msg text-danger small mt-1" id="error_guidance_service"></div>
                    </div>
                    <button type="submit" class="btn btn-success w-100" id="btnadd" >Add Session</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    //setting the working hours
    const MIN_TIME = "08:00";
    const MAX_TIME = "17:00";

    function applyTimeLimits(selector) {
        $(selector).attr("min", MIN_TIME);
        $(selector).attr("max", MAX_TIME);
    }

    function validateWorkingHours(inputId, errorId) {
        const time = $(inputId).val();
        if (!time) return;

        if (time < MIN_TIME || time > MAX_TIME) {
            $(errorId).text("Time must be between 8:00 AM and 5:00 PM only.");
            $(inputId).val("");
        } else {
            $(errorId).text("");
        }
    }

    function clearErrorOnInput(inputSelector, errorSelector) {
        $(document).on("input change", inputSelector, function () {
            $(errorSelector).text("");
        });
    }

    // ADD AREA
    clearErrorOnInput("#add_year_level_select", "#add_error_year_level");
    clearErrorOnInput("#add_program", "#add_error_program");
    clearErrorOnInput("#add_start_date", "#error_start_date");
    clearErrorOnInput("#add_start_time", "#error_start_time");
    clearErrorOnInput("#add_end_time", "#error_end_time");
    clearErrorOnInput("#add_priority_level_input", "#error_priority_level");
    clearErrorOnInput("#add_guidance_service_input", "#error_guidance_service");

    // EDIT AREA
    clearErrorOnInput("#year_level_input_select", "#error_year_level");
    clearErrorOnInput("#program_input", "#error_program");

        
    // RESCHED
    clearErrorOnInput("#resched_date", "#error_new_start_date");

    // FOLLOW-UP
    clearErrorOnInput("#follow_date", "#error_follow_start_date");

    // Apply limits to all time fields
    applyTimeLimits("#add_start_time");
    applyTimeLimits("#add_end_time");
    applyTimeLimits("#resched_start_time");
    applyTimeLimits("#resched_end_time");
    applyTimeLimits("#follow_start_time");
    applyTimeLimits("#follow_end_time");

    // Trigger validation on time change
    $("#add_start_time").on("change", () => validateWorkingHours("#add_start_time", "#error_start_time"));
    $("#add_end_time").on("change", () => validateWorkingHours("#add_end_time", "#error_start_time"));

    $("#resched_start_time").on("change", () => validateWorkingHours("#resched_start_time", "#error_new_start_time"));
    $("#resched_end_time").on("change", () => validateWorkingHours("#resched_end_time", "#error_new_end_time"));
    
    $("#follow_start_time").on("change", () => validateWorkingHours("#follow_start_time", "#error_follow_start_time"));
    $("#follow_end_time").on("change", () => validateWorkingHours("#follow_end_time", "#error_follow_end_time"));

    let currentStatusId = null;
    let currentSessionId = null;
    let currentReschedId = null;
    let currentFollowUpId = null;
    let typingTimer;
    const typingDelay = 0;


    // Program validation
    $('#add_program, #program_input').on('input', function () {
        let input = $(this).val();
        if (input.length > 0) {
            let capitalized = input.charAt(0).toUpperCase() + input.slice(1);
            let sanitized = capitalized.replace(/[^A-Za-z\s]/g, '');
            $(this).val(sanitized);
        }
    });

    // Clear errors when typing times
    $('#add_start_time, #add_end_time, #resched_start_time, #resched_end_time, #follow_start_time, #follow_end_time')
    .on('input', function () {
        $(this).val($(this).val());
    });

    function loadDropdown(url, dropdownId, placeholder, key, label) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                const dropdown = $(dropdownId);
                dropdown.empty().append(`<option disabled selected>${placeholder}</option>`);
                response[key].forEach(item => {
                    dropdown.append(`<option value="${item.id}">${item[label]}</option>`);
                });
            }
        });
    }

    var table = $('#violationTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: { emptyTable: "No scheduled counseling at the moment." }
    });

    $('#addSessionCounseling').on('click', function () {
        $('#addSessionModal').modal('show');
    });

    /* ============================================================
       EDIT SESSION LOGIC
    ============================================================ */

    $(document).on('click', '.edit-btn', function () {
        const sessionId = $(this).data('id');
        currentSessionId = sessionId;

        $.ajax({
            url: `/counseling/getsession/${sessionId}`,
            method: 'GET',
            success: function (data) {

                currentStatusId = data.status;

                $('#editModal .kv-value').eq(0).text(data.student_name);
                $('#editModal .kv-value').eq(1).text(data.student_no);
                $('#editModal .kv-value').eq(2).html(`<span>${data.school_email || 'N/A'}</span>`);
                $('#editModal .kv-value').eq(3).html(`<span>${data.priority_risk_relation?.priority_risk || 'N/A'}</span>`);
                $('#editModal .kv-value').eq(4).html(`<span>${data.guidance_service_relation?.guidance_service || 'N/A'}</span>`);
                $('#editModal .kv-value').eq(5).text(data.start_time || 'N/A');
                $('#editModal .kv-value').eq(6).text(data.end_time || 'N/A');
                $('#editModal .kv-value').eq(7).text(data.start_date || 'N/A');
                $('#editModal .kv-value').eq(8).text(data.end_date || 'N/A');

                $('#year_level_input_select').val(data.year_level || '');
                $('#program_input').val(data.program || '');
                $('#session_notes_input').val(data.session_notes || '');
                $('#emotional_state_input').val(data.emotional_state || '');
                $('#Behavior_observe_input').val(data.behavior_observe || '');
                $('#plans_goals_input').val(data.plan_goals || '');

                $('#editModal').modal('show');
            },
            error: function () {
                Swal.fire('Error', 'Failed to fetch session data.', 'error');
            }
        });
    });

    $('#editModal').on('show.bs.modal', function () {
        $.ajax({
            url: '/get_counselingstatus',
            method: 'GET',
            success: function (response) {
                const dropdown = $('#counselingstatus');
                dropdown.empty();
                dropdown.append('<option disabled>Select status</option>');

                response.counselingstatus_data.forEach(function (status) {
                    if (status.id === 1) return;
                    if (status.id === 3) return;
                    if (status.id === 4) return;
                    const selected = status.id === currentStatusId ? 'selected' : '';
                    dropdown.append(`<option value="${status.id}" ${selected}>${status.session_status}</option>`);
                });
            }
        });
    });

    /* ============================================================
       UPDATE SESSION HANDLER
    ============================================================ */

    $('#sessionForm').on('submit', function (e) {
        e.preventDefault();
        $('.error-msg').text('');

        const yearLevel = $('#year_level_input_select').val();
        const program = $('#program_input').val().trim();
        let hasError = false;

        const yearLevelRegex = /^(?:Grade\s*-\s*\d{1,2}|\d{1,2}(?:st|nd|rd|th)\s*Year|[A-Za-z]+\s*Year)$/;

        if (!yearLevel) {
            $('#error_year_level').text('Year level is required.');
            hasError = true;
        } else if (!yearLevelRegex.test(yearLevel)) {
            $('#error_year_level').text('Invalid format.');
            hasError = true;
        }

        const programRegex = /^[A-Za-z\s]+$/;

        if (!program) {
            $('#error_program').text('Program is required.');
            hasError = true;
        } else if (!programRegex.test(program)) {
            $('#error_program').text('Invalid characters.');
            hasError = true;
        }

        if (!currentSessionId) {
            Swal.fire('Error', 'No session selected for update.', 'error');
            return;
        }

        if (hasError) return;

        const payload = {
            year_level: yearLevel,
            program: program,
            session_notes: $('#session_notes_input').val(),
            emotional_state: $('#emotional_state_input').val(),
            behavior_observe: $('#Behavior_observe_input').val(),
            plan_goals: $('#plans_goals_input').val(),
            status: $('#counselingstatus').val(),
            _token: '{{ csrf_token() }}'
        };

        $("#btnupdate")
        .prop("disabled", true)
        .text("Saving...");

        Swal.fire({
            title: 'Saving Changes',
            text: 'Please wait while updating the record.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/counseling/updatesession/${currentSessionId}`,
            method: 'POST',
            data: payload,
            success: function (response) {
                if (response.status === 204) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Changes Detected',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                if (response.success) {
                    $("#btnupdate")
                    .prop("disabled", false)
                    .text("Save");
                    $('#editModal').modal('hide');
                    if ($.fn.DataTable.isDataTable('#violationTable')) {
                        $('#violationTable').DataTable().destroy();
                    }
                    $('.table-container').load(location.href + " .table-container > *", function () {
                        $('#violationTable').DataTable({
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            language: { emptyTable: "No pending counseling at the moment." }
                        });
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    const endDate = response.end_date || 'N/A';
                    $('#editModal .kv-value').eq(6).text(endDate);
                } else {
                    $("#btnupdate")
                    .prop("disabled", false)
                    .text("Save");
                    Swal.fire('Error', response.message || 'Update failed.', 'error');
                }
            },
            error: function () {
                $("#btnupdate")
                .prop("disabled", false)
                .text("Save");
                Swal.fire('Error', 'Something went wrong while updating.', 'error');
            }
        });
    });

    /* ============================================================
       ADD SESSION HANDLER
    ============================================================ */

    $('#addSessionForm').on('submit', function (e) {
        e.preventDefault();
        $('.error-msg').text('');

        const studentNo = $('#add_student_no').val();
        const studentName = $('#add_student_name').val();
        const studentEmail = $('#add_student_email').val();
        const yearLevel = $('#add_year_level_select').val();
        const program = $('#add_program').val().trim();
        const addstartDate = $('#add_start_date').val();
        const addstartTime = $('#add_start_time').val();
        const addendTime = $('#add_end_time').val();
        const addpriorityLevel = $('#add_priority_level_input').val();
        const addguidanceService = $('#add_guidance_service_input').val();
        let hasError = false;

        const yearLevelRegex = /^(?:Grade\s*-\s*\d{1,2}|\d{1,2}(?:st|nd|rd|th)\s*Year|[A-Za-z]+\s*Year)$/;

        if (!yearLevel) {
            $('#add_error_year_level').text('Year level is required.');
            hasError = true;
        } else if (!yearLevelRegex.test(yearLevel)) {
            $('#add_error_year_level').text('Invalid format.');
            hasError = true;
        }

        const programRegex = /^[A-Za-z\s]+$/;

        if (!program) {
            $('#add_error_program').text('Program is required.');
            hasError = true;
        } else if (!programRegex.test(program)) {
            $('#add_error_program').text('Invalid characters.');
            hasError = true;
        }

        if (!addstartDate) {
            $('#error_start_date').text('Start date is required.');
            hasError = true;
        }
        if (!addstartTime) {
            $('#error_start_time').text('Start time is required.');
            hasError = true;
        }
        if (!addendTime) {
            $('#error_end_time').text('End time is required.');
            hasError = true;
        }

        if (!addpriorityLevel) {
            $('#error_priority_level').text('Priority level is required.');
            hasError = true;
        }
        if (!addguidanceService) {
            $('#error_guidance_service').text('Guidance service is required.');
            hasError = true;
        }

        // Check time order
        if (addstartTime && addendTime && addendTime <= addstartTime) {
            $('#error_end_time').text('End time must be after start time.');
            hasError = true;
        }

        if (hasError) return;

        const payload = {
            student_no: studentNo,
            student_name: studentName,
            school_email: studentEmail,
            year_level: yearLevel,
            violation: null,
            severity: null,
            program: program,
            priority_level: addpriorityLevel,
            guidance_service: addguidanceService,
            start_date: addstartDate,
            start_time: addstartTime,
            end_time: addendTime,
            _token: '{{ csrf_token() }}'
        };

      $("#btnadd")
        .prop("disabled", true)
        .text("Adding Session...");

        Swal.fire({
            title: 'Adding Counseling Session',
            text: 'Please wait while saving the new session record.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/counseling_schedule',
            method: 'POST',
            data: payload,
            success: function (response) {
                if (response.success) {
                        $("#btnadd")
                        .prop("disabled", false)
                        .text("Add Session");
                    $('#addSessionModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#violationTable tbody').load(location.href + " #violationTable tbody > *");
                } else {
                    $("#btnadd")
                    .prop("disabled", false)
                    .text("Add Session");
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                $("#btnadd")
                .prop("disabled", false)
                .text("Add Session");
                const errorMessage = xhr.responseJSON?.message || 'Something went wrong.';
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

 
    //RESCHEDULE HANDLER
    $(document).on('click', '.resched-btn', function () {
        currentReschedId = $(this).data('id');
        $('#reschedModal').modal('show');
    });

    $('#reschedForm').on('submit', function (e) {
        e.preventDefault();

        $('#error_new_start_date, #error_new_start_time, #error_new_end_time').text('');

        const newstartDate = $('#resched_date').val();
        const newstartTime = $('#resched_start_time').val();
        const newendTime = $('#resched_end_time').val();
        let hasError = false;

        if (!newstartDate) { $('#error_new_start_date').text('Date is required.'); hasError = true; }
        if (!newstartTime) { $('#error_new_start_time').text('Start time is required.'); hasError = true; }
        if (!newendTime) { $('#error_new_end_time').text('End time is required.'); hasError = true; }

        if (newstartTime && newendTime && newendTime <= newstartTime) {
            $('#error_new_end_time').text('End time must be after start time.');
            hasError = true;
        }

        if (hasError) return;

        $("#btnconfirm")
        .prop("disabled", true)
        .text("Rescheduling Session...");

        $.ajax({
            url: `/counseling/reschedule/${currentReschedId}`,
            method: 'POST',
            data: {
                start_date: newstartDate,
                start_time: newstartTime,
                end_time: newendTime,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    $("#btnconfirm")
                    .prop("disabled", false)
                    .text("Confirm Reschedule");
                    $('#reschedModal').modal('hide');
                    $('#reschedForm')[0].reset();
                    $('#violationTable tbody').load(location.href + " #violationTable tbody > *");

                    Swal.fire({
                        icon: 'success',
                        title: 'Rescheduled',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    $("#btnconfirm")
                    .prop("disabled", false)
                    .text("Confirm Reschedule");
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                $("#btnconfirm")
                .prop("disabled", false)
                .text("Confirm Reschedule");
                Swal.fire('Error', 'Something went wrong while rescheduling.', 'error');
            }
        });
    });

  
    // FOLLOW-UP HANDLER
    $(document).on('click', '.follow-btn', function () {
        currentFollowUpId = $(this).data('id');
        $('#followModal').modal('show');
    });

    $('#followForm').on('submit', function (e) {
        e.preventDefault();

        const followstartDate = $('#follow_date').val();
        const followstartTime = $('#follow_start_time').val();
        const followendTime = $('#follow_end_time').val();
        let hasError = false;

        if (!followstartDate) { $('#error_follow_start_date').text('Date is required.'); hasError = true; }
        if (!followstartTime) { $('#error_follow_start_time').text('Start time is required.'); hasError = true; }
        if (!followendTime) { $('#error_follow_end_time').text('End time is required.'); hasError = true; }

        if (followstartTime && followendTime && followendTime <= followstartTime) {
            $('#error_follow_end_time').text('End time must be after start time.');
            hasError = true;
        }

        if (hasError) return;

        
       $("#btnfollowup")
        .prop("disabled", true)
        .text("Scheduling Follow-up...");
        $.ajax({
            url: `/counseling/followup/${currentFollowUpId}`,
            method: 'POST',
            data: {
                start_date: followstartDate,
                start_time: followstartTime,
                end_time: followendTime,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                      $("#btnfollowup")
                    .prop("disabled", false)
                    .text("Create Follow-up");
                    $('#followModal').modal('hide');
                    $('#followForm')[0].reset();
                    $('#violationTable tbody').load(location.href + " #violationTable tbody > *");

                    Swal.fire({
                        icon: 'success',
                        title: 'Follow-up Created',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    $("#btnfollowup")
                    .prop("disabled", false)
                    .text("Create Follow-up");
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                $("#btnfollowup")
                .prop("disabled", false)
                .text("Create Follow-up");
                Swal.fire('Error', 'Something went wrong while creating follow-up.', 'error');
            }
        });
    });

    //RESET MODALS
    $('#reschedModal').on('hidden.bs.modal', function () {
        $('#reschedForm')[0].reset();
        $('.new-error-msg').text('');
    });

    $('#followModal').on('hidden.bs.modal', function () {
        $('#followForm')[0].reset();
        $('.follow-error-msg').text('');
    });

  
    //SEARCH STUDENT
    $('#search-student').on('keyup', function () {
        clearTimeout(typingTimer);
        const query = $(this).val().trim();

        if (query === '') {
            $('.student-list').html('<p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>');
            return;
        }

        typingTimer = setTimeout(function () {
            $.ajax({
                url: "/student_search",
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    const results = response.slice(0, 3);

                    if (results.length === 0) {
                        $('.student-list').html('<p class="text-muted text-center">No student found.</p>');
                    } else {
                        let html = '';
                        results.forEach(function (data) {
                            html += `
                                <div class="student-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="initial-icon">${getInitials(data.firstname, data.lastname)}</div>
                                        <div class="ms-2">
                                            <p class="mb-0 fw-bold">${data.firstname} ${data.lastname}</p>
                                            <small>${data.student_no}</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm select-student-btn"
                                        data-id="${data.id}"
                                        data-name="${data.firstname} ${data.lastname}"
                                        data-student_no="${data.student_no}"
                                        data-email="${data.email}">
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

    // Select student
    $(document).on('click', '.select-student-btn', function () {
        const studentNo = $(this).data('student_no');
        const studentName = $(this).data('name');
        const studentEmail = $(this).data('email');

        $('#add_student_no').val(studentNo);
        $('#add_student_name').val(studentName);
        $('#add_student_email').val(studentEmail);

        $('#display_student_number').text(studentNo || '-');
        $('#display_student_name').text(studentName || '-');
        $('#display_student_email').text(studentEmail || '-');

        loadDropdown('/get_priorityrisk', '#add_priority_level_input', 'Select priority level', 'priorityrisk_data', 'priority_risk');
        loadDropdown('/get_guidanceservice', '#add_guidance_service_input', 'Select guidance service', 'guidanceservice_data', 'guidance_service');

        $('#searchView').hide();
        $('#formView').show();
    });

    $('#backToSearch').on('click', function () {
        $('#formView').hide();
        $('#searchView').show();
    });

    $('#addSessionModal').on('hidden.bs.modal', function () {
        $('#searchView').show();
        $('#formView').hide();
        $('#search-student').val('');
        $('.student-list').html('<p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>');
        $(this).find('form').trigger('reset');
        $('.error-msg').text('');
    });
      $('#editModal').on('hidden.bs.modal', function () {
        $('#search-student').val('');
        $('.error-msg').text('');
    });

    function getInitials(firstname, lastname) {
        return (firstname.charAt(0) + lastname.charAt(0)).toUpperCase();
    }

});
</script>
