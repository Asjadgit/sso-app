<?php

use Illuminate\Support\Facades\Route;
use Modules\Filter\Http\Controllers\FilterController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/filters/{entity_type}', [FilterController::class, 'apiindex']); // Get filters for an entity
    Route::post('/filters/create', [FilterController::class, 'store']);
    Route::delete('/delete/filter/{id}', [FilterController::class, 'destroy']);
});


