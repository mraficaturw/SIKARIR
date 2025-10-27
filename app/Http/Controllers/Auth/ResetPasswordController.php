<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     */
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    /**
     * Handle the password change request.
     * Validates email and old password, then sends verification email.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_accounts,email',
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find user by email
        $user = UserAccount::where('email', $request->email)->first();

        // Check if old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'Email atau Password Lama tidak sesuai.',
            ]);
        }

        // Generate token for password change verification
        $token = Str::random(64);

        // Store token in cache with expiration (24 hours)
        $cacheKey = 'password_change_' . $user->id;
        cache([$cacheKey => [
            'token' => $token,
            'new_password' => Hash::make($request->password),
            'expires_at' => Carbon::now()->addHours(24),
        ]], 1440); // 1440 minutes = 24 hours

        // Send verification email
        try {
            Mail::send('emails.password-change-verification', [
                'user' => $user,
                'token' => $token,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verifikasi Perubahan Password');
            });

            return redirect()->route('login')->with('success', 'Link verifikasi perubahan password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi.']);
        }
    }

    /**
     * Handle the password change verification.
     */
    public function verifyPasswordChange(Request $request, $token)
    {
        // Find user by token in cache
        $users = UserAccount::all();
        $user = null;
        $cacheKey = '';

        foreach ($users as $u) {
            $cacheKey = 'password_change_' . $u->id;
            $cachedData = cache($cacheKey);

            if ($cachedData && $cachedData['token'] === $token) {
                $user = $u;
                break;
            }
        }

        if (!$user || !$cachedData) {
            return redirect()->route('password.request')->withErrors(['token' => 'Link verifikasi tidak valid atau telah kadaluarsa.']);
        }

        // Check if token has expired
        if (Carbon::now()->greaterThan($cachedData['expires_at'])) {
            cache()->forget($cacheKey);
            return redirect()->route('password.request')->withErrors(['token' => 'Waktu verifikasi habis, silakan isi ulang form reset password.']);
        }

        // Update password
        $user->password = $cachedData['new_password'];
        $user->save();

        // Clear cache
        cache()->forget($cacheKey);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui.');
    }
}
