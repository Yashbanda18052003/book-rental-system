<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // ✅ prevent showing login page if already logged in
        if (Auth::check()) {
            return auth()->user()->role === 'admin'
                ? redirect('/admin/dashboard')
                : redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|min:3|max:100',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|unique:users,phone',
        'password' => 'required|min:8',
        'password_confirmation' => 'required|same:password',
    ], [
        'password_confirmation.same' => 'Confirm password does not match.',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
        'role' => 'user'
    ]);

    return redirect('/login')
        ->with('success', 'Registration Successful');
}

    // public function showVerifyPhone()
    // {
    //     $userId = session('pending_verification_user_id');

    //     if (!$userId) {
    //         return redirect()->route('register');
    //     }

    //     $user = User::findOrFail($userId);

    //     if ($user->phone_verified_at) {
    //         return redirect()->route('login')
    //             ->with('success', 'Phone already verified. Please log in.');
    //     }

    //     return view('auth.verify-otp', compact('user'));
    // }

    // public function verifyPhone(Request $request)
    // {
    //     $request->validate([
    //         'access_token' => 'required|string',
    //     ]);

    //     $userId = session('pending_verification_user_id');

    //     if (!$userId) {
    //         return redirect()->route('register');
    //     }

    //     $user = User::findOrFail($userId);

    //     $verified = Msg91Service::verifyAccessToken($request->access_token);

    //     if (!$verified) {
    //         return back()->with('error', 'OTP verification failed. Please try again.');
    //     }

    //     $user->update(['phone_verified_at' => now()]);
    //     session()->forget('pending_verification_user_id');

    //     return redirect()
    //         ->route('login')
    //         ->with('success', 'Phone verified! You can now log in.');
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            // Admins are provisioned directly (not through public register), and
            // this check exists to confirm a real customer phone number — so it
            // only applies to the 'user' role.
            // if ($user->role === 'user' && !$user->phone_verified_at) {
            //     Auth::logout();
            //     session(['pending_verification_user_id' => $user->id]);

            //     return redirect()
            //         ->route('verify.phone')
            //         ->with('error', 'Please verify your phone number before logging in.');
            // }

            // ✅ prevents session fixation attacks
            $request->session()->regenerate();

            // ✅ role-based redirect (clean version)
            return $user->role === 'admin'
                ? redirect('/admin/dashboard')
                : redirect('/dashboard');
        }

       return back()->withErrors([
    'login' => 'Invalid Email or Password'
])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // ✅ full session cleanup
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}