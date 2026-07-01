<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::create([
            'name' => 'StockSync Demo HQ',
        ]);

        // 1. Platform Owner (Super Admin) - No company tied
        User::create([
            'name' => 'Platform Owner',
            'email' => 'super@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'company_id' => null,
        ]);

        // 2. Company Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'company_id' => $company->id,
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'company_id' => $company->id,
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@stocksync.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'company_id' => $company->id,
        ]);
    }
}
