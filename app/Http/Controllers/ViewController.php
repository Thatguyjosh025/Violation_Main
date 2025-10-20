<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\sections;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StudentController;

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
    public function faculty_dash(){
        return view('faculty_dashboard');
    }


    //super admin components
    public function authorization()
    {
        return view('super_dashboard', ['view' => 'Authorization']);
    }

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
     public function audit_management()
    {
        return view('super_dashboard', ['view' => 'AuditLogs']);
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
     public function report_analytics()
    {
        return view('discipline_dashboard', ['views' => 'ReportandAnalytics']);
    }
    public function violation_records()
    {
        $studentControllers = new StudentController();
        $studentControllers->getViolationsRecords(request());
        return view('discipline_dashboard', ['views' => 'ViolationRecords']);
    }
    
    //faculty components
    public function faculty_dashboard()
    {
        return view('faculty_dashboard', ['views' => 'FacultyDashboard']);
    }
    public function faculty_violation()
    {
        return view('faculty_dashboard', ['views' => 'ViolationFacultyManagement']);
    }
    public function faculty_incident()
    {
        return view('faculty_dashboard', ['views' => 'IncidentRecords']);
    }

    //counseling components
    public function counseling_dashboard()
    {
        return view('counseling_dashboard', ['views' => 'CounselingDashboard']);
    }
    public function referral_intake()
    {
        return view('counseling_dashboard', ['views' => 'ReferralIntake']);
    }
    public function session_management()
    {
        return view('counseling_dashboard', ['views' => 'SessionManagement']);
    }
    public function student_counseling()
    {
        return view('counseling_dashboard', ['views' => 'StudentCounseling']);
    }



    //student components
    public function student_dashboard() 
    {
        $studentController = new StudentController();
        $studentController->getViolationsRecords(request());
        return view('student_dashboard', ['views' => 'StudentDashboard']);
    }
    public function violation_history()
    {
        return view('student_dashboard', ['views' => 'ViolationHistory']);
    }
    public function violation_tracking()
    {
        return view('student_dashboard', ['views' => 'ViolationTracking']);
    }
 public function violation_handbook()
    {
        $sections = sections::orderBy('created_at')->get();
        $userRole = Auth::user()->role;

        $viewData = ['views' => 'Handbook', 'sections' => $sections];

        switch ($userRole) {
            case 'student':
                return view('student_dashboard', $viewData);
            case 'faculty':
                return view('faculty_dashboard', $viewData);
            case 'discipline':
                return view('discipline_dashboard', $viewData);
            case 'super':
                return view('super_dashboard', ['view' => 'Handbook', 'sections' => $sections]);
            case 'counselor':
                return view('counseling_dashboard', $viewData);
            default:
                return redirect('/');
        }
    }

    
}
