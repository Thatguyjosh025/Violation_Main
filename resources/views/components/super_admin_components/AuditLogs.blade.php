<link rel="stylesheet" href="{{ asset('css/super_admin_css/audit.css') }}">
<link rel="stylesheet" href="{{asset('./vendor/dataTables.dataTables.min.css')}}">

@php
use App\Models\audits;
$auditdata = audits::get();
@endphp

<!-- Dashboard Content -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Audit Log</h3>
            </div>

            <div class="container mt-4">
                <div class="exportBtn d-flex justify-content-end">
                    <button id="printTable" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Table
                    </button>
                </div>
                <div class="table-container mt-3">
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="auditTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                            <th scope="col">ID</th>
                            <th>change_at</th>
                            <th>change_by</th>
                            <th>change_by_email</th>
                            <th>event_type</th>
                            <th>field_name</th>
                            <th>old_value</th>
                            <th>new_value</th>
                            </tr>
                        </thead>
                        <tbody id="auditbody">
                            @foreach ($auditdata as $data)
                            <tr>
                                <th scope="row">{{ $data->id }}</th>
                                <td>{{ $data -> changed_at }}</td>
                                <td>{{ $data -> changed_by }}</td>
                                <td>{{ $data -> changed_by_email }}</td>
                                <td>{{ $data -> event_type }}</td>
                                <td>{{ $data -> field_name }}</td>
                                <td>{{ $data -> old_value }}</td>
                                <td>{{ $data -> new_value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>               

<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="{{ asset('./vendor/jquery.dataTables.min.js') }}"></script>


<script>
    $(document).ready(function() {
        $('#auditTable').DataTable({
            "paging": true,       
            "searching": true,   
            "ordering": true,    
            "info": true,       
            "responsive": true,
        });
    });
    
</script>