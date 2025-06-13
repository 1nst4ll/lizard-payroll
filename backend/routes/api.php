<?php

use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\PayrollSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Business Settings
    Route::get('/settings/business', [BusinessSettingController::class, 'show']);
    Route::put('/settings/business', [BusinessSettingController::class, 'update']);

    // Payroll Settings
    Route::get('/settings/payroll', [PayrollSettingController::class, 'show']);
    Route::put('/settings/payroll', [PayrollSettingController::class, 'update']);
});
