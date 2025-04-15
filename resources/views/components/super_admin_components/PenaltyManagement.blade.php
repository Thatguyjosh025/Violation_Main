<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">
@php
use App\Models\penalties;

$penaltydata = penalties::get();
@endphp


<!-- Penalty -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Penalty Management</h3>
    <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
</div>

<!-- Content Sections -->
<div class="container mt-4">
    <!-- Penalty Type Section -->
    <div class="content-section active" id="penalty-type-section">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Penalty Type</h4>
            <button class="btn btn-add px-4 py-2" id="addPenaltyBtn">+ Add</button>
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
                        <td contenteditable="false">{{ $data->penalties }}</td>
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

<!-- Penalty Modal -->
<div class="modal fade" id="penaltyModal" tabindex="-1" aria-labelledby="penaltyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="penaltyModalLabel">Add Penalty</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="penaltyForm" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="penalties" class="form-label">Penalty:</label>
                <input type="text" name="penalties" id="penalties" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Penalty</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script>

$(document).ready(function(){
    $('#addPenaltyBtn').on('click', function(e){
        e.preventDefault();
        $('#penaltyModal').modal('show');
    });

});

//add penalty
$(document).ready(function () {
        $("#penaltyForm").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "/create_penalties", 
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    penalties: $("#penalties").val()    
                },
                success: function (response) {
                    console.log(response.message);

                    $('#penaltyModal').modal('hide');
                    $('.modal-backdrop').remove();
                    // Append new row to the table
                    $("#penaltyTable tbody").append(
                        "<tr>" +
                            "<th scope='row'>" + response.penalty.penalties_id + "</th>" +
                            "<td contenteditable='false'>" + response.penalty.penalties + "</td>" +
                            "<td>" +
                                "<button class='edit-btn btn btn-warning btn-sm'>Edit</button>" +
                            "</td>" +
                        "</tr>"
                    );

                    $("#penalties").val(""); 
                   
                    //plan b solution
                    //window.location.reload();
                    
                },
                error: function () {
                    console.log("An error occurred. Please try again.");
                }
            });
        });
    });
    
    //editable violation
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr"); //find where the button takes place
        let penaltyCell = row.find("td:first"); //finding the first row of datas
        let penaltyId = row.find("th").text();

        if ($(this).text() === "Edit") { //
            $(this).text("Save");
            penaltyCell.attr("contenteditable", "true").focus();
        } else {
            $(this).text("Edit");
            penaltyCell.attr("contenteditable", "false");

            $.post("/update_penalty/" + penaltyId, {
                _token: "{{ csrf_token() }}",
                penalties: penaltyCell.text()
            });

            console.log("Penalty updated.");
        }
    });
</script>
