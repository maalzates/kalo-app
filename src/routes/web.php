<?php

use App\Modules\Auth\Presentation\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Google OAuth routes
Route::get('/login/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/login/google/callback', [GoogleAuthController::class, 'callback'])->name('login.google.callback');

// SPA catch-all route (must be last)
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
