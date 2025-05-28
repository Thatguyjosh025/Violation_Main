<link rel="stylesheet" href="{{ asset('css/student_css/ViolationTracking.css') }}">
@php
use App\Models\rules;

$ruleinfos = rules::get();
@endphp
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Active Violation</h3>
</div>

<div class="container mt-4" id="activeViolationCards">
    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3" id="violation-cards">
        <!-- Cards will be inserted here dynamically -->
    </div>
</div>

<!-- Modal Section -->
<div class="modal fade" id="appealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Violation Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-dark"><strong>Offense/s:</strong> <span id="offense"></span></p>
                <p class="text text-dark"><strong>Rule:</strong> <a href="#" id="ruleLink"></a></p>
                <p class="text text-dark" id="detailsDescription"></p>
                <hr>
                <p class="text text-dark"><strong>Severity:</strong> <span id="severity"></span></p>
                <p class="text text-dark"><strong>Penalty:</strong> <span id="penalty"></span></p>
                <p class="text text-dark"><strong>Action Taken:</strong> <span id="actionTaken"></span></p>
                <p class="text text-dark"><strong>Status:</strong> <span id="status"></span></p>
                <hr>
                <p class="text text-dark"><strong>Message:</strong></p>
                <p class="text text-dark" id="message"></p>
                <hr>
                <!-- Appeal Section -->
                <div id="appealSection">
                    <p class="text text-dark"><strong>Appeal your case?</strong></p>
                    <input type="radio" id="appealYes" name="appeal" value="Yes">
                    <label for="appealYes" class="text text-dark">Yes</label><br>
                    <input type="radio" id="appealNo" name="appeal" value="No">
                    <label for="appealNo" class="text text-dark">No</label>

                    <div id="appealFormContainer" style="display: none;">
                        <form action="" id="appealform">
                            <textarea id="appealReason" class="form-control mt-2" maxlength="200" style="height: 100px; resize: none;" placeholder="Enter your reason for appeal..."></textarea>
                            <button type="button" id="submitAppeal" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                </div>
                <!-- End Appeal Section -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/student_js/ViolationHistory.js') }}"></script>

<script>
$(document).ready(function () {

    // Load and Display Violation Cards
    $.ajax({
        url: '/get_violations_records',
        method: 'GET',
        success: function (data) {
            let cardsContainer = $('#violation-cards');
            cardsContainer.empty();

            const unresolvedViolations = data.filter(v => v.status !== 'Resolved');

            if (unresolvedViolations.length === 0) {
                cardsContainer.append(`
                    <div class="alignment" style="justify-content: center; width: 100%;">
                        <div class="col-12 text-center mt-5" style="justify-content: center;">
                            <p>No active violations found.</p>
                        </div>
                    </div>
                `);
            } else {
                unresolvedViolations.forEach(function (violation) {
                    let cardHtml = `
                        <div class="col">
                            <div class="card p-3">
                                <h5>${violation.type}</h5>
                                <p><small>Status: ${violation.status}</small></p>
                                <p><small>Date: ${violation.date}</small></p>
                                <div class="d-grid gap-2 d-md-flex">
                                    <button class="btn btn-light view-btn" data-bs-toggle="modal" data-bs-target="#appealModal"
                                            data-violation='${JSON.stringify(violation)}'>View</button>
                                </div>
                            </div>
                        </div>
                    `;
                    cardsContainer.append(cardHtml);
                // $("#activeViolationCards").load(location.href + " #activeViolationCards > *");
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching violations:', error);
        }
    });

    // View Button Click Event
    $(document).on('click', '.view-btn', function () {
        let violation = $(this).data('violation');
        $('#offense').text(violation.type || 'N/A');
        $('#ruleLink').text(violation.rule_Name).attr('href', '/violation_handbook#' + violation.section_Id); // Use the section_Id from the response
        $('#detailsDescription').text(violation.description_Name);
        $('#severity').text(violation.severity_Name);
        $('#penalty').text(violation.penalties || 'N/A');
        $('#actionTaken').text(violation.referals || 'N/A');
        $('#message').text(violation.Remarks);
        $('#status').text(violation.status || 'N/A');
        console.log('Hello world');
        
        // Store data for appeal submit
        $('#appealModal').data('studentId', violation.id);
        $('#appealModal').data('studentName', violation.student_name);


        if (violation.appeal === 'N/A' || violation.appeal === 'Warning') {
            $('#appealSection').show();
        } else {
            $('#appealSection').hide();
        }

        // Reset appeal section state
        $('#appealFormContainer').hide();
        $('#appealReason').show();
        $('#submitAppeal').show();
        $('input[name="appeal"]').prop('checked', false);
    });

    
    $('input[name="appeal"]').change(function () {
        if ($('#appealYes').is(':checked')) {
            $('#appealFormContainer').show();
            $('#appealReason').show();
            $('#submitAppeal').show();
            $('#appealReason').val('');
        } else if ($('#appealNo').is(':checked')) {
            $('#appealFormContainer').show();
            $('#appealReason').hide();
            $('#submitAppeal').show();
            $('#appealReason').val('No Objection');
        }
    });

    // Submit appeal event
    $('#submitAppeal').click(function () {
        let appealReason = $('#appealReason').val();
        let studentId = $('#appealModal').data('studentId');
        let studentName = $('#appealModal').data('studentName');

        $.ajax({
            url: '/update_appeal_reason',
            method: 'POST',
            data: {
                appealReason: appealReason,
                studentId: studentId,
                studentName: studentName,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    $('#appealform').trigger('reset');
                    $('#appealSection').hide(); 
                    $('#appealModal').modal('hide');

                    Swal.fire({
                        icon: "success",
                        text: "Appeal submitted successfully!",
                        timer: 5000
                    });
                } else {
                    console.log('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error updating appeal reason:', error);
            }
        });
    });
});

</script>

