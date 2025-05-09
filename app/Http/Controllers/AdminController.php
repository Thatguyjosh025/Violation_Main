<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\rules;
use App\Models\users;
use App\Models\incident;
use App\Models\violation;
use Illuminate\Http\Request;
use App\Models\notifications;
use App\Models\postviolation;

class AdminController extends Controller
{
    //

    public function getRule($violation_id) {
        $rule = rules::find($violation_id);
    
        if (!$rule) {
            return response()->json(['error' => 'No rule found'], 404);
        }
    
        return response()->json([
            'rule_name' => $rule->rule_name,
            'description' => $rule->description, 
            'severity_name' => $rule->severity->severity
        ]);
    }

    public function getStudentViolations($name, $studentNo){
    $violations = postviolation::where('student_name', $name)
        ->where('student_no', $studentNo)
        ->with('violation')
        ->get()
        ->map(function ($violation) {
            return [
                'type' => optional($violation->violation)->violations,
                'date' => $violation->Date_Created,
                'violatedrule' => $violation->rule_Name,
            ];
        });

    return response()->json($violations);
}

    public function postviolation(Request $request){
        $request->validate([
            'student_no' => 'required|string',
            'student_name' => 'required|string',
            'course' => 'required|string',
            'school_email' => 'required|string',
            'violation_type' => 'required|integer',
            'penalty_type' => 'required|integer',
            'severity_Name' => 'required|string',
            'rule_Name' => 'required|string',
            'description_Name' => 'required|string',
            'faculty_involvement' => 'required|string',
            'counseling_required' => 'required|string',
            'faculty_name' => 'required|string',
            'referal_type' => 'required|string',
            'Remarks' => 'required|string|max:500',
            'appeal' => 'required|string|max:500',
            'upload_evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
        ]);
    
        if ($request->hasFile('upload_evidence')) {
            $evidencePath = $request->file('upload_evidence')->store('evidence', 'public');
        } else {
            $evidencePath = null;
        }
    
        $create = postviolation::create([
            'student_no' => $request->student_no,
            'student_name' => $request->student_name,
            'course' => $request->course,
            'school_email' => $request->school_email,
            'violation_type' => $request->violation_type,
            'penalty_type' => $request->penalty_type,
            'severity_Name' => $request->severity_Name,
            'status_name' => 1,
            'rule_Name' => $request->rule_Name,
            'description_Name' => $request->description_Name,
            'faculty_involvement' => $request->faculty_involvement,
            'counseling_required' => $request->counseling_required,
            'faculty_name' => $request->faculty_name,
            'referal_type' => $request->referal_type,
            'Remarks' => $request->Remarks,
            'Notes' => null,
            'appeal' => $request->appeal,
            'upload_evidence' => $evidencePath,
            'Date_Created' => Carbon::now('Asia/Manila'),
            'Update_at' => Carbon::now('Asia/Manila'),
            'is_active' => true
        ]);

        //notification handler
        $notif = notifications::create([
            'title' => 'New Active Violation',
            'message' => 'A new violation has been assigned to you',
            'role' => 'student',
            'student_no' => $request->student_no,
            'type' => 'posted',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
        ]);

    
        $create->load('referal', 'violation', 'penalty', 'status');

        // incident report update contoller Check if the incident_id is provided
        if ($request->filled('incident_id')) { 
            $incident = incident::find($request->incident_id); //then this line finds if the id input is matched in the other table
            if ($incident) {
                $incident->update(['is_visible' => 'approve']);

                $notif = notifications::create([
                    'title' => 'Incident Approval',
                    'message' => 'Your Incident Report has been approve',
                    'role' => 'faculty',
                    'student_no' => null,
                    'type' => 'approve',
                    'date_created' => Carbon::now()->format('Y-m-d'),
                    'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
                ]);
            
                return response()->json(['message' => 'Updated']);
            }
        }
    
        return response()->json([
            'postviolation' => [
                'student_no' => $create->student_no,
                'student_name' => $create->student_name,
                'school_email' => $create->school_email,
                'violation_name' => $create->violation->violations, 
                'status_name' => $create->status->status,
                'Date_Created' => $create->Date_Created->format('y-m-d')
            ],
            'message' => 'test',
            'related_id' => $create->id
        ]);
    }

    //view student info
    public function getStudentInfo(Request $request){
        $student = postviolation::with(['violation', 'status', 'penalty', 'referal'])
        ->find($request->query('id'));
    
        if (!$student) {
            return response()->json(['status' => 500, 'message' => 'Student not found']);
        }
    
        return response()->json([
            'status' => 200,
            'data' => [
                'view_id' => $student->id,
                'student_no' => $student->student_no,
                'student_name' => $student->student_name,
                'course' => $student->course,
                'school_email' => $student->school_email,
                'violation_type' => $student->violation->violation_id,
                'violation_name' => $student->violation->violations,
                'penalty_type' => $student->penalty->penalties_id,
                'penalty_name' => $student->penalty->penalties,  
                'severity_Name' => $student->severity_Name,
                'status_name' => $student->status->status_id, 
                'status_label' => $student->status->status, 
                'rule_Name' => $student->rule_Name,
                'description_Name' => $student->description_Name,
                'faculty_involvement' => $student->faculty_involvement,
                'faculty_name' => $student->faculty_name,
                'counseling_required' => $student->counseling_required,
                'referal_type' => $student->referal->referal_id,
                'referal_name' => $student->referal->referals,
                'Remarks' => $student->Remarks,
                'appeal' => $student->appeal,
                'upload_evidence' => $student->upload_evidence,
                'Date_Created' => $student->Date_Created,
            ]
        ]);
    }

    public function updateStudentInfo(Request $request, $id){
    $student = postviolation::find($id);
    
    if (!$student) {
        return response()->json(['status' => 500, 'message' => 'Student not found']);
    }

    $student->update([
        'student_no' => $request->update_student_no,
        'student_name' => $request->update_name,
        'course' => $request->update_course,
        'school_email' => $request->update_school_email,
        'violation_type' => $request->update_violation_type,
        'penalty_type' => $request->update_penalty_type,
        'severity_Name' => $request->update_severity,
        'status_name' => $request->update_status,
        'rule_Name' => $request->update_rule_name,
        'description_Name' => $request->update_description,
        'faculty_involvement' => $request->update_faculty_involvement,
        'faculty_name' => $request->update_faculty_name,
        'counseling_required' => $request->update_counseling_required,
        'referal_type' => $request->update_referral_type,
        'Remarks' => $request->update_remarks,
        'Notes' => $request->update_notes,
        'Update_at' => Carbon::now('Asia/Manila')
    ]);

        return response()->json(['status' => 200, 'message' => 'Student information updated successfully']);
    }


// View incident report info
public function getIncidentInfo(Request $request)
{
    $incident = incident::with('violation')->find($request->query('id'));

    if (!$incident) {
        return response()->json(['status' => 500, 'message' => 'Incident not found']);
    }

    return response()->json([
        'status' => 200,
        'data' => [
            'id' => $incident->id,
            'student_name' => $incident->student_name,
            'student_no' => $incident->student_no,
            'course_section' => $incident->course_section,
            'school_email' => $incident->school_email,
            'violation_type' => $incident->violation_type,
            'violation_name' => $incident->violation->violations,
            'penalty_name' => $incident->penalties,
            'rule_name' => $incident->rule_name,
            'description' => $incident->description,
            'severity' => $incident->severity,
            'faculty_name' => $incident->faculty_name,
            'remarks' => $incident->remarks,
            'upload_evidence' => $incident->upload_evidence ?? 'N/A',
            'Date_Created' => $incident->Date_Created,
        ]
    ]);

}

public function UpdateRejected(Request $request)
{
    $incident = incident::findOrFail($request->id);
    $incident->is_visible = 'reject'; 
    $incident->save();

    $notif = notifications::create([
        'title' => 'Incident Rejected',
        'message' => 'Your Incident Report has been rejected',
        'role' => 'faculty',
        'student_no' => null,
        'type' => 'approve',
        'date_created' => Carbon::now()->format('Y-m-d'),
        'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
    ]);

    return response()->json(['message' => 'Incident rejected successfully.']);
}

public function archive($id)
{
    $violation = postviolation::find($id);

    if (!$violation) {
        return response()->json(['message' => 'Record not found'], 404);
    }

    $violation->is_active = false;
    $violation->save();

    return response()->json(['message' => 'Violation archived successfully.']);
}

//student search
public function student_search(Request $request)
{
    $query = $request->get('query');

    $students = users::where('role', 'student')
        ->where(function($q) use ($query) {
            $q->where('firstname', 'LIKE', "%{$query}%")
              ->orWhere('lastname', 'LIKE', "%{$query}%")
              ->orWhere('student_no', 'LIKE', "%{$query}%");
        })->get();

    return response()->json($students);
}
}
