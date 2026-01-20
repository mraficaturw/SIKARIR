<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================================
 * VERIFICATION CONTROLLER
 * ============================================================================
 * Controller ini menangani proses verifikasi email pengguna.
 * Email harus diverifikasi sebelum user bisa mengakses fitur lengkap.
 * 
 * Alur verifikasi:
 * 1. User registrasi → email verifikasi dikirim otomatis
 * 2. User akses route protected → redirect ke halaman notice
 * 3. User klik link di email → verify() dipanggil
 * 4. Email terverifikasi → user bisa akses semua fitur
 * 
 * Middleware terkait:
 * - auth:user_accounts: User harus login
 * - signed: URL harus memiliki signature yang valid
 * - verified: User harus sudah verifikasi email
 * ============================================================================
 */
class VerificationController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Menampilkan Halaman Notice Verifikasi
     * -------------------------------------------------------------------------
     * Method ini menampilkan halaman yang memberitahu user bahwa
     * mereka perlu memverifikasi email sebelum melanjutkan.
     * 
     * Halaman ini biasanya berisi:
     * - Pesan bahwa email verifikasi sudah dikirim
     * - Tombol untuk kirim ulang email verifikasi
     * - Instruksi untuk cek inbox/spam
     * 
     * @return \Illuminate\View\View Halaman notice verifikasi email
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * -------------------------------------------------------------------------
     * Memproses Verifikasi Email
     * -------------------------------------------------------------------------
     * Method ini dipanggil ketika user mengklik link verifikasi di email.
     * Link berisi signed URL dengan ID user dan hash email.
     * 
     * Alur proses:
     * 1. VerifyEmailRequest memvalidasi signature dan hash
     * 2. Cek apakah email sudah diverifikasi sebelumnya
     * 3. Tandai email sebagai terverifikasi (fulfill)
     * 4. Login user dan redirect ke halaman utama
     * 
     * Logging:
     * - Mencatat percobaan verifikasi (untuk debugging)
     * - Mencatat keberhasilan verifikasi
     * 
     * @param VerifyEmailRequest $request Request dengan validasi signature
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman utama
     */
    public function verify(VerifyEmailRequest $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Cek Apakah Sudah Terverifikasi
        // -----------------------------------------------------------------
        // Jika email sudah diverifikasi, informasikan ke user
        // (mencegah proses ulang yang tidak perlu)
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('welcome')->with('info', 'Email kamu sudah diverifikasi sebelumnya.');
        }

        // -----------------------------------------------------------------
        // LANGKAH 2: Log Percobaan Verifikasi
        // -----------------------------------------------------------------
        // Logging untuk keperluan debugging dan audit trail
        Log::info('Email verification attempt', [
            'user_id' => $request->route('id'),
            'hash' => $request->route('hash'),
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 3: Tandai Email Sebagai Terverifikasi
        // -----------------------------------------------------------------
        // fulfill() akan:
        // - Set email_verified_at ke waktu sekarang
        // - Trigger event Verified
        $request->fulfill();

        // Ambil user yang sudah diverifikasi untuk logging
        $user = $request->user();
        Log::info('Email verification completed', [
            'user_id' => $user->id,
            'email_verified_at' => $user->email_verified_at,
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 4: Login User dan Redirect
        // -----------------------------------------------------------------
        // Re-login untuk memastikan session terupdate
        Auth::guard('user_accounts')->login($user);

        return redirect()->route('welcome')->with('success', 'Email kamu berhasil diverifikasi!');
    }

    /**
     * -------------------------------------------------------------------------
     * Kirim Ulang Email Verifikasi
     * -------------------------------------------------------------------------
     * Method ini mengirim ulang email verifikasi ke user.
     * Berguna jika email pertama tidak sampai atau expired.
     * 
     * Rate limiting:
     * - Route ini memiliki throttle:6,1 → maksimal 6 request per menit
     * - Mencegah spam dan penyalahgunaan
     * 
     * @param Request $request Data request
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status
     */
    public function send(Request $request)
    {
        // Cek apakah sudah terverifikasi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('welcome')->with('info', 'Email kamu sudah diverifikasi.');
        }

        // Kirim email verifikasi menggunakan method dari UserAccount
        // Method ini menggunakan CustomVerifyEmail notification
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Link verifikasi baru telah dikirim ke email kamu.');
    }
}
