<!-- Blade View: Rule Management -->
<link rel="stylesheet" href="{{ asset('css/super_admin_css/RuleManagement.css') }}">
@php
    use App\Models\violation;
    use App\Models\severity;
    use App\Models\rules;

    $severityinfo = severity::get();
    $violationinfo = violation::get();
    $ruleinfo = rules::get();
@endphp

<div class="d-flex align-items-center">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Rule Management</h3>
</div>

<div class="container py-4">
    <div class="create-rule-container">
        <button class="btn btn-primary btn-md" id="addrule">
            <i class="bi bi-plus-circle"></i> Create Rule
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover" id="ruleTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rule Name</th>
                        <th>Description</th>
                        <th>Violation</th>
                        <th>Severity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ruleinfo as $rule)
                        <tr>
                            <th>{{ $rule->rule_id }}</th>
                            <td>{{ $rule->rule_name }}</td>
                            <td>{{ $rule->description }}</td>
                            <td>{{ $rule->violation->violations }}</td>
                            <td>{{ $rule->severity->severity }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn-rule"
                                    data-id="{{ $rule->rule_id }}"
                                    data-name="{{ $rule->rule_name }}"
                                    data-description="{{ $rule->description }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Rule Modal -->
<div class="modal fade" id="createRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ruleForm" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rule:</label>
                        <input type="text" name="rule_name" id="rule_name" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Violation:</label>
                        <select name="violation_id" id="violation_id" class="form-select">
                            <option value="">Select Violation</option>
                            @foreach ($violationinfo as $violation)
                                <option value="{{ $violation->violation_id }}">{{ $violation->violations }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Severity:</label>
                        <select name="severity_id" id="severity_id" class="form-select">
                            <option value="">Select Severity</option>
                            @foreach ($severityinfo as $severity)
                                <option value="{{ $severity->severity_id }}">{{ $severity->severity }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Rule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Rule Modal -->
<div class="modal fade" id="editRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRuleForm" novalidate>
                    @csrf
                    <input type="hidden" name="edit_rule_id" id="edit_rule_id">
                    <div class="mb-3">
                        <label class="form-label">Rule:</label>
                        <input type="text" name="edit_rule_name" id="edit_rule_name" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="edit_description" id="edit_description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script>
    function isValidRuleName(name) {
        const pattern = /^[A-Za-z]+([ -][A-Za-z]+)*$/;
        return pattern.test(name);
    }

    function clearValidation(form) {
        $(form).find('.form-control, .form-select').removeClass('is-invalid');
        $(form).find('.invalid-feedback').text('');
    }

    function setInvalid(inputElem, message) {
        inputElem.addClass('is-invalid');
        inputElem.next('.invalid-feedback').text(message);
    }

    $(document).ready(function () {
        // Show create modal
        $('#addrule').click(() => $('#createRuleModal').modal('show'));

        // Reset and clear validation when modals close
        $('#createRuleModal').on('hidden.bs.modal', function () {
            $('#ruleForm')[0].reset();
            clearValidation('#ruleForm');
        });
        $('#editRuleModal').on('hidden.bs.modal', function () {
            $('#editRuleForm')[0].reset();
            clearValidation('#editRuleForm');
        });

        // Clear validation on input/change
        $('#ruleForm input, #ruleForm select, #ruleForm textarea').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });
        $('#editRuleForm input, #editRuleForm textarea').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });

        // CREATE FORM SUBMIT
        $('#ruleForm').submit(function (e) {
            e.preventDefault();
            clearValidation(this);

            const ruleNameInput = $('#rule_name');
            const descriptionInput = $('#description');
            const violationInput = $('#violation_id');
            const severityInput = $('#severity_id');

            const ruleName = ruleNameInput.val().trim();
            const description = descriptionInput.val().trim();
            const violation = violationInput.val();
            const severity = severityInput.val();

            if (!ruleName) {
                setInvalid(ruleNameInput, 'Rule name is required.');
                return;
            }
            if (!isValidRuleName(ruleName)) {
                setInvalid(ruleNameInput, 'Invalid Rule Name. Use letters, spaces, or single hyphen (e.g., test-test or test test)');
                return;
            }
            if (!description) {
                setInvalid(descriptionInput, 'Description is required.');
                return;
            }
            if (!violation) {
                setInvalid(violationInput, 'Violation is required.');
                return;
            }
            if (!severity) {
                setInvalid(severityInput, 'Severity is required.');
                return;
            }

            $.ajax({
                url: "/create_rules",
                type: "POST",
                data: $(this).serialize(),
                success: function () {
                    $('#createRuleModal').modal('hide');
                    $('#ruleTable').load(location.href + " #ruleTable");
                    $('#ruleForm')[0].reset();
                },
                error: function (xhr) {
                    console.log('Create Rule Error:', xhr.responseText);
                }
            });
        });

        // OPEN EDIT MODAL AND POPULATE
        $(document).on('click', '.edit-btn-rule', function () {
            clearValidation('#editRuleForm');
            const id = $(this).data('id');
            const name = $(this).data('name');
            const desc = $(this).data('description');
            $('#edit_rule_id').val(id);
            $('#edit_rule_name').val(name);
            $('#edit_description').val(desc);
            $('#editRuleModal').modal('show');
        });

        // EDIT FORM SUBMIT
        $('#editRuleForm').submit(function (e) {
            e.preventDefault();
            clearValidation(this);

            const ruleNameInput = $('#edit_rule_name');
            const descriptionInput = $('#edit_description');
            const ruleName = ruleNameInput.val().trim();
            const description = descriptionInput.val().trim();
            const id = $('#edit_rule_id').val();

            if (!ruleName) {
                setInvalid(ruleNameInput, 'Rule name is required.');
                return;
            }
            if (!isValidRuleName(ruleName)) {
                setInvalid(ruleNameInput, 'Invalid Rule Name. Use letters, spaces, or single hyphen.');
                return;
            }
            if (!description) {
                setInvalid(descriptionInput, 'Description is required.');
                return;
            }

            $.ajax({
                url: `/update_rule/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rule_name: ruleName,
                    description: description
                },
                success: function () {
                    $('#editRuleModal').modal('hide');
                    $('#ruleTable').load(location.href + " #ruleTable");
                },
                error: function (xhr) {
                    console.log('Update Rule Error:', xhr.responseText);
                }
            });
        });
    });
</script>
