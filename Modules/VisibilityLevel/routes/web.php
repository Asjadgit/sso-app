<?php

use Illuminate\Support\Facades\Route;
use Modules\VisibilityLevel\Http\Controllers\VisibilityLevelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('visibilitylevel', VisibilityLevelController::class)->names('visibilitylevel');
});
