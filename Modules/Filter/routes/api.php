<?php

use Illuminate\Support\Facades\Route;
use Modules\Filter\Http\Controllers\FilterController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('filter', FilterController::class)->names('filter');
// });
Route::get('/filters/{entity_type}', [FilterController::class, 'apiindex']); // Get filters for an entity
Route::post('/filters/create', [FilterController::class, 'store']);
Route::delete('/delete/filter/{id}', [FilterController::class, 'destroy']);
