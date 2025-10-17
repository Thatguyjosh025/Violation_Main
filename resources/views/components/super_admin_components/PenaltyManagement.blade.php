<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
use App\Models\penalties;
$penaltydata = penalties::get();
@endphp

<!-- Header -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Penalty Management</h3>
</div>

<!-- Content -->
<div class="container mt-4">
    <div class="content-section active" id="penalty-type-section">
        <div class="mb-3" style="display: flex; justify-content: end;">
            <button class="btn btn-add btm-md" id="addPenaltyBtn">+ Add</button>
        </div>

        <div class="card-table bg-white">
            <table class="table table-hover mb-0" id="penaltyTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Penalty ID</th>
                        <th scope="col">Penalty UID</th>
                        <th scope="col">Penalty</th>
                        <th scope="col">Visibility</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="penaltybody">
                    @foreach ($penaltydata as $data)
                    <tr>
                        <th scope="row">{{ $data->penalties_id }}</th>
                        <td>{{ $data->penalties_uid }}</td>
                        <td>{{ $data->penalties }}</td>
                        <td>{{ $data->is_visible }}</td>
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

<!-- Penalty Modal -->
<div class="modal fade" id="penaltyModal" tabindex="-1" aria-labelledby="penaltyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="penaltyModalLabel">Add/Edit Penalty</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="penaltyForm" class="mb-4">
                    @csrf
                    <input type="hidden" name="penalties_id" id="penalties_id">

                    <!-- Penalty Name -->
                    <div class="mb-3">
                        <label for="penalties" class="form-label">Penalty:</label>
                        <input type="text" name="penalties" id="penalties" class="form-control" min="5" required>
                        <div class="invalid-feedback">
                            Penalty name should only contain alphanumeric characters and hyphens.
                        </div>
                    </div>

                    <!-- Visibility Dropdown -->
                    <div class="mb-3">
                        <label for="is_visible" class="form-label">Visibility:</label>
                        <select name="is_visible" id="is_visible" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">Save Penalty</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>
$(document).ready(function () {
    $('#penaltyTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true
    });

    // Show Add Modal
    $('#addPenaltyBtn').on('click', function (e) {
        e.preventDefault();
        $("#penalties_id").val("");
        $("#penalties").val("").removeClass("is-invalid");
        $("#is_visible").val("active"); // default
        $('.invalid-feedback').hide();
        $('#penaltyModal').modal('show');
    });

    // Clear error on typing
    $("#penalties").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }
    });

    // Save Penalty (Create or Update)
    $("#penaltyForm").on("submit", function (e) {
        e.preventDefault();

        let penaltyName = $("#penalties").val().trim();
        let visibility = $("#is_visible").val();

        if (penaltyName.length < 5) {
            $("#penalties").addClass("is-invalid");
            $('.invalid-feedback').text("Penalty name must be at least 5 characters long.").show();
            return;
        }

        if (!/^[a-zA-Z0-9\s\/-]+$/.test(penaltyName)) {
            $("#penalties").addClass("is-invalid");
            $('.invalid-feedback').text("Penalty name can only contain letters, numbers, spaces, slashes, and hyphens.").show();
            return;
        } else {
            $("#penalties").removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }

        let url = $("#penalties_id").val()
            ? "/update_penalty/" + $("#penalties_id").val()
            : "/create_penalties";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                penalties: penaltyName,
                is_visible: visibility
            },
            success: function (response) {
                const isEdit = $("#penalties_id").val() !== "";

                if (response.message && response.message.includes("No changes detected")) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Changes',
                        text: 'No changes were made to the penalty.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                $('#penaltybody').load(location.href + " #penaltybody > *");
                const rowCount = $('#intakebody tr').length;

                // If table body is empty after reload, show message manually
                if (rowCount === 0) {
                    $('#intakebody').html(`
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                No pending counseling at the moment.
                            </td>
                        </tr>
                    `);
                }

                Swal.fire({
                    icon: 'success',
                    title: isEdit ? 'Penalty Updated!' : 'Penalty Added!',
                    text: response.message || 'The penalty has been successfully saved.',
                    timer: 2000,
                    showConfirmButton: false
                });

                $("#penalties").val("");
                $("#penalties_id").val("");
                $("#is_visible").val("active");
                $('#penaltyModal').modal('hide');
                $('.modal-backdrop').remove();
            },
            error: function (xhr) {
                let errorMessage = "An error occurred. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.penalties) {
                    errorMessage = xhr.responseJSON.errors.penalties[0];
                }
                $("#penalties").addClass("is-invalid");
                $('.invalid-feedback').text(errorMessage).show();
            }
        });
    });

    // Edit Button
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let penaltyId = row.find("th:eq(0)").text();     
        let penaltyName = row.find("td:eq(1)").text();   
        let visibilityValue = row.find("td:eq(2)").text().trim(); 

        $("#penalties_id").val(penaltyId);
        $("#penalties").val(penaltyName).removeClass("is-invalid");
        $("#is_visible").val(visibilityValue);
        $('.invalid-feedback').hide();
        $('#penaltyModal').modal('show');
        });
    });
</script>
