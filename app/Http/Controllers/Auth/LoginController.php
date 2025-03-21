<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\CentralUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = CentralUser::where('email', $request->email)->first();
        // dd($user);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Credentials do not match');
        }

        Auth::login($user);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        // return redirect()->route('dashboard');
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function home()
    {
        return view('dahboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Check if the user is logged in via API (Sanctum)
            if ($request->user()->tokens()->count() > 0) {
                // Revoke all tokens for API authentication
                $request->user()->tokens()->delete();
            } else {
                // Logout from web session
                Auth::logout();
                $request->session()->invalidate();
                // $request->session()->regenerateToken();
            }
        }

        return redirect()->route('login')->with('message', 'Logged out successfully.');
    }
}
