<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\postviolation;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //

    public function getViolationsRecords(Request $request){
    $user = Auth::user();
    $fullName = $user->firstname . ' ' . $user->lastname;

    // Eager load related models
    $violations = postviolation::with(['violation', 'penalty', 'referal','status'])
        ->where('student_name', $fullName)
        ->get();

    return response()->json($violations);
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
            'appeal' => $appealReason
        ]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Violation not found']);

}
}