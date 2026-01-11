<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:user_accounts'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = UserAccount::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login otomatis ke guard user_accounts agar verifikasi pertama valid
        Auth::guard('user_accounts')->login($user);

        // Trigger event Registered agar email verifikasi dikirim
        event(new Registered($user));

        // Arahkan ke halaman verifikasi email
        return redirect()->route('verification.notice')
            ->with('message', 'Verifikasi email telah dikirim. Silakan cek email Anda.');
    }
}
