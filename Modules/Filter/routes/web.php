<?php

use Illuminate\Support\Facades\Route;
use Modules\Filter\Http\Controllers\FilterController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('filter', FilterController::class)->names('filter');
});
