<?php

use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\PayrollSettingController;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\ReportController;
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

    // Public Holidays
    Route::apiResource('holidays', PublicHolidayController::class);

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Job Roles
    Route::get('/departments/{departmentId}/job-roles', [JobRoleController::class, 'index']);
    Route::post('/departments/{departmentId}/job-roles', [JobRoleController::class, 'store']);
    Route::put('/job-roles/{id}', [JobRoleController::class, 'update']);
    Route::delete('/job-roles/{id}', [JobRoleController::class, 'destroy']);

    // Employees
    Route::apiResource('employees', EmployeeController::class);

    // Employee Documents
    Route::post('/employees/{employeeId}/documents', [EmployeeDocumentController::class, 'store']);
    Route::put('/documents/{id}', [EmployeeDocumentController::class, 'update']);
    Route::delete('/documents/{id}', [EmployeeDocumentController::class, 'destroy']);

    // Punch Management
    Route::post('/punches/import', [PunchController::class, 'import']);
    Route::get('/punches', [PunchController::class, 'index']);
    Route::put('/punches/{id}', [PunchController::class, 'update']);
    Route::post('/punches', [PunchController::class, 'store']);
    Route::delete('/punches/{id}', [PunchController::class, 'destroy']);
    Route::post('/punches/autofix', [PunchController::class, 'autofix']);

    // Reporting
    Route::get('/reports/hours', [ReportController::class, 'hours']);
    Route::get('/reports/hours/export', [ReportController::class, 'exportHours']);
});
