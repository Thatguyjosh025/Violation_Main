<link rel="stylesheet" href="{{ asset('./css/faculty_css/IncidentRecords.css') }}">
@php
    use App\Models\postviolation;
    $update = postviolation::get();

    function fullname() {
        $user = Auth::user();
        return $user->firstname . ' ' . $user->lastname;
    }

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
                        <select id="statusFilter" class="form-select form-select-sm w-auto">
                            <option value="">All Status</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Resolved">Resolved</option>
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
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    @php $hasRecords = false; @endphp
                        @foreach ($update as $updatedata)
                            @if ($updatedata->faculty_name === fullname())
                                @php $hasRecords = true; @endphp
                                <tr>
                                    <td data-label="Student No.">{{$updatedata->student_no}}</td>
                                    <td data-label="Name">{{$updatedata->student_name}}</td>
                                    <td data-label="Email">{{$updatedata->school_email}}</td>
                                    <td data-label="Violation">{{$updatedata->violation->violations}}</td>
                                    <td data-label="Status">
                                        <span class="badge text-dark">{{$updatedata->status->status}}</span>
                                    </td>
                                    <td data-label="Date">{{$updatedata->Date_Created}}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if (!$hasRecords)
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        @endif
                    </tbody>
                    
                </table>
            </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
