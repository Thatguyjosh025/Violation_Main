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
                            <label for="middlename" class="form-label">Middle Name:</label>
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
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
</div>

<script src="{{asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
 $(document).ready(function () {

    var nameRegex = /^(Ma\.|[A-Za-z]+)(?:[ .'-][A-Za-z]+)*$/;
    var emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    $("#registerModal form").submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this);
        var isValid = true;

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
            if (!field.regex.test(input.val().trim())) {
                input.addClass("is-invalid").after('<div class="invalid-feedback">' + field.msg + '</div>');
                isValid = false;
            }
        });

        var password = $("#password").val();
        var confirmPassword = $("#password_confirmation").val();

        // Password match validation
        if (password !== confirmPassword) {
            $("#password_confirmation").addClass("is-invalid").after('<div class="invalid-feedback">Passwords do not match.</div>');
            isValid = false;
        }

        // Student number validation detects the first 4 digits
        var studentNumber = $("#student_no").val();
        var studentNumberRegex = /^(0200|1900|1800)\d{7}$/;
        if (!studentNumberRegex.test(studentNumber)) {
            $("#student_no").addClass("is-invalid").after('<div class="invalid-feedback">Student number must start with "0200", "1900", or "1800".</div>');
            isValid = false;
        }

        // Password strength validation
        var passwordRegex = /^(?!\d{8}$)(?!admin123$)(?!admin$)(?!12345678$)(?!123456789$)(?!1234567890$)(?!user1234$).{8,}$/;
        if (!passwordRegex.test(password)) {
            $("#password").addClass("is-invalid").after('<div class="invalid-feedback">Password is invalid. Please choose a more secure password.</div>');
            isValid = false;
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
    });

    // Password confirmation check
    $("#password_confirmation").on("input", function () {
        $(this).toggleClass("is-invalid", $(this).val() !== $("#password").val());
        $(this).next(".invalid-feedback").remove();
        if ($(this).hasClass("is-invalid")) {
            $(this).after('<div class="invalid-feedback">Passwords do not match.</div>');
        }
    });

    // Reset the form when the modal is closed
    $('#registerModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Reset form fields
        $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove(); // Remove error messages
    });
});


</script>
