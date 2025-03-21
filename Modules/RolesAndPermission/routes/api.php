<?php

use Illuminate\Support\Facades\Route;
use Modules\RolesAndPermission\Http\Controllers\RolesAndPermissionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('rolesandpermission', RolesAndPermissionController::class)->names('rolesandpermission');
});
