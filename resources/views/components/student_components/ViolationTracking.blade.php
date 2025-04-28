<link rel="stylesheet" href="{{ asset('./css/student_css/ViolationTracking.css') }}">
@php
use App\Models\postviolation;

$info = postviolation::get();
@endphp
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Active Violation</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <div class="container mt-4">
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3" id="violation-cards">
                    <!-- Cards will be inserted here -->
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
                            <!-- Inside your modal-body -->
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
                                <p class="text text-dark"><strong>Appeal your case?</strong></p>
                                <div>
                                    <input type="radio" id="appealYes" name="appeal" value="Yes">
                                    <label for="appealYes" class="text text-dark">Yes</label>
                                    <br>
                                    <input type="radio" id="appealNo" name="appeal" value="No">
                                    <label for="appealNo" class="text text-dark">No</label>
                                </div>
                                <div id="appealSection" style="display: none;">
                                    <form action="" id="appealform">
                                        <textarea id="appealReason" class="form-control mt-2" maxlength="200" style="height: 100px; resize: none;" placeholder="Enter your reason for appeal..."></textarea>
                                        <button type="button" id="submitAppeal" class="btn btn-primary mt-2">Submit</button>
                                    </form>
                            </div>
                        </div>
                    </div>
<script src="{{ asset('./js/student_js/ViolationHistory.js  ') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
   $(document).ready(function() {
    $.ajax({
        url: '/get_violations_records',
        method: 'GET',
        success: function(data) {
            let cardsContainer = $('#violation-cards');
            cardsContainer.empty();

            data.forEach(function(violation) {
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
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching violations:', error);
        }
    });
});

$(document).on('click', '.view-btn', function() {
    let violation = $(this).data('violation');
    $('#offense').text(violation.type || 'N/A');
    $('#ruleLink').text(violation.rule_Name).attr('href', violation.rule_Name);
    $('#detailsDescription').text(violation.description_Name);
    $('#severity').text(violation.severity_Name);
    $('#penalty').text(violation.penalties || 'N/A');
    $('#actionTaken').text(violation.referals || 'N/A');
    $('#message').text(violation.Remarks);
    $('#status').text(violation.status || 'N/A');

    $('#appealModal').data('studentId', violation.id);
    $('#appealModal').data('studentName', violation.student_name);
});

$(document).ready(function() {
    if ($('#appealYes').is(':checked')) {
        $('#appealSection').show();
        $('#appealReason').val('');
    } else {
        $('#appealSection').hide();
        $('#appealReason').val('N/A');
    }

    // Event listener for radio button change
    $('input[name="appeal"]').change(function() {
        if ($('#appealYes').is(':checked')) {
            $('#appealSection').show();
            $('#appealReason').val('');
        } else {
            $('#appealSection').hide();
            $('#appealReason').val('N/A');
        }
    });
});

$(document).ready(function() {
    $('#submitAppeal').click(function() {
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
            success: function(response) {
                if (response.success) {
                    console.log('Appeal reason updated successfully!');
                    $('#appealform').trigger('reset');
                    $('#appealModal').modal('hide');
                } else {
                    console.log('Error: ' + response.message);
                }
                Swal.fire({
                    icon: "success",
                    text: "Appeal submitted successfully!",
                    timer: 5000
                });
            },
            error: function(xhr, status, error) {
                console.error('Error updating appeal reason:', error);
            }
        });
    });
});

</script>