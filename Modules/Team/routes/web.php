<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\TeamController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('team', TeamController::class)->names('team');
});
