<?php

namespace App\Http\Controllers;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    // -----------------------------CODE IS NO LONGER IN USE-----------------------------
    // public function register(Request $request){
    //     $request->validate([
    //         'firstname' => ['required', 'string', 'min:2', 'max:55', 'regex:/^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .\'-][A-Za-zÑñ]+)*$/'],
    //         'lastname' => ['required', 'string', 'min:2', 'max:55', 'regex:/^(Ma\.|[A-Za-zÑñ]+)(?:[ .\'-][A-Za-zÑñ]+)*$/'],
    //         'middlename' => ['nullable', 'string', 'min:2', 'max:55', 'regex:/^(Ma\.|[A-Za-zÑñ]+)(?:[ .\'-][A-Za-zÑñ]+)*$/'],
    //         'suffix' => ['nullable', 'string', 'min:2', 'max:55'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:tb_users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
    //         'student_no' => ['required', 'string', 'max:11', 'unique:tb_users'],
    //         'course_and_section' => ['required', 'string', 'max:55'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //         'role' => ['string'],
    //         'status' => ['in:active,inactive'],
    //     ]);
    
    //     $register = users::create([
    //         'firstname' => $request->firstname,
    //         'lastname' => $request->lastname,
    //         'middlename' => $request->middlename,
    //         'suffix' => $request->suffix,
    //         'email' => $request->email,
    //         'student_no' => $request->student_no,
    //         'course_and_section' => $request->course_and_section,
    //         'password' => Hash::make($request->password),
    //         'role' => 'student',
    //         'status' => 'active'
    //     ]);
    
    //     return redirect()->intended('/');
    // }
    // -----------------------------CODE IS NO LONGER IN USE-----------------------------


    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user's status is inactive
            if ($user->status == 'inactive') {
                Auth::logout(); // Log the user out if inactive
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => 'Your account is inactive. Please contact support.',
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'role' => $user->role,
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => [
                'email' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.'
            ]
        ]);
    }

    public function addUser(Request $request) {
        $request->validate([
            'firstname' => ['required', 'string', 'min:2', 'max:55', 'regex:/^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .\'-][A-Za-zÑñ]+)*$/'],
            'lastname' => ['required', 'string', 'min:2', 'max:55', 'regex:/^(ma\.|Ma\.|[A-Za-zÑñ]+)(?:[ .\'-][A-Za-zÑñ]+)*$/'],
            'email' => ['required','string', 'email', 'max:255', 'unique:tb_users', 'regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'student_no' => ['required', 'string', 'max:11', 'unique:tb_users', 'regex:/^(ALA0)[a-zA-Z0-9]{4}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?!.*\s)(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/'],
            'role' => ['required', 'in:student,counselor,discipline,faculty,registar,super'],
            'status' => ['in:active,inactive'],
        ], [
        'student_no.unique' => 'The ID has already been taken.',
        ]);

        users::create([
            'firstname' => ucfirst(strtolower($request->firstname)),
            'lastname' => ucfirst(strtolower($request->lastname)),
            'email' => $request->email,
            'student_no' => $request->student_no,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        return response()->json(['message' => 'User added successfully.'], 200);
    }

    public function getuser(Request $request){
        // Fetch the user by ID from the request
        $user = users::find($request->id);

        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found']);
        }

        // Return the user information as a JSON response
        return response()->json([
            'status' => 200,
            'data' => [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'student_no' => $user->student_no,
                'role' => $user->role,
                'status' => $user->status,
            ]
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = users::find($request->id);

        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found']);
        }

        $validatedData = $request->validate([
            'id' => 'required|exists:tb_users,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_users,email,' . $user->id,
            'student_no' => ['required', 'string', 'max:11', 'unique:tb_users,student_no,' . $user->id],
            'role' => 'string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Check if there are changes
        $changes = false;
        foreach ($validatedData as $key => $value) {
            if ($user->$key != $value) {
                $changes = true;
                break;
            }
        }

        if (!$changes) {
            return response()->json([
                'status' => 204,
                'message' => 'No changes detected. Update not applied.'
            ]);
        }

        $user->update($validatedData);

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully'
        ]);
    }
        
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Laravel logout manual logins
        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // If user was 'super', skip Microsoft logout
        if ($user && $user->role === 'super') {
            return redirect('/');
        }

        // Otherwise, redirect to Microsoft logout
        $microsoftLogoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?' . http_build_query([
            'post_logout_redirect_uri' => url('/')
        ]);

        return redirect($microsoftLogoutUrl);
    }


}

