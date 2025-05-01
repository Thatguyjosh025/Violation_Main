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

            <!-- Violation Records Section -->
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                <select id="statusFilter" class="sort-dropdown">
                    <option value="All" selected>Show All</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Resolved">Resolved</option>
                </select>
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
                            <th>Date_Created</th>
                            <th>Updated_at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="violationTable">
                      
                    </tbody>
                </table>
            </div>
        @include('components.discipline_components.modals.modal')
<script>
   $(document).ready(function() {
    $('#violationrecordstable').DataTable({
        responsive: true, // <-- Enable responsiveness
        processing: true,
        serverSide: true,
        ajax: "{{ route('violation_records.data') }}",
        columns: [
            { data: 'student_no', name: 'student_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'school_email', name: 'school_email' },
            { data: 'violation', name: 'violation' },
            { data: 'status', name: 'status' },
            { data: 'Date_Created', name: 'Date_Created' },
            { data: 'Update_at', name: 'Update_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
