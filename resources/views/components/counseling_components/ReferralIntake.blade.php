<link rel="stylesheet" href="{{ asset('./css/counseling_css/ReferralIntake.css') }}">
<link rel="stylesheet" href="{{ asset('./vendor/dataTables.dataTables.min.css') }}">

@php
    use App\Models\postviolation;
    $counselingperson = postviolation::where('is_admitted', false)->get();
@endphp

<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Referral & Intake</h3>
</div>

<div class="table-container">
    <table id="IntakeTable" class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Violation</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="intakebody">
            @foreach ($counselingperson as $person)
            <tr>
                <td data-label="Student No.">{{ $person->student_no }}</td>
                <td data-label="Name">{{ $person->student_name }}</td>
                <td data-label="Email">{{ $person->school_email }}</td>
                <td data-label="Violation">{{ $person->violation->violations }}</td>
                <td data-label="Status">
                    <span class="badge bg-warning text-dark">Pending Intake</span>
                </td>
                <td data-label="Date">{{ \Carbon\Carbon::parse($person->Date_Created)->format('m/d/y') }}</td>
                <td data-label="Action">
                    <button class="btn btn-sm btn-secondary btn-action-consistent view-report" data-id="{{ $person->id }}" data-bs-toggle="modal" data-bs-target="#CounselingReport">
                        <i class="bi bi-eye"></i> View
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modals -->
<div class="modal fade" id="CounselingReport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3" id="modalBox">
            <div class="modal-header">
                <h3 class="modal-title">Counseling Report</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="reportView">
                <div class="field"><label>Name:</label> <span id="report_name"></span></div>
                <div class="field"><label>Violation:</label> <span id="report_violation"></span></div>
                <div class="field"><label>Severity:</label> <span id="report_severity"></span></div>
                <div class="field"><label>Remarks:</label> <span id="report_remarks"></span></div>
                <input type="hidden" id="report_student_no_input">
                <input type="hidden" id="report_name_input">
                <input type="hidden" id="report_email_input">
                <input type="hidden" id="report_violation_input">
                <input type="hidden" id="report_severity_input">
                <div class="btns">
                    <div>
                        <button class="btn approve bi bi-check-lg"> Approve</button>
                        <button class="btn reject bi bi-x-lg"> Reject</button>
                    </div>
                </div>
            </div>

            <!-- Expanded Modal -->
            <div id="scheduleView" class="hidden">
                <div class="approved-label">Approved</div>
                <h4>Schedule a Counseling Session</h4>
                <div class="field">
                    <label>Start Date</label>
                    <input type="date" id="start_date" class="form-control rounded-pill px-3 py-2">
                    <div class="error-msg text-danger small mt-1" id="error_start_date"></div>
                </div>
                <div class="field">
                    <label>Start Time</label>
                    <input type="time" id="start_time" class="form-control rounded-pill px-3 py-2">
                    <div class="error-msg text-danger small mt-1" id="error_start_time"></div>
                </div>
                <div class="field">
                    <label>End Time</label>
                    <input type="time" id="end_time" class="form-control rounded-pill px-3 py-2">
                    <div class="error-msg text-danger small mt-1" id="error_end_time"></div>
                </div>
                <button class="btn schedule">Set Schedule</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    // Initialize DataTable
    var table = $('#IntakeTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: { emptyTable: "No pending counseling at the moment." }
    });

    // View report 
    $(document).on('click', '.view-report', function () {
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            url: "/counseling_report/" + id,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: 'Counseling record not found.'
                    });
                } else {
                    $('#report_name').text(response.name || 'N/A');
                    $('#report_violation').text(response.violation || 'N/A');
                    $('#report_severity').text(response.severity || 'N/A');
                    $('#report_remarks').text(response.remarks || 'N/A');

                    $('#report_student_no_input').val(response.student_no || '');
                    $('#report_name_input').val(response.name || '');
                    $('#report_violation_input').val(response.violation || '');
                    $('#report_severity_input').val(response.severity || '');
                    $('#report_email_input').val(response.school_email || '');

                    $('#modalBox').removeClass('expanded');
                    $('#reportView').show();
                    $('#scheduleView').removeClass('show');
                    $('.btns').show();

                    $('#CounselingReport').modal('show');
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch counseling report data.'
                });
            }
        });
    });

    // Approve 
    $(document).on('click', '.approve', function () {
        $('#modalBox').addClass('expanded');
        $('#scheduleView').addClass('show');
        $('.btns').hide();
    });

    // Reject 
    $(document).on('click', '.reject', function () {
        $('#CounselingReport').modal('hide');
    });

    // Schedule 
    $(document).on('click', '.schedule', function () {
        $('.error-msg').text('');
        const startDate = $('#start_date').val();
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();
        let hasError = false;

        if (!startDate) { $('#error_start_date').text('Start date is required.'); hasError = true; }
        if (!startTime) { $('#error_start_time').text('Start time is required.'); hasError = true; }
        if (!endTime) { $('#error_end_time').text('End time is required.'); hasError = true; }

        if (hasError) return;

        const data = {
            student_no: $('#report_student_no_input').val(),
            student_name: $('#report_name_input').val(),
            school_email: $('#report_email_input').val(),
            violation: $('#report_violation_input').val(),
            severity: $('#report_severity_input').val(),
            start_date: startDate,
            start_time: startTime,
            end_time: endTime,
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            type: 'POST',
            url: '/counseling_schedule',
            data: data,
            success: function (response) {
                if (response.success) {
                    $('#CounselingReport').modal('hide');
                    
                    $('#CounselingReport').on('hidden.bs.modal', function () {
                        $('.modal-backdrop').remove();  
                        $('body').removeClass('modal-open').css('padding-right', '');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Counseling schedule saved successfully.'
                        });

                        $('#intakebody').load(location.href + " #intakebody > *");
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Failed',
                        text: 'Failed to save schedule.'
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error occurred while saving schedule.'
                });
            }
        });
    });

    // Clear errors on input
    $('#start_date, #start_time, #end_time').on('input change', function () {
        $(this).next('.error-msg').text('');
    });

    // Reset modal when closed
    $('#CounselingReport').on('hidden.bs.modal', function () {
        $('.error-msg').text('');
        $('#start_date, #start_time, #end_time').val('');
        $('#modalBox').removeClass('expanded');
        $('#scheduleView').removeClass('show');
        $('#reportView').show();
        $('.btns').show();
        $('.modal-backdrop').remove(); // ensure backdrop removed
        $('body').removeClass('modal-open').css('padding-right', '');
    });

});
</script>
