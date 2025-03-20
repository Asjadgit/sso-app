<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\ContactController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contact', ContactController::class)->names('contact');
});
