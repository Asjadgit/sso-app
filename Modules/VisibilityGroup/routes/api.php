<?php

use Illuminate\Support\Facades\Route;
use Modules\VisibilityGroup\Http\Controllers\VisibilityGroupController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('visibilitygroup', VisibilityGroupController::class)->names('visibilitygroup');
// });


// for managing visibility_groups
Route::get('visibility-groups', [VisibilityGroupController::class, 'index']);
Route::post('visibility-groups/store', [VisibilityGroupController::class, 'store']);
Route::get('visibility-groups/{id}/edit', [VisibilityGroupController::class, 'edit']);
Route::put('visibility-groups/{id}/update', [VisibilityGroupController::class, 'update']);
Route::get('visibility-groups/{id}/show', [VisibilityGroupController::class, 'show']);
Route::get('visibility-groups/{id}/delete', [VisibilityGroupController::class, 'destroy']);
Route::post('assign-user/{id}', [VisibilityGroupController::class, 'addUser'])->name('assign-user');

// removing user
Route::delete('/visibility-groups/{groupid}/users/{userid}', [VisibilityGroupController::class, 'removeUser'])
    ->name('visibility-groups.remove-user');
