<link rel="stylesheet" href="{{ asset('./css/counseling_css/StudentCounseling.css') }}">
        <!-- Counseling Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Student Counseling Profile</h3>
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
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Student No.">02000911833</td>
                            <td data-label="Name">Mark Jecil M. Bausa</td>
                            <td data-label="Violation">Cheating</td>
                            <td data-label="Status">
                                <span class="badge text-light" style="background-color: green;">Resolved</span>
                            </td>
                            <td data-label="Date">03/04/25</td>
                            <td data-label="Action">
                                <button class="btn btn-sm btn-secondary btn-action-consistent" data-bs-toggle="modal" data-bs-target="#viewModal">
                                <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        <!-- VIEW MODAL -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-custom modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
        <div class="modal-body p-0">
            <div class="report-card position-relative">
            <!-- Close button -->
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            
            <header class="report-title mb-3">
                <h1>Session Record</h1>
                <p>25 Apr 2024, 10:30 AM</p>
            </header>

            <div class="section-title">General Info</div>
            <div class="kv-row"><div class="kv-label">Name</div><div class="kv-value">Mark Jecil Bausa</div></div>
            <div class="kv-row"><div class="kv-label">Violation</div><div class="kv-value">Misbehaviour</div></div>
            <div class="kv-row"><div class="kv-label">Severity</div><div class="kv-value"><span class="badge-severity">Major A</span></div></div>

            <hr class="my-3">

            <div class="section-title">Session Notes</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

            <div class="section-title">Emotional State</div>
            <p><span class="badge-state">Calm</span></p>

            <div class="section-title">Behaviour Observation</div>
            <p>Student was attentive after the initial reminder.</p>

            <div class="section-title">Intervention Plans & Goals</div>
            <p>Encourage positive reinforcement and follow-up check-ins.</p>
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

//   $(document).ready(function () {
//     let table = $('#violationTable').DataTable({
//         responsive: true
//     });

//     $('#statusFilter').on('change', function () {
//         let selected = $(this).val();
//         if (selected) {
//             table.column(4).search('^' + selected + '$', true, false).draw();
//         } else {
//             table.column(4).search('').draw();
//         }
//     });
// });
</script>

