<?php

namespace App\Http\Controllers;

use App\Models\incident;
use App\Models\notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FacultyController extends Controller
{
    public function submitIncidentReport(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string',
            'student_no' => 'required|string',
            'school_email' => 'required|email',
            'violation_type' => 'required|integer',
            'rule_name' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|string',
            'faculty_name' => 'required|string',
            'faculty_id' => 'required|string',
            'remarks' => 'required|string|max:500',
            'upload_evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);

        $evidencePaths = [];
        if ($request->hasFile('upload_evidence')) {
            foreach ($request->file('upload_evidence') as $file) {
                $evidencePaths[] = $file->store('incident_evidence', 'public');
            }
        }

        $incident = incident::create([
            'student_name' => $request->student_name,
            'student_no' => $request->student_no,
            'school_email' => $request->school_email,
            'faculty_name' => $request->faculty_name,
            'faculty_id' => $request->faculty_id,
            'violation_type' => $request->violation_type,
            'rule_name' => $request->rule_name,
            'description' => $request->description,
            'severity' => $request->severity,
            'remarks' => $request->remarks,
            'status' => 'Pending',
            'upload_evidence' => !empty($evidencePaths) ? json_encode($evidencePaths) : null,
            'is_visible' => 'show',
            'Date_Created' => Carbon::now('Asia/Manila'),
        ]);

        notifications::create([
            'title' => 'Incident Report',
            'message' => 'A new incident report has been submitted.',
            'role' => 'admin',
            'student_no' => $request->faculty_id,
            'school_email' => $request->school_email,
            'type' => 'incident',
            'url' => '/incident_report',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A'),
        ]);

        return response()->json([
            'message' => 'Incident report submitted successfully.',
            'data' => $incident
        ]);
    }
}
