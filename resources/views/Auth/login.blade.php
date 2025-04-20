<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close align-self-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="text-center mb-3">Sign In</h2>
                <p class="text-center text-muted" style="font-size: 15px;">Please fill in this form to sign in</p>

                <form id="loginForm" method="POST" action="{{ url('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="login_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="login_email" name="email" placeholder="Enter your email" required>
                        <div id="emailError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login_password" name="password" placeholder="Enter your password" required>
                        <div id="passwordError" class="invalid-feedback"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="#" class="text-decoration-none" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#ForgotModal">Forgot Password?</a>
                    </div>
                    <button type="button" id="loginButton" class="btn btn-primary w-100 ">Sign In</button>
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
   $(document).ready(function () {
    function login() {
        event.preventDefault();

        // Clear previous errors
        $('#login_email').removeClass('is-invalid');
        $('#login_password').removeClass('is-invalid');
        $('#emailError').text('');
        $('#passwordError').text('');

        var email = $('#login_email').val();
        var password = $('#login_password').val();
        var _token = $('input[name="_token"]').val();

        if (email == '' || password == '') {
            if (email == '') {
                $('#login_email').addClass('is-invalid');
                $('#emailError').text('Email is required.');
            }
            if (password == '') {
                $('#login_password').addClass('is-invalid');
                $('#passwordError').text('Password is required.');
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
                if (response.success) {
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
                    if (response.errors) {
                        if (response.errors.email) {
                            $('#login_email').addClass('is-invalid');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "The provided credentials do not match our records.",
                            });
                        }
                        if (response.errors.password) {
                            $('#login_password').addClass('is-invalid');
                            $('#passwordError').text(response.errors.password);
                        }
                    } else {
                        console.log(response.message);
                    }
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });
    }

    $('#loginButton').click(function (e) {
        login();
    });

    $(document).keypress(function (e) {
        if (e.which == 13) { 
            login();
        }
    });
});

</script>
