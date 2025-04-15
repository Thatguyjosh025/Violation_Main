<link rel="stylesheet" href="{{ asset('css/super_admin_css/ViolationManagement.css') }}">

@php
use App\Models\violation;
$violationdata = violation::get();
@endphp

<!-- Violation -->
<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Violation Management</h3>
    <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
</div>

<!-- Content Sections -->
<div class="container mt-4">
    <!-- Violation Type Section -->
    <div class="content-section active" id="violation-type-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Violation Type</h4>
            <button class="btn btn-add px-4 py-2" id="addViolationBtn">+ Add</button>
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
                <tbody>
                @foreach ($violationdata as $data)
                    <tr>
                        <th scope="row">{{ $data->violation_id }}</th>
                        <td contenteditable="false">{{ $data->violations }}</td>
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

<!-- Violation Modal -->
<div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="violationModalLabel">Add Violation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="violationForm" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="violations" class="form-label">Violation:</label>
                <input type="text" name="violations" id="violations" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Violation</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>

<script>

$(document).ready(function(){
    $('#addViolationBtn').on('click', function(e){
        e.preventDefault();
        $('#violationModal').modal('show');
    });

});

$(document).ready(function () {
    // Add Violation
    $("#violationForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "/create_violation",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                violations: $("#violations").val()
            },
            success: function (response) {
                console.log(response.message);

              
                $("#violationTable tbody").append(
                    "<tr>" +
                        "<th scope='row'>" + response.violation.violation_id + "</th>" +
                        "<td contenteditable='false'>" + response.violation.violations + "</td>" +
                        "<td>" +
                            "<button class='edit-btn btn btn-warning btn-sm'>Edit</button>" +
                        "</td>" +
                    "</tr>"
                );

                $("#violations").val("");
                $('#violationModal').modal('hide');
                $('.modal-backdrop').remove();

            },
            error: function () {
                console.log("An error occurred. Please try again.");
            }
        });
    });

    // Edit Violation
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let violationCell = row.find("td:first");
        let violationId = row.find("th").text();

        if ($(this).text() === "Edit") {
            $(this).text("Save");
            violationCell.attr("contenteditable", "true").focus();
        } else {
            $(this).text("Edit");
            violationCell.attr("contenteditable", "false");

            $.post("/update_violation/" + violationId, {
                _token: "{{ csrf_token() }}",
                violations: violationCell.text()
            });

            console.log("Violation updated.");
        }
    });
});
</script>
