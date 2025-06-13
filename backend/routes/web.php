<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobRole;
use App\Models\PublicHoliday;
use App\Models\Punch;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing', [
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
        return Inertia::render('CustomDashboard');
    })->name('dashboard');

    // Public Holidays
    Route::get('/holidays', function () {
        return Inertia::render('PublicHolidays/Index');
    })->name('holidays.index');

    Route::get('/holidays/create', function () {
        return Inertia::render('PublicHolidays/Create');
    })->name('holidays.create');

    Route::get('/holidays/{holiday}/edit', function (PublicHoliday $holiday) {
        return Inertia::render('PublicHolidays/Edit', [
            'holiday' => $holiday,
        ]);
    })->name('holidays.edit');

    // Departments
    Route::get('/departments', function () {
        return Inertia::render('Departments/Index');
    })->name('departments.index');

    Route::get('/departments/create', function () {
        return Inertia::render('Departments/Create');
    })->name('departments.create');

    Route::get('/departments/{department}/edit', function (Department $department) {
        return Inertia::render('Departments/Edit', [
            'department' => $department,
        ]);
    })->name('departments.edit');

    // Job Roles
    Route::get('/job-roles', function () {
        return Inertia::render('JobRoles/Index');
    })->name('job-roles.index');

    Route::get('/job-roles/create', function () {
        return Inertia::render('JobRoles/Create');
    })->name('job-roles.create');

    Route::get('/job-roles/{jobRole}/edit', function (JobRole $jobRole) {
        // Fetch departments to pass to the edit form
        $departments = \App\Models\Department::all();
        return Inertia::render('JobRoles/Edit', [
            'jobRole' => $jobRole,
            'departments' => $departments,
        ]);
    })->name('job-roles.edit');

    // Employees
    Route::get('/employees', function () {
        return Inertia::render('Employees/Index');
    })->name('employees.index');

    Route::get('/employees/create', function () {
        $departments = \App\Models\Department::all();
        $jobRoles = \App\Models\JobRole::all(); // Or filter by department if needed
        return Inertia::render('Employees/Create', [
            'departments' => $departments,
            'jobRoles' => $jobRoles,
        ]);
    })->name('employees.create');

    Route::get('/employees/{employee}/edit', function (Employee $employee) {
        $departments = \App\Models\Department::all();
        // Fetch job roles related to the employee's current department, or all if no department
        $jobRoles = $employee->department_id ? \App\Models\JobRole::where('department_id', $employee->department_id)->get() : \App\Models\JobRole::all();

        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
            'departments' => $departments,
            'jobRoles' => $jobRoles,
        ]);
    })->name('employees.edit');

    // Punch Management
    Route::get('/punches', function () {
        return Inertia::render('Punches/Index');
    })->name('punches.index');

    Route::get('/punches/create', function () {
        $employees = \App\Models\Employee::all();
        return Inertia::render('Punches/Create', [
            'employees' => $employees,
        ]);
    })->name('punches.create');

    Route::get('/punches/{punch}/edit', function (Punch $punch) {
        $employees = \App\Models\Employee::all();
        return Inertia::render('Punches/Edit', [
            'punch' => $punch,
            'employees' => $employees,
        ]);
    })->name('punches.edit');

    // Reporting
    Route::get('/reports/hours', function () {
        return Inertia::render('Reports/Hours');
    })->name('reports.hours');
});
