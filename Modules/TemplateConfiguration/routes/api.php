<?php

use Illuminate\Support\Facades\Route;
use Modules\TemplateConfiguration\Http\Controllers\TemplateConfigurationController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('templateconfiguration', TemplateConfigurationController::class)->names('templateconfiguration');
// });

Route::get('/{entity}/template-configuration', [TemplateConfigurationController::class, 'templateView'])->name('getListViewConfiguration');

Route::Post('/{entity}/template-configuration', [TemplateConfigurationController::class, 'store'])->name('saveListViewConfiguration');

Route::post('/templates/load-view', [TemplateConfigurationController::class, 'loadViewType']);
