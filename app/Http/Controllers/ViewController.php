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

    public function admin_dash(){
        return view('admin_dashboard'); // Show the dashboard if logged in
    }
    public function student_dash(){
        return view('student_dashboard');
    }
    public function super_dash(){
        return view('super_dashboard');
    }
}
