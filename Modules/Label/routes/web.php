<?php

use Illuminate\Support\Facades\Route;
use Modules\Label\Http\Controllers\LabelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('label', LabelController::class)->names('label');
});
