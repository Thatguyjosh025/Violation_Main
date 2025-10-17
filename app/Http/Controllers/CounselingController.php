<?php

namespace App\Http\Controllers;

use App\Models\counseling;
use Illuminate\Http\Request;
use App\Models\postviolation;

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

        // Attempt to create the counseling record
        $counseling = Counseling::create([
            'student_no'      => $validated['student_no'],  
            'student_name'    => $validated['student_name'],
            'school_email'    => $validated['school_email'],
            'violation'       => $validated['violation'],
            'status'          => 1, // Set to 'Pending Intake' status
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

        // Only update postviolation if counseling was created
        if ($counseling) {
            postviolation::where('student_no', $validated['student_no'])
                ->update(['is_admitted' => true]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to create counseling record.'], 500);
    }

    public function getSession($id)
    {
        $session = Counseling::findOrFail($id);
        return response()->json($session);
    }
}
