<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\rules;
use App\Models\penalties;
use App\Models\referals;
use App\Models\violation;

use Illuminate\Http\Request;
use function Laravel\Prompts\alert;

class SuperController extends Controller
{
    //create functions
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
        // Extract the number and increment
        $num = intval(substr($latestUID, 2)) + 1;
        $newUID = 'VO' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        // starting count
        $newUID = 'VO001';
    }

    // Create the new violation with generated UID
    $create = violation::create([
        'violation_uid' => $newUID,
        'violations'    => $request->violations
    ]);

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
        ]
    ]);

    $latestUID = referals::orderBy('referal_id', 'desc')->value('referal_uid');

    if ($latestUID) {
        $num = intval(substr($latestUID, 2)) + 1;
        $newUID = 'RE' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $newUID = 'RE001';
    }

    $create = referals::create([
        'referal_uid' => $newUID,
        'referals' => $request->referals
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
        if ($lastRule) {
            $lastNumber = (int) substr($lastRule->rule_uid, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newUid = 'RL' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $create = rules::create([
            'rule_uid' => $newUid,
            'rule_name' => $request->rule_name,
            'description' => $request->description,
            'violation_id' => $request->violation_id,
            'severity_id' => $request->severity_id
        ]);

        $create->load('violation', 'severity');

        return response()->json([
            'message' => 'Rule added successfully',
            'rule' => [
                'rule_id' => $create->rule_id,
                'rule_uid' => $create->rule_uid,
                'rule_name' => $create->rule_name,
                'description' => $create->description,
                'violation_name' => $create->violation->violations,
                'severity_name' => $create->severity->severity
            ]
        ]);
    }
        

    //update functions
   public function updatePenalty(Request $request, $id){
        $penalty = penalties::find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        // Validation
        $request->validate([
            'penalties' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\/-]+$/',
                Rule::unique('tb_penalties', 'penalties')->ignore($id, 'penalties_id')
            ],
        ]);

        // No change check (case-insensitive)
        if (strcasecmp($penalty->penalties, $request->penalties) === 0) {
            return response()->json([
                'message' => 'No changes detected. Update not performed.'
            ], 200);
        }

        // Perform update
        $penalty->update([
            'penalties' => $request->penalties
        ]);

        return response()->json([
            'message' => 'Penalty updated successfully!',
            'penalty' => $penalty
        ]);
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
        ]);

        if (strcasecmp($violate->violations, $request->violations) === 0) {
            return response()->json([
                'message' => 'No changes detected. Update not performed.'
            ], 200);
        }

        $violate->violations = $request->violations;
        $violate->save();

        return response()->json(['message' => 'Violation updated successfully']);
    }

    public function updateRule(Request $request, $id){
        $rule = rules::find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        //  Check if nothing changed
        if (
            $rule->rule_name === $request->rule_name &&
            $rule->description === $request->description &&
            $rule->violation_id == $request->violation_id &&
            $rule->severity_id == $request->severity_id
        ) {
            return response()->json([
                'message' => 'No changes were made to the rules.',
                'status' => 'no_changes'
            ]);
        }

        //  Validate only if changes exist
        $request->validate([
            'rule_name' => [
                'required',
                'string',
                'max:255',
                'unique:tb_rules,rule_name,' . $id . ',rule_id',
                'regex:/^[A-Za-z]+([ \/-][A-Za-z]+)*$/'
            ],
            'description' => 'required|string|max:500',
            'violation_id' => 'required|exists:tb_violation,violation_id',
            'severity_id' => 'required|exists:tb_severity,severity_id'
        ]);

        $rule->update([
            'rule_name' => $request->rule_name,
            'description' => $request->description,
            'violation_id' => $request->violation_id,
            'severity_id' => $request->severity_id
        ]);

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

        // ✅ Check if no changes were made
        if ($referral->referals === $request->referals) {
            return response()->json([
                'message' => 'No changes were made to the referal.',
                'status' => 'no_changes'
            ]);
        }

        // ✅ Validate input
        $request->validate([
            'referals' => [ 
                'required',
                'string',
                'max:255',
                'regex:/^(?![-\/])(?!.*[-\/]{2})[a-zA-Z0-9 \/]+(?:[-\/][a-zA-Z0-9 ]+)*(?<![-\/])$/',
                'unique:tb_referals,referals,' . $id . ',referal_id'
            ]
        ]);

        // ✅ Update referral
        $referral->update([
            'referals' => $request->referals
        ]);

        return response()->json([
            'message' => 'Referral updated successfully!',
            'status' => 'updated',
            'referral' => $referral
        ]);
    }


    
}
