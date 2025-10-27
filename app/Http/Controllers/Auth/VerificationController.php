<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(VerifyEmailRequest $request)
    {
        Log::info('Email verification attempt', [
            'user_id' => $request->route('id'),
            'hash' => $request->route('hash'),
        ]);

        $request->fulfill();

        $user = $request->user();
        Log::info('Email verification completed', [
            'user_id' => $user->id,
            'email_verified_at' => $user->email_verified_at,
        ]);

        // Login user setelah verifikasi
        Auth::guard('user_accounts')->login($user);

        return redirect()->route('welcome')->with('success', 'Email kamu berhasil diverifikasi!');
    }
}
