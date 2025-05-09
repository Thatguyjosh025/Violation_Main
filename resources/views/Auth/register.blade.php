<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" pattern="^[A-Za-z]+(?:[ .'-][A-Za-z]+)*$" title="Only letters, spaces, periods, hyphens, and apostrophes are allowed">
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" pattern="^[A-Za-z]+(?:[ .'-][A-Za-z]+)*$" title="Only letters, spaces, periods, hyphens, and apostrophes are allowed">
                    </div>
                    <div class="mb-3">
                        <label for="middlename" class="form-label">Middle Name (Optional)</label>
                        <input type="text" class="form-control" id="middlename" name="middlename" pattern="^[A-Za-z]+(?:[ .'-][A-Za-z]+)*$" title="Only letters, spaces, periods, hyphens, and apostrophes are allowed">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address. It should not start with special characters.">
                    </div>
                    <div class="mb-3">
                        <label for="student_no" class="form-label">Student Number:</label>
                        <input type="text" class="form-control" id="student_no" name="student_no" min="11" pattern="[0-9]+" title="Only numbers are allowed" maxlength="11" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="course_and_section" class="form-label">Course and Section:</label>
                        <input type="text" class="form-control" id="course_and_section" name="course_and_section" required>
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
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In here</a></p>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    // start course and section validation
    $("#course_and_section").on("input", function () {
        let val = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, "");

        if (val.length > 4) {
            val = val.slice(0, 4) + "-" + val.slice(4);
        }

        val = val.slice(0, 8); // (max 9 characters)
        $(this).val(val);
    });
    // end course and section validation


    $("#registerModal form").submit(function (e) {
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

        $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove();

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
            $("#password_confirmation").next(".invalid-feedback").remove();
            $('#toggleConfirmPassword').show();
        }

        var studentNumber = $("#student_no").val();
        var studentNumberRegex = /^(0200|1900|1800)\d{7}$/;
        if (!studentNumberRegex.test(studentNumber)) {
            $("#student_no").addClass("is-invalid").after('<div class="invalid-feedback">Student number must start with "0200", "1900", or "1800".</div>');
            isValid = false;
        }

        var passwordRegex = /^(?!.*\s)(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
        if (!passwordRegex.test(password)) {
            $("#userPassword").addClass("is-invalid").next(".invalid-feedback").remove(); 
            $("#userPassword").after('<div class="invalid-feedback">Password must be at least 8 characters long, include at least one uppercase letter, one number, one special character, and should not contain spaces.</div>');
            $('#toggleUserPassword').hide();
            isValid = false;
        } else {
            $("#userPassword").removeClass("is-invalid");
            $("#userPassword").next(".invalid-feedback").remove();
            $('#toggleUserPassword').show();
        }

        if (!isValid) return;

        // Send the form data via AJAX
        $.ajax({
            url: "/register",
            type: "POST",
            data: form.serialize(),
            success: function () {
                Swal.fire({
                    title: "Registration successful!",
                    icon: "success",
                    timer: 3000
                });

                $("#registerModal form")[0].reset();
                $("#registerModal").modal("hide");
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
        $(this).removeClass("is-invalid").next(".invalid-feedback").remove();
        if ($(this).attr('id') === 'userPassword') {
            $('#toggleUserPassword').show();
        }
        if ($(this).attr('id') === 'password_confirmation') {
            $('#toggleConfirmPassword').show();
        }
    });

    $("#password_confirmation").on("input", function () {
        $(this).toggleClass("is-invalid", $(this).val() !== $("#userPassword").val());
        $(this).next(".invalid-feedback").remove();
        if ($(this).hasClass("is-invalid")) {
            $(this).after('<div class="invalid-feedback">Passwords do not match.</div>');
        }
        $('#toggleConfirmPassword').show();
    });

    $('#registerModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove();
        $('#toggleUserPassword').show();
    });
});
</script>
