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
                        <th scope="col">Referrals</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="referalbody">
                    @foreach ($referalsdata as $data)
                    <tr>
                        <th scope="row">{{ $data->referal_id }}</th>
                        <td>{{ $data->referals }}</td>
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
                    <div class="mb-3">
                        <label for="referals" class="form-label">Referral Name:</label>
                        <input type="text" name="referals" id="referals" class="form-control" required>
                        <div class="invalid-feedback">
                            Referral name should only contain alphanumeric characters and hyphens.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Referral</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>
 $(document).ready(function() {
    $('#referralTable').DataTable({
        "paging": true,       
        "searching": true,   
        "ordering": true,    
        "info": true,       
        "responsive": true   
    });
});
$(document).ready(function () {
    // Show modal for adding referral
    $('#addReferralBtn').on('click', function (e) {
        e.preventDefault();
        $("#referal_id").val("");
        $("#referals").val("").removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#referralModal').modal('show');
    });

    // Remove error styling when user starts typing
    $("#referals").on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this).removeClass("is-invalid");
            $('.invalid-feedback').hide();
        }
    });

    // Add/Edit Referral
    $("#referralForm").on("submit", function (e) {
        e.preventDefault();

        let referralName = $("#referals").val().trim();

        // Basic front-end validation
        if (referralName.length < 3) {
            $("#referals").addClass("is-invalid");
            $('.invalid-feedback').text("Referral name must be at least 3 characters long.").show();
            return;
        }

        if (!/^[a-zA-Z0-9 /\-]+$/.test(referralName)) {
            $("#referals").addClass("is-invalid");
            $('.invalid-feedback').text("Referral name can only contain letters, numbers, spaces, hyphens, and slashes.").show();
            return;
        }

        // Decide URL (create or update)
        let url = $("#referal_id").val() 
            ? "/update_referral/" + $("#referal_id").val() 
            : "/create_referals";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                referals: referralName
            },
            success: function (response) {
                console.log(response.message);
                $('#referalbody').load(location.href + " #referalbody > *");
                $("#referals").val("");
                $("#referal_id").val("");
                $('#referralModal').modal('hide');
                $('.modal-backdrop').remove();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors && errors.referals) {
                        $("#referals").addClass("is-invalid");
                        $('.invalid-feedback').text(errors.referals[0]).show();
                    }
                } else {
                    alert("An unexpected error occurred. Please try again.");
                }
            }
        });
    });

    // Edit Referral button
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let referralId = row.find("th").text();
        let referralValue = row.find("td:eq(0)").text();

        $("#referal_id").val(referralId);
        $("#referals").val(referralValue).removeClass("is-invalid");
        $('.invalid-feedback').hide();
        $('#referralModal').modal('show');
    });
});

</script>