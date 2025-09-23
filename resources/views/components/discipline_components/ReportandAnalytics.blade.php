<link rel="stylesheet" href="{{ asset('css/discipline_css/ReportandAnalytics.css') }}">

@php
    use App\Models\postviolation; 
    $violators = postviolation::get();

@endphp
        <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Reports and Analytics</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            <div class="container mt-4 w-100">
                <div class="exportBtn d-flex justify-content-end">
                    <button id="printTable" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Table
                    </button>
                </div>

                <div class="grid-wrapper mt-3">

                <!-- Major A card -->
                <div class="severity-card">
                    <div class="severity-header">
                        <span class="severity-title" id="">Major A</span>
                        <div class="bi bi-exclamation-diamond-fill" style="color: #fd8c00;"></div>
                    </div>
                    <div class="severity-value">34</div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #fd8c00;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="background-letter" style="color: #fd8b0010;">A</span>
                </div>

                <!-- Major B card -->
                <div class="severity-card">
                    <div class="severity-header">
                        <span class="severity-title" id="">Major B</span>
                        <div class="bi bi-exclamation-diamond-fill" style="color: #dc0000;"></div>
                    </div>
                    <div class="severity-value">34</div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #dc0000;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="background-letter" style=" color: #dc000010;">B</span>
                </div>

                <!-- Major C card -->
                <div class="severity-card">
                    <div class="severity-header">
                        <span class="severity-title" id="">Major C</span>
                        <div class="bi bi-exclamation-diamond-fill" style="color: #780000;"></div>
                    </div>
                    <div class="severity-value">34</div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #780000;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="background-letter" style="color: #78000010;">C</span>
                </div>

                <!-- Major D card -->
                  <div class="severity-card">
                    <div class="severity-header">
                        <span class="severity-title" id="">Major D</span>
                        <div class="bi bi-exclamation-diamond-fill" style="color: #780000;"></div>
                    </div>
                    <div class="severity-value">34</div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #780000;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span  class="background-letter" style="color: #78000010;">D</span>
                </div>
                </div>
                           <!-- Minor card -->
                 <div class="minor-container  mt-4">
                    <div class="card-container">
                        <div class="minor-card">
                            <div class="severity-header">
                                <span class="severity-title" id="">Minor</span>
                                <div class="bi bi-exclamation-diamond-fill" style="color: #FDC500;"></div>
                            </div>
                            <div class="severity-value">34</div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #FDC500;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span  class="background-letter" style="color: #FDC50010;">M</span>
                        </div>
                    </div>
                </div>

            </div>
