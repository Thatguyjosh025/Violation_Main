<link rel="stylesheet" href="{{ asset('./css/faculty_css/IncidentRecords.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
    use App\Models\postviolation;
    $update = postviolation::get();

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
                        <!-- Status Filter Dropdown -->
                        <!-- <select id="statusFilter" class="form-select form-select-sm w-auto">
                            <option value="">All Status</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Resolved">Resolved</option> -->
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
                    <tbody>
                    @php $hasRecords = false; @endphp
                        @foreach ($update as $updatedata)
                            @if ($updatedata->faculty_name === fullname())
                                <tr>
                                    <td data-label="Student No.">{{$updatedata->student_no}}</td>
                                    <td data-label="Name">{{$updatedata->student_name}}</td>
                                    <td data-label="Email">{{$updatedata->school_email}}</td>
                                    <td data-label="Violation">{{$updatedata->violation->violations}}</td>
                                    <td data-label="Status">
                                        <span class="badge text-dark">{{$updatedata->status->status}}</span>
                                    </td>
                                    <td data-label="Date">{{ \Carbon\Carbon::parse($updatedata->Date_Created)->format('Y-m-d') }}</td>
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
</script>
