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
            'priority_level'=> 'required|integer',
            'guidance_service'=> 'required|integer',
            'start_date'   => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        $validationResult = $this->validateScheduleTime($validated);
        if (!$validationResult['success']) {
            return response()->json($validationResult);
        }

        // create a NEW parent UID for storeCounselingSchedule
        $latestUid = Counseling::whereNotNull('parent_uid')
            ->orderBy('id', 'desc')
            ->value('parent_uid');

        if ($latestUid) {
            $number = (int) str_replace('SNS-', '', $latestUid);
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }

        $parent_uid = 'SNS-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $parent_session_id = null; // 🧩 FIX: Always null for new roots

        // Create record
        $counseling = Counseling::create([
            'student_no'       => $validated['student_no'],
            'student_name'     => $validated['student_name'],
            'school_email'     => $validated['school_email'],
            'violation'        => $validated['violation'],
            'status'           => 2,
            'severity'         => $validated['severity'],
            'priority_risk'    => $validated['priority_level'],
            'year_level'       => null,
            'program'          => null,
            'guidance_service' => $validated['guidance_service'],
            'start_date'       => $validated['start_date'],
            'end_date'         => null,
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'],
            'session_notes'    => null,
            'emotional_state'  => null,
            'plan_goals'       => null,
            'parent_uid'       => $parent_uid,         
            'parent_session_id'=> $parent_session_id,  
        ]);

        if ($counseling) {
            postviolation::where('student_no', $validated['student_no'])
                ->update(['is_admitted' => false]);

            return response()->json([
                'success' => true,
                'message' => "Counseling session scheduled successfully.",
                'parent_uid' => $parent_uid,
                'parent_session_id' => $parent_session_id
            ]);
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
        $session = Counseling::findOrFail($id);

        $validated = $request->validate([
            'year_level'        => 'nullable|string',
            'program'           => 'nullable|string',
            'session_notes'     => 'nullable|string',
            'emotional_state'   => 'nullable|string',
            'behavior_observe'  => 'nullable|string',
            'plan_goals'        => 'nullable|string',
            'status'            => 'required|integer',
        ]);

        $resolvedStatusId = 5;
        $endDate = ($validated['status'] == $resolvedStatusId) ? Carbon::now()->toDateString() : null;

        $session->update([
            'year_level'        => $validated['year_level'] ?? $session->year_level,
            'program'           => $validated['program'] ?? $session->program,
            'session_notes'     => $validated['session_notes'] ?? $session->session_notes,
            'emotional_state'   => $validated['emotional_state'] ?? $session->emotional_state,
            'behavior_observe'  => $validated['behavior_observe'] ?? $session->behavior_observe,
            'plan_goals'        => $validated['plan_goals'] ?? $session->plan_goals,
            'status'            => $validated['status'],
            'end_date'          => $endDate,
        ]);

        // If marked as resolved, mark all in the family as resolved
        if ($validated['status'] == $resolvedStatusId) {
            $this->markFamilyResolved($session);
        }

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

        $validationResult = $this->validateScheduleTime($validated);
        if (!$validationResult['success']) {
            return response()->json($validationResult);
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

    public function storeFollowUp(Request $request, $parentId)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $validationResult = $this->validateScheduleTime($validated);
        if (!$validationResult['success']) {
            return response()->json($validationResult);
        }

        $parent = Counseling::findOrFail($parentId);

        // Mark all previous as resolved
        $this->markAllParentsResolved($parent);

        // Find the original root session
        $root = $parent;
        while ($root->parent_session_id) {
            $root = Counseling::where('parent_uid', $root->parent_session_id)->first();
            if (!$root) break;
        }

        // Create follow-up always tied to the same root UID
        $followUp = Counseling::create([
            'student_no'        => $parent->student_no,
            'student_name'      => $parent->student_name,
            'school_email'      => $parent->school_email,
            'violation'         => $parent->violation,
            'severity'          => $parent->severity,
            'priority_risk'     => $parent->priority_risk,
            'guidance_service'  => $parent->guidance_service,
            'status'            => 2,
            'start_date'        => $validated['start_date'],
            'start_time'        => $validated['start_time'],
            'end_time'          => $validated['end_time'],
            'parent_session_id' => $root->parent_uid, // Always same root UID
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up created successfully.',
        ]);
    }


    // Remove the comment when needed
    // public function unresolveSession($id)
    // {
    //     $session = counseling::find($id);

    //     if (!$session) {
    //         return response()->json(['message' => 'Session not found.'], 404);
    //     }

    //     if ($session->status != 5) {
    //         return response()->json(['message' => 'Only resolved sessions can be restored.'], 400);
    //     }

    //     $session->status = 2;
    //     $session->end_date = null;
    //     $session->save();

    //     return response()->json(['message' => 'Session successfully restored to active management.']);
    // }



    // Helper functions below caution!
    private function markAllParentsResolved($session)
    {
        while ($session) {
            if ($session->status != 4) { 
                $session->update([
                    'status' => 4,
                    'end_date' => Carbon::now()->toDateString(),
                ]);
            }

            // Move upward by using UID reference instead of relationship
            if ($session->parent_session_id) {
                $session = Counseling::where('parent_uid', $session->parent_session_id)->first();
            } else {
                $session = null;
            }
        }
    }

    private function markFamilyResolved($session)
    {
        $resolvedStatusId = 5;

        // Determine the root UID 
        $rootUid = $session->parent_uid ?? $session->parent_session_id;

        // If this is the parent itself its parent_session_id is null,
        // so use its parent_uid SNS-001
        if (is_null($session->parent_session_id)) {
            $rootUid = $session->parent_uid;
        }

        // Find all sessions in this family
        $familySessions = Counseling::where('parent_uid', $rootUid)
            ->orWhere('parent_session_id', $rootUid)
            ->get();

        // Mark everyone as resolved
        foreach ($familySessions as $record) {
            if ($record->status != $resolvedStatusId) {
                $record->update([
                    'status' => $resolvedStatusId,
                    'end_date' => $record->end_date ?? Carbon::now()->toDateString(),
                ]);
            }
        }
    }

    private function validateScheduleTime($validated)
    {
        $start = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['start_date'] . ' ' . $validated['end_time']);

        // Prevent scheduling in the past
        if ($start->isBefore(Carbon::now())) {
            return [
                'success' => false,
                'message' => 'You cannot schedule a counseling session in the past.'
            ];
        }

        // Prevent backward scheduling
        if ($end->lessThanOrEqualTo($start)) {
            return [
                'success' => false,
                'message' => 'End time must be after start time on the same day.'
            ];
        }

        $startFormatted = $start->format('g:i A');
        $endFormatted = $end->format('g:i A');

        // Check for schedule conflict
        $conflict = DB::table('tb_counseling')
            ->where('start_date', $validated['start_date'])
            ->whereNotIn('status', [4, 5])
            ->whereDate('start_date', '>=', Carbon::today('Asia/Manila')) 
            ->whereRaw('? < end_time AND ? > start_time', [
                $start->format('H:i:s'),
                $end->format('H:i:s')
            ])
        ->exists();

        if ($conflict) {
            return [
                'success' => false,
                'message' => "The time slot from {$startFormatted} to {$endFormatted} is already occupied. Please choose a different time."
            ];
        }

        // Passed validation
        return [
            'success' => true,
            'start' => $start,
            'end' => $end
        ];
    }
}
