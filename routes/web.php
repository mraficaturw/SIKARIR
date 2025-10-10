<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternjobController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Protected routes (harus login)
Route::middleware('auth:user_accounts')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.post');

    // Job interaction routes
    Route::post('job/{id}/favorite-toggle', [InternjobController::class, 'toggleFavorite'])->name('job.favorite.toggle');
    Route::post('job/{id}/applied-toggle', [InternjobController::class, 'toggleApplied'])->name('job.applied.toggle');
});