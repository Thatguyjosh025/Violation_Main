<link rel="stylesheet" href="{{ asset('./css/student_css/ViolationHistory.css') }}">
@php
 use App\Models\postviolation;

 $violationhistory = postviolation::get();

  $filteredHistory = $violationhistory->filter(function ($history) {
        return $history->student_no === Auth::user()->student_no && $history->status->status === 'Resolved';
    });
@endphp
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation History</h3>
            </div>

            <div class="container mt-4">
                @if ($filteredHistory->isEmpty())
                    <div class="alignment" style="justify-content: center; width: 100%;">
                        <div class="col-12 text-center mt-5" style="justify-content: center;">
                            <p>No violation history found.</p>
                        </div>
                    </div>
                @else
                    @foreach ($filteredHistory as $history) 
                        <!-- Violation Card Template -->
                        <div class="col mt">
                            <div class="card p-3" style="background: #2c698d; color: white;">
                                <h5>{{ $history->violation->violations }}</h5>
                                <p>Status: {{ $history->status->status }}</p>
                                <p>Date: {{ $history->Date_Created }}</p>
                                <div class="d-grid gap-2 d-md-flex" >
                                    <button class="btn btn-light view-btn" data-bs-toggle="modal" data-bs-target="#violationModal"
                                            data-violation='@json($history)'>View</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- Modal Section -->
            <div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="violationModalLabel">Violation Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Offense/s:</strong> Improper Uniform</p>
                            <p><strong>Details:</strong> <a href="#">Student Uniform</a></p>
                            <p>Certain programs, courses, or activities require a different set of uniforms. Only STI issued or endorsed uniforms are allowed.</p>
                            <hr>
                            <p><strong>Severity:</strong> Minor Offense</p>
                            <p><strong>Penalty:</strong> Verbal/Oral Warning</p>
                            <p><strong>Action Taken:</strong> Consulted DO/GA</p>
                            <hr>
                            <p><strong>Message:</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor.</p>
                            <hr>
                            <p><strong>Apppeal</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor.</p>
                        </div>
                    </div>
                </div>
            </div>

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

            const resolvedViolations = data.filter(v => v.status === 'Resolved');

            if (resolvedViolations.length === 0) {
                cardsContainer.append(`
                    <div class="alignment" style="justify-content: center; width: 100%;">
                        <div class="col-12 text-center mt-5" style="justify-content: center;">
                            <p>No violation history found.</p>
                        </div>
                    </div>
                `);
            } else {
                resolvedViolations.forEach(function(violation) {
                    let cardHtml = `
                        <div class="col mt-2">
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
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching violations:', error);
        }
    });
});
</script>