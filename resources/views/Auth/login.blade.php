 <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close align-self-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h2 class="central-title mb-3">
                        <div class="central-container">
                            <img src="/Photos/central.png" alt="">
                        </div>
                    </h2>
                    <form>
                        <div class="button-container">
                            <div class="mb-1 text-center">
                                <a href="{{ route('microsoft.auth') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" style="gap: 8px;">
                                    <i class="fab fa-microsoft"></i>
                                    <span>Login with Microsoft</span>
                                </a>
                            </div>
                            <div class="divider">
                                <span>or</span>
                            </div>
                            <button type="button" class="btnSignIn-Admin" data-bs-toggle="modal" data-bs-target="#AdminModal">
                                <i class="bi bi-shield-lock"></i>Login as Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Admin Login Modal-->
  <div class="modal fade" id="AdminModal" tabindex="-1" aria-labelledby="AdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close align-self-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h2 class="central-title mb-3">
                        <div class="central-container">
                            <img src="/Photos/central.png" alt="">
                        </div>
                    </h2>
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
                        <button type="button" id="loginButton" class="btn btn-primary w-100">Login</button>
                        <button type="button" class="btnBack" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-arrow-left"></i> Back
                            </button>
                    </form>
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
         $("#loginButton")
        .prop("disabled", true)
        .text("Logging in...");


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

                $("#loginButton")
                .prop("disabled", false)
                .text("Login");


                if (response.success) {

                $('#login_email').val('');
                $('#login_password').val('');

                    // Redirect based on role
                    if (response.role == 'discipline') {
                        window.location.href = "{{ url('discipline_dashboard') }}";
                    } else if (response.role == 'super') {
                        window.location.href = "{{ url('super_dashboard') }}";
                    } else if (response.role == 'student') {
                        window.location.href = "{{ url('student_dashboard') }}";
                    } else if (response.role == 'faculty') {
                        window.location.href = "{{ url('faculty_dashboard') }}";
                    } else if (response.role == 'counselor') {
                        window.location.href = "{{ url('counseling_dashboard') }}";
                    } else if (response.role == 'head') {
                        window.location.href = "{{ url('academic_head_dashboard') }}";
                    }
                } else {
                    // If the response has validation errors
                    if (response.errors) {
                        $("#loginButton")
                        .prop("disabled", false)
                        .text("Login");

                        $('#login_email').val('');
                        $('#login_password').val('');

                        if (response.errors.email) {
                            $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.email + '</div>');
                        }
                        if (response.errors.password) {
                            $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.password + '</div>');
                        }
                    } else {
                        $("#loginButton")
                        .prop("disabled", false)
                        .text("Login");
                        // General invalid credentials error
                        $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                        $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                    }
                }
            },
            error: function (xhr) {
                $("#loginButton")
                .prop("disabled", false)
                .text("Login");

                $('#login_email').val('');
                $('#login_password').val('');
                
                // Handle server error
                $('#login_email').removeClass('is-invalid').next('.invalid-feedback').remove();
                $('#login_email').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                $('#login_password').removeClass('is-invalid').next('.invalid-feedback').remove();
                $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
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
