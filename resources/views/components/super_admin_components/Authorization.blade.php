<link rel="stylesheet" href="{{ asset('css/super_admin_css/Authorization.css') }}">
@php
use App\Models\users;

$userdata = users::get();

@endphp
       <!-- Dashboard Content -->
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Authorization</h3>
            </div>

            <div class="container mt-4">
                <div class="table-container mt-3">
                    <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
                        <button class="btn btn-action w-auto" type="button" data-bs-toggle="modal" data-bs-target="#dateFilterModal">
                          <i class="bi bi-calendar-range me-1"></i> Filter by Date
                        </button>
                        <button class="btn btn-action w-auto" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                      </div>                                       
            
                  <!-- Table -->
                  <div class="table-responsive-sm overflow-hidden">
                    <table class="table table-hover align-middle text-center table-mobile-wrap">
                      <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Student No.</th>
                            <th scope="col">Course and Section</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($userdata as $data)
                            <tr>
                                <th scope="row">{{ $data->id }}</th>
                                <td>{{ $data->firstname }}</td>
                                <td>{{ $data->lastname }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->student_no }}</td>
                                <td>{{ $data->course_and_section }}</td>
                                <td>{{ $data->role }}</td>
                                <td>{{ $data->status }}</td>
                            </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>                  
                </div>
              </div>
            
              <!-- Edit Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Authorization Form</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form id="authForm">
                        <div class="mb-3">
                          <label class="form-label">Name</label>
                          <input type="text" class="form-control" placeholder="Enter Name" required />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">User ID</label>
                          <input type="text" class="form-control" placeholder="Enter ID" required />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Role of User:</label>
                          <span class="fw-bold">Student</span>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Level of Access</label>
                          <select class="form-select" required>
                            <option selected disabled>Select Privilege</option>
                            <option value="Read">Read</option>
                            <option value="Write">Write</option>
                            <option value="Edit">Edit</option>
                            <option value="Create">Create</option>
                            <option value="Read-Write">Read, Write</option>
                            <option value="Read-Write-Edit">Read, Write, Edit</option>
                            <option value="Read-Write-Edit-Create">Read, Write, Edit, Create</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Current Access:</label>
                          <span class="fw-bold">Read</span>
                        </div>
                        <div class="text-center">
                          <button type="submit" class="btn btn-save">Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
              <!-- Date Filter Modal -->
            <div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content shadow-lg">
                    <div class="modal-header border-0 pb-1">
                      <h5 class="modal-title" id="dateFilterModalLabel">
                        <i class="bi bi-calendar3-range me-2"></i>Filter by Date Range
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <hr class="mt-0 mb-3 mx-3" />
                    <div class="modal-body px-4">
                      <div class="mb-3">
                        <label for="fromDate" class="form-label fw-semibold">From</label>
                        <input type="date" class="form-control rounded-pill px-3 py-2" id="fromDate" />
                      </div>
                      <div class="mb-3">
                        <label for="toDate" class="form-label fw-semibold">To</label>
                        <input type="date" class="form-control rounded-pill px-3 py-2" id="toDate" />
                      </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                      <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary rounded-pill px-4" id="applyDateFilter">Apply</button>
                    </div>
                  </div>
                </div>
              </div>
