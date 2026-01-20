<?php

/**
 * ============================================================================
 * ROUTE CONFIGURATION - WEB.PHP
 * ============================================================================
 * File ini berisi semua route untuk aplikasi SIKARIR.
 * 
 * Struktur route dibagi menjadi beberapa bagian:
 * 1. Public Routes - Dapat diakses tanpa login
 * 2. Authentication Routes - Login, Register, Forgot Password
 * 3. Email Verification Routes - Verifikasi email
 * 4. Protected Routes - Membutuhkan login dan email verified
 * 5. Internjob Routes - Halaman lowongan (public)
 * 6. Feature Routes - Favorit dan Apply (butuh login)
 * 
 * Guards yang digunakan:
 * - user_accounts: Untuk mahasiswa/pengguna biasa
 * - admin: Untuk admin panel (Filament)
 * ============================================================================
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\InternjobController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Route yang bisa diakses oleh semua orang, termasuk guest (belum login).
| Ini adalah halaman utama dan daftar lowongan publik.
*/

// Halaman utama (welcome) - Menampilkan 6 lowongan terbaru
Route::get('/', [InternjobController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Route untuk proses autentikasi: login, register, dan reset password.
| Semua route ini bisa diakses tanpa login (untuk guest).
*/

// ----- LOGIN -----
// GET: Tampilkan form login
// POST: Proses login dengan validasi dan rate limiting
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// ----- REGISTER -----
// GET: Tampilkan form registrasi
// POST: Proses registrasi dan kirim email verifikasi
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// ----- FORGOT PASSWORD -----
// Alur lupa password:
// 1. User request link reset (forgot-password)
// 2. User klik link di email (reset-password/{token})
// 3. User submit password baru (reset-password POST)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
| Route untuk verifikasi email setelah registrasi.
| User harus login tapi belum perlu email verified.
|
| Middleware:
| - auth:user_accounts: Harus login dengan guard user_accounts
| - signed: URL harus memiliki signature yang valid (untuk verify)
| - throttle:6,1: Maksimal 6 request per menit (untuk send)
*/

// Halaman notice - Memberitahu user untuk memverifikasi email
Route::get('/email/verify', [VerificationController::class, 'notice'])
    ->middleware('auth:user_accounts')
    ->name('verification.notice');

// Proses verifikasi - Dipanggil saat user klik link di email
// Signed middleware memastikan URL tidak dimanipulasi
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth:user_accounts', 'signed'])
    ->name('verification.verify');

// Kirim ulang email verifikasi
// Throttle mencegah spam pengiriman email
Route::post('/email/verification-notification', [VerificationController::class, 'send'])
    ->middleware(['auth:user_accounts', 'throttle:6,1'])
    ->name('verification.send');

/*
|--------------------------------------------------------------------------
| Logout Route (Global)
|--------------------------------------------------------------------------
| Route untuk logout yang menangani kedua guard sekaligus.
| Menggunakan closure langsung karena logicnya simple.
*/
Route::post('/logout', function (Request $request) {
    // Logout dari guard yang sedang aktif
    if (Auth::guard('user_accounts')->check()) {
        Auth::guard('user_accounts')->logout();
    } elseif (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }

    // Hapus session untuk keamanan
    $request->session()->invalidate();

    // Generate CSRF token baru
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Kamu telah logout.');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated User)
|--------------------------------------------------------------------------
| Route yang membutuhkan:
| 1. Login (auth:user_accounts)
| 2. Email sudah diverifikasi (verified)
|
| Ini termasuk halaman profil dan fitur yang membutuhkan identitas terverifikasi.
*/
Route::middleware(['auth:user_accounts', 'verified'])->group(function () {
    // ----- PROFILE -----
    // Halaman profil dengan daftar favorit dan lamaran
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Form edit profil (nama, avatar)
    Route::get('/profile/edit', [ProfileController::class, 'editForm'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // ----- CHANGE PASSWORD -----
    // Ganti password dengan verifikasi email
    Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.post');
});

// Verifikasi perubahan password - bisa diakses tanpa email verified
// (User mungkin lupa password sebelum verifikasi email)
Route::get('/password/verify/{token}', [ProfileController::class, 'verifyPasswordChange'])
    ->middleware('auth:user_accounts')
    ->name('password.verify');

/*
|--------------------------------------------------------------------------
| Internjob Routes (Public view)
|--------------------------------------------------------------------------
| Route publik untuk melihat lowongan dan perusahaan.
| Tidak membutuhkan login untuk akses.
*/

// Halaman daftar semua lowongan dengan pagination dan search
Route::get('/jobs', [InternjobController::class, 'jobs'])->name('jobs');

// Halaman detail lowongan - {id} adalah ID lowongan
Route::get('/job/{id}', [InternjobController::class, 'show'])->name('job.detail');

// Halaman detail perusahaan - menampilkan info perusahaan dan lowongan terkait
Route::get('/company/{id}', [InternjobController::class, 'companyDetail'])->name('company.detail');

/*
|--------------------------------------------------------------------------
| Features that require authentication
|--------------------------------------------------------------------------
| Fitur interaktif yang membutuhkan login:
| - Toggle favorit lowongan
| - Toggle status sudah apply
|
| Tidak membutuhkan email verified karena ini fitur ringan.
*/
Route::middleware('auth:user_accounts')->group(function () {
    // Toggle favorit - tambah/hapus lowongan dari daftar favorit
    Route::post('/jobs/{id}/favorite-toggle', [InternjobController::class, 'toggleFavorite'])->name('job.favorite.toggle');

    // Toggle applied - tandai/batalkan status sudah apply
    Route::post('/jobs/{id}/applied-toggle', [InternjobController::class, 'toggleApplied'])->name('job.applied.toggle');
});

// Debug routes removed for security - do not expose internal state in production
