<?php

use Illuminate\Support\Facades\Route;
use Modules\Field\Http\Controllers\FieldController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('field', FieldController::class)->names('field');
});
