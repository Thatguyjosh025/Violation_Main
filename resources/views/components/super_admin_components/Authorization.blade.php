    <link rel="stylesheet" href="{{ asset('css/super_admin_css/Authorization.css') }}">
    <link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">


    @php
    use App\Models\users;

    $userdata = users::get();
    @endphp

    <!-- Dashboard Content -->
    <div class="d-flex align-items-center">
        <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <h3 class="mb-0">Authorization</h3>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Left side: Export and Print buttons -->
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" id="exportCSV">Export CSV</button>
                <button class="btn btn-sm btn-secondary" id="printTable">Print</button>
                <form id="importCSVForm" enctype="multipart/form-data">
                <input type="file" id="importCSVInput" name="csv_file" accept=".csv" style="display:none;">
            </form>
                <button type="button" class="btn btn-sm btn-success" id="importCSVBtn">Import CSV</button>
            </div>

            <!-- This Button is for consulting whether to remove or keep since all the accounts comes from microsoft 
                and Super Admin will be ENV Credentials -->            
                <!-- <button class="btn btn-action w-auto" type="button" data-bs-toggle="modal" data-bs-target="#adduser">
                    + Add
                </button> -->
            <!-- END BUTTON CONCERN -->



        </div>

        <div class="table-container mt-3">
        <!-- Controls (Dropdown & Search Input) -->
        <div class="d-flex gap-2 mb-2">
        <!-- <select class="form-select" id="entriesPerPage" style="width: 5rem;">
                <option value="1">1</option>
                <option value="5">5</option>
                <option value="10">10</option>
            </select>
            <input type="text" class="form-control" id="searchInput" placeholder="Search" style="max-width: 300px;"> -->
        </div>

        <!-- Table -->
        <div class="table-responsive-sm overflow-hidden">
            <table class="table table-hover table-mobile-wrap" id="authTable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Student No.</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="authbody">
                    @foreach ($userdata as $data)
                        <tr>
                            <th scope="row">{{ $data->id }}</th>
                            <td>{{ $data->firstname }}</td>
                            <td>{{ $data->lastname }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->student_no }}</td>
                            <td>{{ $data->role }}</td>
                            <td>{{ $data->status }}</td>
                            <td> 
                                <button class="btn btn-primary btn-edit" value="{{ $data->id }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modal for add user -->
    <div class="modal fade" id="adduser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- This Button is for consulting whether to remove or keep since all the accounts comes from microsoft 
                 and Super Admin will be ENV Credentials -->

                    <!-- <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Add user</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> -->

                <!-- END BUTTON CONCERN -->
                 
                <div class="modal-body">
                    <form id="userForm" action="{{ url('add_user') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                        <div class="mb-3">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="student_no" class="form-label">ID Number:</label>
                            <input type="text" class="form-control" id="student_no" name="student_no" min="11" maxlength="11" >
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" name="role" id="role">
                                <option value="" hidden>Select Role</option>
                                <option value="discipline">Discipline</option>
                                <option value="faculty">Faculty</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password:</label>
                            <div class="input-group" style="position: relative;">
                                <input type="password" class="form-control" id="userPassword" name="password"  style="border-radius: 6px; padding-right: 40px;">
                                <span id="toggleUserPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <div class="invalid-feedback" style="margin-top: 5px;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password:</label>
                            <div class="input-group" style="position: relative;">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" style="border-radius: 6px;">
                                <span id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for edit -->
    <div class="modal fade" id="edituser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Edit user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ url('update_user') }}" method="POST">
                        @csrf
                        <input type="hidden" id="userid">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                        <div class="mb-3">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3" id="studentNoContainer">
                            <label for="student_no" class="form-label">ID Number:</label>
                            <input type="text" class="form-control" id="student_no" name="student_no" min="8" maxlength="8" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" name="edit-role" id="edit-role">
                                <option value="" hidden>Select role</option>
                                <option value="student">Student</option>
                                <option value="discipline">Discipline</option>
                                <option value="faculty">Faculty</option>
                                <option value="counselor">Counselor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="" hidden>Select status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-primary btn-update" value="{{ $data->id }}" style="width: 100%;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./js/super_admin_js/auth_module.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    $(document).ready(function() {
        $('#authTable').DataTable({
            "paging": true,       
            "searching": true,   
            "ordering": true,    
            "info": true,       
            "responsive": true,   
        });
    });
</script>
