<?php

use Illuminate\Support\Facades\Route;
use Modules\Label\Http\Controllers\LabelController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('label', LabelController::class)->names('label');
});
