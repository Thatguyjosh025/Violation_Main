<?php

namespace App\Http\Controllers;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    //roles = faculty,counselor,dicipline,registar,student
    public function register(Request $request){
        $request->validate([
            'firstname' => ['required', 'string', 'max:255', 'regex:/^(Ma\.|[A-Za-z]+)(?:[ .\'-][A-Za-z]+)*$/'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^(Ma\.|[A-Za-z]+)(?:[ .\'-][A-Za-z]+)*$/'],
            'middlename' => ['required', 'string', 'max:255', 'regex:/^(Ma\.|[A-Za-z]+)(?:[ .\'-][A-Za-z]+)*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tb_users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'student_no' => ['required', 'string', 'max:11', 'unique:tb_users'],
            'course_and_section' => ['required', 'string', 'max:55'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['string'],
            'status' => ['in:active,inactive'],
        ]);
    
        $register = users::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'student_no' => $request->student_no,
            'course_and_section' => $request->course_and_section,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'status' => 'active'
        ]);
    
        return redirect()->intended('/');
    }

    public function login(Request $request){
    $credentials = $request->only('email', 'password');

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Return success response with the userssssss roleeeee
        return response()->json([
            'success' => true,
            'role' => $user->role,
        ]);
    }

    // Return error message if credentials are invalid
    return response()->json([
        'success' => false,
        'errors' => [
            'email' => 'The provided credentials do not match our records.',
            '   password' => 'The provided credentials do not match our records.'
        ]
    ]);
}

public function logout(Request $request) {
    Auth::logout(); 
    Session::flush(); 
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // Redirect to landing page after logout
}


}

