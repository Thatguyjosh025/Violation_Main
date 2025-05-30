<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
use App\Models\violation;
$violationdata = violation::get();
@endphp

    <!-- CONTINUE BUILDING THIS APPLY THIS TO THE REST OF SUPER ADMIN -->
    <!-- Violation -->
    <div class="d-flex align-items-center">
        <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <h3 class="mb-0">Violation Management</h3>
    </div>

    <!-- Content Sections -->
    <div class="container mt-4">
        <!-- Violation Type Section -->
        <div class="content-section active" id="violation-type-section">
            <div class="mb-3" style="display: flex; justify-content: end;">
                <button class="btn btn-add btn-md" id="addViolationBtn">+ Add</button>
            </div>
            <div class="card-table bg-white">
                <table class="table table-hover mb-0" id="violationTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Violation ID</th>
                            <th scope="col">Violation</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="violationbody">
                    @foreach ($violationdata as $data)
                        <tr>
                            <th scope="row">{{ $data->violation_id }}</th>
                            <td contenteditable="false">{{ $data->violations }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Violation Modal -->
    <div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="violationModalLabel">Add/Edit Violation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="violationForm" class="mb-4">
                        @csrf
                        <input type="hidden" name="violation_id" id="violation_id">
                        <div class="mb-3">
                            <label for="violations" class="form-label">Violation:</label>
                            <input type="text" name="violations" id="violations" class="form-control" required>
                            <div class="invalid-feedback">
                                Violation name should only contain alphabetic characters and spaces.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Violation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#violationTable').DataTable({
        "paging": true,       
        "searching": true,   
        "ordering": true,    
        "info": true,       
        "responsive": true   
    });
});
$(document).ready(function () {
    // Show modal for adding a violation
    $('#addViolationBtn').on('click', function (e) {
        e.preventDefault();
        $("#violation_id").val("");
        $("#violations").val("").removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#violationModal').modal('show');
    });

    $("#violations").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }
    });

    // Add/Edit Violation
    $("#violationForm").on("submit", function (e) {
        e.preventDefault();

        let violationName = $("#violations").val().trim();

        if (violationName.length < 5) {
            $("#violations").addClass("is-invalid");
            $('.invalid-feedback').text("Violation name must be at least 5 characters long.").show();
            return;
        } else if (!/^[A-Za-z ]+$/.test(violationName)) {
            $("#violations").addClass("is-invalid");
            $('.invalid-feedback').text("Violation name should only contain alphabetic characters and spaces.").show();
            return;
        } else {
            $("#violations").removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }

        let url = $("#violation_id").val() ? "/update_violation/" + $("#violation_id").val() : "/create_violation";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                violations: violationName
            },
            success: function (response) {
                console.log(response.message);
                $('#violationbody').load(location.href + " #violationbody > *");
                $("#violations").val("");
                $("#violation_id").val("");
                $('#violationModal').modal('hide');
                $('.modal-backdrop').remove();
            },
            error: function (xhr) {
                let errorMessage = "An error occurred. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.violations) {
                    errorMessage = xhr.responseJSON.errors.violations[0];
                }
                $("#violations").addClass("is-invalid");
                $('.invalid-feedback').text(errorMessage).show();
            }
        });
    });

    // Edit Violation
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let violationCell = row.find("td:eq(0)");  // Correctly selects the violation name cell
        let violationId = row.find("th").text();

        $("#violation_id").val(violationId);
        $("#violations").val(violationCell.text()).removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#violationModal').modal('show');
    });
});
</script>
