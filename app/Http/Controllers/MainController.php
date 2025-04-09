<?php

namespace App\Http\Controllers;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{

    //roles = faculty,counselor,dicipline,registar,student
    public function register(Request $request){
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tb_users',
            'student_no' => 'required|string|max:11|unique:tb_users',
            'course_and_section' => 'required|string|max:55',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'string',
            'status' => 'enum'
        ]);

        $register = users::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
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

}

