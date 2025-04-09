<?php

use App\Models\MainModel;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\LogoutController;
use App\Http\Middleware\RedirectIfNotAuthenticated;

Route::get('/', function () {
    return view('Landing_page');
});



Route::post('/register',[MainController::class,'register']);
Route::post('/login',[MainController::class,'login']);
Route::post('/logout',[LogoutController::class,'logout']);

Route::get('/landing-page', [MainController::class, 'landing']);

Route::get('/admin_dashboard',[ViewController::class, 'admin_dash'])->middleware([RedirectIfNotAuthenticated::class, 'permission:admin']);
Route::get('/student_dashboard',[ViewController::class, 'student_dash'])->middleware([RedirectIfNotAuthenticated::class, 'permission:student']);
Route::get('/super_dashboard',[ViewController::class, 'super_dash'])->middleware([RedirectIfNotAuthenticated::class, 'permission:super']);
