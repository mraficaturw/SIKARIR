<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\InternjobController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [InternjobController::class, 'index'])->name('welcome');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Forgot & Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:user_accounts')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('welcome')->with('info', 'Email kamu sudah diverifikasi sebelumnya.');
    }

    $request->fulfill();
    return redirect()->route('welcome')->with('success', 'Email kamu berhasil diverifikasi.');
})->middleware(['auth:user_accounts', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user() && !$request->user()->hasVerifiedEmail()) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim ke email kamu.');
    }

    return back()->with('info', 'Email kamu sudah diverifikasi.');
})->middleware(['auth:user_accounts', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Logout Route (Global)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    if (Auth::guard('user_accounts')->check()) {
        Auth::guard('user_accounts')->logout();
    } elseif (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Kamu telah logout.');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:user_accounts', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.post');
});

/*
|--------------------------------------------------------------------------
| Internjob Routes (Public view)
|--------------------------------------------------------------------------
*/
Route::get('/', [InternjobController::class, 'index'])->name('welcome');
Route::get('/jobs', [InternjobController::class, 'jobs'])->name('jobs');
Route::get('/job/{id}', [InternjobController::class, 'show'])->name('job.detail');

/*
|--------------------------------------------------------------------------
| Features that require authentication
|--------------------------------------------------------------------------
*/
Route::middleware('auth:user_accounts')->group(function () {
    Route::post('/jobs/{id}/favorite-toggle', [InternjobController::class, 'toggleFavorite'])->name('job.favorite.toggle');
    Route::post('/jobs/{id}/applied-toggle', [InternjobController::class, 'toggleApplied'])->name('job.applied.toggle');
});

Route::get('/debug-scheme', function (Request $request) {
    return [
        'scheme' => $request->getScheme(),
        'host' => $request->getHost(),
        'url' => $request->fullUrl(),
    ];
});

/** @var \Illuminate\Contracts\Auth\Guard $auth */
$auth = auth();

return response()->json([
    'auth_guard' => config('auth.defaults.guard'),
    'user' => $auth->user(),
    'https' => request()->isSecure(),
]);

