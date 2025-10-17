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
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-secondary btn-action-consistent" data-bs-toggle="modal" data-bs-target="#viewModal">
                                        <i class="bi bi-eye"></i> View
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
                <textarea class="form-control" placeholder="Write session notes..."></textarea>
                </div>

                <div class="row gx-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Emotional State</label>
                    <input class="form-control" placeholder="e.g., Calm, Agitated">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Behaviour Observation</label>
                    <input class="form-control" placeholder="Short observation summary">
                </div>
                </div>

                <div class="mb-4">
                <label class="form-label fw-semibold section-title">Intervention Plans & Goals</label>
                <textarea class="form-control" placeholder="Define interventions, steps, and goals"></textarea>
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

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//soon scripts keep this

$(document).ready(function () {
    $('.edit-btn').on('click', function () {
        const sessionId = $(this).data('id');

        $.ajax({
            url: `/counseling/session/${sessionId}`,
            method: 'GET',
            success: function (data) {
                $('#editModal .kv-value').eq(0).text(data.student_name);
                $('#editModal .kv-value').eq(1).text(data.violation);
                $('#editModal .kv-value').eq(2).html(`<span class="badge-severity">${data.severity || 'N/A'}</span>`);

                $('#editModal .kv-value').eq(3).text(data.start_time || 'N/A');
                $('#editModal .kv-value').eq(4).text(data.end_time || 'N/A');
                $('#editModal .kv-value').eq(5).text(data.start_date || 'N/A');
                $('#editModal .kv-value').eq(6).text(data.end_date || 'N/A');

                $('#editModal textarea').eq(0).val(data.session_notes || '');
                $('#editModal input').eq(0).val(data.emotional_state || '');
                $('#editModal input').eq(1).val(data.behavior_observe || '');
                $('#editModal textarea').eq(1).val(data.plan_goals || '');
            },
            error: function () {
                Swal.fire('Error', 'Failed to fetch session data.', 'error');
            }
        });
    });
});
</script>
