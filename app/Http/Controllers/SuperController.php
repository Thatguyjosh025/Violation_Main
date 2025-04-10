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
            'violations' => 'required|string|max:255|unique:tb_violation'
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
        'referals' => 'required|string|max:255|unique:tb_referals'
    ]);

    // Create the referral
    $create = referals::create([
        'referals' => $request->referals
    ]);

    // Return the response
    return response()->json([
        'message' => 'Referral added successfully!',
        'referals' => $create // Ensure this contains referal_id and referals
    ]);
}
    
    
    public function penalties(Request $request) {
        $request->validate([
            'penalties' => 'required|string|max:255|unique:tb_penalties'     
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
            'rule_name' => 'required|string|max:255|unique:tb_rules',
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
    
        // takes the name from the tablesss instead of displaying id in the user view
        // load() method in Laravel is used to eager load relationships for an existing model instance 
        //This means it fetches related data from FK
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
    public function updatePenalty(Request $request, $id) {
        $penalty = penalties::find($id);
    
        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }
    
        $penalty->penalties = $request->penalties;
        $penalty->save();
    
        return response()->json(['message' => 'Penalty updated successfully']);
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

    $referral->referals = $request->referals;
    $referral->save();

    return response()->json(['message' => 'Referral updated successfully']);
}

    
}
