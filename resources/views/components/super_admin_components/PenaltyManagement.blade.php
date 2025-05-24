<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
@php
use App\Models\penalties;

$penaltydata = penalties::get();
@endphp

<!-- Penalty -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Penalty Management</h3>
</div>

<!-- Content Sections -->
<div class="container mt-4">
    <!-- Penalty Type Section -->
    <div class="content-section active" id="penalty-type-section">
        <div class="mb-3" style="display: flex; justify-content: end;">
            <button class="btn btn-add btm-md" id="addPenaltyBtn">+ Add</button>
        </div>

        <div class="card-table bg-white">
            <table class="table table-hover mb-0" id="penaltyTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Penalty ID</th>
                        <th scope="col">Penalty</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penaltydata as $data)
                    <tr>
                        <th scope="row">{{ $data->penalties_id }}</th>
                        <td>{{ $data->penalties }}</td>
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
                    <div class="mb-3">
                        <label for="penalties" class="form-label">Penalty:</label>
                        <input type="text" name="penalties" id="penalties" class="form-control" min="5" required>
                        <div class="invalid-feedback">
                            Penalty name should only contain alphanumeric characters and hyphens.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Penalty</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>
$(document).ready(function () {
    // Show modal for adding a penalty
    $('#addPenaltyBtn').on('click', function (e) {
        e.preventDefault();
        $("#penalties_id").val("");
        $("#penalties").val("").removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#penaltyModal').modal('show');
    });

    $("#penalties").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }
    });

    // Add/Edit Penalty
    $("#penaltyForm").on("submit", function (e) {
        e.preventDefault();

        let penaltyName = $("#penalties").val().trim();
        
        // Validate minimum length 5
        if (penaltyName.length < 5) {
            $("#penalties").addClass("is-invalid");
            $('.invalid-feedback').text("Penalty name must be at least 5 characters long.").show();
            return;
        }

        // Client-side validation only
        if (!/^[a-zA-Z0-9 ]+(\/[a-zA-Z0-9 ]+)*$/.test(penaltyName)) {
            $("#penalties").addClass("is-invalid");
            $('.invalid-feedback').text("Violation name can only contain letters, numbers, spaces, and slashes").show();
            return;
        } else {
            $("#penalties").removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }

        let url = $("#penalties_id").val() ? "/update_penalty/" + $("#penalties_id").val() : "/create_penalties";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                penalties: penaltyName
            },
            success: function (response) {
                console.log(response.message);
                $("#penaltyTable").load(location.href + " #penaltyTable > *");
                $("#penalties").val("");
                $("#penalties_id").val("");
                $('#penaltyModal').modal('hide');
                $('.modal-backdrop').remove();
            }
        });
    });

    // Edit Penalty
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let penaltyId = row.find("th").text();
        let penaltyValue = row.find("td:eq(0)").text();

        $("#penalties_id").val(penaltyId);
        $("#penalties").val(penaltyValue).removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#penaltyModal').modal('show');
    });
});
</script>
