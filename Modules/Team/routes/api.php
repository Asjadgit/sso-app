<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\TeamController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('team', TeamController::class)->names('team');
// });

Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index']);
    Route::post('/store', [TeamController::class, 'store']);
    Route::get('{id}/show-members', [TeamController::class, 'show']);
    Route::get('/{id}/edit', [TeamController::class, 'edit'])->name('teams.members');
    Route::put('/{id}/update', [TeamController::class, 'update']);
    Route::post('/{teamId}/add-members', [TeamController::class, 'addMembersToTeam']);
    Route::post('/toggle-status/{id}', [TeamController::class, 'toggleStatus']);
    Route::delete('/{id}/delete', [TeamController::class, 'destroy']);
});
