<?php

use Carbon\Carbon;
use App\Models\users;
use App\Models\MainModel;
use App\Models\postviolation;
use App\Http\Controllers\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Utilities\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\RedirectIfNotAuthenticated;


//Landing routes
Route::get('/', function () {
    return view('Landing_page');
});


//Auth Routes
Route::post('/register',[AuthController::class,'register']);
Route::post('/add_user',[AuthController::class,'addUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

// ==========================
// SUPER ADMIN ROUTES
// ==========================
Route::middleware(['permission:super', RedirectIfNotAuthenticated::class])->group(function () {
    // Dashboard & Views
    Route::get('/super_dashboard', [ViewController::class, 'authorization'])->name('super_dashboard');
    Route::get('/authorization', [ViewController::class, 'authorization'])->name('authorization');
    Route::get('/rule_management', [ViewController::class, 'rule_management'])->name('rule_management');
    Route::get('/violation_management', [ViewController::class, 'violation_management'])->name('violation_management');
    Route::get('/penalty_management', [ViewController::class, 'penalty_management'])->name('penalty_management');
    Route::get('/referal_management', [ViewController::class, 'referal_management'])->name('referal_management');
    Route::get('/audit_management', [ViewController::class, 'audit_management'])->name('audit_management');

    // Create
    Route::post('/create_penalties', [SuperController::class, 'penalties']);
    Route::post('/create_violation', [SuperController::class, 'violation']);
    Route::post('/create_referals', [SuperController::class, 'referal']);
    Route::post('/create_rules', [SuperController::class, 'rules']);

    // Update
    Route::post('/update_penalty/{id}', [SuperController::class, 'updatePenalty']);
    Route::post('/update_violation/{id}', [SuperController::class, 'updateViolation']);
    Route::post('/update_rule/{id}', [SuperController::class, 'updateRule']);
    Route::post('/update_referral/{id}', [SuperController::class, 'updateReferral']);

    // User Management
    Route::post('/update_user', [AuthController::class, 'updateUser']);

    //GET
    Route::get('/get_user_info', [AuthController::class, 'getuser']);
});

// ==========================
// DISCIPLINE ROUTES
// ==========================
Route::middleware(['permission:discipline', RedirectIfNotAuthenticated::class])->group(function () {
    // Dashboard & Views
    Route::get('/discipline_dashboard', [ViewController::class, 'admin_dashboard'])->name('discipline_dashboard');
    Route::get('/incident_report', [ViewController::class, 'incident_report'])->name('incident_report');
    Route::get('/violation_manage', [ViewController::class, 'violation_manage'])->name('violation_manage');
    Route::get('/violation_records', [ViewController::class, 'violation_records'])->name('violation_records');

    // Create/Update
    Route::post('/post_violation', [AdminController::class, 'postviolation']);
    Route::post('/update_student_info/{id}', [AdminController::class, 'updateStudentInfo']);
    Route::post('/incident_rejected', [AdminController::class, 'UpdateRejected']);
    Route::post('/violation_records/{id}', [AdminController::class, 'archive']);
});

// ==========================
// FACULTY ROUTES
// ==========================
Route::middleware(['permission:faculty', RedirectIfNotAuthenticated::class])->group(function () {
    // Dashboard & Views
    Route::get('/faculty_dashboard', [ViewController::class, 'faculty_dashboard'])->name('faculty_dashboard');
    Route::get('/faculty_violation', [ViewController::class, 'faculty_violation'])->name('faculty_violation');
    Route::get('/faculty_incident', [ViewController::class, 'faculty_incident'])->name('faculty_incident');

    // Create
    Route::post('/submit_incident_report', [FacultyController::class, 'submitIncidentReport']);
});

// ==========================
// STUDENT ROUTES
// ==========================
Route::middleware(['permission:student', RedirectIfNotAuthenticated::class])->group(function () {
    // Dashboard & Views
    Route::get('/student_dashboard', [ViewController::class, 'student_dashboard'])->name('student_dashboard');
    Route::get('/violation_history', [ViewController::class, 'violation_history'])->name('violation_history');
    Route::get('/violation_tracking', [ViewController::class, 'violation_tracking'])->name('violation_tracking');
    Route::get('/get_violations_records', [StudentController::class, 'getViolationsRecords']);
    Route::post('/update_appeal_reason', [StudentController::class, 'updateAppealReason']);

});


//get routes


Route::get('/get_violations', [DataController::class, 'getViolations']);
Route::get('/get_penalty', [DataController::class, 'getPenalties']);
Route::get('/get_referal', [DataController::class, 'getReferals']);
Route::get('/get_status', [DataController::class, 'getStatus']);

Route::middleware([RedirectIfNotAuthenticated::class,'permission:faculty,discipline'])->group(function () {
    Route::get('/get_rule/{violation_id}', [AdminController::class, 'getRule']);
    Route::get('/get_info', [AdminController::class, 'getStudentInfo']);
    Route::get('/get_violators_history/{name}/{id}', [AdminController::class, 'getStudentViolations']);
    Route::get('/get_incident_info', [AdminController::class, 'getIncidentInfo']);
    Route::get('/student_search', [AdminController::class, 'student_search'])->name('student_search');
});


//import csv
Route::post('/import_users_csv', [App\Http\Controllers\SuperController::class, 'importUsersCSV']);

//export csv
Route::get('/export-users-csv', [SuperController::class, 'exportUsersCSV']);



//students

//notif
Route::post('/update_notification_status', [NotificationController::class, 'updateNotificationStatus']);

//handbookview
Route::get('/violation_handbook', [ViewController::class, 'violation_handbook'])->name('violation_handbook')
->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline,student,faculty,super']);


Route::get('/violation_records/data', function (Request $request) {
    $query = postviolation::with(['violation', 'status']);

    // Apply status filter if requested and not empty
    if ($request->has('status') && $request->status !== '') {
        $query->whereHas('status', function($q) use ($request) {
            $q->where('status', $request->status);
        });

        // If the status is "Resolved", show records with is_active = false
        if ($request->status === 'Resolved') {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }
    } else {
        // If no status filter is applied, show only active records
        $query->where('is_active', true);
    }

    return DataTables::of($query)
        ->addColumn('violation', fn($data) => $data->violation->violations ?? 'N/A')
        ->addColumn('status', fn($data) => $data->status->status ?? 'N/A')
        ->addColumn('actions', function ($data) {
            $viewBtn = '<button class="btn btn-primary btn-view-post" value="' . $data->id . '">View</button>';
            $editBtn = '<button class="btn btn-primary btn-edit-post" value="' . $data->id . '">Edit</button>';

            return $viewBtn . ' ' . $editBtn;
        })
        ->editColumn('Date_Created', function($data) {
            return Carbon::parse($data->Date_Created)->format('Y-m-d');
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
})->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('violation_records.data');
