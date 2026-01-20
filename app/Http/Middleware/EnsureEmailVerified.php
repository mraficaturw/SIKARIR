<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('user_accounts')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (is_null($user->email_verified_at)) {
            // Auth::guard('user_accounts')->logout(); // Removed to fix UX loop
            return redirect()->route('verification.notice')
                ->with('message', 'Silakan verifikasi email kamu sebelum mengakses halaman ini.');
        }

        return $next($request);
    }
}
