<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * ============================================================================
 * LOGIN CONTROLLER
 * ============================================================================
 * Controller ini menangani proses login pengguna ke aplikasi SIKARIR.
 * 
 * Fitur utama:
 * - Menampilkan form login
 * - Memproses autentikasi pengguna
 * - Perlindungan dari serangan brute force dengan rate limiting
 * - Redirect berdasarkan status verifikasi email
 * 
 * Guard yang digunakan: 'user_accounts'
 * ============================================================================
 */
class LoginController extends Controller
{
    /**
     * Maksimal percobaan login yang diizinkan sebelum akun dikunci sementara.
     * Setelah 5x percobaan gagal, pengguna harus menunggu sebelum mencoba lagi.
     */
    protected int $maxAttempts = 5;

    /**
     * Durasi penguncian dalam detik (5 menit = 300 detik).
     * Setelah waktu ini, pengguna bisa mencoba login lagi.
     */
    protected int $decaySeconds = 300;

    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Login
     * -------------------------------------------------------------------------
     * Method ini dipanggil ketika pengguna mengakses halaman /login
     * 
     * @return \Illuminate\View\View Halaman form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * -------------------------------------------------------------------------
     * Memproses Login Pengguna
     * -------------------------------------------------------------------------
     * Method ini memproses data login yang dikirim dari form.
     * 
     * Alur proses:
     * 1. Cek rate limiting (apakah terlalu banyak percobaan gagal?)
     * 2. Validasi format email dan password
     * 3. Coba autentikasi dengan guard 'user_accounts'
     * 4. Jika berhasil, regenerasi session untuk keamanan
     * 5. Redirect ke halaman utama atau halaman verifikasi email
     * 
     * @param Request $request Data request berisi email & password
     * @return \Illuminate\Http\RedirectResponse Redirect sesuai hasil login
     * @throws ValidationException Jika validasi atau autentikasi gagal
     */
    public function login(Request $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Cek Rate Limiting
        // -----------------------------------------------------------------
        // Pastikan pengguna belum melebihi batas percobaan login.
        // Ini mencegah serangan brute force (percobaan password berulang).
        $this->checkTooManyFailedAttempts($request);

        // -----------------------------------------------------------------
        // LANGKAH 2: Validasi Input
        // -----------------------------------------------------------------
        // Pastikan email valid dan password terisi.
        // Jika tidak valid, Laravel akan otomatis mengembalikan error.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 3: Coba Autentikasi
        // -----------------------------------------------------------------
        // Gunakan guard 'user_accounts' untuk autentikasi.
        // Parameter kedua adalah opsi "remember me" (ingat saya).
        if (Auth::guard('user_accounts')->attempt($credentials, $request->boolean('remember'))) {

            // Ambil data pengguna yang berhasil login
            $user = Auth::guard('user_accounts')->user();

            // Hapus catatan percobaan login gagal setelah berhasil
            RateLimiter::clear($this->throttleKey($request));

            // -------------------------------------------------------------
            // LANGKAH 4: Cek Verifikasi Email
            // -------------------------------------------------------------
            // Jika email belum diverifikasi, arahkan ke halaman verifikasi.
            // Pengguna tidak bisa mengakses fitur lengkap tanpa verifikasi.
            if (is_null($user->email_verified_at)) {
                return redirect()->route('verification.notice');
            }

            // -------------------------------------------------------------
            // LANGKAH 5: Regenerasi Session
            // -------------------------------------------------------------
            // Ini penting untuk keamanan - mencegah session fixation attack.
            // Session ID lama diganti dengan yang baru.
            $request->session()->regenerate();

            // Redirect ke halaman yang diminta sebelumnya, atau ke welcome
            return redirect()->intended(route('welcome'))->with('success', 'Login successful.');
        }

        // -----------------------------------------------------------------
        // LANGKAH 6: Login Gagal
        // -----------------------------------------------------------------
        // Jika sampai di sini, berarti email/password salah.
        // Catat percobaan gagal untuk rate limiting.
        RateLimiter::hit($this->throttleKey($request), $this->decaySeconds);

        // Lempar error validasi dengan pesan standar Laravel
        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * Cek Apakah Terlalu Banyak Percobaan Login Gagal
     * -------------------------------------------------------------------------
     * Method ini memeriksa apakah pengguna sudah melebihi batas percobaan login.
     * Jika sudah, tampilkan pesan error dengan waktu tunggu.
     * 
     * Ini adalah mekanisme keamanan untuk mencegah:
     * - Serangan brute force (mencoba banyak password)
     * - Serangan credential stuffing
     * 
     * @param Request $request Data request untuk identifikasi pengguna
     * @throws ValidationException Jika batas percobaan terlampaui
     */
    protected function checkTooManyFailedAttempts(Request $request): void
    {
        // Cek apakah sudah terlalu banyak percobaan gagal
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {

            // Hitung waktu tunggu yang tersisa dalam detik
            $seconds = RateLimiter::availableIn($this->throttleKey($request));

            // Lempar error dengan pesan throttle
            // Pesan ini diterjemahkan dari file lang/xx/auth.php
            throw ValidationException::withMessages([
                'email' => [trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ])],
            ]);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * Generate Throttle Key
     * -------------------------------------------------------------------------
     * Method ini membuat kunci unik untuk rate limiting berdasarkan:
     * - Email pengguna (diubah ke huruf kecil semua)
     * - Alamat IP pengguna
     * 
     * Kombinasi ini memastikan rate limiting tepat sasaran:
     * - Satu email dari IP berbeda dihitung terpisah
     * - IP yang sama dengan email berbeda dihitung terpisah
     * 
     * @param Request $request Data request berisi email dan IP
     * @return string Kunci unik untuk rate limiting
     */
    protected function throttleKey(Request $request): string
    {
        // Gabungkan email (lowercase) dengan IP, pisahkan dengan |
        // Str::transliterate menghilangkan karakter khusus
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /**
     * -------------------------------------------------------------------------
     * Logout Pengguna
     * -------------------------------------------------------------------------
     * Method ini mengeluarkan pengguna dari sistem.
     * 
     * Proses logout:
     * 1. Logout dari guard 'user_accounts'
     * 2. Hapus semua data session
     * 3. Generate CSRF token baru
     * 4. Redirect ke halaman utama
     * 
     * @param Request $request Data request
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman utama
     */
    public function logout(Request $request)
    {
        // Logout dari guard user_accounts
        Auth::guard('user_accounts')->logout();

        // Hapus semua data session pengguna
        $request->session()->invalidate();

        // Generate CSRF token baru untuk keamanan
        $request->session()->regenerateToken();

        return redirect(route('welcome'))->with('success', 'Logged out successfully.');
    }
}
