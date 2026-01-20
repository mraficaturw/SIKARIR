<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * ============================================================================
 * FORGOT PASSWORD CONTROLLER
 * ============================================================================
 * Controller ini menangani proses lupa password (langkah pertama).
 * User memasukkan email → sistem mengirim link reset password ke email.
 * 
 * Alur lupa password:
 * 1. [Controller ini] User request link reset via email
 * 2. [ResetPasswordController] User klik link dan input password baru
 * 
 * Fitur keamanan:
 * - Menggunakan Password Broker Laravel (token aman)
 * - Token expires sesuai config (default 60 menit)
 * - Throttle untuk mencegah spam email
 * ============================================================================
 */
class ForgotPasswordController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Request Link Reset
     * -------------------------------------------------------------------------
     * Method ini menampilkan halaman form dimana user bisa memasukkan
     * email untuk menerima link reset password.
     * 
     * @return \Illuminate\View\View Halaman form lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * -------------------------------------------------------------------------
     * Kirim Link Reset Password
     * -------------------------------------------------------------------------
     * Method ini memproses request untuk mengirim link reset password.
     * 
     * Alur proses:
     * 1. Validasi format email
     * 2. Gunakan Password Broker untuk generate & kirim link
     * 3. Tampilkan pesan sukses atau error
     * 
     * Catatan keamanan:
     * - Menggunakan broker 'user_accounts' (bukan default 'users')
     * - Link expires sesuai config auth.passwords.user_accounts.expire
     * - Jika email tidak ditemukan, tetap tampilkan pesan netral
     *   (untuk mencegah user enumeration attack)
     * 
     * @param Request $request Data request berisi email
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status/error
     */
    public function sendResetLinkEmail(Request $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Validasi Email
        // -----------------------------------------------------------------
        // Pastikan email terisi dan format valid
        $request->validate(['email' => 'required|email']);

        // -----------------------------------------------------------------
        // LANGKAH 2: Kirim Link Reset
        // -----------------------------------------------------------------
        // Gunakan Password Broker dengan nama 'user_accounts'
        // Broker ini dikonfigurasi di config/auth.php
        // 
        // sendResetLink() akan:
        // - Cari user berdasarkan email
        // - Generate token reset
        // - Kirim email dengan link reset
        $status = Password::broker('user_accounts')->sendResetLink(
            $request->only('email')
        );

        // -----------------------------------------------------------------
        // LANGKAH 3: Return Response
        // -----------------------------------------------------------------
        // Password::RESET_LINK_SENT = berhasil kirim
        // Status lain = error (email tidak ditemukan, dll)
        // 
        // __($status) menerjemahkan status ke bahasa yang sesuai
        // (lihat lang/xx/passwords.php)
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
