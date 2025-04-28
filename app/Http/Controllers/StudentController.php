<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\notifications;
use App\Models\postviolation;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //

    public function getViolationsRecords(Request $request){
        $user = Auth::user();
        $student_number = $user->student_no;
    
        // Eager load related models
        $violations = postviolation::with(['violation', 'penalty', 'referal', 'status'])
            ->where('student_no', $student_number)
            ->get();
    
        // Map the violations to include the desired fields
        $mappedViolations = $violations->map(function ($violation) {
            return [
                'id' => $violation->id,
                'student_name' => $violation->student_name,
                'type' => optional($violation->violation)->violations,
                'date' => $violation->Date_Created,
                'rule_Name' => $violation->rule_Name,
                'description_Name' => $violation->description_Name,
                'severity_Name' => $violation->severity_Name,
                'penalties' => optional($violation->penalty)->penalties, 
                'referals' => optional($violation->referal)->referals, 
                'status' => optional($violation->status)->status, 
                'Remarks' => $violation->Remarks,
            ];
        });
    
        return response()->json($mappedViolations);
    }
public function updateAppealReason(Request $request)
{
    $studentId = $request->input('studentId');
    $studentName = $request->input('studentName');
    $appealReason = $request->input('appealReason');

    $violation = postviolation::where('id', $studentId)
                              ->where('student_name', $studentName)
                              ->first();

    if ($violation) {
        $violation->update([
            'appeal' => $appealReason,
            'status_name' => 5             
        ]);
        $notif = notifications::create([
            'title' => 'Student Appeal Submitted',
            'message' => 'A student has submitted an appeal regarding a violation.',
            'role' => 'admin',
            'student_no' => null,
            'type' => 'incident',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A')
        ]);
       
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Violation not found']);

}
}