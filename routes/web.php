<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::middleware('guest')->group(function () {
    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
});



Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
});


Route::group(['middleware' => ['auth','admin']], function() {
    Route::get('admin/dashboard', [HomeController::class, 'admindashboard'])->name('admin.dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});