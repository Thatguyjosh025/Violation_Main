<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ViewController extends Controller
{
    //

    public function landing(){
        return view('/');
    }
    public function admin_dash(){
        return view('discipline_dashboard'); // Show the dashboard if logged in
    }
    public function student_dash(){
        return view('student_dashboard');
    }
    public function super_dash(){
        return view('super_dashboard');
    }
    public function authorization()
    {
        return view('super_dashboard', ['view' => 'Authorization']);
    }

    //super admin components
    public function rule_management()
    {
        return view('super_dashboard', ['view' => 'RuleManage']);
    }
    public function violation_management()
    {
        return view('super_dashboard', ['view' => 'ViolationManagement']);
    }
    public function penalty_management()
    {
        return view('super_dashboard', ['view' => 'PenaltyManagement']);
    }
    public function referal_management()
    {
        return view('super_dashboard', ['view' => 'ReferalManagement']);
    }

    //discipline components
    public function admin_dashboard()
    {
        return view('discipline_dashboard', ['views' => 'AdminDashboard']);
    }
    public function incident_report()
    {
        return view('discipline_dashboard', ['views' => 'IncidentReport']);
    }
    public function violation_manage()
    {
        return view('discipline_dashboard', ['views' => 'ViolationsManagement']);
    }
    public function violation_records()
    {
        return view('discipline_dashboard', ['views' => 'ViolationRecords']);
    }


    
}
