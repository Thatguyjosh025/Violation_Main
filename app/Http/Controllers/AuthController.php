<?php

namespace App\Http\Controllers;
use App\Models\users;
use App\Models\audits;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $email = $request->email;
    $cacheKey = 'login_attempts_' . $email;
    $lockoutKey = 'login_lockout_' . $email;

    // Use Asia/Manila timezone
    $now = Carbon::now('Asia/Manila');

    // Check if the user is currently locked out
    if (Cache::has($lockoutKey)) {
        $lockoutTimestamp = Cache::get($lockoutKey); // store as timestamp
        $secondsLeft = $lockoutTimestamp - $now->timestamp;

        if ($secondsLeft > 0) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'username' => "Too many login attempts. Please try again in " . ceil($secondsLeft / 60) . " minute(s)."
                ]
            ]);
        } else {
            Cache::forget($lockoutKey);
        }
    }

    // Attempt login
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Reset login attempts and lockout on successful login
        Cache::forget($cacheKey);
        Cache::forget($lockoutKey);

        if ($user->status == 'inactive') {
            Auth::logout();
            return response()->json([
                'success' => false,
                'errors' => [
                    'username' => 'Your account is inactive. Please contact support.',
                ]
            ]);
        }

        // Logout other sessions for this user
        $sessionId = session()->getId();
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $sessionId)
            ->pluck('id');

        foreach ($sessions as $s) {
            Session::getHandler()->destroy($s);
        }

        return response()->json([
            'success' => true,
            'role' => $user->role,
        ]);
    }

    // Handle failed login attempt
    $attempts = Cache::get($cacheKey, 0) + 1;
    Cache::put($cacheKey, $attempts, 10); // 10 minutes expiry

    // Lockout logic
    if ($attempts >= 10) {
        $lockoutMultiplier = floor($attempts / 10);
        $lockoutTime = 5 * 60 * $lockoutMultiplier; // in seconds

        // Store lockout as timestamp
        $lockoutTimestamp = $now->copy()->addSeconds($lockoutTime)->timestamp;
        Cache::put($lockoutKey, $lockoutTimestamp, $lockoutTime);

        return response()->json([
            'success' => false,
            'errors' => [
                'username' => "Too many login attempts. Please try again in " . ceil($lockoutTime / 60) . " minute(s)."
            ]
        ]);
    }

    return response()->json([
        'success' => false,
        'errors' => [
            'username' => 'The provided credentials do not match our records.',
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

        // Store old values for role and status
        $oldValues = [
            'role' => $user->role,
            'status' => $user->status,
        ];

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

        // Audit logging for role and status
        $userId = Auth::user()->id;
        $fieldsToAudit = [
            'role' => $validatedData['role'],
            'status' => $validatedData['status'],
        ];

        foreach ($fieldsToAudit as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;

            audits::create([
                'changed_at' => now()->format('Y-m-d H:i'),
                'changed_by' => $userId,
                'event_type' => 'Update',
                'field_name' => $field,
                'old_value' => $oldValue,
                'old_value_text' => $oldValue, // For string fields, the text is the same as the value
                'new_value' => $newValue,
                'new_value_text' => $newValue, // For string fields, the text is the same as the value
            ]);
        }


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

