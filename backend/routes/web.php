<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Employee Management Routes
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::get('employees/expiring-work-permits', [\App\Http\Controllers\EmployeeController::class, 'expiringWorkPermits'])
         ->name('employees.expiring-work-permits');
    Route::post('employees/{id}/restore', [\App\Http\Controllers\EmployeeController::class, 'restore'])
         ->name('employees.restore');
});
