<link rel="stylesheet" href="{{ asset('css/super_admin_css/Authorization.css') }}">
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
    <div class="add-btn" style="display: flex; justify-content: end;">
        <button class="btn btn-action w-auto" type="button" data-bs-toggle="modal" data-bs-target="#adduser">
            + Add
        </button>
    </div>
    <div class="table-container mt-3">
        <!-- Table -->
        <div class="table-responsive-sm overflow-hidden">
            <table class="table table-hover align-middle text-center table-mobile-wrap" id="authTable">
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
                <tbody>
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
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Add user</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
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
                        <label for="middlename" class="form-label">Middle Name (Optional)</label>
                        <input type="text" class="form-control" id="middlename">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="student_no" class="form-label">ID Number:</label>
                        <input type="text" class="form-control" id="student_no" name="student_no" min="11" maxlength="11" required>
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
                            <input type="password" class="form-control" id="userPassword" name="password" required style="border-radius: 6px; padding-right: 40px;">
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
                        <label for="middlename" class="form-label">Middle Name (Optional)</label>
                        <input type="text" class="form-control" id="middlename">
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

                    <!-- <div class="mb-3">
                        <label for="userPassword" class="form-label">Password:</label>
                        <div class="input-group" style="position: relative;">
                            <input type="password" class="form-control" id="userPassword" name="password" required style="border-radius: 6px; padding-right: 40px;">
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
                    </div> -->
                    <button type="button" class="btn btn-primary btn-update" value="{{ $data->id }}" style="width: 100%;">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
$(document).ready(function () {
    // Toggle visibility of password when the eye icon is clicked
    $('#toggleUserPassword').on('click', function () {
        var passwordField = $('#userPassword');
        var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#toggleConfirmPassword').on('click', function () {
        var confirmPasswordField = $('#password_confirmation');
        var type = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    var nameRegex = /^(ma\.|Ma\.|[A-Za-z]+)(?:[ .'-][A-Za-z]+)*$/;
    var emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    function capitalizeName(name) {
        return name.replace(/\b\w/g, char => char.toUpperCase());
    }

    $("#userForm").submit(function (e) {
        console.log("Form submitted"); // Debug statement
        e.preventDefault(); // Prevent default form submission

        var form = $(this);
        var isValid = true;

        // Start Capitalize name fields before validation
        ["#firstname", "#lastname", "#middlename"].forEach(function(id) {
            var input = $(id).val().trim();
            if (input !== "") {
                var capitalized = capitalizeName(input);
                $(id).val(capitalized);
            }
        });
        // End Capitalize name fields before validation

        // Remove existing error messages
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        // Validation for fields
        var fields = [
            { id: "#firstname", regex: nameRegex, msg: "Invalid first name format." },
            { id: "#lastname", regex: nameRegex, msg: "Invalid last name format." },
            { id: "#middlename", regex: nameRegex, msg: "Invalid middle name format." },
            { id: "#email", regex: emailRegex, msg: "Invalid email format." }
        ];

        $.each(fields, function(index, field) {
            var input = $(field.id);
            var value = input.val().trim();

            if (field.id === "#middlename" && value === "") {
                return; // Skip validation if middle name is empty
            }

            if (value.length < 2) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">Must be at least 2 characters long.</div>');
                isValid = false;
            } else if (!field.regex.test(value)) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">' + field.msg + '</div>');
                isValid = false;
            }
        });

        var password = $("#userPassword").val();
        var confirmPassword = $("#password_confirmation").val();

        if (password !== confirmPassword) {
            $("#password_confirmation").addClass("is-invalid").after('<div class="invalid-feedback">Passwords do not match.</div>');
            $('#toggleConfirmPassword').hide();
            isValid = false;
        } else {
            $("#password_confirmation").removeClass("is-invalid");
            $('#toggleConfirmPassword').show();
        }

        var studentNumber = $("#student_no").val();
        var studentNumberRegex = /^(ALA0)[a-zA-Z0-9]{4}$/;
        if (!studentNumberRegex.test(studentNumber)) {
            $("#student_no").addClass("is-invalid").after('<div class="invalid-feedback">ID number must start with "ALA0" and be followed by exactly 4 alphanumeric characters.</div>');
            isValid = false;
        }

        var passwordRegex = /^(?!.*\s)(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
        if (!passwordRegex.test(password)) {
            $("#userPassword").addClass("is-invalid").after('<div class="invalid-feedback">Password must be at least 8 characters long, include at least one uppercase letter, one number, one special character, and should not contain spaces.</div>');
            $('#toggleUserPassword').hide();
            isValid = false;
        } else {
            $("#userPassword").removeClass("is-invalid");
            $('#toggleUserPassword').show();
        }

        if (!isValid) return;

        // Send the form data via AJAX
        $.ajax({
            url: '/add_user',
            type: "POST",
            data: form.serialize(),
            success: function () {

                Swal.fire({
                    title: "Registration successful!",
                    icon: "success",
                    timer: 3000
                });

                form[0].reset();
                $("#authTable").load(location.href + " #authTable");
                $('#adduser').modal('hide');
                $(".form-control").removeClass("is-invalid");
                $(".invalid-feedback").remove();
                $('#toggleUserPassword, #toggleConfirmPassword').show();
            },
            error: function (xhr) {
                var response = xhr.responseJSON.errors;
                for (var field in response) {
                    var input = $('[name="' + field + '"]');
                    input.addClass("is-invalid").after('<div class="invalid-feedback">' + response[field][0] + '</div>');
                }
            }
        });
    });

    // Remove error messages when typing
    $(document).on("input", ".form-control", function () {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
        if ($(this).attr('id') === 'userPassword') {
            $('#toggleUserPassword').show();
        }
        if ($(this).attr('id') === 'password_confirmation') {
            $('#toggleConfirmPassword').show();
        }
    });

    // Remove error messages when a selection is made in the dropdown
    $("#role").on("change", function () {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
    });

    $("#password_confirmation").on("input", function () {
        $(this).toggleClass("is-invalid", $(this).val() !== $("#userPassword").val());
        $(this).next(".invalid-feedback").remove();
        if ($(this).hasClass("is-invalid")) {
            $(this).after('<div class="invalid-feedback">Passwords do not match.</div>');
        }
        $('#toggleConfirmPassword').show();
    });

    $('#adduser').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").remove();
        $('#toggleUserPassword').show();
    });
});

$(document).ready(function() {
    $('.btn-edit').on('click', function() {
        var userId = $(this).val(); // Get the user ID from the button's value attribute
        getuserdata(userId); // Call the function to fetch user data
    });

    $('#edituser .btn-close').on('click', function() {
        $('#edituser').find('form')[0].reset();
        $('#edituser').find(".form-control").removeClass("is-invalid");
        $('#edituser').find(".invalid-feedback").remove();
    });

    // $(document).on('click', '.btn-edit', function() {
    //     var userId = $(this).val();
    //     getuserdata(userId);
    // });

    // Function to fetch user data
    function getuserdata(userId) {
        $.ajax({
            url: '/get_user_info',
            type: "GET",
            data: { id: userId },
            success: function(response) {
                if (response.status === 200) {
                    console.log(response.data); // Log the fetched data to the console

                    // Populate the edit modal with the user data
                    $('#edituser #userid').val(response.data.id); // Ensure the user ID is set here
                    $('#edituser #firstname').val(response.data.firstname);
                    $('#edituser #lastname').val(response.data.lastname);
                    $('#edituser #middlename').val(response.data.middlename);
                    $('#edituser #email').val(response.data.email);
                    $('#edituser #student_no').val(response.data.student_no);
                    $('#edituser #edit-role').val(response.data.role);
                    $('#edituser #status').val(response.data.status);

                    // Inside the getuserdata function, after setting the role value
                    $('#edituser #edit-role').val(response.data.role);

                    // Check the role and hide the ID Number field if the role is "student"
                    if (response.data.role === 'student') {
                        $('#studentNoContainer').hide();
                    } else {
                        $('#studentNoContainer').show();
                    }

                    // Show the edit modal
                    $('#edituser').modal('show');


                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error fetching user data');
            }
        });
    }

    // Update logic
    $('.btn-update').on('click', function() {
        var userId = $('#edituser #userid').val();
        var isValid = true;

        // Remove existing error messages
        $("#edituser .form-control").removeClass("is-invalid");
        $("#edituser .invalid-feedback").remove();

        var nameRegex = /^(ma\.|Ma\.|[A-Za-z]+)(?:[ .'-][A-Za-z]+)*$/;
        var emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var studentNumberRegex = /^(ALA0)[a-zA-Z0-9]{4}$/;

        // Validate fields
        var fieldsToValidate = [
            { id: "#edituser #firstname", regex: nameRegex, message: "Invalid first name format." },
            { id: "#edituser #lastname", regex: nameRegex, message: "Invalid last name format." },
            { id: "#edituser #middlename", regex: nameRegex, message: "Invalid middle name format." },
            { id: "#edituser #email", regex: emailRegex, message: "Invalid email format." },
            { id: "#edituser #student_no", regex: studentNumberRegex, message: "ID number must start with ALA0 and be followed by exactly 4 alphanumeric characters." },
            { id: "#edituser #edit-role", message: "Role is required." },
            { id: "#edituser #status", message: "Status is required." }
        ];

        fieldsToValidate.forEach(function(field) {
            var input = $(field.id);
            var value = input.val().trim();

            if (field.id === "#edituser #middlename" && value === "") {
                return; // Skip validation if middle name is empty
            }

            if (value.length < 2) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">Must be at least 2 characters long.</div>');
                isValid = false;
            } else if (field.regex && !field.regex.test(value)) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">' + field.message + '</div>');
                isValid = false;
            } else if (!field.regex && !value) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">' + field.message + '</div>');
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please fill out all required fields before submitting." });
            return;
        }

        updateuserdata(userId);
    });

    function updateuserdata(userId) {
        var formData = {
            id: userId,
            firstname: $('#edituser #firstname').val(),
            lastname: $('#edituser #lastname').val(),
            middlename: $('#edituser #middlename').val(),
            email: $('#edituser #email').val(),
            student_no: $('#edituser #student_no').val(),
            role: $('#edituser #edit-role').val(),
            status: $('#edituser #status').val(),
            _token: '{{ csrf_token() }}'
        };

        // Send the form data via AJAX
        $.ajax({
            url: '/update_user',
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.status === 200) {
                    // Show success message
                    Swal.fire({
                        title: "Update successful!",
                        icon: "success",
                        timer: 3000
                    });

                    // Hide the modal
                    $('#edituser').modal('hide');

                    // Refresh the table or update the specific row
                    $("#authTable").load(location.href + " #authTable");
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON.errors;
                for (var field in response) {
                    var input = $('#edituser [name="' + field + '"]');

                    input.removeClass("is-invalid");
                    input.next('.invalid-feedback').remove();

                     // Customize the error message 
                    if (field === 'student_no' && response[field][0] === 'The student no has already been taken.') {
                        input.addClass("is-invalid").after('<div class="invalid-feedback">This ID number is already in use. Please use a different one.</div>');
                    } else {
                        input.addClass("is-invalid").after('<div class="invalid-feedback">' + response[field][0] + '</div>');
                    }
                }
            }
        });
    }
});
</script>
