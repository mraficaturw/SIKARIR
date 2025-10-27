<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('user_accounts')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('user_accounts')->user();

            if (is_null($user->email_verified_at)) {
                // Redirect to verification notice instead of logging out
                return redirect()->route('verification.notice');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('welcome'))->with('success', 'Login successful.');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('user_accounts')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('welcome'))->with('success', 'Logged out successfully.');
    }
}
