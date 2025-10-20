<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\counseling;
use Illuminate\Http\Request;
use App\Models\postviolation;
use Illuminate\Support\Facades\DB;

class CounselingController extends Controller
{
    //
    public function fetchCounselingReport($id)
    {
        $record = postviolation::with(['violation', 'status'])->find($id);

        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json([
            'name' => $record->student_name,
            'student_no' => $record->student_no,
            'school_email' => $record->school_email,
            'violation' => $record->violation->violations,
            'severity' => $record->severity_Name ?? 'N/A',
            'remarks' => $record->remarks ?? 'No remarks provided',
        ]);
    }

  public function storeCounselingSchedule(Request $request)
    {
        $validated = $request->validate([
            'student_no'   => 'required|string',
            'student_name' => 'required|string',
            'school_email' => 'required|email',
            'violation'    => 'required|string',
            'severity'     => 'required|string',
            'start_date'   => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        $start = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['start_date'] . ' ' . $validated['end_time']);

        // cant schedule time backwards type shi
        if ($end->lessThanOrEqualTo($start)) {
            return response()->json([
                'success' => false,
                'message' => 'End time must be after start time on the same day.'
            ]);
        }

        $startFormatted = $start->format('g:i A');
        $endFormatted = $end->format('g:i A');

        // check for time conflicts like frfrfr
        $conflict = DB::table('tb_counseling')
            ->where('start_date', $validated['start_date'])
            ->whereRaw('? < end_time AND ? > start_time', [
                $start->format('H:i:s'),
                $end->format('H:i:s')
            ])
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => "The time slot from {$startFormatted} to {$endFormatted} is already occupied. Please choose a different time."
            ]);
        }

        $counseling = Counseling::create([
            'student_no'      => $validated['student_no'],  
            'student_name'    => $validated['student_name'],
            'school_email'    => $validated['school_email'],
            'violation'       => $validated['violation'],
            'status'          => 2,
            'severity'        => $validated['severity'],
            'start_date'      => $validated['start_date'],
            'end_date'        => null,
            'start_time'      => $validated['start_time'],
            'end_time'        => $validated['end_time'],
            'session_notes'   => null,
            'emotional_state' => null,
            'behavior_observe'=> null,
            'plan_goals'      => null,
        ]);

        if ($counseling) {
            postviolation::where('student_no', $validated['student_no'])
                ->update(['is_admitted' => true]);

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create counseling record.'
        ], 500);
    }
    public function getSession($id)
    {
        $session = Counseling::findOrFail($id);
        return response()->json($session);
    }
    public function updateSession(Request $request, $id)
    {
        $session = counseling::findOrFail($id);

        $validated = $request->validate([
            'session_notes' => 'nullable|string',
            'emotional_state' => 'nullable|string',
            'behavior_observe' => 'nullable|string',
            'plan_goals' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $resolvedStatusId = 5; // e.g. "Resolved" status
        $endDate = ($validated['status'] == $resolvedStatusId)
            ? Carbon::now()->toDateString()
            : null;

        $session->update([
            'session_notes' => $validated['session_notes'] ?? $session->session_notes,
            'emotional_state' => $validated['emotional_state'] ?? $session->emotional_state,
            'behavior_observe' => $validated['behavior_observe'] ?? $session->behavior_observe,
            'plan_goals' => $validated['plan_goals'] ?? $session->plan_goals,
            'status' => $validated['status'],
            'end_date' => $endDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Counseling session updated successfully.',
            'end_date' => $endDate,
        ]);
    }
    public function rescheduleSession(Request $request, $id)
    {
        $session = counseling::findOrFail($id);

        $validated = $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $start = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['start_date'] . ' ' . $validated['end_time']);

        // Prevent scheduling backward
        if ($end->lessThanOrEqualTo($start)) {
            return response()->json([
                'success' => false,
                'message' => 'End time must be after start time.',
            ]);
        }

        // Check conflicts
        $conflict = DB::table('tb_counseling')
            ->where('id', '!=', $id)
            ->where('start_date', $validated['start_date'])
            ->whereRaw('? < end_time AND ? > start_time', [
                $start->format('H:i:s'),
                $end->format('H:i:s'),
            ])
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is already occupied. Please choose a different time.',
            ]);
        }

        $session->update([
            'start_date' => $validated['start_date'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            // 'status' => 'Rescheduled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session successfully rescheduled.',
        ]);
    }
}
