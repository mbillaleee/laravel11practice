<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


Route::middleware('guest')->group(function () {
    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
});