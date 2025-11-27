<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MicrosoftLoginController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tenantId = 'common';
    private $scopes = 'openid profile email User.Read';

    public function __construct()
    {
        $this->clientId     = env('MICROSOFT_CLIENT_ID');
        $this->clientSecret = env('MICROSOFT_CLIENT_SECRET');
        $this->redirectUri  = route('microsoft.callback');
    }

    public function redirectToProvider()
    {
        $state = bin2hex(random_bytes(16));
        Session::put('oauth_state', $state);

        $authorizeUrl = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/authorize?" . http_build_query([
            'client_id'     => $this->clientId,
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'response_mode' => 'query',
            'scope'         => $this->scopes,
            'state'         => $state,
        ]);

        return redirect($authorizeUrl);
    }

    public function handleProviderCallback(Request $request)
{
    $state = $request->get('state');
    $code  = $request->get('code');
    if (!$state || !$code || $state !== Session::get('oauth_state')) {
        abort(403, 'Access Denied: Invalid state');
    }
    Session::forget('oauth_state');

    // Exchange code for access token
    $tokenResponse = Http::asForm()->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
        'client_id'     => $this->clientId,
        'client_secret' => $this->clientSecret,
        'code'          => $code,
        'redirect_uri'  => $this->redirectUri,
        'grant_type'    => 'authorization_code',
        'scope'         => $this->scopes,
    ]);

    if (!$tokenResponse->ok() || !isset($tokenResponse['access_token'])) {
        abort(500, 'Failed to retrieve access token');
    }

    $accessToken = $tokenResponse['access_token'];
    $userResponse = Http::withToken($accessToken)->get('https://graph.microsoft.com/v1.0/me');

    if (!$userResponse->ok()) {
        abort(500, 'Failed to retrieve user info');
    }

    $userData = $userResponse->json();
    $allowedDomain = 'alabang.sti.edu.ph';

    if (!str_ends_with($userData['userPrincipalName'], "@$allowedDomain")) {
        return redirect('/403');
    }

    $role = null;
    $status = 'active';

    if (isset($userData['displayName'])) {
        if (str_contains($userData['displayName'], '(Student)')) {
            $role = 'student';
        } elseif (str_contains($userData['displayName'], '(Faculty)')) {
            $role = 'faculty';
        }
    }

    preg_match('/\.(\d+)@/', $userData['mail'], $matches);
    $student_id = isset($matches[1]) ? '02000' . $matches[1] : null;

    $user = Users::where('email', $userData['mail'])->first();

    if (!$user) {
        $user = Users::create([
            'firstname'   => $userData['givenName'],
            'lastname'    => $userData['surname'],
            'email'       => $userData['mail'],
            'password'    => null,
            'role'        => $role,
            'student_no'  => $student_id,
            'status'      => $status,
        ]);
    }

    if ($user->status === 'inactive') {
        return response()->view('errors.Inactive', [], 403);
    }

    // --- Logout other sessions for this user ---
    $sessionId = session()->getId();
    $sessions = DB::table('sessions')
        ->where('user_id', $user->id)
        ->where('id', '!=', $sessionId)
        ->pluck('id');

    foreach ($sessions as $s) {
        Session::getHandler()->destroy($s);
    }
    // --- End logout logic ---

    Auth::login($user);

    return match ($user->role) {
        'discipline' => redirect()->route('discipline_dashboard'),
        'student'    => redirect()->route('student_dashboard'),
        'faculty'    => redirect()->route('faculty_dashboard'),
        'counselor'  => redirect()->route('counseling_dashboard'),
        'head'       => redirect()->route('academic-head_dashboard'),
        default      => redirect()->route('home'),
    };
}



}
