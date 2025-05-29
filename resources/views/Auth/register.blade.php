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
                        <div class="row g-2 align-items-end">
                            <div class="col-md-9">
                                <label for="middlename" class="form-label">Middle Name (Optional)</label>
                                <input type="text" class="form-control" id="middlename" name="middlename"
                                    pattern="^[A-Za-zÑñ]+(?:[ .'-][A-Za-zÑñ]+)*$"
                                    title="Only letters, spaces, periods, hyphens, and apostrophes are allowed">
                            </div>
                            <div class="col-md-3">
                                <p for="suffix" class="form-label" style="font-size: small;">Suffix (Optional)</p>
                                <select class="form-select" id="suffix" name="suffix">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>
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
                        <label for="course_and_section" class="form-label">Course and Section (Ex: BSIT-611)</label>
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
    $('#toggleUserPassword').on('click', function () {
        const passwordField = $('#userPassword');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#toggleConfirmPassword').on('click', function () {
        const confirmPasswordField = $('#password_confirmation');
        const type = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    const nameRegex = /^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .'-][A-Za-zÑñ]+)*$/;
    const emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const courseSectionRegex = /^[A-Z]{4}-\d{3}$/;
    const studentNumberRegex = /^(0200|1900|1800)\d{7}$/;
    const passwordRegex = /^(?!.*\s)(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

    function formatName(str) {
        return str
            .split(" ")
            .map(part =>
                part.charAt(0).toUpperCase() +
                part.slice(1).toLowerCase()
            )
            .join(" ");
    }

    $("#course_and_section").on("input", function () {
        let val = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, "");
        if (val.length > 4) val = val.slice(0, 4) + "-" + val.slice(4);
        val = val.slice(0, 8);
        $(this).val(val);
    });

    $("#registerModal form").submit(function (e) {
        e.preventDefault();
        const form = $(this);
        let isValid = true;

        // Format names
        ["#firstname", "#lastname", "#middlename"].forEach(function (id) {
            const input = $(id);
            const trimmed = input.val().trim();
            if (trimmed !== "") input.val(formatName(trimmed));
        });

        $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove();

        const fields = [
            { id: "#firstname", regex: nameRegex, msg: "Invalid first name format." },
            { id: "#lastname", regex: nameRegex, msg: "Invalid last name format." },
            { id: "#middlename", regex: nameRegex, msg: "Invalid middle name format." },
            { id: "#email", regex: emailRegex, msg: "Invalid email format." }
        ];

        fields.forEach(field => {
            const input = $(field.id);
            const value = input.val().trim();
            if (field.id === "#middlename" && value === "") return;
            if (value.length < 2) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">Must be at least 2 characters long.</div>');
                isValid = false;
            } else if (!field.regex.test(value)) {
                input.addClass("is-invalid").after(`<div class="invalid-feedback">${field.msg}</div>`);
                isValid = false;
            }
        });

        const password = $("#userPassword").val();
        const confirmPassword = $("#password_confirmation").val();

        if (!passwordRegex.test(password)) {
            $("#userPassword").addClass("is-invalid").after(
                '<div class="invalid-feedback">Password must be at least 8 characters long, include one uppercase letter, one number, one special character, and no spaces.</div>'
            );
            $('#toggleUserPassword').hide();
            isValid = false;
        }

        if (password !== confirmPassword) {
            $("#password_confirmation").addClass("is-invalid").after('<div class="invalid-feedback">Passwords do not match.</div>');
            $('#toggleConfirmPassword').hide();
            isValid = false;
        }

        const studentNumber = $("#student_no").val();
        if (!studentNumberRegex.test(studentNumber)) {
            $("#student_no").addClass("is-invalid").after('<div class="invalid-feedback">Student number must start with "0200", "1900", or "1800" and contain 11 digits.</div>');
            isValid = false;
        }

        const courseSection = $("#course_and_section").val().trim();
        if (!courseSectionRegex.test(courseSection)) {
            $("#course_and_section").addClass("is-invalid").after('<div class="invalid-feedback">Course and Section must be in the format BSIT- followed by numbers, e.g., BSIT-611.</div>');
            isValid = false;
        }

        if (!isValid) return;

        $.ajax({    
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            success: function () {
                $('#registerModal').modal('hide');
                Swal.fire({
                    title: "Registration successful!",
                    icon: "success",
                    timer: 3000
                });

                form[0].reset();    
                $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove();
                $('#toggleUserPassword, #toggleConfirmPassword').show();
            },
            error: function (xhr) {
                const response = xhr.responseJSON.errors;
                for (const field in response) {
                    const input = $('[name="' + field + '"]');
                    input.addClass("is-invalid").after(`<div class="invalid-feedback">${response[field][0]}</div>`);
                }
            }
        });
    });

    $(document).on("input", ".form-control", function () {
        $(this).removeClass("is-invalid").next(".invalid-feedback").remove();
        if (this.id === 'userPassword') $('#toggleUserPassword').show();
        if (this.id === 'password_confirmation') $('#toggleConfirmPassword').show();
    });

    $("#password_confirmation").on("input", function () {
        const match = $(this).val() !== $("#userPassword").val();
        $(this).toggleClass("is-invalid", match);
        $(this).next(".invalid-feedback").remove();
        if (match) {
            $(this).after('<div class="invalid-feedback">Passwords do not match.</div>');
        }
        $('#toggleConfirmPassword').show();
    });

    $('#registerModal').on('hidden.bs.modal', function () {
        const form = $(this).find('form')[0];
        form.reset();
        $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove();
        $('#toggleUserPassword, #toggleConfirmPassword').show();
    });
});
</script>
