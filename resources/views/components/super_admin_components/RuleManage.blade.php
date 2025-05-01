<link rel="stylesheet" href="{{ asset('css/super_admin_css/RuleManagement.css') }}">
@php
    use App\Models\violation;
    use App\Models\severity;
    use App\Models\rules;

    $severityinfo = severity::get();
    $violationinfo = violation::get();
    $ruleinfo = rules::get();
@endphp   

<!-- Dashboard Content -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Rule Management</h3>
            </div>

            <div class="container py-4">

                <!-- Create Rule Button (moved to left) -->
                <div class="create-rule-container">
                    <button class="btn btn-primary" id="addrule">
                        <i class="bi bi-plus-circle"></i> Create Rule
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover" id="ruleTable">
                            <thead>
                            <tr>
                                <th scope="col">Rule ID</th>
                                <th scope="col">Rule Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Violation</th>
                                <th scope="col">Severity</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ruleinfo as $rule)
                                <tr>
                                    <th scope="row">{{ $rule->rule_id }}</th>
                                    <td>{{ $rule->rule_name }}</td>
                                    <td>{{ $rule->description }}</td>
                                    <td>{{ $rule->violation->violations }}</td> <!-- Displaying violation name -->
                                    <td>{{ $rule->severity->severity }}</td>   <!-- Displaying severity name -->
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn-rule">Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            <!-- Create Rule Modal -->
            <div class="modal fade" id="createRuleModal" tabindex="-1" aria-labelledby="createRuleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRuleModalLabel">Create Rule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="ruleForm" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="rule_name" class="form-label">Rule:</label>
                                    <input type="text" name="rule_name" id="rule_name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description:</label>
                                    <textarea class="form-control" id="description" rows="3" name="description" placeholder="Enter description..." maxlength="200"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="violation_id" class="form-label">Corresponding Violation:</label>
                                    <select name="violation_id" id="violation_id" class="form-select" required>
                                        <option value="">Select Violation</option>
                                        @foreach ($violationinfo as $violation)
                                            <option value="{{ $violation->violation_id }}">{{ $violation->violations }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="severity_id" class="form-label">Corresponding Severity:</label>
                                    <select name="severity_id" id="severity_id" class="form-select" required>
                                        <option value="">Select Severity</option>
                                        @foreach ($severityinfo as $severity)
                                            <option value="{{ $severity->severity_id }}">{{ $severity->severity }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Create Rule</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Archive Confirmation Modal
            <div class="modal fade" id="archiveConfirmModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Archive</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to archive this rule?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-archive text-white" id="confirmArchive">Archive</button>
                        </div>
                    </div>
                </div>
            </div> -->

<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>

$(document).ready(function(){
    $('#addrule').on('click', function(e){
        e.preventDefault();
        $('#createRuleModal').modal('show');
    });

});

 $(document).ready(function () {
        $("#ruleForm").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "/create_rules",  // Define the route for creating the rule
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rule_name: $("#rule_name").val(),
                    description: $("#description").val(),
                    violation_id: $("#violation_id").val(),
                    severity_id: $("#severity_id").val()
                },
                success: function (response) {
                    console.log(response.message);

                    $('#createRuleModal').modal('hide');  // Hide the modal after success
                    $('.modal-backdrop').remove();  // Remove the backdrop

                    // Append new row to the table
                    $("#ruleTable tbody").append(
                        "<tr>" +
                            "<th scope='row'>" + response.rule.rule_id + "</th>" +
                            "<td>" + response.rule.rule_name + "</td>" +
                            "<td>" + response.rule.description + "</td>" +
                            "<td>" + response.rule.violation_name + "</td>" +
                            "<td>" + response.rule.severity_name + "</td>" +  
                            "<td>" +
                                "<button class='btn btn-warning btn-sm edit-btn-rule'>Edit</button>" +
                            "</td>" +
                        "</tr>"
                    );

                    // Reset form fields after submission
                    $("#rule_name").val(""); 
                    $("#description").val("");
                    $("#violation_id").val(""); 
                    $("#severity_id").val(""); 
                },
                error: function () {
                    console.log("An error occurred. Please try again.");
                }
            });
        });

        // Editable rule
        $(document).on("click", ".edit-btn-rule", function () {
            let row = $(this).closest("tr");
            let ruleNameCell = row.find("td:nth-child(2)"); // Rule name cell
            let descriptionCell = row.find("td:nth-child(3)"); // Description cell
            let violationCell = row.find("td:nth-child(4)"); // Violation cell
            let severityCell = row.find("td:nth-child(5)"); // Severity cell
            let ruleId = row.find("th").text();

            if ($(this).text() === "Edit") {
                $(this).text("Save");
                ruleNameCell.attr("contenteditable", "true").focus();
                descriptionCell.attr("contenteditable", "true");
            } else {
                $(this).text("Edit");
                ruleNameCell.attr("contenteditable", "false");
                descriptionCell.attr("contenteditable", "false");

                $.post("/update_rule/" + ruleId, {
                    _token: "{{ csrf_token() }}",
                    rule_name: ruleNameCell.text(),
                    description: descriptionCell.text(),
                    violation_id: violationCell.text(),  // Assuming violation is saved as text or ID
                    severity_id: severityCell.text()     // Assuming severity is saved as text or ID
                });

                console.log("Rule updated.");
            }
        });
    });
</script>