<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // Settings Management
        Permission::firstOrCreate(['name' => 'manage settings']);
        Permission::firstOrCreate(['name' => 'view business settings']);
        Permission::firstOrCreate(['name' => 'edit business settings']);
        Permission::firstOrCreate(['name' => 'view payroll settings']);
        Permission::firstOrCreate(['name' => 'edit payroll settings']);

        // Public Holidays Management
        Permission::firstOrCreate(['name' => 'manage public holidays']);
        Permission::firstOrCreate(['name' => 'view public holidays']);
        Permission::firstOrCreate(['name' => 'create public holidays']);
        Permission::firstOrCreate(['name' => 'edit public holidays']);
        Permission::firstOrCreate(['name' => 'delete public holidays']);

        // Department Management
        Permission::firstOrCreate(['name' => 'manage departments']);
        Permission::firstOrCreate(['name' => 'view departments']);
        Permission::firstOrCreate(['name' => 'create departments']);
        Permission::firstOrCreate(['name' => 'edit departments']);
        Permission::firstOrCreate(['name' => 'delete departments']);

        // Job Role Management
        Permission::firstOrCreate(['name' => 'manage job roles']);
        Permission::firstOrCreate(['name' => 'view job roles']);
        Permission::firstOrCreate(['name' => 'create job roles']);
        Permission::firstOrCreate(['name' => 'edit job roles']);
        Permission::firstOrCreate(['name' => 'delete job roles']);

        // Employee Management
        Permission::firstOrCreate(['name' => 'manage employees']);
        Permission::firstOrCreate(['name' => 'view employees']);
        Permission::firstOrCreate(['name' => 'create employees']);
        Permission::firstOrCreate(['name' => 'edit employees']);
        Permission::firstOrCreate(['name' => 'delete employees']); // Consider soft delete/deactivation
        Permission::firstOrCreate(['name' => 'view own employee profile']);

        // Employee Document Management
        Permission::firstOrCreate(['name' => 'manage employee documents']);
        Permission::firstOrCreate(['name' => 'view employee documents']);
        Permission::firstOrCreate(['name' => 'create employee documents']);
        Permission::firstOrCreate(['name' => 'edit employee documents']);
        Permission::firstOrCreate(['name' => 'delete employee documents']);

        // Punch Management
        Permission::firstOrCreate(['name' => 'manage punches']);
        Permission::firstOrCreate(['name' => 'view punches']);
        Permission::firstOrCreate(['name' => 'import punches']);
        Permission::firstOrCreate(['name' => 'edit punches']);
        Permission::firstOrCreate(['name' => 'add punches']);
        Permission::firstOrCreate(['name' => 'delete punches']);
        Permission::firstOrCreate(['name' => 'autofix punches']);
        Permission::firstOrCreate(['name' => 'view own punches']);

        // Reporting
        Permission::firstOrCreate(['name' => 'generate reports']);
        Permission::firstOrCreate(['name' => 'view hours report']);
        Permission::firstOrCreate(['name' => 'export hours report']);
        Permission::firstOrCreate(['name' => 'view own hours report']);

        // User Management (for Admin)
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Admin has all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Manager permissions
        $managerRole->givePermissionTo([
            'view business settings',
            'view payroll settings',
            'view public holidays',
            'view departments',
            'view job roles',
            'view employees',
            'view employee documents', // Managers can view documents for their department's employees
            'view punches',
            'import punches',
            'edit punches',
            'add punches',
            'delete punches',
            'autofix punches',
            'generate reports',
            'view hours report',
            'export hours report',
            'view own employee profile',
            'view own punches',
            'view own hours report',
        ]);

        // Employee permissions
        $employeeRole->givePermissionTo([
            'view own employee profile',
            'view own punches',
            'view own hours report',
        ]);
    }
}
