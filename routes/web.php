<?php

use App\Models\MainModel;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ViewControllerDiscipline;
use App\Http\Middleware\RedirectIfNotAuthenticated;


//Landing routes
Route::get('/', function () {
    return view('Landing_page');
});


//Auth Routes
Route::post('/register',[AuthController::class,'register']);    
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

//Auth Dashboard routes
Route::get('/discipline_dashboard',[ViewController::class, 'admin_dashboard'])->middleware([RedirectIfNotAuthenticated::class, 'permission:discipline'])->name('discipline_dashboard');
Route::get('/student_dashboard',[ViewController::class, 'student_dash'])->middleware([RedirectIfNotAuthenticated::class, 'permission:student']);
Route::get('/super_dashboard', [ViewController::class, 'authorization'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super'])->name('super_dashboard');

//View routes superadmin
Route::get('/authorization', [ViewController:: class, 'authorization'])->name('authorization');
Route::get('/rule_management', [ViewController::class, 'rule_management'])->name('rule_management');
Route::get('/violation_management', [ViewController::class, 'violation_management'])->name('violation_management');
Route::get('/penalty_management', [ViewController::class, 'penalty_management'])->name('penalty_management');
Route::get('/referal_management', [ViewController::class, 'referal_management'])->name('referal_management');

//view routes discipline
Route::get('/incident_report', [ViewController::class, 'incident_report'])->name('incident_report');
Route::get('/violation_manage', [ViewController::class, 'violation_manage'])->name('violation_manage'); 
Route::get('/violation_records', [ViewController::class, 'violation_records'])->name('violation_records');

//Create routes
Route::post('/create_penalties', [SuperController::class, 'penalties']);
Route::post('/create_violation', [SuperController::class, 'violation']);
Route::post('/create_referals', [SuperController::class, 'referal']);
Route::post('/create_rules', [SuperController::class, 'rules']);
Route::post('/post_violation',[AdminController::class,'postviolation']);
Route::post('/update_student_info/{id}', [AdminController::class, 'updateStudentInfo']);


//Update routes
Route::post('/update_penalty/{id}', [SuperController::class, 'updatePenalty']);
Route::post('/update_violation/{id}', [SuperController::class, 'updateViolation']);
Route::post('/update_rule/{id}', [SuperController::class, 'updateRule']);
Route::post('/update_referral/{id}', [SuperController::class, 'updateReferral']);

//get routes
Route::get('/get_rule/{violation_id}', [AdminController::class, 'getRule']);
Route::get('/get_info', [AdminController::class, 'getStudentInfo']);
Route::get('/get_violations', [DataController::class, 'getViolations']);
Route::get('/get_penalty', [DataController::class, 'getPenalties']);
Route::get('/get_referal', [DataController::class, 'getReferals']);
Route::get('/get_status', [DataController::class, 'getStatus']);
Route::get('/get_violators_history/{name}/{id}', [AdminController::class, 'getStudentViolations']);
