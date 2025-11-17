<link rel="stylesheet" href="{{ asset('css/discipline_css/ReportandAnalytics.css') }}">

@php
    use App\Models\postviolation;

    $violators = postviolation::get();

    function countSeverity($type) {
        return postviolation::where('severity_Name', 'like', "%$type%")->count();
    }

    $counts = [
        'Minor'   => countSeverity('Minor'),
        'Major A' => countSeverity('Major A'),
        'Major B' => countSeverity('Major B'),
        'Major C' => countSeverity('Major C'),
        'Major D' => countSeverity('Major D'),
    ];

@endphp
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Reports and Analytics</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>
            <div class="container mt-4 w-100">
                
                <div class="exportBtn d-flex justify-content-end">
                    <button onclick="printSection('narrativeSection')" id="printTable" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Narrative Report
                    </button>
                </div>

                <div class="grid-wrapper mt-3">

                <!-- Major A card -->
                <div class="severity-card">
                    <div class="severity-header">
                        <span class="severity-title" id="">Major A</span>
                        <div class="bi bi-exclamation-diamond-fill" style="color: #fd8c00;"></div>
                    </div>
                    <div class="severity-value">{{ $counts['Major A'] }}</div>
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
                    <div class="severity-value">{{ $counts['Major B'] }}</div>
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
                    <div class="severity-value">{{ $counts['Major C'] }}</div>
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
                    <div class="severity-value">{{ $counts['Major D'] }}</div>
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
                            <div class="severity-value">{{ $counts['Minor'] }}</div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #FDC500;;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span  class="background-letter" style="color: #FDC50010;">M</span>
                        </div>
                    </div>
                </div>
                <!-- Narrative Report Section -->
                    <div class="container mt-5">
                        <div class="card p-4 shadow-sm">
                        <h4 class="mb-3">Narrative Report Summary</h4>
                            <div id="narrativeSection">
                                @php
                                    $severities = DB::table('tb_severity')->pluck('severity');
                                    $statusLabels = [ 2 => 'Under Review', 3 => 'Confirmed', 8 => 'Resolved'];
                                    $narratives = [];
                                    $tableData = [];

                                    //Text Narrative Generation
                                    foreach ($severities as $severity) {
                                        $violations = postviolation::where('severity_Name', $severity)->get();

                                        if ($violations->isEmpty()) {
                                            $narratives[$severity] = "No violations have been recorded under $severity severity.";
                                            continue;   
                                        }

                                        // Group by violation type name for narrative summary
                                        $grouped = $violations->groupBy(function ($item) {
                                            return optional($item->violation)->violations ?? 'Unknown';
                                        });
                                        $violationCounts = collect($grouped)->map(fn($group) => count($group));

                                        if ($violationCounts->isEmpty()) {
                                            $narratives[$severity] = "Violations under $severity severity exist, but no rule names are available to summarize.";
                                            continue;
                                        }

                                        // Count by status ID
                                        $underReviewCount = $violations->where('status_name', 2)->count();
                                        $confirmedCount = $violations->where('status_name', 3)->count();
                                        $resolvedCount = $violations->where('status_name', 8)->count();

                                        // Compose main text
                                        $totalCount = $violationCounts->sum();
                                        $topViolation = $violationCounts->sortDesc()->keys()->first();
                                        $violationList = $violationCounts->keys()->implode(', ');

                                        $narrativeText = "$severity reflects $totalCount recorded cases, involving violations such as $violationList. Among these, $topViolation appears most frequently, indicating a pattern that warrants attention.";
                                        
                                        $narratives[$severity] = $narrativeText;
                                    }
                                    
                                    foreach ($statusLabels as $statusId => $statusName) {
                                        $row = ['status' => $statusName];
                                        foreach ($severities as $severity) {
                                            $violations = postviolation::where('severity_Name', $severity)->get();
                                            $row[$severity] = $violations->where('status_name', $statusId)->count();
                                        }
                                        $tableData[] = $row;
                                    }

                                @endphp

                                @foreach ($narratives as $severity => $text)
                                    <p><strong>{{ $severity }}:</strong> {!! $text !!}</p>
                                @endforeach
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-success">
                                            <tr>
                                                <th>Status</th>
                                                @foreach ($severities as $severity)
                                                    <th>{{ $severity }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tableData as $row)
                                                <tr>
                                                    <td><strong>{{ $row['status'] }}</strong></td>
                                                    @foreach ($severities as $severity)
                                                        <td>{{ $row[$severity] }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <p>
                                    These findings highlight the importance of targeted interventions, especially where specific violations dominate.
                                    Continued monitoring and responsive measures will be essential in curbing these trends.
                                </p>
                            </div>
                        </div>
                    </div>
                <!-- End of Narrative Report Section -->

            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function printSection(sectionId) {
        const content = document.getElementById(sectionId).innerHTML;
        const printWindow = window.open('', '', 'height=700,width=900');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Report</title>

                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

                    <style>
                        body {
                            font-family: 'Segoe UI', sans-serif;
                            padding: 40px;
                            line-height: 1.8;
                            text-align: justify;
                        }
                        p {
                            margin-bottom: 1.2rem;
                        }

                        /* Ensure table borders when printing */
                        table, th, td {
                            border: 1px solid #000 !important;
                            border-collapse: collapse !important;
                        }
                        th, td {
                            padding: 8px !important;
                        }
                    </style>
                </head>
                <body>
                    ${content}
                </body>
            </html>
        `);
        printWindow.print();
        printWindow.document.close();
    }
</script>
