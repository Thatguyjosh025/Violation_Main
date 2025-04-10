<link rel="stylesheet" href="{{ asset('./css/discipline_css/IncidentReport.css') }}">
<div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Incident Report</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <div class="container mt-5">
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-3">
                    <div class="col">
                        <div class="card p-3 position-relative">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="mb-0">Improper Uniform</h5>
                                <span class="badge badge-minor">Minor</span>
                            </div>
                            <p>Mark Jecil Bausa <br> 0200067751</p>
                            <p><small>Submitted by: Keith Izzam Magante</small></p>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#violationModal" style="background: #376881; color: white; width: fit-content;">Review</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Modal Violation Process (View) -->
            <div class="modal fade" id="violationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Violation Process</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Name of violator:</strong> Mark Jecil Bausa</p>
                            <p><strong>Student No:</strong> 02000311488</p>
                            <p><strong>Section:</strong> BSIT 611</p>
                            <p><strong>School email:</strong> mark@gmail.com</p>
                            <hr>
                            <p><strong>Reason/s for Referral:</strong> Cheating</p>
                            <p><strong>Details:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <p><strong>Severity of Offense/s:</strong> Minor</p>
                            <p><strong>Submitted by:</strong> Keith Izzam Magante</p>
                            <p><strong>Remarks:</strong> The student was caught looking at a classmate’s answers.</p>
                            <p><strong>Evidence/s:</strong> <span class="fw-bold">N/A</span></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#violationProcess">Approve</button>
                            <button class="btn btn-danger">Reject</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Violation Process (Write) -->
            <div class="modal fade" id="violationProcess" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="background-color: #2c698d; color: white; padding: 15px; border-radius: 10px;">
                        <div class="modal-header border-0">
                            <h4 class="modal-title fw-bold" id="violationModalLabel">Violation Process</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Name of violator:</strong> Mark Jecil Bausa</p>
                            <p><strong>Student No:</strong> 02000311488</p>
                            <p><strong>Section:</strong> BSIT 611</p>
                            <p><strong>School email:</strong> mark@gmail.com</p>
                            <hr>
                            <p><strong>Reason/s for Referral:</strong> Cheating</p>
                            <p><strong>Details:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <p><strong>Severity of Offense/s:</strong> Minor</p>
                            <p><strong>Submitted by:</strong> Keith Izzam Magante</p>
                            <hr>
                           
                            <!-- Referral Dropdown Section -->
                            <label class="fw-bold mb-1">Action Taken Prior to Referral</label>
                            <select class="form-select" aria-label="Default select example" aria-placeholder="Select penalty...">
                                <option selected hidden>Select penalty...</option>
                                <option value="Verbal/Oral Warning">Verbal Reprimand</option>
                                <option value="Community Service">Held conference with the student</option>
                                <option value="Suspension">Consulted DO/GA</option>
                                <option value="Expulsion">Contacted Parents</option>
                                <option value="Expulsion">Changed Seat</option>
                                <option value="Expulsion">Held Conference with the Parent</option>
                            </select>
                            <!-- End of Dropdown Section -->
                         
        
                            <!-- Penalty Dropdown Section -->
                            <label class="fw-bold mt-2">Penalty</label>
                            <select class="form-select" aria-label="Default select example" aria-placeholder="Select penalty...">
                                <option selected hidden>Select penalty...</option>
                                <option value="Verbal/Oral Warning">Verbal/Oral Warning</option>
                                <option value="Community Service">Community Service</option>
                                <option value="Suspension">Suspension</option>
                                <option value="Expulsion">Expulsion</option>
                              </select>
                            <!-- End of Dropdown -->

                            <div>
                                <label class="fw-bold">Counseling Required?</label><br>
                                <div>
                                    <input type="radio" name="counseling" value="yes" id="counselingYes">
                                    <label for="counselingYes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="counseling" value="no" id="counselingNo">
                                    <label for="counselingNo">No</label>
                                </div>
                            </div>
        
                            <label class="fw-bold mt-2">Others</label>
                            <textarea style="width: 100%; max-height: 100px; min-height: 40px;" placeholder="Please specify..."></textarea>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-md-end">
                            <button class="btn submit-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
