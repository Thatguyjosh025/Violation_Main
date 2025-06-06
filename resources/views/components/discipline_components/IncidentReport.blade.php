<link rel="stylesheet" href="{{ asset('./css/discipline_css/IncidentReport.css') }}">

@php
use App\Models\incident;
use App\Models\referals;
use App\Models\penalties;

$ref = referals::get();
$pen = penalties::get();
$reports = incident::get();
@endphp
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Incident Report</h3>
            </div>

            <div class="container mt-5 ">
                <ul class="nav nav-tabs ms-auto mb-3" id="incidentTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#active-incidents" role="tab" aria-controls="active-incidents" aria-selected="true">Active Incidents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="rejected-tab" data-bs-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">Rejected</a>
                    </li>
                     <!-- <li class="nav-item">
                        <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                    </li> -->
                </ul>
                <div class="tab-content" style="height: 45rem; background: white; border-radius: 7px; max-height: 50%;">
                    <!-- Active Incidents -->
                     <div class="tab-pane fade show active" id="active-incidents" role="tabpanel">
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @php $activeReports = $reports->where('is_visible', '===', 'show'); @endphp
                            @if ($activeReports->isEmpty())
                                <div class="alignment-show" style="justify-content: center; width: 100%;">
                                    <div class="col-12 text-center mt-5" style="justify-content: center;">
                                        <p>No active incident reports found.</p>
                                    </div>
                                </div>
                            @else
                                @foreach ($activeReports as $datareport)
                                    <div class="col incident-card" style="width: 23rem;" data-id="{{ $datareport->id }}">
                                        <input type="hidden" value="{{ $datareport->id }}">
                                        <div class="card p-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mb-0">{{ $datareport->violation->violations }}</h5>
                                                <span class="badge badge-minor">{{ $datareport->severity }}</span>
                                            </div>
                                            <p>{{ $datareport->student_name }}<br>{{ $datareport->student_no }}</p>
                                            <p><small>Submitted by: {{ $datareport->faculty_name }}</small></p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button class="btn btn-view-incident" data-source="active" value="{{ $datareport->id }}" style="background: #376881; color: white;">Review</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Approved -->
                    <!-- <div class="tab-pane fade" id="archives" role="tabpanel">
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @php $archivedReports = $reports->where('is_visible', '===', 'approve'); @endphp
                            @if ($archivedReports->isEmpty())
                                <div class="alignment" style="justify-content: center; width: 100%;">
                                    <div class="col-12 text-center mt-5" style="justify-content: center;">
                                        <p>No archived incident reports found.</p>
                                    </div>
                                </div> 
                            @else
                                @foreach ($archivedReports as $datareport)
                                    <div class="col" style="width: 23rem;">
                                        <input type="hidden" value="{{ $datareport->id }}">
                                        <div class="card p-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mb-0">{{ $datareport->violation->violations }}</h5>
                                                <span class="badge badge-minor">{{ $datareport->severity }}</span>
                                            </div>
                                            <p>{{ $datareport->student_name }}<br>{{ $datareport->student_no }}</p>
                                            <p><small>Submitted by: {{ $datareport->faculty_name }}</small></p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button class="btn btn-view-incident" data-source="archive" value="{{ $datareport->id }}" style="background: #376881; color: white;">Review</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div> -->

                    <!-- Reject -->
                   <div class="tab-pane fade" id="rejected" role="tabpanel">
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @php $rejectedReports = $reports->where('is_visible', '===', 'reject'); @endphp
                            @if ($rejectedReports->isEmpty())
                                <div class="alignment-reject" style="justify-content: center; width: 100%;">
                                    <div class="col-12 text-center mt-5" style="justify-content: center;">
                                        <p>No rejected incident reports found.</p>
                                    </div>
                                </div>
                            @else
                                @foreach ($rejectedReports as $datareport)
                                    <div class="col incident-reject" style="width: 23rem;">
                                        <input type="hidden" value="{{ $datareport->id }}">
                                        <div class="card p-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mb-0">{{ $datareport->violation->violations }}</h5>
                                                <span class="badge badge-minor">{{ $datareport->severity }}</span>
                                            </div>
                                            <p>{{ $datareport->student_name }}<br>{{ $datareport->student_no }}</p>
                                            <p><small>Submitted by: {{ $datareport->faculty_name }}</small></p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button class="btn btn-view-incident" data-source="reject" value="{{ $datareport->id }}" style="background: #376881; color: white;">Review</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>

            <!-- Modal Violation Process (Write) -->
            <div class="modal fade" id="violationProcess" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="background-color: white; color: black; padding: 15px; border-radius: 10px;">
                        <div class="modal-header border-0">
                            <h4 class="modal-title fw-bold" id="violationModalLabel">Violation Process</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
                        </div>
                        <div class="modal-body">
                        <form action="" id="incidentReportForm">
                            @csrf
                            <p id="write_incident_name"><strong>Name of violator:</strong> Mark Jecil Bausa</p>
                            <p id="write_incident_no"><strong>Student No:</strong> 02000311488</p>
                            <p id="write_incident_course"><strong>Course and Section:</strong> BSIT 611</p>
                            <p id="write_incident_email"><strong>School email:</strong> mark@gmail.com</p>
                            <hr>
                            <p id="write_incident_violation"><strong>Reason/s for Referral:</strong> Cheating</p>
                            <p id="write_incident_details"><strong>Details:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <p id="write_incident_severity"><strong>Severity of Offense/s:</strong> Minor</p>
                            <p id="write_incident_facultyname"><strong>Submitted by:</strong> Keith Izzam Magante</p>
                            <p id="write_incident_date"><strong>Date Created:</strong>-</p>
                            <input type="hidden" id="incident_id">            
                            <input type="hidden" class="form-control" id="incident_name_input" name="student_name"> 
                            <input type="hidden" class="form-control" id="incident_no_input" name="student_no">                                                                                                                    
                            <input type="hidden" class="form-control" id="incident_course_input" name="course">                                                                                                                    
                            <input type="hidden" class="form-control" id="incident_email_input" name="school_email">
                            <select name="" id="violation_type" style="display: none;"></select>                                                                                                                                                                                                                                        
                            <input type="hidden" class="" id="incident_rule_input" name="rule_Name">
                            <input type="hidden" class="" id="incident_desc_input" name="description_Name">
                            <input type="hidden" class="" id="incident_sev_input" name="severity_Name">
                            <input type="radio" name="faculty_involvement" value="Yes" id="faculty_involvement" style="display: none;" checked>
                            <input type="hidden" id="faculty_name" name="faculty_name">
                            <textarea class="form-control" id="appeal" name="appeal" style="display: none;" maxlength="200">N/A</textarea>
                                                                                                                      
                            <hr>
                           
                            <div id="incident-dropdowns">
                                <!-- Referral Dropdown Section -->
                                <label class="fw-bold mb-1">Action Taken Prior to Referral</label>
                                <select class="form-select" id="referal_type" name="referal_type">
                                    <option value="">Select referal...</option>
                                    @foreach ($ref as $refdata )
                                        <option value="{{ $refdata -> referal_id }}">{{ $refdata -> referals }}</option>
                                    @endforeach 
                                </select>
                                <!-- End of Dropdown Section -->

                                 <!-- Penalty Dropdown Section -->
                                <label class="fw-bold mt-2">Penalty</label>
                                <select class="form-select" id="penalty_type" name="penalty_type">
                                    <option value="" hidden>Select Penaltyy</option>
                                    @foreach ($pen as $pendata )
                                        <option value="{{ $pendata -> penalties_id }}">{{ $pendata -> penalties }}</option>
                                    @endforeach
                                </select>
                                <!-- End of Dropdown -->
                            </div>
                            

                            <div id="counseling_req">
                                <label class="fw-bold">Counseling Required</label><br>
                                <div>
                                    <input type="radio" name="counseling_required" value="Yes" id="counseling_yes">
                                    <label for="counselingYes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="counseling_required" value="No" id="counseling_no" checked>
                                    <label for="counselingNo">No</label>
                                </div>
                            </div>
                            
                            <!-- Remarks -->
                            <div id="remarks-incident">
                                <label class="fw-bold">Remarks:</label>
                                <textarea class="form-control" id="Remarks" name="Remarks" style="height: 100px; resize: none;"></textarea>
                            </div>
                
                            <!-- Approve and Reject buttons -->
                            <div class="write-btn mt-2">
                                <button class="btn btn-success" id="approve-btn">Approve</button>
                                <button type="button" class="btn btn-danger" id="reject-btn">Reject</button>
                            </div>
            
                            </form>
                        </div>
                    </div>
                </div>
            </div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


$(document).ready(function(){

     $("#referal_type, #penalty_type").on("change", function () {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
    });

    $("#Remarks").on("input", function () {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
    });
    
   

    $(document).on('click', '.btn-view-incident', function(e){
        e.preventDefault();
        resetModal();

        const source = $(this).data('source');
        $('#violationProcess').modal('show');

        if (source === 'reject') {
            $('#approve-btn').hide();
            $('#approve-btn').next('.btn-danger').hide();
            $('#incident-dropdowns').hide();
            $('#counseling_req').hide();
            $('#remarks-incident').hide();
        } else {
            $('#approve-btn').show();
            $('#approve-btn').next('.btn-danger').show();
        }

        var id = $(this).val();
        $.ajax({
            type: "GET",
            url: "/get_incident_info?id=" + id,
            success: function (response) {
                if (response.status == 500) {
                    console.log(response.message);
                } else if (response.status == 200) {
                    const data = response.data;
                    updateModalContent(data);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error loading incident info:", error);
            }
        });
    });

    // Ensure the Active Incidents tab is selected by default
    $('#active-tab').tab('show');

    // Hide the specific div when the Rejected tab is clicked
    $('#rejected-tab').on('click', function() {
        $('.alignment-reject').show();
        $('.alignment-show').hide();
        $('.incident-card').hide();
        $('.incident-reject').show();
    });

    // Show the specific div when the Active Incidents tab is clicked
    $('#active-tab').on('click', function() {
        $('.alignment-show').show();
        $('.incident-card').show();
    });

    function resetModal() {
        $('#approve-btn').show();
        $('#approve-btn').next('.btn-danger').show();
        $('#incident-dropdowns').show();
        $('#counseling_req').show();
        $('#remarks-incident').show();
    }

    function updateModalContent(data) {
        $('#incident_id').val(data.id);
        $('#write_incident_name').html(`<strong>Name of violator:</strong> ${data.student_name}`);
        $('#write_incident_no').html(`<strong>Student No:</strong> ${data.student_no}`);
        $('#write_incident_course').html(`<strong>Course and section:</strong> ${data.course_section}`);
        $('#write_incident_email').html(`<strong>School Email:</strong> ${data.school_email}`);
        $('#write_incident_violation').html(`<strong>Reason for Referral:</strong> ${data.violation_name}`);
        $('#write_incident_details').html(`<strong>Details:</strong> ${data.remarks}`);
        $('#write_incident_severity').html(`<strong>Severity of Offense/s:</strong> ${data.severity}`);
        $('#write_incident_facultyname').html(`<strong>Submitted by:</strong> ${data.faculty_name}`);
        $('#write_incident_date').html(`<strong>Date Created:</strong> ${data.Date_Created}`);

        $('#incident_name_input').val(data.student_name);
        $('#incident_no_input').val(data.student_no);
        $('#incident_course_input').val(data.course_section);
        $('#incident_email_input').val(data.school_email);
        $('#violation_type').val(data.violation_type);
        $('#incident_rule_input').val(data.rule_name);
        $('#incident_desc_input').val(data.description);
        $('#incident_sev_input').val(data.severity);
        $('#faculty_name').val(data.faculty_name);

        if (data.upload_evidence !== 'N/A') {
            $('#view_incident_evidence').html(
                `<strong>Evidence/s:</strong> <a href="/storage/${data.upload_evidence}" target="_blank">View Evidence</a>`
            );
        } else {
            $('#view_incident_evidence').html(`<strong>Evidence/s:</strong> <span class="fw-bold">N/A</span>`);
        }

        loadViolationDropdown('/get_violations', '#violation_type', data.violation_type);
    }
});

function loadViolationDropdown(url, id, selectedValue) {
    $.ajax({
        url: url,
        success: function(response) {
        var dropdown = $(id);
        dropdown.empty();

        response.violation_data.forEach(function(item) {
            var isSelected = item.violation_id == selectedValue ? 'selected' : '';
            dropdown.append(`<option value="${item.violation_id}" ${isSelected}>${item.violations}</option>`);
        });

        }
    });
}

//submit incident
$(document).ready(function () {
    $("#approve-btn").click(function (e) {
        e.preventDefault();


        let isValid = true;

        // Remove existing error messages
        $(".invalid-feedback").remove();
        $("#referal_type, #penalty_type, #Remarks, input[name='counseling_required']").removeClass("is-invalid");

        // Validate dropdowns and textarea
        const fieldsToValidate = [
            { id: "#referal_type", message: "Please select a referral action." },
            { id: "#penalty_type", message: "Please select a penalty." },
            { id: "#Remarks", message: "Please provide remarks." }
        ];

        fieldsToValidate.forEach(field => {
            if (!$(field.id).val()) {
                $(field.id).addClass("is-invalid").after(`<div class="invalid-feedback">${field.message}</div>`);
                isValid = false;
            }
        });

        // Validate radio buttons
        if (!$("input[name='counseling_required']:checked").val()) {
            $("input[name='counseling_required']").addClass("is-invalid")
                .first().parent().append('<div class="invalid-feedback">Please select counseling requirement.</div>');
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please fill out all required fields before submitting." });
            return;
        }


        let formData = new FormData();

        formData.append('incident_id', $("#incident_id").val());

        formData.append('_token', $('input[name="_token"]').val());
        formData.append('student_name', $("#incident_name_input").val());
        formData.append('student_no', $("#incident_no_input").val());
        formData.append('course', $("#incident_course_input").val());
        formData.append('school_email', $("#incident_email_input").val());
        formData.append('violation_type', $("#violation_type").val());
        formData.append('rule_Name', $("#incident_rule_input").val());
        formData.append('description_Name', $("#incident_desc_input").val());
        formData.append('severity_Name', $("#incident_sev_input").val());
        formData.append('faculty_involvement', $("input[name='faculty_involvement']:checked").val());
        formData.append('faculty_name', $("#faculty_name").val());
        formData.append('referal_type', $("#referal_type").val());
        formData.append('penalty_type', $("#penalty_type").val());
        formData.append('counseling_required', $("input[name='counseling_required']:checked").val());
        formData.append('Remarks', $("#Remarks").val());
        formData.append('appeal', $("#appeal").val());

        $.ajax({
            url: "/post_violation",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#violationProcess').modal('hide');
                console.log("Incident recorded successfully!");

                $('#incidentReportForm')[0].reset();
                $("#active-incidents").load(location.href + " #active-incidents > *");
                $("#archives").load(location.href + " #archives > *");
                $("#Reject").load(location.href + " #Reject > *");

                Swal.fire({
                    icon: "success",
                    text: "Incident recorded successfully!",
                    timer: 5000
                });
            },
            error: function (xhr) {
                console.log("Error submitting form. Please check the inputs.");
                console.log(xhr.responseText);
            }
        });
    });
});

//reject button
$(document).ready(function () {
    $('#reject-btn').on('click', function (e) {
        e.preventDefault(); // Prevent default form submission behavior

        Swal.fire({
            title: "Are you sure?",
            text: "This action will mark the violation as rejected.",
            icon: "warning",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Yes, reject it",
            denyButtonText: `Don't reject`
        }).then((result) => {
            if (result.isConfirmed) {
                let incidentId = $('#incident_id').val();

                $.ajax({
                    url: '/incident_rejected',
                    type: 'POST',
                    data: {
                        id: incidentId,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        Swal.fire("Rejected!", "The violation has been marked as rejected.", "success");

                        $('.incident-card[data-id="' + incidentId + '"]').fadeOut(500, function () {
                            $("#active-incidents").load(location.href + " #active-incidents > *");
                            $("#archives").load(location.href + " #archives > *");
                            $("#Reject").load(location.href + " #Reject > *");
                        });

                        $('#violationProcess').modal('hide');

                    },
                    error: function (xhr) {
                        console.log('Something went wrong.');
                        console.log(xhr.responseText);
                        Swal.fire("Error", "Something went wrong. Please try again.", "error");
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelled", "No changes were made.", "info");
            }
        });
    });
});
</script>
