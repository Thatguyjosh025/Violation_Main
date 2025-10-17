<link rel="stylesheet" href="{{ asset('./css/counseling_css/SessionManagement.css') }}">
@php
    use App\Models\counseling;
    $counselingsessions = counseling::get();
@endphp
<!-- Counseling Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Session Management</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            
            <!-- Table Contents -->
            <div class="table-container">
                
                <table id="violationTable" class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Violation</th>
                            <th>Status</th>
                            <th>Date Schedule</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Student No.">02000911833</td>
                            <td data-label="Name">Mark Jecil M. Bausa</td>
                            <td data-label="Violation">Cheating</td>
                            <td data-label="Status">
                                <span class="badge bg-warning text-dark">Pending Intake</span>
                            </td>
                            <td data-label="Date">03/04/25</td>
                            <td data-label="Start Time">1:30 PM</td>
                            <td data-label="End Time">2:00 PM</td>
                            <td data-label="Action">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary me-1 btn-action-consistent" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-secondary btn-action-consistent" data-bs-toggle="modal" data-bs-target="#viewModal">
                                    <i class="bi bi-eye"></i> View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
    <!-- EDIT MODAL -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-custom modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
        <div class="modal-body p-0">
            <div class="report-card position-relative">
            <!-- Close button -->
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>

            <form id="sessionForm" novalidate>
                <header class="report-title mb-3">
                <h1>Session Report</h1>
                <p>05 Oct 2025, 2:30 PM</p>
                </header>

                <div class="section-title">General Info</div>
                <div class="kv-row"><div class="kv-label">Name</div><div class="kv-value">Mark Jecil Bausa</div></div>
                <div class="kv-row"><div class="kv-label">Violation</div><div class="kv-value">Misbehaviour</div></div>
                <div class="kv-row"><div class="kv-label">Severity</div><div class="kv-value"><span class="badge-severity">Major A</span></div></div>

                <hr class="my-3">

                <div class="mb-4">
                <div class="section-title">Session Notes</div>
                <textarea class="form-control" placeholder="Write session notes..."></textarea>
                </div>

                <div class="row gx-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Emotional State</label>
                    <input class="form-control" placeholder="e.g., Calm, Agitated">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Behaviour Observation</label>
                    <input class="form-control" placeholder="Short observation summary">
                </div>
                </div>

                <div class="mb-4">
                <label class="form-label fw-semibold section-title">Intervention Plans & Goals</label>
                <textarea class="form-control" placeholder="Define interventions, steps, and goals"></textarea>
                </div>

                <div class="save-wrap">
                <button type="submit" class="btn btn-save">Save</button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
//soon scripts keep this
</script>
