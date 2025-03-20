<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Auth routes
Route::get('/auth', [AuthController::class, 'show'])->name('auth');
Route::post('/central-login', [AuthController::class, 'logIn'])->name('central-login');
Route::get('/redirect-user/{globalUserId}/to-tenant/{tenant}', [AuthController::class, 'redirectUserToTenant'])->name('redirect-user-to-tenant');
