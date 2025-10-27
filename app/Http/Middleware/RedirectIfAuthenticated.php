<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'user_accounts') {
                    $user = Auth::guard('user_accounts')->user();
                    if (is_null($user->email_verified_at)) {
                        Auth::guard('user_accounts')->logout();
                        return redirect()->route('verification.notice')
                            ->with('message', 'Verifikasi email kamu terlebih dahulu sebelum masuk.');
                    }
                    return redirect()->route('welcome');
                }
            }
        }

        return $next($request);
    }
}
