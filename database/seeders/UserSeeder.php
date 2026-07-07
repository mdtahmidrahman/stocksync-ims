<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Spatie Roles
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'staff']);

        $company = Company::create([
            'name' => 'StockSync Demo HQ',
        ]);

        // 1. Platform Owner (Super Admin) - No company tied
        $superAdmin = User::create([
            'name' => 'Platform Owner',
            'email' => 'super@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'company_id' => null,
        ]);
        $superAdmin->assignRole('super_admin');

        // 2. Company Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'company_id' => $company->id,
        ]);
        $admin->assignRole('admin');

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'company_id' => $company->id,
        ]);
        $manager->assignRole('manager');

        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'company_id' => $company->id,
        ]);
        $staff->assignRole('staff');
    }
}
