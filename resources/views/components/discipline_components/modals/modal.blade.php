@php
  use App\Models\users;
  use App\Models\violation;
  use App\Models\referals;
  use App\Models\penalties;

  $accounts = users::get();
  $violate = violation::get();
  $ref = referals::get();
  $pen = penalties::get();
  $facultyUsers = users::where('role', 'faculty')->get();
@endphp
<!-- Violation Process Form Modal -->
<div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background-color:rgb(255, 255, 255); color: white; padding: 20px; border-radius: 10px;">
                <div class="modal-header border-0">
                  <h4 class="modal-title fw-bold text text-dark" id="violationModalLabel">Violation Process</h4>
                <button type="button" class="btn-close" id="close-btn" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="postviolationForm">
                    @csrf
                    <input type="hidden" class="form-control mb-2" id="modal_student_no" name="student_no"  readonly required>
                    <input type="hidden" class="form-control mb-2" id="modal_student_name" name="student_name"  readonly required>
                    <input type="hidden" class="form-control mb-2" id="modal_student_course" name="course" readonly required>
                    <input type="hidden" class="form-control mb-2" id="modal_student_email" name="school_email" readonly required>

                    <label class="fw-bold mb-1 text text-dark">Reason/s for Referral</label>
                    <!-- Violation Dropdown Section -->
                    <div class="dropdown" id="dropdown1">
                        <select name="violation_type" id="violation_type" class="form-select">
                            <option value="" hidden>Select Violation</option>
                            @foreach ($violate as $data)
                                <option value="{{ $data->violation_id }}">{{ $data->violations }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- End of Dropdown Section -->

                    <!-- RuleName, Description, Severity auto populate -->
                    <label class="fw-bold mt-2 text text-dark">Rule Name:</label>
                    <p id="ruleName" class="text text-dark">-</p>
                    <input type="hidden" id="ruleNameInput" name="rule_Name" style="display: none;"> 

                    <label class="fw-bold mt-2 text text-dark">Description: </label>
                    <p id="descriptionName" class="text text-dark">-</p>
                    <input type="hidden" id="descriptionNameInput" name="description_Name" style="display: none;">

                    <div class="Severity mb-2 text text-dark">
                        <label class="fw-bold text text-dark">Severity of Offense: </label>
                        <p id="severityName" class="text text-dark">-</p> 
                        <input type="hidden" id="severityNameInput" name="severity_Name" style="display: none;"> 
                    </div>
                    <!-- RuleName, Description, Severity auto populate END -->

                    
                    <!-- Referral Dropdown Section -->
                    <label class="fw-bold mb-1 text text-dark">Action Taken Prior to Referral</label>
                    <select class="form-select" id="referal_type" name="referal_type">
                        <option value="" hidden>Select Referals</option>
                       @foreach ( $ref as $referaldata )
                        <option value="{{ $referaldata -> referal_id }}">{{ $referaldata -> referals }}</option>
                       @endforeach
                    </select>
                    <!-- End of Dropdown Section -->

                    <!-- Penalty Dropdown Section -->
                    <label class="fw-bold mt-2 text text-dark">Penalty</label>
                    <select class="form-select" id="penalty_type" name="penalty_type">
                        <option value="" hidden>Select Penalty</option>
                       @foreach ( $pen as $pendata )
                        <option value="{{ $pendata -> penalties_id }}">{{ $pendata -> penalties }}</option>
                       @endforeach
                    </select>
                    <!-- End of Dropdown Section -->

                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <label class="fw-bold text text-dark">Faculty Involvement?</label><br>
                            <div>
                                <input class="form-check-input text text-dark" type="radio" id="faculty_yes" name="faculty_involvement" value="Yes">
                                <label class="form-check-label text text-dark" for="faculty_yes">Yes</label>
                            </div>
                            <div>
                                <input class="form-check-input text text-dark" type="radio" id="faculty_no" name="faculty_involvement" value="No" checked>
                                <label class="form-check-label text text-dark" for="faculty_no">No</label>
                            </div>
                            <label for="" id="facultyLabel" class="text text-dark" style="display: none;"></label>
                        </div>
                        <div>
                            <label class="fw-bold text text-dark">Counseling Required?</label><br>
                            <div>
                                <input class="form-check-input text text-dark" type="radio" id="counseling_yes" name="counseling_required" value="Yes">
                                <label class="form-check-label text text-dark" for="counseling_yes">Yes</label>
                            </div>
                            <div>
                                <input class="form-check-input text text-dark" type="radio" id="counseling_no" name="counseling_required" value="No" checked>
                                <label class="form-check-label text text-dark" for="counseling_no">No</label>
                            </div>
                        </div>
                    </div>

                        <!-- faculty involvement name -->
                        <!-- <input type="text" name="faculty_name" id="facultyName" class="form-control mt-2 text text-dark" style="display: none;"> -->
                        <select class="form-select mt-2" name="faculty_name" id="facultyName" style="display: none;">
                            <option value="">Select Faculty</option>
                            @foreach ($facultyUsers as $faculty)
                                <option value="{{ $faculty->firstname }} {{ $faculty->lastname }}">
                                    {{ $faculty->firstname }} {{ $faculty->lastname }}
                                </option>
                            @endforeach
                        </select>
    
                        <!-- Remarks-->
                        <div class="form-group mt-3">
                            <label for="floatingTextarea" class="text text-dark">Remarks</label>
                            <textarea class="form-control" id="remarks" style="height: 100px; resize: none;" maxlength="200"></textarea>
                        </div>
                    
                        <textarea class="form-control" id="appeal" name="appeal" style="display: none;" maxlength="200">N/A</textarea>
                        
                            <!-- UPLOAD FILES -->
                        <div class="my-3">
                          <label id="dropArea" 
                                for="uploadEvidence" 
                                class="d-block w-100 p-5 border border-dark rounded text-center bg-white" 
                                style="cursor: pointer; border-style: dashed;">
                            <div class="text-dark fw-medium">Upload Evidence</div>
                            <small class="text-muted">Click to select files or drag them here</small>
                          </label>
                          <input type="file" id="uploadEvidence" name="upload_evidence[]" multiple hidden>
                        </div>

                        <!-- preview stack -->
                        <ul id="fileList" class="list-group mt-2" 
                            style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 5px;">
                        </ul>
                        <br>
                        <button type="submit" id="submit_violation" class="btn btn-primary">Create Violation</button> 

                    </form>
                </div>
            </div>
        </div>
</div>


<!-- Violation Process View Modal -->
<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Violation Information</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="view_student_id" name="view_id">
        <div class="row">
          <!-- UPLOAD -->
         <div class="UploadEvidence col-md-6" style="width: 100%;">
            <label for="uploadEvidence" class="form-label" style="font-size: 1rem; font-weight: 500; margin-bottom: .5rem;">Uploaded Evidence</label>
            <ul id="viewFileList" class="list-group mt-2"
                style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 5px; scrollbar-width: none; -ms-overflow-style: none;">
            </ul>
            <span id="noFileLabel" class="form-control" style="display: none;">N/A</span>
        </div>

         <!-- APPEAL UPLOAD -->
         <div class="AppealUploadEvidence col-md-6 mt-3" style="width: 100%;">
            <label for="AppealuploadEvidence" class="form-label" style="font-size: 1rem; font-weight: 500; margin-bottom: .5rem;">Appeal Uploaded Evidence</label>
            <ul id="AppealviewFileList" class="list-group mt-2"
                style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 5px; scrollbar-width: none; -ms-overflow-style: none;">
            </ul>
            <span id="AppealnoFileLabel" class="form-control" style="display: none;">N/A</span>
        </div>

          <section class="StudentInformation">
            <hr>
            <div class="container" style="background-color: #fcfcfc; padding: 1rem; border-radius: 0.3rem;">
              <p class="SectionLabel" style="font-size: 1.3rem; font-weight: 500; margin-bottom: 0.5rem;">Student Information</p>
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="studentNo" class="form-label" style="font-size: 1rem; font-weight: 500;">Student Number:</label>
                    <p id="viewstudent_no" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="studentName" class="form-label" style="font-size: 1rem; font-weight: 500;">Student Name:</label>
                    <p id="viewstudent_name" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="course" class="form-label" style="font-size: 1rem; font-weight: 500;">Course:</label>
                    <p id="viewcourse" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div> -->
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="schoolEmail" class="form-label" style="font-size: 1rem; font-weight: 500;">School Email:</label>
                    <p id="viewschool_email" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
              </div>
            </div>
            <hr>
          </section>

          <section class="ViolationDetails">
            <div class="container" style="background-color: #fcfcfc; padding: 1rem; border-radius: 0.3rem;">
              <p class="SectionLabel" style="font-size: 1.3rem; font-weight: 500; margin-bottom: 0.5rem;">Violation Details</p>
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="violationType" class="form-label" style="font-size: 1rem; font-weight: 500;">Violation Type:</label>
                    <p id="viewviolation_type" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="penaltyType" class="form-label" style="font-size: 1rem; font-weight: 500;">Penalty Type:</label>
                    <p id="viewpenalty_type" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="severityName" class="form-label" style="font-size: 1rem; font-weight: 500;">Severity:</label>
                    <p id="viewseverity_Name" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="statusName" class="form-label" style="font-size: 1rem; font-weight: 500;">Status:</label>
                    <p id="viewstatus_name" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="ruleName" class="form-label" style="font-size: 1rem; font-weight: 500;">Rule Name:</label>
                    <p id="viewrule_Name" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                    <label for="descriptionName" class="form-label" style="font-size: 1rem; font-weight: 500;">Description:</label>
                    <textarea id="viewdescription_Name" class="form-control" style="resize: none;" readonly disabled></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="facultyInvolvement" class="form-label" style="font-size: 1rem; font-weight: 500;">Faculty Involvement:</label>
                    <p id="viewfaculty_involvement" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="facultyName" class="form-label" style="font-size: 1rem; font-weight: 500;">Faculty Name</label>
                    <p id="viewfaculty_name" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="counselingRequired" class="form-label" style="font-size: 1rem; font-weight: 500;">Counseling Required:</label>
                    <p id="viewcounseling_required" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="referalType" class="form-label" style="font-size: 1rem; font-weight: 500;">Referral Type:</label>
                    <p id="viewreferal_type" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div> <br>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="remarks" class="form-label" style="font-size: 1rem; font-weight: 500;">Remarks</label>
                  <textarea id="viewRemarks" class="form-control" style="resize: none;" readonly disabled></textarea>
                </div>
                <div class="col-md-6">
                  <label for="remarks" class="form-label" style="font-size: 1rem; font-weight: 500;">Appeal Description</label>
                  <textarea id="viewappeal" class="form-control" style="resize: none;" readonly disabled></textarea>
                </div>

                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-2" style="margin-top: 0.5rem;">
                    <label for="dateCreated" class="form-label" style="font-size: 1rem; font-weight: 500;">Date Created:</label>
                    <p id="viewDate_Created" style="margin-bottom: 0.5rem; font-weight: 300;"></p>
                  </div>
                </div>
              </div>
            </div>
            <hr>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Violation Process Edit Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Student Information</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editStudentForm">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="edit_student_id" name="update_id">

          <div class="mb-3" style="display: none;">
            <label for="edit_student_no" class="form-label">Student Number</label>
            <input id="edit_student_no" class="form-control" name="update_student_no"  pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_student_name" class="form-label">Student Name</label>
            <input id="edit_student_name" class="form-control" name="update_name" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_course" class="form-label">Course</label>
            <input id="edit_course" class="form-control" name="update_course" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_school_email" class="form-label">School Email</label>
            <input id="edit_school_email" class="form-control" name="update_school_email" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3">
            <label for="edit_violation_type" class="form-label">Violation Type</label>
            <select id="edit_violation_type" class="form-select" name="update_violation_type"></select>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_rule_Name" class="form-label">Rule Name</label>
            <input id="edit_rule_Name" class="form-control" name="update_rule_name" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_description_Name" class="form-label">Description</label>
            <input id="edit_description_Name" class="form-control" name="update_description" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3" style="display: none;">
            <label for="edit_severity_Name" class="form-label">Severity</label>
            <input id="edit_severity_Name" class="form-control" name="update_severity" pattern="[A-Za-z]+" title="Only letters are allowed" readonly required>
          </div>

          <div class="mb-3">
            <label for="edit_penalty_type" class="form-label">Penalty Type</label>
            <select id="edit_penalty_type" class="form-select" name="update_penalty_type"></select>
          </div>

          <div class="mb-3">
          <h5>Faculty Involvement</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="edit_faculty_yes" name="edit_faculty_involvement" value="Yes">
                <label class="form-check-label" for="faculty_yes">Yes</label>
            </div> 
            <div class="form-check">
                <input class="form-check-input" type="radio" id="edit_faculty_no" name="edit_faculty_involvement" value="No" checked>
                <label class="form-check-label" for="faculty_no">No</label>   
            </div>
            <label for="" id="editfacultyLabel" style="display: none;"></label>
            <!-- <input type="text" name="edit_faculty_name" id="edit_faculty_Name" class="form-control mt-2" style="display: none;"> -->
            <select class="form-select mt-2" name="edit_faculty_name" id="edit_faculty_Name" style="display: none;">
                <option value="">Select Faculty</option>
                @foreach ($facultyUsers as $faculty)
                    <option value="{{ $faculty->firstname }} {{ $faculty->lastname }}">
                        {{ $faculty->firstname }} {{ $faculty->lastname }}
                    </option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
          <h5>Counseling Required</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="edit_counseling_yes" name="edit_counseling_required" value="Yes">
                <label class="form-check-label" for="counseling_yes">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="edit_counseling_no" name="edit_counseling_required" value="No">
                <label class="form-check-label" for="counseling_no">No</label>
          </div>
          </div>

          <div class="mb-3">
            <label for="edit_referal_type" class="form-label">Referral Type</label>
            <select id="edit_referal_type" class="form-select" name="update_referral_type"></select>
          </div>

          <div class="mb-3">
            <label for="edit_Remarks" class="form-label">Remarks</label>
            <textarea class="form-control" name="update_remarks" id="edit_Remarks" style="height: 100px; resize: none;" maxlength="200"></textarea>
          </div>

          <div class="mb-3">
            <label for="edit_notes" class="form-label">Note:</label>
            <textarea class="form-control" name="update_remarks" id="edit_notes" style="height: 100px; resize: none;" maxlength="200" placeholder="Leave a reminder"></textarea>
          </div>
          
          <div class="mb-3">
            <label for="edit_status_type" class="form-label">Status</label>
            <select id="edit_status_type" class="form-select" name="update_status"></select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
  
// View student
$(document).on('click', '.btn-view-post', function () {
    var id = $(this).val();
    console.log("test", id);

    $.ajax({
        type: "GET",
        url: "/get_info?id=" + id,
        success: function (response) {
            if (response.status == 500) {
                console.log(response.message);
            } else if (response.status == 200) {
                console.log(response);

                // Populate fields
                $('#viewviolation_type').text(response.data.violation_name);
                $('#viewpenalty_type').text(response.data.penalty_name);
                $('#viewstatus_name').text(response.data.status_label);
                $('#viewreferal_type').text(response.data.referal_name);
                $('#view_student_id').val(response.data.view_id);
                $('#viewstudent_no').text(response.data.student_no);
                $('#viewstudent_name').text(response.data.student_name);
                $('#viewschool_email').text(response.data.school_email);
                $('#viewseverity_Name').text(response.data.severity_Name);
                $('#viewrule_Name').text(response.data.rule_Name);
                $('#viewdescription_Name').text(response.data.description_Name);
                $('#viewfaculty_involvement').text(response.data.faculty_involvement);
                $('#viewfaculty_name').text(response.data.faculty_name);
                $('#viewcounseling_required').text(response.data.counseling_required);
                $('#viewRemarks').text(response.data.Remarks);
                $('#viewappeal').text(response.data.appeal);
                $('#viewDate_Created').text(response.data.Date_Created);

                // Handle uploaded evidence
                $('#viewFileList').empty(); 
                  if (response.data.upload_evidence) {
                      var files = JSON.parse(response.data.upload_evidence);
                      files.forEach(function(file, index) {
                          var fileName = file.split('/').pop(); // get just the file name
                          $('#viewFileList').append(
                              `<li class="list-group-item d-flex justify-content-between align-items-center">
                                  <span>${fileName}</span>
                                  <button type="button" class="btn btn-sm btn-primary" onclick="window.open('/storage/${file}', '_blank')">View</button>
                              </li>`
                          );
                      });
                      $('#viewFileList').show();
                      $('#noFileLabel').hide();
                  } else {
                      $('#viewFileList').hide();
                      $('#noFileLabel').show();
                  }

                  $('#AppealviewFileList').empty(); 
                  if (response.data.appeal_evidence) {
                      var files = JSON.parse(response.data.appeal_evidence);
                      files.forEach(function(file) {
                          var fileName = file.split('/').pop();
                          $('#AppealviewFileList').append( 
                              `<li class="list-group-item d-flex justify-content-between align-items-center">
                                  <span>${fileName}</span>
                                  <button type="button" class="btn btn-sm btn-primary" onclick="window.open('/storage/${file}', '_blank')">View</button>
                              </li>`
                          );
                      });
                      $('#AppealviewFileList').show();
                      $('#AppealnoFileLabel').hide();
                  } else {
                      $('#AppealviewFileList').hide();
                      $('#AppealnoFileLabel').show();
                  }

                // Show the modal
                $('#viewStudentModal').modal('show');
            }
        }
    });
});

// dropdown loader
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

function loadPenaltyDropdown(url, id, selectedValue){
  $.ajax({
    url:url,
    success: function(response){
      var dropdown = $(id);

      dropdown.empty();

      response.penalties_data.forEach(function(item){
        var isSelected = item.penalties_id == selectedValue ? 'selected' : '';
        dropdown.append(`<option value="${item.penalties_id}" ${isSelected}>${item.penalties}</option>`);
      });
    }
  })
}

function loadStatusDropdown(url, id, selectedValue){
  $.ajax({
    url:url,
    success:function(response){
      var dropdown = $(id);

      dropdown.empty();

      response.status_data.forEach(function(item){

        var isSelected  = item.status_id == selectedValue ? 'selected' : '';
        dropdown.append(`<option value="${item.status_id}" ${isSelected}>${item.status}</option>`)
      });
    }
  });
}

function loadReferalDropdown(url, id, selectedValue){
  $.ajax({
    url:url,
    success:function(response){
      var dropdown = $(id);

      dropdown.empty();

      response.referals_data.forEach(function(item){
        var isSelected = item.referal_id == selectedValue ? 'selected' : '';
        dropdown.append(`<option value="${item.referal_id}" ${isSelected}>${item.referals}</option>`)
      });
    }
  });
}
// End dropdown loader

//populate selected violation for edit form
$(document).on("change", "#edit_violation_type", function (e) {
        e.preventDefault();

        var violation_id = $(this).val();

        if (!violation_id) {
            updateRuleDetails("-", "-", "-");
            return;
        }

        $.get("/get_rule/" + violation_id, function (response) {
            if (response.error) {
                updateRuleDetails("-", "-", "-");
            } else {
                updateRuleDetails(response.rule_name, response.description, response.severity_name);
            }
        });

        function updateRuleDetails(rule, desc, severity) {
       
            $("#edit_description_Name").val(desc);
            $("#edit_severity_Name").val(severity);
            $("#edit_rule_Name").val(rule);
        }
  });

//Preview edit
$(document).on('click', '.btn-edit-post', function(){
  var id = $(this).val();
  console.log(id + "test");

  $.ajax({
    type: "GET",
    url: '/get_info?id=' + id,
    success: function(response){
      if(response.status == 500)
      {
        console.log(response.message);
      }
      else if(response.status == 200)
      {
        console.log(response);

        //fetching the selected value
        $('#edit_violation_type').val(response.data.violation_type);
        if (!$('#edit_violation_type').val()) {
            $('#edit_violation_type').append(
                `<option value="${response.data.violation_type}" selected>${response.data.violation_name}</option>`
            );
        }

        $('#edit_penalty_type').val(response.data.penalty_type);
        if(!$('#edit_penalty_type').val()){
          $('#edit_penalty_type').append(
                `<option value="${response.data.penalty_type}" selected>${response.data.penalty_name}</option>`
            );
        }

        $('#edit_status_type').val(response.data.status_name);
        if(!$('#edit_status_type').val()){
          $('#edit_status_type').append(
                `<option value="${response.data.status_name}" selected>${response.data.status_label}</option>`
            );
        }

        $('#edit_referal_type').val(response.data.referal_type);
        if(!$('#edit_referal_type').val()){
          $('#edit_referal_type').append(
                `<option value="${response.data.referal_type}" selected>${response.data.referal_name}</option>`
            );
        }

         // Populate fields with response data
         $('#edit_student_id').val(response.data.view_id);
         $('#edit_student_no').val(response.data.student_no);
         $('#edit_student_name').val(response.data.student_name);
         $('#edit_course').val(response.data.course);
         $('#edit_school_email').val(response.data.school_email);
         $('#edit_rule_Name').val(response.data.rule_Name);
         $('#edit_description_Name').val(response.data.description_Name);
         $('#edit_severity_Name').val(response.data.severity_Name);
         $('#edit_faculty_involvement').val(response.data.faculty_involvement);  
         $('#edit_faculty_name').val(response.data.faculty_name);
         $('#edit_counseling_required').val(response.data.counseling_required);
         $('#edit_Remarks').val(response.data.Remarks);
         $('#edit_notes').val(response.data.notes);
         $('#Date_Created').text(response.data.Date_Created);
  
         loadViolationDropdown('/get_violations', '#edit_violation_type', response.data.violation_type);
         loadPenaltyDropdown('/get_penalty', '#edit_penalty_type', response.data.penalty_type);
         loadStatusDropdown('/get_status', '#edit_status_type', response.data.status_name);
         loadReferalDropdown('/get_referal', '#edit_referal_type', response.data.referal_type);

        $('input[name="edit_counseling_required"]').each(function () {
            if ($(this).val() === response.data.counseling_required) {
                $(this).prop('checked', true);
            }
        });

        $('input[name="edit_faculty_involvement"]').each(function () {
             if ($(this).val() === response.data.faculty_involvement) {
                 $(this).prop('checked', true);
             }
        });

        if ($('#edit_faculty_yes').is(':checked')) {
            $('#edit_faculty_Name').show().val(response.data.faculty_name);
            $("#editfacultyLabel").show().text('Enter Faculty Name:').addClass('text-dark');        
          }
        else {
            $('#edit_faculty_Name').hide().val('N/A');
            $("#editfacultyLabel").hide().text('Enter Faculty Name:').addClass('text-dark');        
          }

        $('input[name="edit_faculty_involvement"]').change(function () {
            if ($('#edit_faculty_yes').is(':checked')) {
                $('#edit_faculty_Name').show().val(''); 
                $("#editfacultyLabel").show().text('Enter Faculty Name:').addClass('text-dark');            
              } else {
                $('#edit_faculty_Name').hide().val('N/A');
                $("#editfacultyLabel").hide().text('Enter Faculty Name:').addClass('text-dark');            
              }
        });

      }
    }
  });
        $('#editStudentModal').modal('show');
});

// Remove validation error for faculty name dropdown
  $("#edit_faculty_Name").on("change", function () {
      if ($(this).val() !== "") {
          $(this).removeClass("is-invalid");
          $(this).next(".invalid-feedback").remove();
      }
  });

//submit student edit form
  $('#editStudentForm').submit(function(e) {
      e.preventDefault();
      let isValid = true; // Define isValid

      // Remove existing error messages
      $(".invalid-feedback").remove();
      $("#edit_Remarks").removeClass("is-invalid");

      // Validate the Remarks field
      if (!$("#edit_Remarks").val()) {
          $("#edit_Remarks").addClass("is-invalid").after('<div class="invalid-feedback">Please provide remarks.</div>');
          isValid = false;
      }

      // Validate the Faculty Name dropdown if it is visible
      if ($("#edit_faculty_Name").is(":visible") && !$("#edit_faculty_Name").val()) {
          $("#edit_faculty_Name").addClass("is-invalid").after('<div class="invalid-feedback">Please select a faculty name.</div>');
          isValid = false;
      }

      if (!isValid) {
          Swal.fire({ icon: "error", title: "Oops...", text: "Please fill out all required fields before submitting." });
          return;
      }

      var id = $('#edit_student_id').val();

      let facultyName = $('#edit_faculty_yes').is(':checked') ? $("#edit_faculty_Name").val() : "N/A";

      $.ajax({
          type: "POST",
          url: "/update_student_info/" + id,
          data: {
              _token: $('input[name="_token"]').val(),
              update_student_no: $('#edit_student_no').val(),
              update_name: $('#edit_student_name').val(),
              update_course: $('#edit_course').val(),
              update_school_email: $('#edit_school_email').val(),
              update_violation_type: $('#edit_violation_type').val(),
              update_rule_name: $('#edit_rule_Name').val(),
              update_description: $('#edit_description_Name').val(),
              update_severity: $('#edit_severity_Name').val(),
              update_penalty_type: $('#edit_penalty_type').val(),
              update_status: $('#edit_status_type').val(),
              update_faculty_involvement: $('input[name="edit_faculty_involvement"]:checked').val(),
              update_faculty_name: facultyName,
              update_counseling_required: $('input[name="edit_counseling_required"]:checked').val(),
              update_referral_type: $('#edit_referal_type').val(),
              update_remarks: $('#edit_Remarks').val(),
              update_notes: $('#edit_notes').val()
          },
          success: function(response) {

              Swal.fire({
                  title: "Updated Successfully!",
                  text: "The information has been saved.",
                  icon: "success",
                  confirmButtonText: "OK"
              });
              
              if (response.status == 200) {
                  console.log(response.message);
                  $('#editStudentModal').modal('hide');
                  console.log('update success');
              } else {
                  console.log("Error updating student info");
              }

              $('#violationrecordstable').DataTable().ajax.reload(null, false);
          },
          error: function(xhr) {
              console.log(xhr.responseText);
              console.log("An error occurred.");
          }
      });
  });

// Add event listener to remove error messages when the user types in the Remarks textarea
$("#edit_Remarks").on("input", function () {
    $(this).removeClass("is-invalid");
    $(this).next(".invalid-feedback").remove();
});

//archive edit button
$(document).on('click', '.btn-archive-post', function () {
    const postId = $(this).val();

    Swal.fire({
        title: "Are you sure you want to archive this violation?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/violation_records/' + postId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire("Archived!", response.message, "success");
                    $('#violationrecordstable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    Swal.fire("Error", "Something went wrong.", "error");
                }
            });
        }
    });
});

</script>


    

