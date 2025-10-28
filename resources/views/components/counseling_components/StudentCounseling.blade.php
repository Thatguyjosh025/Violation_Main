<link rel="stylesheet" href="{{ asset('./css/counseling_css/StudentCounseling.css') }}">
<link rel="stylesheet" href="{{ asset('./vendor/dataTables.dataTables.min.css') }}">
@php
    use App\Models\counseling;
    $resolvedsessions = counseling::whereIn('status', [4, 5])->get();
@endphp
        <!-- Counseling Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Student Counseling Profile</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            
            <!-- Table Contents -->
            <div class="table-container">
                
                <table id="resolvetable" class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Violation</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resolvedsessions as $session)
                         <tr>
                            <td data-label="Student No.">{{ $session ->student_no }}</td>
                            <td data-label="Name">{{ $session -> student_name }}</td>
                            <td data-label="Violation">{{ $session -> violation }}</td>
                            <td data-label="Status">
                                <span class="badge text-light" style="background-color: green;">{{ $session -> statusRelation -> session_status }}</span>
                            </td>
                            <td data-label="Start Date">{{ $session -> start_date }}</td>
                            <td data-label="End Date">{{ $session -> end_date }}</td>
                            <td data-label="Action">
                                    <button class="btn btn-sm btn-secondary btn-action-consistent view-btn" data-id="{{ $session->id }}">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <!-- <button class="btn btn-sm btn-warning unresolve-btn" data-id="{{ $session->id }}">
                                        <i class="bi bi-arrow-counterclockwise"></i> Unresolve
                                    </button> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


    <!-- VIEW MODAL -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-custom modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
        <div class="modal-body p-0">
            <div class="report-card position-relative">
            <!-- Close button -->
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            
            <header class="report-title mb-3">
                <h1>Session Record</h1>
            </header>

            <div class="section-title">General Info</div>
            <div class="kv-row"><div class="kv-label">Name</div><div class="kv-value" id="view_name">Mark Jecil Bausa</div></div>
            <div class="kv-row"><div class="kv-label">Violation</div><div class="kv-value" id="view_violation">Misbehaviour</div></div>
            <div class="kv-row"><div class="kv-label">Severity</div><div class="kv-value"><span class="badge-severity" id="view_status">Major A</span></div></div>

            <hr class="my-3">

            <div class="section-title">Session Notes</div>
            <p id="view_session_notes"></p>

            <div class="section-title">Emotional State</div>
            <p><span class="badge-state" id="view_emotional"></span></p>

            <div class="section-title">Behaviour Observation</div>
            <p id="view_behavior"></p>

            <div class="section-title">Intervention Plans & Goals</div>
            <p id="view_plan_goals"></p>
            </div>
        </div>
        </div>
    </div>
    </div>


<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    var table = $('#resolvetable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: { emptyTable: "No scheduled counseling at the moment." }
    });

    // VIEW BUTTON
    $(document).on('click', '.view-btn', function () {
        let sessionId = $(this).data('id');
        let url = `/get_session/${sessionId}`;

        Swal.fire({
            title: 'Loading...',
            text: 'Fetching counseling session details.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                Swal.close();

                $('#view_name').text(response.student_name || 'N/A');
                $('#view_violation').text(response.violation || 'N/A');
                $('#view_status').text(response.severity || 'N/A');
                $('#view_session_notes').text(response.session_notes || 'No session notes provided.');
                $('#view_emotional').text(response.emotional_state || 'N/A');
                $('#view_behavior').text(response.behavior_observe || 'N/A');
                $('#view_plan_goals').text(response.plan_goals || 'N/A');

                $('#viewModal').modal('show');
            },
            error: function (xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Failed to load session data.'
                });
            }
        });
    });

    $(document).on('click', '.unresolve-btn', function () {
        let sessionId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will restore the session back to active management.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, unresolve it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/counseling/unresolve/${sessionId}`,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Session Restored',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to unresolve session.'
                        });
                    }
                });
            }
        });
    });
});
</script>

