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
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-secondary" id="printTable">Print Table</button>
                <button class="btn btn-sm btn-primary" id="exportCSV">Export Backup CSV</button>        
                <button type="button" class="btn btn-sm btn-success" id="importCSVBtn">Import Backup CSV</button>
                <!-- <form id="importCSVForm" enctype="multipart/form-data">
                    <input type="file" id="importCSVInput" name="csv_file" accept=".csv" style="display:none;">
                </form> -->
                <button type="button" class="btn btn-sm btn-danger" id="insert_graduates">
                    Import list of graduates
                </button>
            </div>
        </div>

        <div class="table-container mt-3">
        <!-- Table -->
        <div class="table-responsive-sm overflow-hidden">
            <table class="table table-hover table-mobile-wrap" id="authTable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th data-label="firstname" scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="authbody">
                    @foreach ($userdata as $data)
                        <tr>
                            <th scope="row" data-label="ID">{{ $data->id }}</th>
                            <td data-label="First Name">{{ $data->firstname }}</td>
                            <td data-label="Last Name">{{ $data->lastname }}</td>
                            <td data-label="Email">{{ $data->email }}</td>
                            <td data-label="Role">{{ $data->role }}</td>
                            <td data-label="Status">{{ $data->status }}</td>
                            <td data-label="Actions">
                                @if($data->role !== 'super')
                                    <button class="btn btn-primary btn-edit" value="{{ $data->id }}">Edit</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modal for add user -->
    <!-- <div class="modal fade" id="adduser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                 
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
    </div> -->

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
                        <div class="mb-3" style="display: none;">
                            <label for="firstname" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="mb-3" style="display: none;">
                            <label for="lastname" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
    
                        <div class="mb-3" style="display: none;">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3" id="studentNoContainer" style="display: none;">
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
                                <option value="head">Head</option>
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

    <!-- Modal: Upload Graduates CSV -->
    <div class="modal fade" id="graduatesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Graduates CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="graduatesForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" name="file" id="graduatesFile"
                                class="form-control" accept=".csv" required>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="uploadGraduatesBtn">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Import Backup CSV -->
    <div class="modal fade" id="importBackupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Backup CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="importBackupForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" id="backupCSVFile" class="form-control" accept=".csv" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="importBackupBtn">Upload</button>
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

    $('#insert_graduates').on('click', function () {
        $('#graduatesModal').modal('show');
    });

    $('#uploadGraduatesBtn').on('click', function () {
        let formData = new FormData(document.getElementById('graduatesForm'));

        // Show SweetAlert loading
        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while we process your file.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $("#uploadGraduatesBtn")
        .prop("disabled", true)
        .text("Uploading...");

        $.ajax({
            url: '/deactivate-graduates',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function(response) {

                Swal.close(); // close loader

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000
                });

            $("#uploadGraduatesBtn")
            .prop("disabled", false)
            .text("Upload");

                $('#authbody').load(location.href + " #authbody > *");
                $('#graduatesFile').val('');
                $('#graduatesModal').modal('hide');
            },

            error: function(xhr) {

                Swal.close(); // close loader

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'The csv file field is required.'
                });
            $("#uploadGraduatesBtn")
            .prop("disabled", false)
            .text("Upload");
            }
        });
    });
</script>
