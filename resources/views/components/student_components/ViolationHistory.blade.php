<link rel="stylesheet" href="{{ asset('./css/student_css/ViolationHistory.css') }}">
@php
 use App\Models\postviolation;

 $violationhistory = postviolation::get();
@endphp
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation History</h3>
            </div>

            <div class="container mt-4">
                @foreach ( $violationhistory as $history )
                    @if ($history->student_no === Auth::user()->student_no && $history->status->status === 'Resolved')
                        <!-- Violation Card Template -->
                        <div class="violation-card d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <div class="fw-bold date-text">Date: {{ $history->Date_Created }}</div>
                                <div class="violation-details">Description: {{ $history->description_Name }}</div>
                                <div class="status-details">Status: {{ $history->status->status }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold violation-title fs-5">{{ $history->violation->violations }}</div>
                                <button class="btn view-btn" data-bs-toggle="modal" data-bs-target="#violationModal">View</button>
                            </div>
                        </div>
                    @endif
                @endforeach
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