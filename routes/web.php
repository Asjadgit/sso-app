<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login',[LoginController::class, 'index'])->name('login');
Route::post('/login',[LoginController::class, 'login'])->name('user.login');


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard',[LoginController::class, 'home'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
// Auth routes
Route::get('/auth', [AuthController::class, 'show'])->name('auth');
Route::post('/central-login', [AuthController::class, 'logIn'])->name('central-login');
Route::get('/redirect-user/{globalUserId}/to-tenant/{tenant}', [AuthController::class, 'redirectUserToTenant'])->name('redirect-user-to-tenant');
