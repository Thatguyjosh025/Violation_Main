<link rel="stylesheet" href="{{ asset('./css/counseling_css/ReferralIntake.css') }}">
<!-- Module Section -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Referral & Intake</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            
            <!-- Table Contents -->
            <div class="table-container">
                
                <table id="violationTable" class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Email</th>
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
                            <td data-label="Email">markjecil@gmail.com</td>
                            <td data-label="Violation">Cheating</td>
                            <td data-label="Status">
                                <span class="badge bg-warning text-dark">Pending</span>
                            </td>
                            <td data-label="Date">03/04/25</td>
                            <td data-label="Action">
                                <button class="btn btn-sm btn-secondary btn-action-consistent" data-bs-toggle="modal" data-bs-target="#CounselingReport">
                                <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Modal Content -->
             <div class="modal fade" id="CounselingReport" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-3" id="modalBox">
                        <div class="modal-header">
                            <h3 class="modal-title">Counseling Report</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div id="reportView">
                        <div class="field"><label>Name:</label> Mark Jecil Bausa</div>
                        <div class="field"><label>Violation:</label> Misbehaviour</div>
                        <div class="field"><label>Severity:</label> Major A</div>
                        <div class="field"><label>Remarks:</label> Lorem ipsum dolor sit amet.</div>
                        
                        <div class="btns">
                            <div>
                                <button class="btn approve bi bi-check-lg" onclick="expandModal()"> Approve</button>
                            <button class="btn reject bi bi-x-lg" onclick="closeModal()"> Reject</button>
                            </div>
                        </div>
                        </div>

                        <!-- Expanded Content -->
                        <div id="scheduleView" class="hidden">
                        <div class="approved-label">Approved</div>
                        <h4>Schedule a Counseling Session</h4> 
                        <div class="field">
                            <label>Date</label>
                            <input type="date" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <div class="field">
                            <label>Start Time</label>
                            <input type="time" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <div class="field">
                            <label>End Time</label>
                            <input type="time" class="form-control rounded-pill px-3 py-2">
                        </div>
                        <button class="btn schedule" onclick="closeModal()">Schedule</button>
                        </div>
                    </div>
                </div>
            </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

//  $(document).ready(function () {
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

//important
function openModal() {
    document.getElementById("CounselingReport").style.display = "flex";
  }
function closeModal() {
    document.getElementById("CounselingReport").style.display = "none";
    document.getElementById("modalBox").classList.remove("expanded");
    document.getElementById("reportView").style.display = "block";
    document.getElementById("scheduleView").classList.remove("show");
}
function expandModal() {
    document.getElementById("modalBox").classList.add("expanded");
    document.getElementById("scheduleView").classList.add("show");
    document.querySelector(".btns").style.display = "none";
}
</script>
