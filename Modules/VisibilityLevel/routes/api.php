<?php

use Illuminate\Support\Facades\Route;
use Modules\VisibilityLevel\Http\Controllers\VisibilityLevelController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('visibilitylevel', VisibilityLevelController::class)->names('visibilitylevel');
// });

// for managing visibility levels

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('visibility-levels', [VisibilityLevelController::class, 'index']);
    Route::post('visibility-levels/store', [VisibilityLevelController::class, 'store']);
    Route::get('visibility-levels/{id}/edit', [VisibilityLevelController::class, 'apiedit']);
    Route::put('visibility-levels/{id}/update', [VisibilityLevelController::class, 'apiupdate']);
    Route::get('visibility-levels/{id}/delete', [VisibilityLevelController::class, 'apidestroy']);
});
