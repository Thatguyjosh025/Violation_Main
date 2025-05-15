<?php

use App\Models\MainModel;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Models\postviolation;
use Illuminate\Contracts\View\View;
use Yajra\DataTables\DataTables;


//Landing routes
Route::get('/', function () {
    return view('Landing_page');
});


//Auth Routes
Route::post('/register',[AuthController::class,'register']);
Route::post('/add_user',[AuthController::class,'addUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

//Auth Dashboard routes
Route::get('/discipline_dashboard',[ViewController::class, 'admin_dashboard'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('discipline_dashboard');
Route::get('/student_dashboard',[ViewController::class, 'student_dashboard'])->middleware([RedirectIfNotAuthenticated::class, 'permission:student']);
Route::get('/super_dashboard', [ViewController::class, 'authorization'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('super_dashboard');
Route::get('/faculty_dashboard', [ViewController::class, 'faculty_dashboard'])->middleware([RedirectIfNotAuthenticated::class, 'permission:faculty'])->name('faculty_dashboard');



//View routes superadmin
Route::get('/authorization', [ViewController:: class, 'authorization'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('authorization');
Route::get('/rule_management', [ViewController::class, 'rule_management'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('rule_management');
Route::get('/violation_management', [ViewController::class, 'violation_management'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('violation_management');
Route::get('/penalty_management', [ViewController::class, 'penalty_management'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('penalty_management');
Route::get('/referal_management', [ViewController::class, 'referal_management'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('referal_management');

//view routes discipline
Route::get('/incident_report', [ViewController::class, 'incident_report'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('incident_report');
Route::get('/violation_manage', [ViewController::class, 'violation_manage'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('violation_manage'); 
Route::get('/violation_records', [ViewController::class, 'violation_records'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('violation_records');

//view routes faculty
Route::get('/faculty_violation', [ViewController::class, 'faculty_violation'])->name('faculty_violation')->middleware([RedirectIfNotAuthenticated::class, 'permission:faculty']);
Route::get('/faculty_incident', [ViewController::class, 'faculty_incident'])->name('faculty_violation')->middleware([RedirectIfNotAuthenticated::class, 'permission:faculty']);

//view student faculty
Route::get('/violation_history', [ViewController::class, 'violation_history'])->name('faculty_violation')->middleware([RedirectIfNotAuthenticated::class, 'permission:student']);
Route::get('/violation_tracking', [ViewController::class, 'violation_tracking'])->name('faculty_violation')->middleware([RedirectIfNotAuthenticated::class, 'permission:student']);


//Create routes
Route::post('/create_penalties', [SuperController::class, 'penalties'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/create_violation', [SuperController::class, 'violation'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/create_referals', [SuperController::class, 'referal'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/create_rules', [SuperController::class, 'rules'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/post_violation',[AdminController::class,'postviolation'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline']);
Route::post('/update_student_info/{id}', [AdminController::class, 'updateStudentInfo'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline']);
Route::post('/submit_incident_report',[FacultyController::class,'submitIncidentReport'])->middleware([RedirectIfNotAuthenticated::class, 'permission:faculty']);


//Update routes
Route::post('/update_penalty/{id}', [SuperController::class, 'updatePenalty'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/update_violation/{id}', [SuperController::class, 'updateViolation'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/update_rule/{id}', [SuperController::class, 'updateRule'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/update_referral/{id}', [SuperController::class, 'updateReferral'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
Route::post('/incident_rejected', [AdminController::class, 'UpdateRejected']);
Route::post('/violation_records/{id}', [AdminController::class, 'archive'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline']);
Route::post('/update_user', [AuthController::class, 'updateUser']);


//get routes
Route::get('/get_rule/{violation_id}', [AdminController::class, 'getRule']);
Route::get('/get_info', [AdminController::class, 'getStudentInfo']);
Route::get('/get_violations', [DataController::class, 'getViolations']);
Route::get('/get_penalty', [DataController::class, 'getPenalties']);
Route::get('/get_referal', [DataController::class, 'getReferals']);
Route::get('/get_status', [DataController::class, 'getStatus']);
Route::get('/get_violators_history/{name}/{id}', [AdminController::class, 'getStudentViolations']);
Route::get('/get_incident_info', [AdminController::class, 'getIncidentInfo']);
Route::get('/student_search', [AdminController::class, 'student_search'])->name('student_search');
Route::get('/get_user_info', [AuthController::class, 'getuser']);



//students
Route::get('/get_violations_records', [StudentController::class, 'getViolationsRecords']);
Route::post('/update_appeal_reason', [StudentController::class, 'updateAppealReason']);

//notif
Route::post('/update_notification_status', [NotificationController::class, 'updateNotificationStatus']);

//handbookview
Route::get('/violation_handbook',[ViewController::class,'violation_handbook'])->name('violation_handbook');
Route::get('/violation_handbook',[ViewController::class,'disicipline_handbook'])->name('violation_handbook');


//datatables
Route::get('/violation_records/data', function () {
    return DataTables::of(
        postviolation::with(['violation', 'status'])
            ->where('is_active', true)
    )
    ->addColumn('violation', fn($data) => $data->violation->violations ?? 'N/A')
    ->addColumn('status', fn($data) => $data->status->status ?? 'N/A')
    ->addColumn('actions', function ($data) {
        return '
            <button class="btn btn-primary btn-view-post" value="' . $data->id . '">View</button>
            <button class="btn btn-primary btn-edit-post" value="' . $data->id . '">Edit</button>
            <button class="btn btn-primary btn-archive-post" value="' . $data->id . '">Archive</button>
        ';
    })
    ->rawColumns(['actions'])
    ->filterColumn('violation', function($query, $keyword) {
        $query->whereHas('violation', function($q) use ($keyword) {
            $q->where('violations', 'like', "%{$keyword}%");
        });
    })
    ->filterColumn('status', function($query, $keyword) {
        $query->whereHas('status', function($q) use ($keyword) {
            $q->where('status', 'like', "%{$keyword}%");
        });
    })
    ->make(true);
})->middleware(['auth', 'permission:discipline'])->name('violation_records.data');
