    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
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

        var nameRegex = /^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .'-][A-Za-zÑñ]+)*$/;
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
                    $('#authbody').load(location.href + " #authbody > *");
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
        // Use event delegation for the edit button
        $(document).on('click', '.btn-edit', function() {
            var userId = $(this).val();
            getuserdata(userId);
        });

        // Function to fetch user data
        function getuserdata(userId) {
            $.ajax({
                url: '/get_user_info',
                type: "GET",
                data: { id: userId },
                success: function(response) {
                    if (response.status === 200) {
                        console.log(response.data);

                        // Populate the edit modal with the user data
                        $('#edituser #userid').val(response.data.id);
                        $('#edituser #firstname').val(response.data.firstname);
                        $('#edituser #lastname').val(response.data.lastname);
                        $('#edituser #middlename').val(response.data.middlename);
                        $('#edituser #suffix').val(response.data.suffix);
                        $('#edituser #email').val(response.data.email);
                        $('#edituser #student_no').val(response.data.student_no);
                        $('#edituser #edit-role').val(response.data.role);
                        $('#edituser #status').val(response.data.status);

                        if (response.data.role === 'student') {
                            $('#studentNoContainer').hide();
                        } else {
                            $('#studentNoContainer').show();
                        }

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
        $(document).on('click', '.btn-update', function() {
            var userId = $('#edituser #userid').val();
            var isValid = true;

            // Remove existing error messages
            $("#edituser .form-control").removeClass("is-invalid");
            $("#edituser .invalid-feedback").remove();

            var nameRegex = /^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .'-][A-Za-zÑñ]+)*$/;
            var emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            var studentNumberRegex = /^(ALA0)[a-zA-Z0-9]{4}$/;

            // Validate fields
            var fieldsToValidate = [
                { id: "#edituser #firstname", regex: nameRegex, message: "Invalid first name format." },
                { id: "#edituser #lastname", regex: nameRegex, message: "Invalid last name format." },
                { id: "#edituser #middlename", regex: nameRegex, message: "Invalid middle name format." },
                { id: "#edituser #email", regex: emailRegex, message: "Invalid email format." },
                { id: "#edituser #edit-role", message: "Role is required." },
                { id: "#edituser #status", message: "Status is required." }
            ];

            var role = $('#edituser #edit-role').val();
            if (role !== 'student') {
                fieldsToValidate.push({ id: "#edituser #student_no", regex: studentNumberRegex, message: "ID number must start with ALA0 and be followed by exactly 4 alphanumeric characters." });
            }

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
                suffix: $('#edituser #suffix').val(),
                email: $('#edituser #email').val(),
                student_no: $('#edituser #student_no').val(),
                role: $('#edituser #edit-role').val(),
                status: $('#edituser #status').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: '/update_user',
                type: "POST",
                data: formData,
               success: function(response) {
                    if (response.status === 200) {
                        Swal.fire({
                            title: "Update successful!",
                            icon: "success",
                            timer: 3000
                        });

                        $('#edituser').modal('hide');
                        $('#authbody').load(location.href + " #authbody > *");
                    } 
                    else if (response.status === 204) {
                        Swal.fire({
                            title: "No changes detected!",
                            text: response.message,
                            icon: "info",
                            timer: 3000
                        });
                    } 
                    else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON.errors;
                    for (var field in response) {
                        var input = $('#edituser [name="' + field + '"]');
                        input.removeClass("is-invalid");
                        input.next('.invalid-feedback').remove();

                        // if (field === 'student_no' && response[field][0] === 'The student no has already been taken') {
                        //     input.addClass("is-invalid").after('<div class="invalid-feedback">This ID number is already in use. Please use a different one.</div>');
                        // } else {
                        //     input.addClass("is-invalid").after('<div class="invalid-feedback">' + response[field][0] + '</div>');
                        // }
                    }
                }
            });
        }
    });

    $(document).ready(function () {
            function cloneTableWithoutActions() {
                let cloned = $('#authTable').clone();
                cloned.find('tr').each(function () {
                    $(this).find('th:last-child, td:last-child').remove(); // remove last column
                });
                return cloned;
            }

            $('#exportCSV').click(function () {
                window.location.href = '/export-users-csv';
            });


            $('#printTable').click(function () {
                // Clone the DataTable and remove the Actions column
                let clonedTable = $('#authTable').clone();

                // Remove the last column (Actions)
                clonedTable.find('tr').each(function () {
                    $(this).find('th:last-child, td:last-child').remove();
                });

                // Remove all DataTable classes and IDs for clean print
                clonedTable.removeClass().removeAttr('id');
                clonedTable.find('*').removeClass();

                // Define CSS for print
                let styles = `
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #000; padding: 8px; font-size: 12px; text-align: left; }
                        th { background-color: #f2f2f2; }
                    </style>
                `;

                // Open new window for print
                let printWindow = window.open('', '', 'width=1000,height=700');
                printWindow.document.write('<html><head><title>Print Violation Records</title>' + styles + '</head><body>');
                printWindow.document.write('<h2>Violation Records</h2>');
                printWindow.document.write(clonedTable.prop('outerHTML'));
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            });
        });

        $('#importCSVBtn').on('click', function() {
            $('#importCSVInput').click();
        });

        $('#importCSVInput').on('change', function() {
            var formData = new FormData($('#importCSVForm')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // add csrf token

            $.ajax({
                url: '/import_users_csv',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({ icon: "success", text: "CSV imported successfully!" });
                    $('#authbody').load(location.href + " #authbody > *");
                },
                error: function(xhr) {
                    Swal.fire({ icon: "error", text: "Failed to import CSV." });
                }
            });
        });