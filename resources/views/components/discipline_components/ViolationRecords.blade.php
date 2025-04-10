<link rel="stylesheet" href="{{ asset('./css/discipline_css/ViolationRecords.css') }}">
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation Records</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <!-- Violation Records Section -->
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                <select id="statusFilter" class="sort-dropdown">
                    <option value="All" selected>Show All</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Resolved">Resolved</option>
                </select>
            </div>
    
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Violation</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="violationTable">
                        <tr>
                            <td data-label="Student No.">02000911833</td>
                            <td data-label="Name">Mark Jecil M. Bausa</td>
                            <td data-label="Email">markjecil@gmail.com</td>
                            <td data-label="Violation">Violation Code 4032</td>
                            <td data-label="Status"><span class="badge-ongoing">Ongoing</span></td>
                            <td data-label="Date">03/04/25</td>
                        </tr>
                        <tr>
                            <td data-label="Student No.">02000911834</td>
                            <td data-label="Name">Jane Doe</td>
                            <td data-label="Email">janedoe@gmail.com</td>
                            <td data-label="Violation">Violation Code 5012</td>
                            <td data-label="Status"><span class="badge-resolved">Resolved</span></td>
                            <td data-label="Date">03/07/25</td>
                        </tr>
                        <tr>
                            <td data-label="Student No.">02000911835</td>
                            <td data-label="Name">John Smith</td>
                            <td data-label="Email">johnsmith@gmail.com</td>
                            <td data-label="Violation">Violation Code 6073</td>
                            <td data-label="Status"><span class="badge-ongoing">Ongoing</span></td>
                            <td data-label="Date">03/15/25</td>
                        </tr>
                        <tr>
                            <td data-label="Student No.">02000911836</td>
                            <td data-label="Name">Emily Davis</td>
                            <td data-label="Email">emilyd@gmail.com</td>
                            <td data-label="Violation">Violation Code 3056</td>
                            <td data-label="Status"><span class="badge-resolved">Resolved</span></td>
                            <td data-label="Date">03/04/25</td>
                        </tr>
                        <tr>
                            <td data-label="Student No.">02000911837</td>
                            <td data-label="Name">Michael Brown</td>
                            <td data-label="Email">michaelb@gmail.com</td>
                            <td data-label="Violation">Violation Code 2098</td>
                            <td data-label="Status"><span class="badge-ongoing">Ongoing</span></td>
                            <td data-label="Date">03/07/25</td>
                        </tr>
                    </tbody>
                </table>
            </div>
