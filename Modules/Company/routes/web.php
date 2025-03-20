<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\CompanyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('company', CompanyController::class)->names('company');
});
