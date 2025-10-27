<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternjobController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Controllers\Auth\VerificationController;

// Public routes
Route::get('/', [InternjobController::class, 'index'])->name('welcome');
Route::get('/jobs', [InternjobController::class, 'jobs'])->name('jobs');
Route::get('/job/{id}', [InternjobController::class, 'show'])->name('job.detail');

// Guest routes (hanya untuk yang belum login)
Route::middleware('guest:user_accounts')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'changePassword'])->name('password.change');
    Route::get('password-change-verify/{token}', [ResetPasswordController::class, 'verifyPasswordChange'])->name('password.change.verify');
});

// Email verification routes (tidak memerlukan auth untuk verifikasi)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:user_accounts')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('user_accounts')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi sudah dikirim ulang ke email kamu.');
})->middleware(['auth:user_accounts', 'throttle:6,1'])->name('verification.send');

// Protected routes (harus login)
Route::middleware('auth:user_accounts')->group(function () {

    // Logout route
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Routes yang hanya boleh diakses oleh user yang sudah diverifikasi
    Route::middleware('verified')->group(function () {
        // Profile routes
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
        Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.post');

        // Job interaction routes
        Route::post('job/{id}/favorite-toggle', [InternjobController::class, 'toggleFavorite'])->name('job.favorite.toggle');
        Route::post('job/{id}/applied-toggle', [InternjobController::class, 'toggleApplied'])->name('job.applied.toggle');
    });
});