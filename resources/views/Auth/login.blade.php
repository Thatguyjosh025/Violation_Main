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
                            <label for="login_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="login_username" name="username" placeholder="Enter your username">
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
$(document).ready(function () {

    // ---- Password Toggle ----
    $('#togglePassword').hide();

    $('#login_password').on('input', function() {
        $(this).val().length > 0 ? $('#togglePassword').show() : $('#togglePassword').hide();
    });

    $('#togglePassword').on('click', function() {
        var passwordField = $('#login_password');
        var icon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // ---- Login Function ----
    function login() {
        event.preventDefault();

        // Clear errors first
        $('#login_username, #login_password').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        var username = $('#login_username').val();
        var password = $('#login_password').val();
        var _token = $('input[name="_token"]').val();

        // Required validation
        if (username === '' || password === '') {
            if (username === '') {
                $('#login_username').addClass('is-invalid').after('<div class="invalid-feedback">Username is required.</div>');
            }
            if (password === '') {
                $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Password is required.</div>');
            }
            return;
        }

        $("#loginButton").prop("disabled", true).text("Logging in...");

        $.ajax({
            url: "{{ url('login') }}",
            type: 'POST',
            data: {
                username: username,
                password: password,
                _token: _token
            },
            success: function (response) {

                $("#loginButton").prop("disabled", false).text("Login");

                if (response.success) {

                    // Clear fields
                    $('#login_username').val('');
                    $('#login_password').val('');

                    // Redirect by role
                    switch (response.role) {
                        case 'discipline': window.location.href = "{{ url('discipline_dashboard') }}"; break;
                        case 'super': window.location.href = "{{ url('super_dashboard') }}"; break;
                        case 'student': window.location.href = "{{ url('student_dashboard') }}"; break;
                        case 'faculty': window.location.href = "{{ url('faculty_dashboard') }}"; break;
                        case 'counselor': window.location.href = "{{ url('counseling_dashboard') }}"; break;
                        case 'head': window.location.href = "{{ url('academic_head_dashboard') }}"; break;
                    }

                } else {
                    // Validation errors
                    if (response.errors) {

                        $('#login_password').val('');

                        if (response.errors.username) {
                            $('#login_username').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.username + '</div>');
                        }
                        if (response.errors.password) {
                            $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">' + response.errors.password + '</div>');
                        }

                    } else {
                        // General error
                        $('#login_username').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                        $('#login_password').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                    }
                }
            },
            error: function () {

                $("#loginButton").prop("disabled", false).text("Login");

                $('#login_username').val('').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
                $('#login_password').val('').addClass('is-invalid').after('<div class="invalid-feedback">Invalid credentials.</div>');
            }
        });
    }

    // Button click login
    $('#loginButton').click(function (e) {
        login();
    });

    // Enter key login
    $(document).keypress(function (e) {
        if (e.which == 13) {
            login();
        }
    });

});
</script>
