<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// temporary main page
Route::get('/', function () {
    return view('home');
})->name('home');

// only for GUESTS
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// only for AUTHORIZED
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});