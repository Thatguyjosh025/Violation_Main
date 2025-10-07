<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\postviolation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function showNarrative()
    {
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

        return view('discipline.ReportandAnalytics', compact('narratives'));
    }
}
