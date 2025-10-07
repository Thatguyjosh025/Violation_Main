<!-- Blade View: Rule Management -->
<link rel="stylesheet" href="{{ asset('css/super_admin_css/RuleManagement.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

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
                <tbody id="rulebody">
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
                                    data-description="{{ $rule->description }}"
                                    data-violation="{{ $rule->violation_id }}"
                                    data-severity="{{ $rule->severity_id }}">Edit</button>
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
                    <div class="mb-3">
                        <label class="form-label">Violation:</label>
                        <select name="edit_violation_id" id="edit_violation_id" class="form-select">
                            @foreach ($violationinfo as $violation)
                                <option value="{{ $violation->violation_id }}">{{ $violation->violations }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Severity:</label>
                        <select name="edit_severity_id" id="edit_severity_id" class="form-select">
                            @foreach ($severityinfo as $severity)
                                <option value="{{ $severity->severity_id }}">{{ $severity->severity }}</option>
                            @endforeach
                        </select>
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
$(document).ready(function() {
    $('#ruleTable').DataTable({
        "paging": true,       
        "searching": true,   
        "ordering": true,    
        "info": true,       
        "responsive": true   
    });
});
    function isValidRuleName(name) {
        // Allow letters, spaces, slashes, and hyphens between words
        const pattern = /^[A-Za-z]+([ \/-][A-Za-z]+)*$/;
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

        // Reset forms and clear validation on modal close
        $('#createRuleModal, #editRuleModal').on('hidden.bs.modal', function () {
            $('form', this)[0].reset();
            clearValidation(this);
        });

        // Clear validation on input/change
        $('#ruleForm input, #ruleForm select, #ruleForm textarea').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });
        $('#editRuleForm input, #editRuleForm textarea, #editRuleForm select').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });

        // CREATE RULE FORM SUBMIT
        $('#ruleForm').submit(function (e) {
            e.preventDefault();
            clearValidation(this);

            const ruleName = $('#rule_name').val().trim();
            const description = $('#description').val().trim();
            const violation = $('#violation_id').val();
            const severity = $('#severity_id').val();

            let valid = true;

            if (!ruleName || !isValidRuleName(ruleName)) {
                setInvalid($('#rule_name'), 'Invalid Rule Name. Example: fish-fish, fish/fish, or fish fish');
                valid = false;
            }
            if (!description) {
                setInvalid($('#description'), 'Description is required.');
                valid = false;
            }
            if (!violation) {
                setInvalid($('#violation_id'), 'Please select a violation.');
                valid = false;
            }
            if (!severity) {
                setInvalid($('#severity_id'), 'Please select a severity.');
                valid = false;
            }

            if (!valid) return;

            $.ajax({
                url: "/create_rules",
                type: "POST",
                data: $(this).serialize(),
                success: function () {
                    $('#createRuleModal').modal('hide');
                    $('#rulebody').load(location.href + " #rulebody > *");

                Swal.fire({
                    icon: 'success',
                    title: 'Rule Added!',
                    text: 'The Rule has been successfully saved.',
                    timer: 2000,
                    showConfirmButton: false
                });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            setInvalid($(`[name=${field}]`), errors[field][0]);
                        }
                    } else {
                        alert('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });

        // OPEN EDIT MODAL AND POPULATE DATA
        $(document).on('click', '.edit-btn-rule', function () {
            $('#edit_rule_id').val($(this).data('id'));
            $('#edit_rule_name').val($(this).data('name'));
            $('#edit_description').val($(this).data('description'));
            $('#edit_violation_id').val($(this).data('violation'));
            $('#edit_severity_id').val($(this).data('severity'));
            $('#editRuleModal').modal('show');
        });

        // EDIT RULE FORM SUBMIT
        $('#editRuleForm').submit(function (e) {
            e.preventDefault();
            clearValidation(this);

            const ruleName = $('#edit_rule_name').val().trim();
            const description = $('#edit_description').val().trim();
            const violation = $('#edit_violation_id').val();
            const severity = $('#edit_severity_id').val();

            let valid = true;

            if (!ruleName || !isValidRuleName(ruleName)) {
                setInvalid($('#edit_rule_name'), 'Invalid Rule Name. Example: fish-fish, fish/fish, or fish fish');
                valid = false;
            }
            if (!description) {
                setInvalid($('#edit_description'), 'Description is required.');
                valid = false;
            }
            if (!violation) {
                setInvalid($('#edit_violation_id'), 'Please select a violation.');
                valid = false;
            }
            if (!severity) {
                setInvalid($('#edit_severity_id'), 'Please select a severity.');
                valid = false;
            }

            if (!valid) return;

            $.ajax({
                url: `/update_rule/${$('#edit_rule_id').val()}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rule_name: ruleName,
                    description: description,
                    violation_id: violation,
                    severity_id: severity
                },
                success: function (response) {
                    if (response.status === "no_changes") {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Changes',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }

                    $('#editRuleModal').modal('hide');
                    $('#rulebody').load(location.href + " #rulebody > *");

                    Swal.fire({
                        icon: 'success',
                        title: 'Rule Updated!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            setInvalid($(`[name=edit_${field}]`), errors[field][0]);
                        }
                    } else {
                        alert('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
