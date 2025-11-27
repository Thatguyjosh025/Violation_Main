<link rel="stylesheet" href="{{ asset('css/discipline_css/ReportandAnalytics.css') }}">

@php
use App\Models\postviolation;
use Carbon\Carbon;

// Get selected year from query string (if any)
$selectedYear = request()->get('year', null);

function getViolationsBySeverity($severity, $year = null) {
    $query = postviolation::where('severity_Name', $severity);

    if ($year) {
        $start = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $end = Carbon::createFromDate($year, 12, 31)->endOfYear();
        $query->whereBetween('Date_Created', [$start, $end]);
    }

    return $query->get();
}

// Counts for severity cards
$counts = [
    'Minor'   => getViolationsBySeverity('Minor', $selectedYear)->count(),
    'Major A' => getViolationsBySeverity('Major A', $selectedYear)->count(),
    'Major B' => getViolationsBySeverity('Major B', $selectedYear)->count(),
    'Major C' => getViolationsBySeverity('Major C', $selectedYear)->count(),
    'Major D' => getViolationsBySeverity('Major D', $selectedYear)->count(),
];

// Narrative Report Data
$severities = DB::table('tb_severity')->pluck('severity');
$statusLabels = [2 => 'Under Review', 3 => 'Confirmed', 8 => 'Resolved'];
$narratives = [];
$tableData = [];

foreach ($severities as $severity) {
    $violations = getViolationsBySeverity($severity, $selectedYear);

    if ($violations->isEmpty()) {
        $narratives[$severity] = "No violations have been recorded under $severity severity for this year.";
        continue;
    }

    // Group by violation type for narrative summary
    $grouped = $violations->groupBy(fn($item) => optional($item->violation)->violations ?? 'Unknown');
    $violationCounts = collect($grouped)->map(fn($group) => count($group));

    $underReviewCount = $violations->where('status_name', 2)->count();
    $confirmedCount = $violations->where('status_name', 3)->count();
    $resolvedCount = $violations->where('status_name', 8)->count();

    $totalCount = $violationCounts->sum();
    $topViolation = $violationCounts->sortDesc()->keys()->first();
    $violationList = $violationCounts->keys()->implode(', ');

    $narrativeText = "$severity reflects $totalCount recorded cases, involving violations such as $violationList. Among these, $topViolation appears most frequently, indicating a pattern that warrants attention.";
    $narratives[$severity] = $narrativeText;
}

foreach ($statusLabels as $statusId => $statusName) {
    $row = ['status' => $statusName];
    foreach ($severities as $severity) {
        $row[$severity] = getViolationsBySeverity($severity, $selectedYear)
            ->where('status_name', $statusId)->count();
    }
    $tableData[] = $row;
}
@endphp

<div class="d-flex align-items-center mb-3">
    <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h3 class="mb-0">Reports and Analytics</h3>
</div>

<div class="container mt-4 w-100">
    <div class="exportBtn d-flex justify-content-end align-items-center mb-3">
        <!-- Year Filter -->
        <label for="reportYear" class="me-2 mb-0">Select Year:</label>
        <select id="reportYear" class="form-select w-auto me-3">
            <option value="">All Years</option>
            @for ($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        <!-- Print Button -->
        <button onclick="printSection('narrativeSection')" id="printTable" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print Narrative Report
        </button>
    </div>

    <div class="grid-wrapper mt-3">
        <!-- Major A card -->
        <div class="severity-card">
            <div class="severity-header">
                <span class="severity-title">Major A</span>
                <div class="bi bi-exclamation-diamond-fill" style="color: #fd8c00;"></div>
            </div>
            <div class="severity-value">{{ $counts['Major A'] }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #fd8c00;"></div>
            </div>
            <span class="background-letter" style="color: #fd8b0010;">A</span>
        </div>

        <!-- Major B card -->
        <div class="severity-card">
            <div class="severity-header">
                <span class="severity-title">Major B</span>
                <div class="bi bi-exclamation-diamond-fill" style="color: #dc0000;"></div>
            </div>
            <div class="severity-value">{{ $counts['Major B'] }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #dc0000;"></div>
            </div>
            <span class="background-letter" style="color: #dc000010;">B</span>
        </div>

        <!-- Major C card -->
        <div class="severity-card">
            <div class="severity-header">
                <span class="severity-title">Major C</span>
                <div class="bi bi-exclamation-diamond-fill" style="color: #780000;"></div>
            </div>
            <div class="severity-value">{{ $counts['Major C'] }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #780000;"></div>
            </div>
            <span class="background-letter" style="color: #78000010;">C</span>
        </div>

        <!-- Major D card -->
        <div class="severity-card">
            <div class="severity-header">
                <span class="severity-title">Major D</span>
                <div class="bi bi-exclamation-diamond-fill" style="color: #780000;"></div>
            </div>
            <div class="severity-value">{{ $counts['Major D'] }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #780000;"></div>
            </div>
            <span class="background-letter" style="color: #78000010;">D</span>
        </div>
    </div>

    <!-- Minor card -->
    <div class="minor-container mt-4">
        <div class="card-container">
            <div class="minor-card">
                <div class="severity-header">
                    <span class="severity-title">Minor</span>
                    <div class="bi bi-exclamation-diamond-fill" style="color: #FDC500;"></div>
                </div>
                <div class="severity-value">{{ $counts['Minor'] }}</div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #FDC500;"></div>
                </div>
                <span class="background-letter" style="color: #FDC50010;">M</span>
            </div>
        </div>
    </div>

    <!-- Narrative Report Section -->
    <div class="container mt-5">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-3">Narrative Report Summary</h4>
            <div id="narrativeSection">
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
</div>

<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Print Narrative Report
    function printSection(sectionId) {
        const content = document.getElementById(sectionId).innerHTML;
        const printWindow = window.open('', '', 'height=700,width=900');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Report</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
                    <style>
                        body { font-family: 'Segoe UI', sans-serif; padding: 40px; line-height: 1.8; text-align: justify; }
                        p { margin-bottom: 1.2rem; }
                        table, th, td { border: 1px solid #000 !important; border-collapse: collapse !important; }
                        th, td { padding: 8px !important; }
                    </style>
                </head>
                <body>${content}</body>
            </html>
        `);
        printWindow.print();
        printWindow.document.close();
    }

    // Reload page on year filter change
    $('#reportYear').on('change', function() {
        const year = $(this).val();
        let url = new URL(window.location.href);
        if (year) {
            url.searchParams.set('year', year);
        } else {
            url.searchParams.delete('year');
        }
        window.location.href = url.toString();
    });
</script>
