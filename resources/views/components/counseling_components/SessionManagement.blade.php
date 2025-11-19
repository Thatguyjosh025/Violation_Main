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
                            <td data-label="Student No.">{{ $currentsession -> student_no}}</td>
                            <td data-label="Name">{{ $currentsession -> student_name }}</td>
                            <td data-label="Violation">{{ $currentsession -> violation }}</td>
                            <td data-label="Status">
                                <span class="badge bg-warning text-dark">{{ $currentsession -> statusRelation-> session_status }}</span>
                            </td>
                            <td data-label="Start Time">{{ \Carbon\Carbon::parse($currentsession->start_time)->format('g:i A') }}</td>
                            <td data-label="End Time">{{ \Carbon\Carbon::parse($currentsession->end_time)->format('g:i A') }}</td>
                            <td data-label="Start Date">{{ $currentsession -> start_date }}</td>
                            <td data-label="End Date">{{ $currentsession -> end_date ?? 'N/A' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary me-1 btn-action-consistent edit-btn" data-id="{{ $currentsession->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1 btn-action-consistent resched-btn"
                                            data-id="{{ $currentsession->id }}">
                                        <i class="bi bi-calendar-event"></i> Reschedule
                                    </button>
                                    <button class="btn btn-sm btn-secondary btn-action-consistent follow-btn"
                                            data-id="{{ $currentsession->id }}">
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
                        <!-- <p>05 Oct 2025, 2:30 PM</p> -->
                        </header>

                        <div class="section-title">General Info</div>
                        <div class="kv-row"><div class="kv-label">Name</div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Violation</div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Severity</div><div class="kv-value"><span class="badge-severity"></span></div></div>

                        <hr class="my-3">
                        <div class="section-title">Time and Date</div>
                        <div class="kv-row"><div class="kv-label">Start Time: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">End Time: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">Start Date: </div><div class="kv-value"></div></div>
                        <div class="kv-row"><div class="kv-label">End Date: </div><div class="kv-value"></div></div>
                        <hr class="my-3">


                        <div class="mb-3">
                            <div class="section-title">Year level / Grade</div>
                            <input class="form-control" id="year_level_input" placeholder="1st Year or Grade 11"></input>
                        </div>

                        <div class="mb-3">
                            <div class="section-title">Program</div>
                            <input class="form-control" id="program_input" placeholder="Bachelor of Science in Information Technology"></input>
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
                        <button type="submit" class="btn btn-save">Save</button>
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
                            <label class="form-label">New Start Date</label>
                            <input type="date" id="resched_date" class="form-control">
                            <div class="new-error-msg text-danger small mt-1" id="error_new_start_date"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Start Time</label>
                            <input type="time" id="resched_start_time" class="form-control">
                            <div class="new-error-msg text-danger small mt-1" id="error_new_start_time"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New End Time</label>
                            <input type="time" id="resched_end_time" class="form-control">
                            <div class="new-error-msg text-danger small mt-1" id="error_new_end_time"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm Reschedule</button>
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
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" id="follow_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" id="follow_start_time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" id="follow_end_time" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Create Follow-up</button>
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
                                    <label class="form-label">Student Number</label>
                                    <input type="text" id="add_student_no" class="form-control" placeholder="e.g., 23-12345" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" id="add_student_name" class="form-control" placeholder="e.g., Juan Dela Cruz" required>
                                </div>

                                 <div class="mb-2">
                                    <label class="form-label">Year level / Grade</label>
                                    <input type="text" id="add_year_level" class="form-control" placeholder="e.g., Juan Dela Cruz" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Program</label>
                                    <input type="text" id="add_program" class="form-control" placeholder="e.g., Juan Dela Cruz" required>
                                </div>

                                <hr>

                                <div class="section-title">Time and Date</div>

                                <div class="mb-2">
                                    <label>Start Date</label>
                                    <input type="date" id="add_start_date" class="form-control rounded-pill px-3 py-2">
                                    <div class="error-msg text-danger small mt-1" id="error_start_date"></div>
                                </div>

                                <div class="mb-2">
                                    <label>Start Time</label>
                                    <input type="time" id="add_start_time" class="form-control rounded-pill px-3 py-2">
                                    <div class="error-msg text-danger small mt-1" id="error_start_time"></div>
                                </div>

                                <div class="mb-2">
                                    <label>End Time</label>
                                    <input type="time" id="add_end_time" class="form-control rounded-pill px-3 py-2">
                                    <div class="error-msg text-danger small mt-1" id="error_end_time"></div>
                                </div>


                                <button type="submit" class="btn btn-success w-100">Add Session</button>
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

    let currentStatusId = null;
    let currentSessionId = null;
    let currentReschedId = null;
    let currentFollowUpId = null;


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


    // Edit button handler
    $(document).on('click', '.edit-btn', function () {
        const sessionId = $(this).data('id');
        currentSessionId = sessionId;

        $.ajax({
            url: `/counseling/getsession/${sessionId}`,
            method: 'GET',
            success: function (data) {
                currentStatusId = data.status;

                // Populate modal fields
                $('#editModal .kv-value').eq(0).text(data.student_name);
                $('#editModal .kv-value').eq(1).text(data.violation);
                $('#editModal .kv-value').eq(2).html(`<span class="badge-severity">${data.severity || 'N/A'}</span>`);

                $('#editModal .kv-value').eq(3).text(data.start_time || 'N/A');
                $('#editModal .kv-value').eq(4).text(data.end_time || 'N/A');
                $('#editModal .kv-value').eq(5).text(data.start_date || 'N/A');
                $('#editModal .kv-value').eq(6).text(data.end_date || 'N/A');

                $('#year_level_input').val(data.year_level || '');
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
    //end edit button handler

    // Populate counseling status dropdown when modal is opened
    $('#editModal').on('show.bs.modal', function () {
        $.ajax({
            url: '/get_counselingstatus',
            method: 'GET',
            success: function (response) {
                const dropdown = $('#counselingstatus');
                dropdown.empty();
                dropdown.append('<option disabled>Select status</option>');

                response.counselingstatus_data.forEach(function (status) {
                    if (status.id === 4) return; // Skip id 4

                    const selected = status.id === currentStatusId ? 'selected' : '';
                    dropdown.append(`<option value="${status.id}" ${selected}>${status.session_status}</option>`);
                });
            },
            error: function () {
                console.error('Failed to fetch counseling statuses.');
            }
        });
    });

    // submits the form handler
    $('#sessionForm').on('submit', function (e) {
        e.preventDefault();

        if (!currentSessionId) {
            Swal.fire('Error', 'No session selected for update.', 'error');
            return;
        }

        const payload = {
            year_level: $('#year_level_input').val(),
            program: $('#program_input').val(),
            session_notes: $('#session_notes_input').val(),
            emotional_state: $('#emotional_state_input').val(),
            behavior_observe: $('#Behavior_observe_input').val(),
            plan_goals: $('#plans_goals_input').val(),
            status: $('#counselingstatus').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: `/counseling/updatesession/${currentSessionId}`,
            method: 'POST',
            data: payload,
            success: function (response) {

                if (response.success) {

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
                    Swal.fire('Error', response.message || 'Update failed.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong while updating.', 'error');
            }
        });
    });
    //end submits the form handler hello world


    // Reschedule handler
    $(document).on('click', '.resched-btn', function () {
        currentReschedId = $(this).data('id');
        $('#reschedModal').modal('show');
    });

    $('#reschedForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous error messages
        $('#error_new_start_date, #error_new_start_time, #error_new_end_time').text('');

        const newstartDate = $('#resched_date').val();
        const newstartTime = $('#resched_start_time').val();
        const newendTime = $('#resched_end_time').val();

        let hasError = false;

        if (!newstartDate) {$('#error_new_start_date').text('Start date is required.'); hasError = true;}
        if (!newstartTime) {$('#error_new_start_time').text('Start time is required.'); hasError = true;}
        if (!newendTime) {$('#error_new_end_time').text('End time is required.'); hasError = true;}

        if (hasError) {
            return;
        }

        // Continue only if all fields are valid
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
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong while rescheduling.', 'error');
            }
        });
    });
    //end reschedule

    // Follow-up handler
    $(document).on('click', '.follow-btn', function () {
        currentFollowUpId = $(this).data('id');
        $('#followModal').modal('show');
    });

    $('#followForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: `/counseling/followup/${currentFollowUpId}`,
            method: 'POST',
            data: {
                start_date: $('#follow_date').val(),
                start_time: $('#follow_start_time').val(),
                end_time: $('#follow_end_time').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    $('#followModal').modal('hide');
                    $('#followForm')[0].reset();

                    // Refresh the table to show the new follow-up session
                    $('#violationTable tbody').load(location.href + " #violationTable tbody > *");

                    Swal.fire({
                        icon: 'success',
                        title: 'Follow-up Created',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong while creating follow-up.', 'error');
            }
        });
    });
    //end follow up

    $('#reschedModal').on('hidden.bs.modal', function () {
        $('#reschedForm')[0].reset();
    });

    $('#followModal').on('hidden.bs.modal', function () {
        $('#followForm')[0].reset();
    });

     // Clear errors on input
    $('#resched_date, #resched_start_time, #resched_end_time').on('input change', function () {
        $(this).next('.new-error-msg').text('');
    });


    // Add this inside your $(document).ready(function () { ... });
let typingTimer;
const typingDelay = 0; // debounce (ms)

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
                                <button class="btn btn-outline-primary btn-sm select-student-btn"
                                    data-id="${data.id}"
                                    data-name="${data.firstname} ${data.lastname}"
                                    data-student_no="${data.student_no}">
                                    Select
                                </button>
                            </div>`;
                    });
                    $('.student-list').html(html);
                }
            }
        });
    }, typingDelay);
});

    // select student button handler
    $(document).on('click', '.select-student-btn', function() {
        const studentNo = $(this).data('student_no');
        const studentName = $(this).data('name');
        $('#add_student_no').val(studentNo);
        $('#add_student_name').val(studentName);
    });

    //auto fill
    $(document).on('click', '.select-student-btn', function() {
        const studentNo = $(this).data('student_no');
        const studentName = $(this).data('name');

        // Auto-fill the form fields
        $('#add_student_no').val(studentNo);
        $('#add_student_name').val(studentName);

        // Hide the search view and show the form view
        $('#searchView').hide();
        $('#formView').show();
    });

    $(document).on('click', '#backToSearch', function() {
        $('#formView').hide();
        $('#searchView').show();
    });

    // Reset modal
    $('#addSessionModal').on('hidden.bs.modal', function () {
        $('#searchView').show();
        $('#formView').hide();
        $('#search-student').val('');
        $('.student-list').html('<p class="text-muted text-center" id="search-placeholder">Start searching student name...</p>');
    });

    // Helper function to get initials
    function getInitials(firstname, lastname) {
        return (firstname.charAt(0) + lastname.charAt(0)).toUpperCase();
    }

});

</script>

