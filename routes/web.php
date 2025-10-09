<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternjobController;

Route::get('/', [InternjobController::class, 'index'])->name('welcome');

Route::get('/jobs', [InternjobController::class, 'jobs'])->name('jobs');

Route::get('/job/{id}', [InternjobController::class, 'show'])->name('job.detail');
