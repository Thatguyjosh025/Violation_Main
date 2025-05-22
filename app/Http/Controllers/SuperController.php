<?php

namespace App\Http\Controllers;

use App\Models\rule;
use App\Models\rules;
use App\Models\penalties;
use App\Models\referals;
use App\Models\violation;
use GuzzleHttp\Psr7\Message;

use Illuminate\Http\Request;
use function Laravel\Prompts\alert;

class SuperController extends Controller
{
    //create functions
   public function violation(Request $request) {
        $request->validate([
           'violations' => ['required', 'string', 'max:255', 'unique:tb_violation', 'regex:/^[a-zA-Z ]+$/'],
        ]);

        $create = violation::create([
            'violations' => $request->violations
        ]);

        return response()->json([
            'message' => 'Violation added successfully!',
            'violation' => $create
        ]);
    }

    public function referal(Request $request){
        // Validate input
        $request->validate([
            'referals' => [
                'required',
                'string',
                'max:255',
                'unique:tb_referals,referals',
                'regex:/^(?![-\/])(?!.*[-\/]{2})[a-zA-Z0-9 \/]+(?:[-\/][a-zA-Z0-9 ]+)*(?<![-\/])$/'
            ]
        ]);

        // Create the referral
        $create = referals::create([
            'referals' => $request->referals
        ]);

        // Return the response
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
                'regex:/^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$/'
            ],
        ]);

        $create = penalties::create([
            'penalties' => $request->penalties
        ]);

        return response()->json([
            'message' => 'Penalty added successfully!',
            'penalty' => $create
        ]);
    }

    
   public function rules(Request $request) {
       $request->validate([
            'rule_name' => ['required', 'string', 'max:255', 'unique:tb_rules', 'regex:/^[A-Za-z]+([ -][A-Za-z]+)*$/'],
            'description' => 'required|string|max:500',
            'violation_id' => 'required|exists:tb_violation,violation_id',
            'severity_id' => 'required|exists:tb_severity,severity_id' 
        ]);

        $create = rules::create([
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

        $request->validate([
            'penalties' => [
                'required',
                'string',
                'max:255',
                'unique:tb_penalties,penalties,' . $id . ',penalties_id',
                'regex:/^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$/'
            ]
        ]);

        $penalty->update([
            'penalties' => $request->penalties
        ]);

        return response()->json([
            'message' => 'Penalty updated successfully!',
            'penalty' => $penalty
        ]);
    }


   public function updateViolation(Request $request, $id) {
        $violate = violation::find($id);

        if (!$violate) {
            return response()->json(['message' => 'Violation not found'], 404);
        }

        $violate->violations = $request->violations;
        $violate->save();

        return response()->json(['message' => 'Violation updated successfully']);
    }

    public function updateRule(Request $request, $id) {
        $rule = rules::find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        $request->validate([
            'rule_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+([ -][A-Za-z]+)*$/'],
            'description' => 'required|string|max:500'
        ]);

        $rule->rule_name = $request->rule_name;
        $rule->description = $request->description;
        $rule->save();

        return response()->json(['message' => 'Rule updated successfully']);
    }

    public function updateReferral(Request $request, $id){
        $referral = referals::find($id);

        if (!$referral) {
            return response()->json(['message' => 'Referral not found'], 404);
        }

        // Validate input
        $request->validate([
            'referals' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?![-\/])(?!.*[-\/]{2})[a-zA-Z0-9 \/]+(?:[-\/][a-zA-Z0-9 ]+)*(?<![-\/])$/',
                'unique:tb_referals,referals,' . $id . ',referal_id'
            ]
        ]);

        // Update the referral using update()
        $referral->update([
            'referals' => $request->referals
        ]);

        return response()->json(['message' => 'Referral updated successfully']);
    }

    
}
