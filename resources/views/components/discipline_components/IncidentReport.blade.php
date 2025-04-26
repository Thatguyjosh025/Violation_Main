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
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <div class="container mt-5">
                <ul class="nav nav-tabs ms-auto mb-3" id="incidentTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#active-incidents" role="tab">Active Incidents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="archives-tab" data-bs-toggle="tab" href="#archives" role="tab">Archives</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Active Incidents -->
                    <div class="tab-pane fade show active" id="active-incidents" role="tabpanel">
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @foreach ($reports->where('is_visible', '===','show') as $datareport)
                                <div class="col">
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
                        </div>
                    </div>

                    <!-- Archives -->
                    <div class="tab-pane fade" id="archives" role="tabpanel">
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @foreach ($reports->where('is_visible', '===','hide') as $datareport)
                                <div class="col">
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
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Modal Violation Process (View) -->
            <div class="modal fade" id="violationModalview" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Violation Process</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="view_incident_name"><strong>Name of violator:</strong></p>
                            <p id="view_incident_studentno"><strong>Student No:</strong></p>
                            <p id="view_incident_course"><strong>Section:</strong></p>
                            <p id="view_incident_email"><strong>School email:</strong></p>
                            <hr>    
                            <p id="view_incident_violation"><strong>Reason/s for Referral:</strong></p>
                            <p id="view_incident_details"><strong>Details:</strong></p>
                            <p id="view_incident_severity"><strong>Severity of Offense/s:</strong></p>
                            <p id="view_incident_facultyname"><strong>Submitted by:</strong>    </p>
                            <p><strong>Evidence/s:</strong> <span class="fw-bold">N/A</span></p>
                            <input type="text" id="incident_id">
                            <input type="hidden" class="form-control" id="incident_name">
                            <input type="hidden" class="form-control" id="incident_no">
                            <input type="hidden" class="form-control" id="incident_course">
                            <input type="hidden" class="form-control" id="incident_email">
                            <!-- <input type="hidden" class="form-control" id="incident_violation"> -->
                            <input type="hidden" class="form-control" id="incident_severity">
                            <input type="hidden" class="form-control" id="incident_faculty">
                            <input type="hidden" class="form-control" id="incident_details">
                            <select name="" id="incident_violation" style="display: none;"></select>

                        <div class="modal-footer">
                                <button class="btn btn-success" id="approve-btn">Approve</button>
                                <button class="btn btn-danger">Reject</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Violation Process (Write) -->
            <div class="modal fade" id="violationProcess" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="background-color: #77abc9; color: white; padding: 15px; border-radius: 10px;">
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
                           
                            <!-- Referral Dropdown Section -->
                            <label class="fw-bold mb-1">Action Taken Prior to Referral</label>
                            <select class="form-select" id="referal_type" name="referal_type">
                                <option selected hidden>Select referal...</option>
                                @foreach ($ref as $refdata )
                                    <option value="{{ $refdata -> referal_id }}">{{ $refdata -> referals }}</option>
                                @endforeach 
                            </select>
                            <!-- End of Dropdown Section -->
                         
                            <!-- Penalty Dropdown Section -->
                            <label class="fw-bold mt-2">Penalty</label>
                            <select class="form-select" id="penalty_type" name="penalty_type">
                                <option selected hidden>Select Penaltyy</option>
                                @foreach ($pen as $pendata )
                                    <option value="{{ $pendata -> penalties_id }}">{{ $pendata -> penalties }}</option>
                                @endforeach
                            </select>
                            <!-- End of Dropdown -->

                            <div>
                                <label class="fw-bold">Counseling Required</label><br>
                                <div>
                                    <input type="radio" name="counseling_required" value="Yes" id="counseling_yes">
                                    <label for="counselingYes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="counseling_required" value="No" id="counseling_no">
                                    <label for="counselingNo">No</label>
                                </div>
                            </div>
        
                            <label class="fw-bold">Remarks:</label>
                                <textarea class="form-control" id="Remarks" name="Remarks" style="height: 100px; resize: none;"></textarea>
                                <button type="button" class="btn btn-primary mt-2" id="submit_incident">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function(){
    $('.btn-view-incident').on('click', function(e){
        e.preventDefault();
        $('#violationModalview').modal('show');

        var id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/get_incident_info?id=" + id,
            success: function (response) {
                if (response.status == 500) {
                    console.log(response.message);
                } else if (response.status == 200) {    
                    const data = response.data;

                    $('#view_incident_name').html(`<strong>Name of violator:</strong> ${data.student_name}`);
                    $('#view_incident_studentno').html(`<strong>Student No:</strong> ${data.student_no}`);
                    $('#view_incident_course').html(`<strong>Section:</strong> ${data.course_section}`);
                    $('#view_incident_email').html(`<strong>School email:</strong> ${data.school_email}`);
                    $('#view_incident_violation').html(`<strong>Reason/s for Referral:</strong> ${data.violation_name}`);
                    $('#view_incident_details').html(`<strong>Details:</strong> ${data.remarks}`);
                    $('#view_incident_severity').html(`<strong>Severity of Offense/s:</strong> ${data.severity}`);
                    $('#view_incident_facultyname').html(`<strong>Submitted by:</strong> ${data.faculty_name}`);
                    $('#view_incident_remarks').html(`<strong>Remarks:</strong> ${data.remarks}`);
                    $('#view_incident_date').html(`<strong>Date Submitted:</strong> ${data.Date_Created}`);

                    $('#incident_id').val(response.data.id);
                    $('#incident_name').val(response.data.student_name);
                    $('#incident_no').val(response.data.student_no);
                    $('#incident_course').val(response.data.course_section);
                    $('#incident_email').val(response.data.school_email);
                    $('#incident_violation').val(response.data.violation_name);
                    $('#incident_severity').val(response.data.severity);
                    $('#incident_faculty').val(response.data.faculty_name);
                    $('#incident_details').val(response.data.remarks);

                    $('#incident_violation').val(response.data.violation_type);
                    if (!$('#incident_violation').val()) {
                        $('#incident_violation').append(
                            `<option value="${response.data.violation_type}" selected>${response.data.violation_name}</option>`
                        );
                    }

                    if (data.upload_evidence !== 'N/A') {
                        $('#view_incident_evidence').html(
                            `<strong>Evidence/s:</strong> <a href="/storage/${data.upload_evidence}" target="_blank">View Evidence</a>`
                        );
                    } else {
                        $('#view_incident_evidence').html(`<strong>Evidence/s:</strong> <span class="fw-bold">N/A</span>`);
                    }

                    loadViolationDropdown('/get_violations', '#incident_violation', response.data.violation_type);

                    $('#viewIncidentModal').modal('show');
                }
            }
        });
    });
});

//approve-btn it needs to pull the data from the first modal
$(document).ready(function(){
    $('#approve-btn').on('click', function(e){
        e.preventDefault();
        $('#violationProcess').modal('show');
        $('#viewIncidentModal').modal('hide');
   
        var incidentID = $('#incident_id').val();
        var studentnameinput = $('#incident_name').val();
        var studentno = $('#incident_no').val();
        var studentcourse = $('#incident_course').val();
        var studentemail = $('#incident_email').val();
        var studentviolation = $('#incident_violation').val();
        var studentseverity = $('#incident_severity').val();
        var studentfacultyname = $('#incident_faculty').val();
        var studentdetails = $('#incident_details').val();

        $('#write_incident_name').html(`<strong>Name of violator:</strong> ${studentnameinput}`);
        $('#write_incident_no').html(`<strong>Student No:</strong> ${studentno}`);
        $('#write_incident_course').html(`<strong>Course and section:</strong> ${studentcourse}`);
        $('#write_incident_email').html(`<strong>School Email:</strong> ${studentemail}`);
        $('#write_incident_details').html(`<strong>Details:</strong> ${studentdetails}`);
        $('#write_incident_severity').html(`<strong>Severity of Offense/s:</strong> ${studentseverity}`);
        $('#write_incident_facultyname').html(`<strong>Submitted by:</strong> ${studentfacultyname}`);

        
        $('#incident_name_input').val(studentnameinput);
        $('#incident_no_input').val(studentno);
        $('#incident_course_input').val(studentcourse);
        $('#incident_email_input').val(studentemail);
        $('#violation_type').val(studentviolation);
        $('#faculty_name').val(studentfacultyname);


        loadViolationDropdown('/get_violations', '#violation_type', studentviolation);

    });
});

//dropdown loader
function loadViolationDropdown(url, id, selectedValue) {
  $.ajax({
    url: url,
    success: function(response) {
      var dropdown = $(id);
      dropdown.empty();
      dropdown.append('<option value="">Select</option>');

      //this block of code loops through a list of violations and adds each one in the option to a dropdown.
      response.violation_data.forEach(function(item) {
        var isSelected = item.violation_id == selectedValue ? 'selected' : '';
        dropdown.append(`<option value="${item.violation_id}" ${isSelected}>${item.violations}</option>`);
      });

        dropdown.val(selectedValue);

        $.get("/get_rule/" + selectedValue, function (response) {
        if (response.error) {
            updateRuleDetails("", "", "");
        } else {
            updateRuleDetails(response.rule_name, response.description, response.severity_name);
        }
        });

        function updateRuleDetails(rule, desc, severity) {
            $("#incident_rule_input").val(rule);
            $("#incident_desc_input").val(desc);
            $("#incident_sev_input").val(severity);
        }
    }
  });
}

// Selecting violation populating the rule, desc, severity
$(document).on("change", "#violation_type", function (e) {
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
});

//submit incident
$(document).ready(function () {
    $("#submit_incident").click(function (e) {
        e.preventDefault();

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

                $("#active-incidents").load(location.href + " #active-incidents > *");
                $("#archives").load(location.href + " #archives > *");
                
            },
            error: function (xhr) {
                console.log("Error submitting form. Please check the inputs.");
                console.log(xhr.responseText);
            }
        });
    });
});

$(document).on('click', '.btn-view-incident', function () {

        const source = $(this).data('source'); 
        $('#violationModalview').modal('show');

        if (source === 'archive') {
            console.log('test hello wold')
            $('#approve-btn').hide();
            $('#approve-btn').next('.btn-danger').hide(); 
        } else {
            $('#approve-btn').show();
            $('#approve-btn').next('.btn-danger').show();
        }
    });
</script>
