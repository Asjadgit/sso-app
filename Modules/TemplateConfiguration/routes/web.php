<?php

use Illuminate\Support\Facades\Route;
use Modules\TemplateConfiguration\Http\Controllers\TemplateConfigurationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('templateconfiguration', TemplateConfigurationController::class)->names('templateconfiguration');
});
