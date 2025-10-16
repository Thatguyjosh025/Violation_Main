<link rel="stylesheet" href="{{ asset('./css/counseling_css/ReferralIntake.css') }}">
@php
    use App\Models\postviolation;
    $counselingperson = postviolation::where('counseling_required', 'Yes')->get();
@endphp

<!-- Module Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Referral & Intake</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            
            <!-- Table Contents -->
            <div class="table-container">
                
                <table id="violationTable" class="table table-hover table-bordered table-striped">
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
                    <tbody>
                        @if ($counselingperson->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center text-muted">No pending counseling at the moment.</td>
                            </tr>
                        @else
                            @foreach ($counselingperson as $person)
                            <tr>
                                <td data-label="Student No.">{{ $person->student_no }}</td>
                                <td data-label="Name">{{ $person->student_name }}</td>
                                <td data-label="Email">{{ $person->school_email }}</td>
                                <td data-label="Violation">{{ $person->violation->violations}}</td>
                                <td data-label="Status">
                                    <span class="badge bg-warning text-dark">{{ $person->status->status }}</span>
                                </td>
                                <td data-label="Date">{{ \Carbon\Carbon::parse($person->Date_Created)->format('m/d/y') }}</td>
                                <td data-label="Action">
                                    <button class="btn btn-sm btn-secondary btn-action-consistent view-report" data-id="{{ $person->id }}" data-bs-toggle="modal" data-bs-target="#CounselingReport">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- next work try to pull datas from table to modal -->

            <!-- Modal Content -->
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
                        <input type="hidden" name="" id="report_student_no_input">
                        <input type="hidden" name="" id="report_name_input">
                        <input type="hidden" name="" id="report_email_input">
                        <input type="hidden" name="" id="report_violation_input">
                        <input type="hidden" name="" id="report_severity_input">
                                                
                        <div class="btns">
                            <div>
                                <button class="btn approve bi bi-check-lg"> Approve</button>
                            <button class="btn reject bi bi-x-lg"> Reject</button>
                            </div>
                        </div>
                        </div>

                        <!-- Expanded Content -->
                        <div id="scheduleView" class="hidden">
                        <div class="approved-label">Approved</div>
                        <h4>Schedule a Counseling Session</h4> 
                        <div class="field">
                            <label>Start Date</label>
                            <input type="date" id="start_date" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <div class="field">
                            <label>Start Time</label>
                            <input type="time" id="start_time" name="start_time" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <div class="field">
                            <label>End Time</label>
                            <input type="time" id="end_time" name="end_time" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <button class="btn schedule">Set Schedule</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End modal Content -->


<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).on('click', '.view-report', function () {
    var id = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "/counseling_report/" + id,
        success: function (response) {
            if (response.error) {
                console.log(response.error);
                alert('Counseling record not found.');
            } else {

                // Populate modal fields
                $('#report_name').text(response.name || 'N/A');
                $('#report_violation').text(response.violation || 'N/A');
                $('#report_severity').text(response.severity || 'N/A');
                $('#report_remarks').text(response.remarks || 'N/A');

                $('#report_student_no_input').val(response.student_no || '');
                $('#report_name_input').val(response.name || '');
                $('#report_violation_input').val(response.violation || '');
                $('#report_severity_input').val(response.severity || '');
                $('#report_email_input').val(response.school_email || '');

                // Reset modal to default view
                $('#modalBox').removeClass('expanded');
                $('#reportView').show();
                $('#scheduleView').removeClass('show');
                $('.btns').show();

                // Show the modal
                $('#CounselingReport').modal('show');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", error);
            alert('Failed to fetch counseling report data.');
        }
    });
});

$(document).on('click', '.schedule', function () {
    const data = {
        student_no: $('#report_student_no_input').val(),
        student_name: $('#report_name_input').val(),
        school_email: $('#report_email_input').val(),
        violation: $('#report_violation_input').val(),
        severity: $('#report_severity_input').val(),
        start_date: $('#start_date').val(),
        start_time: $('#start_time').val(),
        end_time: $('#end_time').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        type: 'POST',
        url: '/counseling_schedule',
        data: data,
        success: function (response) {
            if (response.success) {
                alert('Counseling schedule saved successfully.');
                $('#CounselingReport').modal('hide');
            } else {
                alert('Failed to save schedule.');
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Error occurred while saving schedule.');
        }
    });
});

// Expand modal (Approve)
$(document).on('click', '.approve', function () {
    $('#modalBox').addClass('expanded');
    $('#scheduleView').addClass('show');
    $('.btns').hide();
});

// Close expanded modal (Reject)
$(document).on('click', '.reject', function () {
    // $('#modalBox').removeClass('expanded');
    // $('#scheduleView').removeClass('show');
    // $('.btns').show();
    $('#CounselingReport').modal('hide');
});
</script>