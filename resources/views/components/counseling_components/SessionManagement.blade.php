<link rel="stylesheet" href="{{ asset('./css/counseling_css/SessionManagement.css') }}">
<link rel="stylesheet" href="{{ asset('./vendor/dataTables.dataTables.min.css') }}">

@php
    use App\Models\counseling;
    $counselingsessions = counseling::get();
@endphp
<!-- Counseling Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Session Management</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
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
                            <td data-label="Start Time">{{ $currentsession -> start_time }}</td>
                            <td data-label="End Time">{{ $currentsession -> end_time }}</td>
                            <td data-label="Start Date">{{ $currentsession -> start_date }}</td>
                            <td data-label="End Date">{{ $currentsession -> end_date ?? 'N/A' }}</td>
                            <td data-label="Action">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary me-1 btn-action-consistent edit-btn"
                                            data-id="{{ $currentsession->id }}"
                                            >
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1 btn-action-consistent resched-btn"
                                            data-id="{{ $currentsession->id }}">
                                        <i class="bi bi-calendar-event"></i> Reschedule
                                    </button>
                                    <!-- <button class="btn btn-sm btn-secondary btn-action-consistent">
                                        <i class="bi bi-eye"></i> View
                                    </button> -->
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


                        <div class="mb-4">
                        <div class="section-title">Session Notes</div>
                        <textarea class="form-control" id="session_notes_input" placeholder="Write session notes..."></textarea>
                        </div>

                        <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Emotional State</label>
                            <input class="form-control" id="emotional_state_input" placeholder="e.g., Calm, Agitated">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Behaviour Observation</label>
                            <input class="form-control" id="Behavior_observe_input" placeholder="Short observation summary">
                        </div>
                        </div>

                        <div class="mb-4">
                        <label class="form-label fw-semibold section-title">Intervention Plans & Goals</label>
                        <textarea class="form-control" id="plans_goals_input" placeholder="Define interventions, steps, and goals"></textarea>
                        </div>

                        <div class="mb-4">
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
                        <input type="date" id="resched_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">New Start Time</label>
                        <input type="time" id="resched_start_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">New End Time</label>
                        <input type="time" id="resched_end_time" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm Reschedule</button>
                    </form>
                    </div>
                </div>
            </div>



<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    let currentStatusId = null;
    let currentSessionId = null;
    let currentReschedId = null;

     var table = $('#violationTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: { emptyTable: "No scheduled counseling at the moment." }
    });

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
                    const selected = status.id === currentStatusId ? 'selected' : '';
                    dropdown.append(`<option value="${status.id}" ${selected}>${status.session_status}</option>`);
                });
            },
            error: function () {
                console.error('Failed to fetch counseling statuses.');
            }
        });
    });

    // submits the form
    $('#sessionForm').on('submit', function (e) {
        e.preventDefault();

        if (!currentSessionId) {
            Swal.fire('Error', 'No session selected for update.', 'error');
            return;
        }

        const payload = {
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


    // Reschedule handler
     $(document).on('click', '.resched-btn', function () {
        currentReschedId = $(this).data('id');
        $('#reschedModal').modal('show');
    });

    $('#reschedForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: `/counseling/reschedule/${currentReschedId}`,
            method: 'POST',
            data: {
                start_date: $('#resched_date').val(),
                start_time: $('#resched_start_time').val(),
                end_time: $('#resched_end_time').val(),
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
});

</script>
