<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
<link rel="stylesheet" href="{{ asset('./vendor/dataTables.dataTables.min.css') }}">

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
                        <th scope="row" data-label="Penalty ID">{{ $data->penalties_id }}</th>
                        <td data-label="Penalty UID">{{ $data->penalties_uid }}</td>
                        <td data-label="Penalty">{{ $data->penalties }}</td>
                        <td data-label="Visibility">{{ $data->is_visible }}</td>
                        <td data-label="Action">
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
                <h5 class="modal-title" id="penaltyModalLabel">Add/Edit Penalty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="penaltyForm" class="mb-4" autocomplete="off">
                    @csrf
                    <input type="hidden" name="penalties_id" id="penalties_id">

                    <!-- Penalty Name -->
                    <div class="mb-3">
                        <label for="penalties" class="form-label required">Penalty:</label>
                        <!-- use minlength instead of min for text -->
                        <input type="text" name="penalties" id="penalties" class="form-control" minlength="5" required>
                        <div class="invalid-feedback">
                            Penalty name should only contain letters, numbers, spaces, hyphens (-) and slashes (/). Minimum 5 characters.
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
                    <button type="submit" class="btn btn-primary" id="btnsubmit" >Save Penalty</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/dataTables.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    function initPenaltyTable() {
        if ($.fn.DataTable.isDataTable('#penaltyTable')) {
            $('#penaltyTable').DataTable().destroy();
        }
        $('#penaltyTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
        });
    }

    initPenaltyTable();

    function resetModalForAdd() {
        $("#penalties_id").val("");
        $("#penalties").val("").removeClass("is-invalid");
        $("#is_visible").val("active");
        $('.invalid-feedback').hide();
        // remove any leftover backdrops and ensure modal class state clean
        $('.modal-backdrop').remove();
    }

    // Show Add Modal
    $('#addPenaltyBtn').on('click', function (e) {
        e.preventDefault();
        resetModalForAdd();
        $('#penaltyModal').modal('show');
        // focus after shown
        setTimeout(function(){ $('#penalties').focus(); }, 200);
    });

    // Clear error on typing
    $("#penalties").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $(this).next('.invalid-feedback').hide();
        }
    });


    const penaltyRegex = /^(?![-\/\s])(?!.*[-\/\s]$)(?!.*[-\/]{2,})(?!.*\s{2,})[A-Za-z]+(?:[ \/-][A-Za-z]+)*$/;

    function reloadPenaltyTableFragment() {
        // Destroy DataTable before replacing content
        if ($.fn.DataTable.isDataTable('#penaltyTable')) {
            $('#penaltyTable').DataTable().destroy();
        }

        $('#penaltybody').load(location.href + " #penaltybody > *", function(response, status, xhr) {
            if (status === "error") {
                console.error("Error reloading penalty body:", xhr.statusText);
                return;
            }
            // After replacing markup, re-init table
            initPenaltyTable();
        });
    }

$("#penaltyForm").on("submit", function (e) {
    e.preventDefault();

    let penaltyName = $("#penalties").val().trim();
    let visibility = $("#is_visible").val();

    if (penaltyName.length < 5) {
        $("#penalties").addClass("is-invalid");
        $('.invalid-feedback').text("Penalty name must be at least 5 characters long.").show();
        return;
    }

    if (!penaltyRegex.test(penaltyName)) {
        $("#penalties").addClass("is-invalid");
        $('.invalid-feedback').text("Penalty name may only contain letters, single spaces, hyphens (-), and slashes (/). It cannot start or end with a special character, and it cannot contain consecutive special characters or double spaces.").show();
        return;
    } else {
        $("#penalties").removeClass("is-invalid");
        $('.invalid-feedback').hide();
    }

    let url = $("#penalties_id").val()
        ? "/update_penalty/" + $("#penalties_id").val()
        : "/create_penalties";

    $("#btnsubmit")
    .prop("disabled", true)
    .text("Saving penalty...");

    Swal.fire({
        title: 'Saving...',
        text: 'Please wait while we process your request.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

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
                $("#btnsubmit")
                .prop("disabled", false)
                .text("Save penalty");
                return;
            }

            reloadPenaltyTableFragment();

            Swal.fire({
                icon: 'success',
                title: isEdit ? 'Penalty Updated!' : 'Penalty Added!',
                text: response.message || 'The penalty has been successfully saved.',
                timer: 1600,
                showConfirmButton: false
            });

                $("#btnsubmit")
                .prop("disabled", false)
                .text("Save penalty");

            resetModalForAdd();
            $('#penaltyModal').modal('hide');
        },
        error: function (xhr) {
            let errorMessage = "An error occurred. Please try again.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.penalties) {
                errorMessage = xhr.responseJSON.errors.penalties[0];
            }
              $("#btnsubmit")
                .prop("disabled", false)
                .text("Save penalty");
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
            });
            $("#penalties").addClass("is-invalid");
            $('.invalid-feedback').text(errorMessage).show();
        }
    });
});

    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");


        let penaltyId = row.find("th:eq(0)").text().trim();
        let penaltyName = row.find("td:eq(1)").text().trim();
        let visibilityValue = row.find("td:eq(2)").text().trim();

        // normalize visibility to lowercase to match option values (active/inactive)
        if (visibilityValue) {
            visibilityValue = visibilityValue.toLowerCase();
        } else {
            visibilityValue = "active";
        }

        $("#penalties_id").val(penaltyId);
        $("#penalties").val(penaltyName).removeClass("is-invalid");
        $("#is_visible").val(visibilityValue);
        $('.invalid-feedback').hide();

        $('#penaltyModal').modal('show');

        setTimeout(function(){ $('#penalties').focus(); }, 200);
    });

    $('#penaltyModal').on('hidden.bs.modal', function () {
        resetModalForAdd();
    });

}); // end document.ready
</script>
