<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close align-self-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="text-center mb-3">Login</h2>
                <p class="text-center text-muted" style="font-size: 15px;">Please fill in this form to sign in</p>

                <form id="loginForm" method="POST" action="{{ url('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="login_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="login_email" name="email" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <div class="input-group" style="position: relative;">
                        <input type="password" class="form-control" id="login_password" name="password" placeholder="Enter your password" style="border-radius: 6px;">
                        <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div style="text-align:center; margin-top:100px;">
                        <a href="{{ route('microsoft.auth') }}" class="login-btn">Login with Microsoft</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="#" class="text-decoration-none" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#">Forgot Password?</a>
                    </div>
                    <button type="button" id="loginButton" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3">
                    Don't have an Account?
                    <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Sign Up here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 $(document).ready(function() {
        // Initially, the eye icon should be hidden
        $('#togglePassword').hide();

        // Show the eye icon when typing starts
        $('#login_password').on('input', function() {
            if ($(this).val().length > 0) {
                $('#togglePassword').show();
            } else {
                $('#togglePassword').hide();
            }
        });

        // Toggle the password visibility when clicking on the eye icon
        $('#togglePassword').on('click', function() {
            var passwordField = $('#login_password');
            var icon = $(this).find('i');

            // Toggle between 'password' and 'text' input types
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
   
$(document).ready(function () {
    function login() {
        event.preventDefault();

        // Clear previous errors
        $('#login_email').removeClass('is-invalid').next('.invalid-feedback').remove();
        $('#login_password').removeClass('is-invalid').next('.invalid-feedback').remove();

        var email = $('#login_email').val();
        var password = $('#login_password').val();
        var _token = $('input[name="_token"]').val();

        if (email == '' || password == '') {
            if (email == '') {
                $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">Email is required.</div>');
            }
            if (password == '') {
                $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Password is required.</div>');
            }
            return false;
        }

        // AJAX request to login
        $.ajax({
            url: "{{ url('login') }}",
            type: 'POST',
            data: {
                email: email,
                password: password,
                _token: _token
            },
            success: function (response) {
                // Clear previous errors again
                $('#login_email').removeClass('is-invalid').next('.invalid-feedback').remove();
                $('#login_password').removeClass('is-invalid').next('.invalid-feedback').remove();

                if (response.success) {
                    // Redirect based on role
                    if (response.role == 'discipline') {
                        window.location.href = "{{ url('discipline_dashboard') }}";
                    } else if (response.role == 'super') {
                        window.location.href = "{{ url('super_dashboard') }}";
                    } else if (response.role == 'student') {
                        window.location.href = "{{ url('student_dashboard') }}";
                    } else if (response.role == 'faculty') {
                        window.location.href = "{{ url('faculty_dashboard') }}";
                    }
                } else {
                    // If the response has validation errors
                    if (response.errors) {
                        if (response.errors.email) {
                            $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.email + '</div>');
                        }
                        if (response.errors.password) {
                            $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.password + '</div>');
                        }
                    } else {
                        // General invalid credentials error
                        $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                        $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                    }
                }
            },
            error: function (xhr) {
                // Handle server error
                $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">Something went wrong. Please try again later.</div>');
                $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Something went wrong. Please try again later.</div>');
            }
        });
    }

// Trigger login function on button click
    $('#loginButton').click(function (e) {
        login();
    });

    // Trigger login on pressing the Enter key
    $(document).keypress(function (e) {
        if (e.which == 13) { 
            login();
        }
    });

    // Reset the form when the modal is closed
    $('#loginModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();  // Reset form fields
        $('#login_email').removeClass('is-invalid').next('.invalid-feedback').remove(); // Remove error classes and messages
        $('#login_password').removeClass('is-invalid').next('.invalid-feedback').remove();
    });
});
</script>
