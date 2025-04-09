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
                            <input type="text" class="form-control" id="firstname" name="firstname" pattern="[A-Za-z]+" title="Only letters are allowed" >
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" pattern="[A-Za-z]+" title="Only letters are allowed" >
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" >
                        </div>
                        <div class="mb-3">
                            <label for="student_no" class="form-label">Student Number:</label>
                            <input type="text" class="form-control" id="student_no" name="student_no" min="11" pattern="[0-9]+" title="Only numbers are allowed" maxlength="11" >
                        </div>
                        <div class="mb-3">
                            <label for="course_and_section" class="form-label">Course and Section:</label>
                            <input type="text" class="form-control" id="course_and_section" name="course_and_section" >
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" >
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
        $("#registerModal").find("form").submit(function (e) {
            e.preventDefault(); 

            let form = $(this);
            $(".form-control").removeClass("is-invalid").next(".invalid-feedback").remove(); 

            $.ajax({
                url: "{{ url('register') }}",
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
                    let response = xhr.responseJSON.errors; 
                    for (let field in response) {
                        let input = $(`[name="${field}"]`);
                        input.addClass("is-invalid").after(`<div class="invalid-feedback">${response[field][0]}</div>`);
                    }
                }
            });
        });

        // Remove error messages when typing thanks to github
        $(document).on("input", ".form-control", function () {
            $(this).removeClass("is-invalid").next(".invalid-feedback").remove();
        });

        $("#password_confirmation").on("input", function () {
            $(this).toggleClass("is-invalid", $(this).val() !== $("#password").val());
            $(this).next(".invalid-feedback").remove();
            if ($(this).hasClass("is-invalid")) {
                $(this).after('<div class="invalid-feedback">Passwords do not match.</div>');
            }
        });
    });
</script>
