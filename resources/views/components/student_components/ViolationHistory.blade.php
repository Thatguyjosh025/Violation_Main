<link rel="stylesheet" href="{{ asset('./css/student_css/ViolationHistory.css') }}">
@php
    use App\Models\postviolation;
    // APPLY PAGINATION (3 per page)
    $violationhistory = postviolation::where('student_no', Auth::user()->student_no)
    ->whereHas('status', function($q){
        $q->whereIn('status', ['Resolved', 'Appeal Denied','Appeal Approved']);
    })
    ->paginate(4);
@endphp

<div class="d-flex align-items-center mb-4">
    <button class="toggle-btn me-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Violation History</h3>
</div>

<div class="container mt-4">
     @if ($violationhistory->isEmpty())
        <div class="alignment" style="justify-content: center; width: 100%;">
            <div class="col-12 text-center mt-5" style="justify-content: center;">
                <p>No violation history found.</p>
            </div>
        </div>
    @else

        {{-- LOOP PAGINATED ITEMS --}}
        @foreach ($violationhistory as $history)
            <div class="col mt">
                <div class="card p-3" style="background: #2c698d; color: white; margin-bottom: 0.8rem;">
                    <h5>{{ $history->violation->violations }}</h5>
                    <p>Status: {{ $history->status->status }}</p>
                    <p>Date: {{ $history->Date_Created }}</p>

                    <div class="d-grid gap-2 d-md-flex">
                        <button class="btn btn-light view-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#violationModal"
                                data-violation='@json($history)'>
                            View
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-center mt-4">
            {{ $violationhistory->links('pagination::bootstrap-4') }}
        </div>
        </div>
        <!-- PAGINATION -->
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
                <p><strong>Offense/s:</strong> <span id="offense"></span></p>
                <p><strong>Details:</strong> <span id="detailsDescription"></span></p>
                <hr>
                <p><strong>Severity:</strong> <span id="severity"></span></p>
                <hr>
                <p><strong>Message:</strong> <span id="message"></span></p>
                <hr>
                <p><strong>Appeal:</strong> <span id="appealview"></span></p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.view-btn', function () {
        let violation = $(this).data('violation');
        $('#offense').text(violation.violation?.violations || 'N/A');
        $('#detailsDescription').html(violation.description_Name?.replace(/\n/g, '<br>') || 'N/A');
        $('#severity').text(violation.severity_Name || 'N/A');
        $('#message').text(violation.Remarks || 'N/A');
        $('#appealview').text(violation.appeal || 'N/A');
    });
});
</script>
