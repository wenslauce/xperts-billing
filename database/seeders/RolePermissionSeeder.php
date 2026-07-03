<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view dashboard',
            'manage customers',
            'manage products',
            'manage orders',
            'manage invoices',
            'manage payments',
            'manage servers',
            'manage tickets',
            'manage settings',
            'manage reports',
            'impersonate customers',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'view dashboard',
            'manage customers',
            'manage products',
            'manage orders',
            'manage invoices',
            'manage payments',
            'manage servers',
            'manage tickets',
            'manage reports',
        ]);

        $support = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);
        $support->syncPermissions([
            'view dashboard',
            'manage customers',
            'manage tickets',
            'impersonate customers',
        ]);

        $billing = Role::firstOrCreate(['name' => 'billing', 'guard_name' => 'web']);
        $billing->syncPermissions([
            'view dashboard',
            'manage invoices',
            'manage payments',
            'manage orders',
        ]);

        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customer->syncPermissions([]);

        // Create super-admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@xpertsafrica.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole('super-admin');
    }
}