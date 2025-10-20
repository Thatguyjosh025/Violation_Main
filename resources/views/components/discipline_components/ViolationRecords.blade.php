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
                </div>
                <div class="d-flex align-items-center">
                    <select id="statusFilter" class="sort-dropdown form-select" style="width: auto; min-width: 150px;">
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
        @include('components.discipline_components.modals.modal')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
   $(document).ready(function() {
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
            }
        },
        columns: [
            { data: 'student_no', name: 'student_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'school_email', name: 'school_email' },
            { data: 'violation', name: 'violation'},
            { data: 'status', name: 'status'},
            { data: 'Date_Created', name: 'Date_Created' },
            { data: 'Update_at', name: 'Update_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    // Filter table when dropdown changes
    $('#statusFilter').on('change', function () {
        table.ajax.reload();
    });
    // $('#showArchived').on('change', function () {
    //     table.ajax.reload(); // Reload table with updated filters
    // });
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
    });
</script>
