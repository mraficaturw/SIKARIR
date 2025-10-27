<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Exception;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:user_accounts,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@student.unsika.ac.id')) {
                        $fail('Gunakan email kampus dengan domain @student.unsika.ac.id.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Buat akun tapi belum diverifikasi
        $user = UserAccount::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Kirim email verifikasi otomatis
        try {
            $user->sendEmailVerificationNotification();
            // Login user dan redirect ke halaman verifikasi
            Auth::guard('user_accounts')->login($user);
            return redirect()->route('verification.notice')->with('success', 'Pendaftaran berhasil! Silakan cek email untuk verifikasi.');
        } catch (Exception $e) {
            Log::error('Failed to send verification email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            // Jika email gagal dikirim, tetap login user agar bisa akses halaman verifikasi dan coba kirim ulang
            Auth::guard('user_accounts')->login($user);
            return redirect()->route('verification.notice')->with('warning', 'Pendaftaran berhasil, tetapi kami gagal mengirim email verifikasi. Silakan gunakan tombol "Kirim Ulang Email Verifikasi" di halaman ini.');
        }
    }
}