<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegistationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegistationController::class, 'register'])->name('register');
Route::post('login', [RegistationController::class, 'login'])->name('login');

Route::middleware("auth:sanctum")->group(function(){
    Route::resource('posts', PostController::class);
});


