<?php

use Illuminate\Support\Facades\Route;
use Modules\RolesAndPermission\Http\Controllers\RolesAndPermissionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('rolesandpermission', RolesAndPermissionController::class)->names('rolesandpermission');
});
