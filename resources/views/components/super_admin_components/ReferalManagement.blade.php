<!-- Referral Management Content -->
<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
use App\Models\referals;
$referalsdata = referals::get();
@endphp

<!-- Referral Header -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Referral Management</h3>
</div>

<!-- Content Sections -->
<div class="container mt-4">
    <!-- Referral Type Section -->
    <div class="content-section active" id="referral-type-section">
        <div class="mb-3" style="display: flex; justify-content: end;">
            <button class="btn btn-add btn-md" id="addReferralBtn">+ Add</button>
        </div>

        <div class="card-table bg-white">
            <table class="table table-hover mb-0" id="referralTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Referral ID</th>
                        <th scope="col">Referral</th>
                        <th scope="col">is_visible</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="referralbody">
                    @foreach ($referalsdata as $data)
                    <tr>
                        <th scope="row">{{ $data->referal_id }}</th>
                        <td>{{ $data->referals }}</td>
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

<!-- Referral Modal -->
<div class="modal fade" id="referralModal" tabindex="-1" aria-labelledby="referralModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="referralModalLabel">Add/Edit Referral</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="referralForm" class="mb-4">
                    @csrf
                    <input type="hidden" name="referal_id" id="referal_id">

                    <!-- Referral Name -->
                    <div class="mb-3">
                        <label for="referals" class="form-label">Referral Name:</label>
                        <input type="text" name="referals" id="referals" class="form-control" required>
                        <div class="invalid-feedback">
                            Referral name must be valid.
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

                    <!-- Save Button -->
                    <button type="submit" class="btn btn-primary">Save Referral</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>
$(document).ready(function () {
    $('#referralTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true
    });

    // Show modal for adding referral
    $('#addReferralBtn').on('click', function (e) {
        e.preventDefault();
        $("#referal_id").val("");
        $("#referals").val("").removeClass("is-invalid");
        $("#is_visible").val("active"); // default value
        $('.invalid-feedback').hide();
        $('#referralModal').modal('show');
    });

    $("#referals").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }
    });

    // Add/Edit Referral Submit
    $("#referralForm").on("submit", function (e) {
        e.preventDefault();

        let referralName = $("#referals").val().trim();
        let isVisible = $("#is_visible").val();

        if (referralName.length < 3) {
            $("#referals").addClass("is-invalid");
            $('.invalid-feedback').text("Referral name must be at least 3 characters long.").show();
            return;
        } else if (!/^[A-Za-z0-9 \-\/]+$/.test(referralName)) {
            $("#referals").addClass("is-invalid");
            $('.invalid-feedback').text("Referral name may contain letters, numbers, spaces, hyphens, and slashes.").show();
            return;
        } else {
            $("#referals").removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }

        let url = $("#referal_id").val()
            ? "/update_referral/" + $("#referal_id").val()
            : "/create_referals";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                referals: referralName,
                is_visible: isVisible
            },
            success: function (response) {
                $('#referralbody').load(location.href + " #referralbody > *");

                let isEdit = $("#referal_id").val() ? true : false;

                Swal.fire({
                    icon: 'success',
                    title: isEdit ? 'Referral Updated!' : 'Referral Added!',
                    text: response.message || 'The referral has been successfully saved.',
                    timer: 3000,
                    showConfirmButton: false
                });

                $("#referal_id").val("");
                $("#referals").val("");
                $("#is_visible").val("active");
                $('#referralModal').modal('hide');
                $('.modal-backdrop').remove();
            },
            error: function (xhr) {
                let errorMessage = "An error occurred. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.referals) {
                    errorMessage = xhr.responseJSON.errors.referals[0];
                }
                $("#referals").addClass("is-invalid");
                $('.invalid-feedback').text(errorMessage).show();
            }
        });
    });

    // Edit Referral
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");

        let referralID = row.children().eq(0).text().trim();  // referal_id
        let referralName = row.children().eq(1).text().trim(); // referals
        let visibility = row.children().eq(2).text().trim();   // is_visible

        $("#referal_id").val(referralID);
        $("#referals").val(referralName).removeClass("is-invalid");
        $("#is_visible").val(visibility.toLowerCase()); 
        $('.invalid-feedback').hide();
        $('#referralModal').modal('show');
    });
});
</script>
