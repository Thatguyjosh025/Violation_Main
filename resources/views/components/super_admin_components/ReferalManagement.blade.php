<!-- Referral Management Content -->
<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
<!-- Dashboard Content -->
@php
use App\Models\referals;

$referalsdata = referals::get();
@endphp
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Referral Management</h3>
    <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
</div>

<!-- Content Sections -->
<div class="container mt-4">
    <!-- Referral Type Section -->
    <div class="content-section active" id="referral-type-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Referral Type</h4>
            <button class="btn btn-add px-4 py-2" id="addreferalBtn">+ Add</button>
        </div>
        <div class="card-table bg-white">
            <table class="table table-hover mb-0" id="referalTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Referral ID</th>
                        <th scope="col">Referrals</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referalsdata as $data)
                        <tr>
                            <th scope="row">{{ $data->referal_id}}</th>
                            <td contenteditable="false">{{ $data->referals }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn">Edit</button>
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
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveReferralBtn">Save Referral</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>

<script>

$(document).ready(function(){
    $('#addreferalBtn').on('click', function(e){
        e.preventDefault();
        $('#referralModal').modal('show');
    });

});

$(document).ready(function () {
    // Add referral form submission via AJAX
    $("#referralForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "/create_referals", 
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                referals: $("#referals").val()    
            },
            success: function (response) {
                console.log(response.message);

                $('#referralModal').modal('hide');
                $('.modal-backdrop').remove();
                // Append new row to the table
                $("#referalTable tbody").append(
                    "<tr>" +
                        "<th scope='row'>" + response.referals.referal_id + "</th>" +
                        "<td contenteditable='false'>" + response.referals.referals + "</td>" +
                        "<td>" +
                            "<button class='edit-btn btn btn-warning btn-sm'>Edit</button>" +
                        "</td>" +
                    "</tr>"
                );

                $("#referals").val(""); 
            },
            error: function () {
                console.log("An error occurred. Please try again.");
            }
        });
    });

    // Editable referral
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let referralCell = row.find("td:first");
        let referralId = row.find("th").text();

        if ($(this).text() === "Edit") {
            $(this).text("Save");
            referralCell.attr("contenteditable", "true").focus();
        } else {
            $(this).text("Edit");
            referralCell.attr("contenteditable", "false");

            $.post("/update_referral/" + referralId, {
                _token: "{{ csrf_token() }}",
                referals: referralCell.text()
            });

            console.log("Referral updated.");
        }
    });
});
</script>
