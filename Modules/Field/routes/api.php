<?php

use Illuminate\Support\Facades\Route;
use Modules\Field\Http\Controllers\FieldController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('field', FieldController::class)->names('field');
});
