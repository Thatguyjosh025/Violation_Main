<?php

namespace App\Http\Controllers;

use App\Models\rules;
use App\Models\users;
use App\Models\audits;
use App\Models\referals;
use App\Models\severity;
use App\Models\penalties;

use App\Models\violation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\alert;
use Illuminate\Support\Facades\Auth;

class SuperController extends Controller
{
    // ------------------------------------------------------------------------------------------------------------------------
    // START Create functions
    // ------------------------------------------------------------------------------------------------------------------------

    public function violation(Request $request){
        $request->validate([
            'violations' => [
                'required',
                'string',
                'max:255',
                'unique:tb_violation,violations',
                'regex:/^[a-zA-Z ]+$/'
            ],
        ]);

        // Get latest violation UID
        $latestUID = violation::orderBy('violation_id', 'desc')->value('violation_uid');

        if ($latestUID) {
            $num = intval(substr($latestUID, 2)) + 1;
            $newUID = 'VO' . str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $newUID = 'VO001';
        }

        // Create the new violation
        $create = violation::create([
            'violation_uid' => $newUID,
            'violations'    => $request->violations,
            'is_visible'    => 'active'
        ]);

        // Create audit entry
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $audit = audits::create([
            'changed_at' => now()->format('Y-m-d H:i'),
            'changed_by' => $name,
            'event_type' => 'Create',
            'field_name' => 'violations',
            'old_value'  => null,
            'new_value'  => $request->violations,
        ]);
        //end audit

        return response()->json([
            'message' => 'Violation added successfully!',
            'violation' => $create
        ]);
    }

    public function referal(Request $request){
        $request->validate([
            'referals' => [
                'required',
                'string',
                'max:255',
                'unique:tb_referals,referals',
                'regex:/^(?![-\/])(?!.*[-\/]{2})[a-zA-Z0-9 \/]+(?:[-\/][a-zA-Z0-9 ]+)*(?<![-\/])$/'
            ],
        ]);

        // Get latest referral UID
        $latestUID = referals::orderBy('referal_id', 'desc')->value('referal_uid');

        if ($latestUID) {
            $num = intval(substr($latestUID, 2)) + 1;
            $newUID = 'RE' . str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $newUID = 'RE001';
        }

        // Create the new referral
        $create = referals::create([
            'referal_uid' => $newUID,
            'referals'    => $request->referals,
            'is_visible'  => 'active'
        ]);

        // Create audit entry
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $email = Auth::user()->email;

        audits::create([
            'changed_at' => now()->format('Y-m-d H:i'),
            'changed_by' => $name,
            'event_type' => 'Create',
            'field_name' => 'referals',
            'old_value'  => null,
            'new_value'  => $request->referals,
        ]);

        return response()->json([
            'message' => 'Referral added successfully!',
            'referals' => $create
        ]);
    }
        
        
    public function penalties(Request $request){
        $request->validate([
            'penalties' => [
                'required',
                'string',
                'max:255',
                'unique:tb_penalties',
                'regex:/^[a-zA-Z0-9\s\/-]+$/'
            ],
        ]);

        // Generate next penalties_uid
        $lastPenalty = penalties::orderBy('penalties_uid', 'desc')->first();
        if ($lastPenalty) {
            $lastNumber = (int) substr($lastPenalty->penalties_uid, 2);
            $newNumber = $lastNumber + 1;
        } else {    
            $newNumber = 1;
        }

        $newUid = 'PE' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $create = penalties::create([
            'penalties_uid' => $newUid,
            'penalties' => $request->penalties
        ]);

        // Create audit entry
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $audit = audits::create([
            'changed_at' => now()->format('Y-m-d H:i'),
            'changed_by' => $name,
            'event_type' => 'Create',
            'field_name' => 'penalties',
            'old_value'  => null,
            'new_value'  => $request->penalties,
        ]);
        //end audit

        return response()->json([
            'message' => 'Penalty added successfully!',
            'penalty' => $create
        ]);
    }

    public function rules(Request $request) {
        $request->validate([
            'rule_name' => [
                'required',
                'string',
                'max:255',
                'unique:tb_rules',
                'regex:/^[A-Za-z]+([ \/-][A-Za-z]+)*$/'
            ],
            'description' => 'required|string|max:500',
            'violation_id' => 'required|exists:tb_violation,violation_id',
            'severity_id' => 'required|exists:tb_severity,severity_id'
        ]);

        $lastRule = rules::orderBy('rule_uid', 'desc')->first();
        $newNumber = $lastRule ? ((int) substr($lastRule->rule_uid, 2)) + 1 : 1;
        $newUid = 'RL' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $create = rules::create([
            'rule_uid' => $newUid,
            'rule_name' => $request->rule_name,
            'description' => $request->description,
            'violation_id' => $request->violation_id,
            'severity_id' => $request->severity_id
        ]);

        $create->load('violation', 'severity');

        // Audit creation
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;

        audits::insert([
            [
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Create',
                'field_name' => 'rule_name',
                'old_value'  => null,
                'new_value'  => $create->rule_name,
            ],
            [
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Create',
                'field_name' => 'description',
                'old_value'  => null,
                'new_value'  => $create->description,
            ],
            [
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Create',
                'field_name' => 'violation',
                'old_value'  => null,
                'new_value'  => $create->violation->violation ?? $create->violation_id,
            ],
            [
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Create',
                'field_name' => 'severity',
                'old_value'  => null,
                'new_value'  => $create->severity->severity ?? $create->severity_id,
            ]
        ]);

        return response()->json([
            'message' => 'Rule added successfully',
            'rule' => [
                'rule_id' => $create->rule_id,
                'rule_uid' => $create->rule_uid,
                'rule_name' => $create->rule_name,
                'description' => $create->description,
                'violation_name' => $create->violation->violation,
                'severity_name' => $create->severity->severity
            ]
        ]);
    }

    // ------------------------------------------------------------------------------------------------------------------------
    // END Create functions
    // ------------------------------------------------------------------------------------------------------------------------

        
    // ------------------------------------------------------------------------------------------------------------------------
    // START Update functions
    // ------------------------------------------------------------------------------------------------------------------------
    public function updatePenalty(Request $request, $id){
        $penalty = penalties::find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        $request->validate([
            'penalties' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\/-]+$/',
                Rule::unique('tb_penalties', 'penalties')->ignore($id, 'penalties_id')
            ],
            'is_visible' => 'required|in:active,inactive',
        ]);

        // Check if there are no changes
        if (
            strcasecmp($penalty->penalties, $request->penalties) === 0 &&
            $penalty->is_visible === $request->is_visible
        ) {
            return response()->json([
                'message' => 'No changes detected. Update not performed.'
            ], 200);
        }

        // Get user name for audit
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $email = Auth::user()->email;

        // Audit changed fields
        if (strcasecmp($penalty->penalties, $request->penalties) !== 0) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'penalties',
                'old_value'  => $penalty->penalties,
                'new_value'  => $request->penalties,
            ]);
        }

        if ($penalty->is_visible !== $request->is_visible) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'is_visible',
                'old_value'  => $penalty->is_visible,
                'new_value'  => $request->is_visible,
            ]);
        }

        // Update penalty
        $penalty->penalties = $request->penalties;
        $penalty->is_visible = $request->is_visible;
        $penalty->save();

        return response()->json(['message' => 'Penalty updated successfully']);
    }


    public function updateViolation(Request $request, $id){
        $violate = violation::find($id);

        if (!$violate) {
            return response()->json(['message' => 'Violation not found'], 404);
        }

        $request->validate([
            'violations' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z ]+$/',
                Rule::unique('tb_violation', 'violations')->ignore($id, 'violation_id')
            ],
            'is_visible' => 'required|in:active,inactive',
        ]);

        // Check if there are no changes
        if (
            strcasecmp($violate->violations, $request->violations) === 0 &&
            $violate->is_visible === $request->is_visible
        ) {
            return response()->json([
                'message' => 'No changes detected. Update not performed.'
            ], 200);
        }

        // Get user name for audit
        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $email = Auth::user()->email;

        // Audit changed fields
        if (strcasecmp($violate->violations, $request->violations) !== 0) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'violations',
                'old_value'  => $violate->violations,
                'new_value'  => $request->violations,
            ]);
        }

        if ($violate->is_visible !== $request->is_visible) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'is_visible',
                'old_value'  => $violate->is_visible,
                'new_value'  => $request->is_visible,
            ]);
        }

        // Update violation
        $violate->violations = $request->violations;
        $violate->is_visible = $request->is_visible;
        $violate->save();

        return response()->json(['message' => 'Violation updated successfully']);
    }

    public function updateRule(Request $request, $id){
        $rule = rules::find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        $request->validate([
            'rule_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z]+([ \/-][A-Za-z]+)*$/',
                Rule::unique('tb_rules', 'rule_name')->ignore($id, 'rule_id')
            ],
            'description' => 'required|string|max:500',
            'violation_id' => 'required|exists:tb_violation,violation_id',
            'severity_id' => 'required|exists:tb_severity,severity_id'
        ]);

        // Fetch related severity and violation models
        $newSeverity = severity::find($request->severity_id);
        $oldSeverity = $rule->severity; 

        $newViolation = violation::find($request->violation_id);
        $oldViolation = $rule->violation; 

        // Check if there are no changes
        if (
            $rule->rule_name === $request->rule_name &&
            $rule->description === $request->description &&
            $rule->violation_id == $request->violation_id &&
            $rule->severity_id == $request->severity_id
        ) {
            return response()->json([
                'message' => 'No changes detected. Update not performed.'
            ], 200);
        }

        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;

        // Audit changed fields
        if ($rule->rule_name !== $request->rule_name) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'rule_name',
                'old_value'  => $rule->rule_name,
                'new_value'  => $request->rule_name,
            ]);
        }

        if ($rule->description !== $request->description) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'description',
                'old_value'  => $rule->description,
                'new_value'  => $request->description,
            ]);
        }

        if ($rule->violation_id != $request->violation_id) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'violation',
                'old_value'  => $oldViolation->violations ?? $rule->violation_id,
                'new_value'  => $newViolation->violations ?? $request->violation_id,
            ]);
        }

        if ($rule->severity_id != $request->severity_id) {
            audits::create([
                'changed_at' => now(),
                'changed_by' => $name,
                'event_type' => 'Update',
                'field_name' => 'severity',
                'old_value'  => $oldSeverity->severity ?? $rule->severity_id,
                'new_value'  => $newSeverity->severity ?? $request->severity_id,
            ]);
        }

        // Update rule
        $rule->rule_name = $request->rule_name;
        $rule->description = $request->description;
        $rule->violation_id = $request->violation_id;
        $rule->severity_id = $request->severity_id;
        $rule->save();

        return response()->json([
            'message' => 'Rule updated successfully',
            'rule' => $rule->load('violation', 'severity')
        ]);
    }
    public function updateReferral(Request $request, $id){
        $referral = referals::find($id);

        if (!$referral) {
            return response()->json(['message' => 'Referral not found'], 404);
        }

        // Validation
        $request->validate([
            'referals' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?![-\/])(?!.*[-\/]{2})[a-zA-Z0-9 \/]+(?:[-\/][a-zA-Z0-9 ]+)*(?<![-\/])$/',
                'unique:tb_referals,referals,' . $id . ',referal_id'
            ],
            'is_visible' => ['required', 'in:active,inactive']
        ]);

        // Check if no changes
        if (
            $referral->referals === $request->referals &&
            $referral->is_visible === $request->is_visible
        ) {
            return response()->json([
                'message' => 'No changes were made to the referral.',
                'status' => 'no_changes'
            ]);
        }

        // Update
        $referral->update([
            'referals'   => $request->referals,
            'is_visible' => $request->is_visible
        ]);

        return response()->json([
            'message'  => 'Referral updated successfully!',
            'status'   => 'updated',
            'referral' => $referral
        ]);
    }

    // ------------------------------------------------------------------------------------------------------------------------
    //  END Update functions
    // ------------------------------------------------------------------------------------------------------------------------

    public function exportUsersCSV()
    {
        $fileName = 'user_records.csv';
        $users = Users::all(['firstname', 'lastname','email', 'password', 'role', 'student_no','status']); 

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['First Name', 'Last Name','Email', 'Password', 'Role', 'Student No', 'Status'];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->firstname,
                    $user->lastname,
                    $user->email,
                    $user->password, // this is danger but okay
                    $user->role,
                    $user->student_no,
                    $user->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importUsersCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            // Insert only if email does not exist
            users::firstOrCreate(
                ['email' => $data['Email']], // Unique identifier
                [
                    'firstname'   => $data['First Name'],
                    'lastname'    => $data['Last Name'],
                    'student_no'  => $data['Student No'],
                    'role'        => $data['Role'],
                    'status'      => $data['Status'],
                    'password'    => $data['Password'], 
                ]
            );
        }

        fclose($handle);

        return response()->json(['status' => 200, 'message' => 'CSV imported successfully']);
    }

    
}
