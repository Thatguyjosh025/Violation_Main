<link rel="stylesheet" href="{{ asset('./css/faculty_css/IncidentRecords.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
    use App\Models\postviolation;
    use App\Models\incident;
    $update = postviolation::get();
    $rejected = incident::get();

    function fullname() {
        $user = Auth::user();
        return $user->firstname . ' ' . $user->lastname;
    }
    function filterbyname(){
    
    }
 $hasRecords = true; 
@endphp
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Incident Records</h3>
            </div>

            <!-- Table Contents -->
            <div class="table-container">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="ms-auto d-flex gap-2">
                        <select id="recordFilter" class="form-select form-select-sm w-auto">
                            <option value="active">Active Records</option>
                            <option value="rejected">Rejected Records</option>
                        </select>
                    </div>
                </div>
                
                <table id="violationTable" class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Violation</th>  
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="activeRecords">
                        @foreach ($update as $updatedata)
                            @if ($updatedata->faculty_name === fullname())
                                <tr>
                                    <td>{{$updatedata->student_no}}</td>
                                    <td>{{$updatedata->student_name}}</td>
                                    <td>{{$updatedata->school_email}}</td>
                                    <td>{{$updatedata->violation->violations}}</td>
                                    <td><span class="badge text-dark">{{$updatedata->status->status}}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($updatedata->Date_Created)->format('Y-m-d') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                    <tbody id="rejectedRecords" style="display:none;">
                        @foreach ($rejected as $reject)
                            @if ($reject->faculty_name === fullname())
                                <tr>
                                    <td>{{$reject->student_no}}</td>
                                    <td>{{$reject->student_name}}</td>
                                    <td>{{$reject->school_email}}</td>
                                    <td>{{$reject->violation}}</td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td>{{ \Carbon\Carbon::parse($reject->created_at)->format('Y-m-d') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#violationTable').DataTable({
        "paging": true,       
        "searching": true,   
        "ordering": true,    
        "info": true,       
        "responsive": true   
    });
});
$(document).ready(function () {

    let table = $('#violationTable').DataTable();

    // Load default: Active
    loadData('active');

    $('#recordFilter').on('change', function () {
        let type = $(this).val();
        loadData(type);
    });

    function loadData(type) {

        $.ajax({
            url: "/incident-records/filter",
            type: "GET",
            data: { type: type },

            success: function (response) {

                table.clear().draw();

                response.forEach(function (item) {
                    table.row.add([
                        item.student_no,
                        item.student_name,
                        item.school_email,
                        item.violation,
                        item.status,
                        item.date
                    ]).draw(false);
                });
            }
        });
    }

});
</script>
