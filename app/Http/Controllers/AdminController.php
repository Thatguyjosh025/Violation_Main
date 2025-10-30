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
use App\Models\statuses;

class AdminController extends Controller
{
    //

    public function getRule($violation_id) {
        $rule = rules::where('violation_id', $violation_id)
            ->with('severity')
            ->first();

        if (!$rule) {
            return response()->json([
                'rule_name'     => '-',
                'description'   => '-',
                'severity_name' => '-',
                'test no rule found'
            ]);
        }

        return response()->json([
            'rule_name'     => $rule->rule_name,
            'description'   => $rule->description,
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

    //submit violation
    public function postviolation(Request $request)
    {
        $request->validate([
            'student_no' => 'required|string',
            'student_name' => 'required|string',
            // 'course' => 'required|string',  <------ DELTETE THIS MF
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
            'upload_evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
        ]);

        $evidencePaths = [];
        if ($request->hasFile('upload_evidence')) {
            foreach ($request->file('upload_evidence') as $file) {
                $evidencePaths[] = $file->store('evidence', 'public');
            }
        }

        $evidenceJson = !empty($evidencePaths) ? json_encode($evidencePaths) : null;

        if ($request->filled('incident_id')) {

            $incident = incident::find($request->incident_id);
            $incidentEvidence = [];
            if ($incident && !empty($incident->upload_evidence)) {
                // Incident evidence may be stored as JSON or array
                if (is_string($incident->upload_evidence)) {
                    $decoded = json_decode($incident->upload_evidence, true);
                    $incidentEvidence = is_array($decoded) ? $decoded : [$incident->upload_evidence];
                } elseif (is_array($incident->upload_evidence)) {
                    $incidentEvidence = $incident->upload_evidence;
                }
            }

            $newUploads = [];
            if ($request->hasFile('upload_evidence')) {
                foreach ($request->file('upload_evidence') as $file) {
                    $newUploads[] = $file->store('evidence', 'public');
                }
            }

            // Merge incident evidence and new uploads
            $allEvidence = array_merge($incidentEvidence, $newUploads);
            $evidenceJson = !empty($allEvidence) ? json_encode($allEvidence) : null;

            // Create postviolation record
            $create = postviolation::create([
                'student_no' => $request->student_no,
                'student_name' => $request->student_name,
                'school_email' => $request->school_email,
                'violation_type' => $request->violation_type,
                'penalty_type' => $request->penalty_type,
                'severity_Name' => $request->severity_Name,
                'status_name' => 2,
                'rule_Name' => $request->rule_Name,
                'description_Name' => $request->description_Name,
                'faculty_involvement' => $request->faculty_involvement,
                'counseling_required' => $request->counseling_required,
                'faculty_name' => $request->faculty_name,
                'referal_type' => $request->referal_type,
                'Remarks' => $request->Remarks,
                'Notes' => null,
                'appeal' => $request->appeal,
                'upload_evidence' => $evidenceJson,
                'Date_Created' => Carbon::now('Asia/Manila'),
                'Update_at' => Carbon::now('Asia/Manila'),
                'is_active' => true,
                'is_admitted' => ($request->counseling_required === 'Yes') ? true : false,
            ]);

            // Delete incident and notify faculty
            if ($incident) {
                $facultyId = $incident->faculty_id;
                $incident->delete();

                notifications::create([
                    'title' => 'Incident Approval',
                    'message' => 'Your Incident Report has been approved',
                    'role' => 'faculty',
                    'student_no' => $facultyId,
                    'school_email' => $create->school_email,
                    'type' => 'approve',
                    'url' => '/faculty_incident',
                    'date_created' => Carbon::now()->format('Y-m-d'),
                    'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
                ]);
            }

            return response()->json(['message' => 'Updated']);
        } else {
            $create = postviolation::create([
                'student_no' => $request->student_no,
                'student_name' => $request->student_name,
                // 'course' => $request->course,  <------ DELETE THIS MF
                'school_email' => $request->school_email,
                'violation_type' => $request->violation_type,
                'penalty_type' => $request->penalty_type,
                'severity_Name' => $request->severity_Name,
                'status_name' => 2,
                'rule_Name' => $request->rule_Name,
                'description_Name' => $request->description_Name,
                'faculty_involvement' => $request->faculty_involvement,
                'counseling_required' => $request->counseling_required,
                'faculty_name' => $request->faculty_name,
                'referal_type' => $request->referal_type,
                'Remarks' => $request->Remarks,
                'Notes' => null,
                'appeal' => $request->appeal,
                'upload_evidence' => $evidenceJson,
                'Date_Created' => Carbon::now('Asia/Manila'),
                'Update_at' => Carbon::now('Asia/Manila'),
                'is_active' => true,
                'is_admitted' => ($request->counseling_required === 'Yes') ? true : false,
            ]);
        }

        $notif = notifications::create([
            'title' => 'New Active Violation',
            'message' => 'A new violation has been assigned to you',
            'role' => 'student',
            'student_no' => $request->student_no,
            'school_email' => $request->school_email,
            'type' => 'posted',
            'url' => '/violation_tracking',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
        ]);

        $create->load('referal', 'violation', 'penalty', 'status');

        return response()->json([
            'postviolation' => [
                'student_no' => $create->student_no,
                'student_name' => $create->student_name,
                'school_email' => $create->school_email,
                'violation_name' => $create->violation->violations,
                'status_name' => $create->status->status,
                'Date_Created' => $create->Date_Created->format('Y-m-d')
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
                // 'course' => $student->course,  <------ DELETE THIS MF
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
                'appeal_evidence' => $student->appeal_evidence,
                'notes' => $student->Notes,
                'Date_Created' => Carbon::parse($student->Date_Created)->format('Y-m-d'),
            ]
        ]);
    }

    public function updateStudentInfo(Request $request, $id)
    {
        $student = postviolation::with('status')->find($id);

        if (!$student) {
            return response()->json(['status' => 500, 'message' => 'Student not found']);
        }

        $oldStatus = $student->status ? $student->status->status_text : null;

        $student->update([
            'student_no' => $request->update_student_no,
            'student_name' => $request->update_name,
            // 'course' => $request->update_course, <------ DELETE THIS MF
            'school_email' => $request->update_school_email,
            'violation_type' => $request->update_violation_type,
            'penalty_type' => $request->update_penalty_type,
            'severity_Name' => $request->update_severity,
            'status_name' => $request->update_status, // this is the new status ID
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

        $newStatusText = statuses::find($request->update_status)->status ?? 'Unknown';

        if ($newStatusText === 'Resolved') {
            $student->is_active = false;
            $student->save();
        }

        if ($oldStatus != $newStatusText) {
            $url = ($newStatusText === 'Resolved') ? '/violation_history' : '/violation_tracking';

            $notif = notifications::create([
                'title' => 'Violation Status Update',
                'message' => 'Your violation has been escalated to ' . $newStatusText,
                'role' => 'student',
                'student_no' => $request->update_student_no,
                'school_email' => $request->update_school_email,
                'type' => 'approve',
                'url' => $url,
                'date_created' => Carbon::now()->format('Y-m-d'),
                'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'Student information updated successfully']);
    }


// View incident report info
public function getIncidentInfo(Request $request)
{
    $incident = incident::with('violation')->find($request->query('id'));

    if (!$incident) {
        return response()->json(['status' => 500, 'message' => 'Incident not found']);
    }

    // Convert upload_evidence to array if stored as JSON
    $files = [];
    if (!empty($incident->upload_evidence)) {
        if (is_string($incident->upload_evidence)) {
            $decoded = json_decode($incident->upload_evidence, true);
            $files = is_array($decoded) ? $decoded : [$incident->upload_evidence];
        } elseif (is_array($incident->upload_evidence)) {
            $files = $incident->upload_evidence;
        }
    }

    return response()->json([
        'status' => 200,
        'data' => [
            'id' => $incident->id,
            'student_name' => $incident->student_name,
            'student_no' => $incident->student_no,
            'school_email' => $incident->school_email,
            'violation_type' => $incident->violation_type,
            'violation_name' => $incident->violation->violations,
            'penalty_name' => $incident->penalties,
            'rule_name' => $incident->rule_name,
            'description' => $incident->description,
            'severity' => $incident->severity,
            'faculty_name' => $incident->faculty_name,
            'remarks' => $incident->remarks,
            'upload_evidence' => $files,
            'Date_Created' => $incident->Date_Created,
        ]
    ]);
}

public function UpdateRejected(Request $request){
    $incident = incident::findOrFail($request->id);
    $incident->is_visible = 'reject'; 
    $incident->save();

    $facultyId = $incident->faculty_id;

    $notif = notifications::create([
        'title' => 'Incident Rejected',
        'message' => 'Your Incident Report has been rejected',
        'role' => 'faculty',
        'student_no' => $facultyId,
        'school_email' => $incident -> school_email,
        'type' => 'approve',
        'date_created' => Carbon::now()->format('Y-m-d'),
        'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
    ]);

    return response()->json(['message' => 'Incident rejected successfully.']);
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
