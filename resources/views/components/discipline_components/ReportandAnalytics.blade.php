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
                                    $narratives = [];

                                    foreach ($severities as $severity) {
                                        $violations = postviolation::where('severity_Name', $severity)->get();

                                        if ($violations->isEmpty()) {
                                            $narratives[$severity] = "No violations have been recorded under $severity severity.";
                                            continue;
                                        }

                                        $grouped = $violations->groupBy('rule_Name');
                                        $violationCounts = collect($grouped)->map(function ($group) {
                                            return count($group);
                                        });

                                        if ($violationCounts->isEmpty()) {
                                            $narratives[$severity] = "Violations under $severity severity exist, but no rule names are available to summarize.";
                                            continue;
                                        }

                                        $totalCount = $violationCounts->sum();
                                        $topViolation = $violationCounts->sortDesc()->keys()->first();
                                        $violationList = $violationCounts->keys()->implode(', ');

                                        $narratives[$severity] = "$severity reflects $totalCount recorded cases, involving violations such as $violationList. Among these, $topViolation appears most frequently, indicating a pattern that warrants attention.";
                                    }
                                @endphp

                                @foreach ($narratives as $severity => $text)
                                    <p><strong>{{ $severity }}:</strong> {{ $text }}</p>
                                @endforeach

                                <p>These findings highlight the importance of targeted interventions, especially where specific violations dominate. Continued monitoring and responsive measures will be essential in curbing these trends.</p>
                            </div>

                            <button onclick="printSection('narrativeSection')" class="btn btn-success mt-3">
                                <i class="bi bi-printer"></i> Print Narrative
                            </button>
                        </div>
                    </div>
                <!-- End of Narrative Report Section -->

            </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function printSection(sectionId) {
        const $content = $('#' + sectionId).html();
        const printWindow = window.open('', '', 'height=600,width=800');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Report</title>
                    <style>
                        body {
                            font-family: 'Segoe UI', sans-serif;
                            padding: 40px;
                            line-height: 1.8;
                            text-align: justify;
                            margin: 0;
                        }
                        p {
                            text-align: justify;
                            margin-bottom: 1.2rem;
                        }
                    </style>
                </head>
                <body>${$content}</body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }
</script>
