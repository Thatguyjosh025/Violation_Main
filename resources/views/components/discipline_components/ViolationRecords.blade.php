<link rel="stylesheet" href="{{ asset('./css/discipline_css/ViolationRecords.css') }}">
@php
    use App\Models\postviolation; 
    $violators = postviolation::get();
@endphp
        <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Violation Records</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
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
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="violationTable">
                        @foreach ($violators as $data )
                        <tr>
                            <td data-label="Student No.">{{$data -> student_no}}</td>
                            <td data-label="Name">{{$data -> student_name}}</td>
                            <td data-label="Email">{{$data -> school_email}}</td>
                            <td data-label="Violation">{{$data -> violation -> violations}}</td>
                            <td data-label="Status"><span class="badge-ongoing">{{$data -> status -> status}}</span></td>
                            <td data-label="Date">{{$data -> Date_Created}}</td>
                            <td>
                                <button class="btn btn-primary btn-view-post" value="{{ $data -> id }}">View</button>
                                <button class="btn btn-primary btn-edit-post" value="{{ $data -> id }}">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @include('components.discipline_components.modals.modal')
