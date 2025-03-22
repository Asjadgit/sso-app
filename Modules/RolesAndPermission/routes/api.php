<?php

use Illuminate\Support\Facades\Route;
use Modules\RolesAndPermission\Http\Controllers\RoleController;
use Modules\RolesAndPermission\Http\Controllers\RolesAndPermissionController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('permissions')->group(function () {
        Route::get('/', [RolesAndPermissionController::class, 'index']);
        Route::post('/store', [RolesAndPermissionController::class, 'store']);
        Route::delete('/{id}',[RolesAndPermissionController::class, 'destroy']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/store', [RoleController::class, 'store']);
        Route::delete('/{id}',[RoleController::class, 'destroy']);
    });
});
