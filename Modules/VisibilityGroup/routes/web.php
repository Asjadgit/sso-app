<?php

use Illuminate\Support\Facades\Route;
use Modules\VisibilityGroup\Http\Controllers\VisibilityGroupController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('visibilitygroup', VisibilityGroupController::class)->names('visibilitygroup');
});
