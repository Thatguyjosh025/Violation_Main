<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\users;
use Illuminate\Http\Request;
use App\Models\notifications;
use App\Models\postviolation;
use Symfony\Component\Clock\now;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //

    public function getViolationsRecords(Request $request){
        $user = Auth::user();
        $student_number = $user->student_no;
        $school_email = $user->email;
    
        $violations = postviolation::with(['violation', 'penalty', 'referal', 'status'])
            ->where('student_no', $student_number)
            ->get();

        // 5 mins violation demo expiration check
        foreach ($violations as $violation) {
            $createdDate = Carbon::parse($violation->Date_Created,'Asia/Manila'); // Note: always set the timezone in asia this is fucking sucks
            $now = Carbon::now('Asia/Manila');
            $minutesSinceCreated = $createdDate->diffInMinutes($now);

            if ($minutesSinceCreated > 10 && $violation->appeal === 'N/A') {
                $violation->appeal = 'No Objection';
                $violation->status_name = 3;
                $violation->is_active = true;
                $violation->save();

                notifications::create([
                    'title' => 'Violation Automatically Finalized',
                    'message' => 'Your violation has been automatically marked as Confirmed status due to no student appeal within the allowed time.',
                    'role' => 'student',
                    'student_no' => $student_number,
                    'school_email' => $school_email,
                    'type' => 'incident',
                    'url' => '/violation_tracking',
                    'date_created' => Carbon::now()->format('Y-m-d'),
                    'created_time' => Carbon::now('Asia/Manila')->format('h:i A'),
                ]);
            }
        }


        // ------------------------------------------------------------------------------------------------------------------------
        // IMPORTANT: DO NOT DELETE THIS BLOCK OF CODE
        // ------------------------------------------------------------------------------------------------------------------------

        // 3-day demo expiration check 
        // foreach ($violations as $violation) {
        //     $createdDate = Carbon::parse($violation->Date_Created);
        //     $now = Carbon::now();
        //     $daysSinceCreated = $createdDate->diffInDays($now);

        //     if ($daysSinceCreated > 3 && $violation->appeal === 'N/A') {
        //         $violation->appeal = 'No Objection';
        //         $violation->status_name = 3;
        //         $violation->save();

        //         notifications::create([
        //             'title' => 'Violation Automatically Finalized',
        //             'message' => 'Your violation has been automatically marked as Confirmed status due to no student appeal within the allowed time.',
        //             'role' => 'student',
        //             'student_no' =>  $student_number,
        //             'type' => 'incident',
        //             'date_created' => Carbon::now()->format('Y-m-d'),
        //             'created_time' => Carbon::now('Asia/Manila')->format('h:i A'),
        //         ]);
        //     }
        //     else if ($daysSinceCreated >= 1 && $violation->appeal === 'N/A') {
        //         notifications::create([
        //             'title' => 'Warning',
        //             'message' => 'Warning final warning',
        //             'role' => 'student',
        //             'student_no' =>  $student_number,
        //             'type' => 'incident',
        //             'date_created' => Carbon::now()->format('Y-m-d'),
        //             'created_time' => Carbon::now('Asia/Manila')->format('h:i A'),
        //         ]);

        //         // Prevent duplicate warning
        //         $violation->appeal = 'Warning'; 
        //         $violation->save();
        //     }
        // }


        $mappedViolations = $violations->map(function ($violation) {
            $sectionId = '';
            if ($violation->violation->violations === 'Bullying') {
                $sectionId = 'antibullyingsection';
            } elseif ($violation->violation->violations === 'Discourtesy or Disrespect') {
                $sectionId = 'antisexualsection';
            } elseif ($violation->violation->violations === 'Improper Use of School Facilities/Equipment'){
                $sectionId = 'improperuseoffacilities';
            }
            else{
                $sectionId = 'frontpage';
            }
            
            return [
                'id' => $violation->id,
                'student_name' => $violation->student_name,
                'type' => optional($violation->violation)->violations,
                'date' => Carbon::parse($violation->Date_Created)->format('Y-m-d'),
                'rule_Name' => $violation->rule_Name,
                'section_Id' => $sectionId,
                'description_Name' => $violation->description_Name,
                'severity_Name' => $violation->severity_Name,
                'penalties' => optional($violation->penalty)->penalties, 
                'referals' => optional($violation->referal)->referals, 
                'status' => optional($violation->status)->status, 
                'Remarks' => $violation->Remarks,
                'upload_evidence' => $violation->upload_evidence,
                'appeal' => $violation->appeal,
                
            ];
        });

    
        return response()->json($mappedViolations);
    }

  public function updateAppealReason(Request $request)
{
    $studentId = $request->input('studentId');
    $studentName = $request->input('studentName');
    $appealReason = $request->input('appealReason');
    $user = Auth::user();
    $school_email = $user->email;

    $violation = postviolation::where('id', $studentId)
                              ->where('student_name', $studentName)
                              ->first();

    if (!$violation) {
        return response()->json(['success' => false, 'message' => 'Violation not found']);
    }

    $uploadedFiles = [];
    if ($request->hasFile('upload_appeal_evidence')) {
        foreach ($request->file('upload_appeal_evidence') as $file) {
            $path = $file->store('appeal_evidence', 'public');
            $uploadedFiles[] = $path;
        }
    }

    if ($appealReason === 'No Objection') {
        $violation->update([
            'appeal' => $appealReason,
            'status_name' => 3,
        ]);
    } elseif (!empty($appealReason) && $appealReason !== 'N/A') {
        $violation->update([
            'appeal' => $appealReason,
            'status_name' => 5,
            'appeal_evidence' => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
        ]);

        notifications::create([
            'title' => 'Student Appeal Submitted',
            'message' => 'A student has submitted an appeal regarding a violation.',
            'role' => 'admin',
            'student_no' => null,
            'school_email' => $school_email,
            'type' => 'incident',
            'url' => '/violation_records',
            'date_created' => Carbon::now()->format('Y-m-d'),
            'created_time' => Carbon::now('Asia/Manila')->format('h:i A'),
        ]);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid appeal input.']);
    }

    return response()->json(['success' => true]);
}
}