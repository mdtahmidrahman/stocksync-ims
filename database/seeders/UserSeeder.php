<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@stocksync.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Store Manager
        User::updateOrCreate(
            ['email' => 'manager@stocksync.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        // Floor Staff
        User::updateOrCreate(
            ['email' => 'staff@stocksync.com'],
            [
                'name' => 'Floor Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );
    }
}
