<?php

namespace App\Http\Controllers;

use App\Models\incident;
use Illuminate\Http\Request;
use App\Models\notifications;
use Illuminate\Support\Carbon;

class FacultyController extends Controller
{
    //
    public function submitIncidentReport(Request $request){
        $request->validate([
            'student_name' => 'required|string',
            'student_no' => 'required|string',
            'course_section' => 'required|string',
            'school_email' => 'required|email',
            'violation_type' => 'required|integer',
            'rule_name' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|string',
            'faculty_name' => 'required|string',
            'remarks' => 'required|string|max:500',
            'upload_evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);
    
        if ($request->hasFile('upload_evidence')) {
            $evidencePath = $request->file('upload_evidence')->store('incident_evidence', 'public');
        } else {
            $evidencePath = null;
        }
    
        $create = incident::create([
            'student_name' => $request->student_name,
            'student_no' => $request->student_no,
            'course_section' => $request->course_section,
            'school_email' => $request->school_email,
            'faculty_name' => $request->faculty_name,
            'violation_type' => $request->violation_type,
            'rule_name' => $request->rule_name,
            'description' => $request->description,
            'severity' => $request->severity,
            'remarks' => $request->remarks,
            'status' => 'Pending',
            'upload_evidence' => $evidencePath,
            'is_visible' => 'show',
            'Date_Created' => Carbon::now()
        ]);
    
        $create->load('violation');
    
        $notif = notifications::create([
            'title' => 'Incident Report',
            'message' => 'You have new incident report',
            'role' => 'admin',
            'student_no' => null,
            'type' => 'incident',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
        ]);
    
        return response()->json([
            'incidentreport' => [
                'message' => 'Incident report submitted successfully.',
                'violation_type' => $create->violation->violations, 
            ]
        ]);
    }
}
