<link rel="stylesheet" href="{{ asset('./css/discipline_css/ViolationRecords.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
    use App\Models\postviolation; 
    $violators = postviolation::get();
@endphp
        <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation Records</h3>
            </div>
    
            <!-- Filter Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
 
                </div>

            <div class="mb-2 d-flex align-items-center justify-content-between">
                <div>
                    <button class="btn btn-sm btn-primary" id="exportCSV">Export CSV</button>
                    <button class="btn btn-sm btn-secondary" id="printTable">Print</button>
                    <button class="btn btn-sm btn-secondary" id="validateName">Name Validator</button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex flex-column me-2">
                        <label for="startMonth" class="form-label mb-0">From</label>
                        <input type="month" id="startMonth" class="form-control" style="width: 150px;">
                    </div>
                    <div class="d-flex flex-column me-2">
                        <label for="endMonth" class="form-label mb-0">To</label>
                        <input type="month" id="endMonth" class="form-control" style="width: 150px;">
                    </div>
                    <button class="btn btn-sm btn-primary ms-2 mt-3" id="applyDateFilter" style="margin-right: 7px;" >Apply</button>
                    <select id="statusFilter" class="sort-dropdown form-select mt-3" style="width: auto; min-width: 150px;">
                        <option value="">Show All Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Appealed">Appealed</option>
                        <option value="Appeal Under Review">Appeal Under Review</option>
                        <option value="Appeal Approved">Appeal Approved</option>
                        <option value="Appeal Denied">Appeal Denied</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>
            </div>
    
            <div class="table-container">
                <table class="table table-hover" id="violationrecordstable">
                    <thead>
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Violation</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th>Modified On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="violationTable">
                      
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="validatorModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content p-3">
                        <h5>Upload CSV file</h5>
                        <input type="file" id="validatorFile" class="form-control mt-2" accept=".csv">
                        <button class="btn btn-primary mt-3" id="runValidator">Run Validator</button>
                    </div>
                </div>
            </div>
    @include('components.discipline_components.modals.modal')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
   $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    let table = $('#violationrecordstable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('violation_records.data') }}",
           data: function (d) {
                const status = $('#statusFilter').val();
                if (status) {
                    d.status = status;
                }
                const start = $('#startMonth').val();
                const end = $('#endMonth').val();
                if (start) d.start_month = start;
                if (end) d.end_month = end;
            }
        },
        columns: [
            { data: 'student_no', name: 'student_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'school_email', name: 'school_email' },
            { data: 'violation', name: 'violation' },
            { data: 'status', name: 'status' },
            { data: 'Date_Created', name: 'Date_Created' },
            { data: 'Update_at', name: 'Update_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $('td', row).eq(0).attr('data-label', 'Student No.');
            $('td', row).eq(1).attr('data-label', 'Name');
            $('td', row).eq(2).attr('data-label', '');
            $('td', row).eq(3).attr('data-label', 'Violation');
            $('td', row).eq(4).attr('data-label', 'Status');
            $('td', row).eq(5).attr('data-label', 'Created On');
            $('td', row).eq(6).attr('data-label', 'Modified On');
            $('td', row).eq(7).attr('data-label', 'Actions');
        }
    });

    $('#applyDateFilter').on('click', function() {
        table.ajax.reload();
    });
    // Filter table when dropdown changes
    $('#statusFilter').on('change', function () {
        table.ajax.reload();
    });
});

$(document).ready(function () {
        function cloneTableWithoutActions() {
            let cloned = $('#violationrecordstable').clone();
            cloned.find('tr').each(function () {
                $(this).find('th:last-child, td:last-child').remove(); // remove last column
            });
            return cloned;
        }

        $('#exportCSV').click(function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to export all violation records to CSV.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clone table without Actions column
                    function cloneTableWithoutActions() {
                        let cloned = $('#violationrecordstable').clone();
                        cloned.find('tr').each(function () {
                            $(this).find('th:last-child, td:last-child').remove(); // remove last column
                        });
                        return cloned;
                    }

                    let csv = [];
                    let table = cloneTableWithoutActions();

                    table.find('tr').each(function () {
                        let row = [];
                        $(this).find('th, td').each(function () {
                            let text = $(this).text().replace(/"/g, '""');
                            row.push('"' + text + '"');
                        });
                        csv.push(row.join(','));
                    });

                    let csvString = csv.join('\n');
                    let blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
                    let link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'violation_records.csv';
                    link.click();

                    Swal.fire(
                        'Exported!',
                        'Violation records have been exported.',
                        'success'
                    );
                }
            });
        });


        $('#printTable').click(function () {
            // Clone the DataTable and remove the Actions column
            let clonedTable = $('#violationrecordstable').clone();

            // Remove the last column (Actions)
            clonedTable.find('tr').each(function () {
                $(this).find('th:last-child, td:last-child').remove();
            });

            // Remove all DataTable classes and IDs for clean print
            clonedTable.removeClass().removeAttr('id');
            clonedTable.find('*').removeClass();

            // Define CSS for print
            let styles = `
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h2 { text-align: center; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; font-size: 12px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            `;

            // Open new window for print
            let printWindow = window.open('', '', 'width=1000,height=700');
            printWindow.document.write('<html><head><title>Print Violation Records</title>' + styles + '</head><body>');
            printWindow.document.write('<h2>Violation Records</h2>');
            printWindow.document.write(clonedTable.prop('outerHTML'));
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        });

        $('#validateName').click(function() {
            $('#validatorModal').modal('show');
        });
        
        $('#runValidator').click(function() {
            let file = $('#validatorFile')[0].files[0];
            if (!file) {
                Swal.fire("Error", "Please upload a file!", "error");
                return;
            }

            let formData = new FormData();
            formData.append('file', file);

            Swal.fire({
                title: "Comparing...",
                text: "Please wait while validating data",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: "{{ route('validator.run') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    Swal.close();

                    $('#validatorModal').modal('hide');

                    $('#validatorFile').val('');

                    Swal.fire({
                        icon: "success",
                        title: "Done",
                        html: `
                            <p>Validation completed.</p>
                            <a href="{{ url('/name-validator/export/csv') }}" class="btn btn-success">Download CSV</a>
                            <a href="{{ url('/name-validator/export/pdf') }}" class="btn btn-danger" target="_blank">Open PDF</a>
                        `
                    });
                },
                error: function() {
                    Swal.fire("Error", "Failed to process file", "error");
                }
            });
        });
    });
</script>
