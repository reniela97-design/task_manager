<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Gigamit kini sa ubos

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Paghimo og Administrator Role
        $adminRole = Role::firstOrCreate(['role_name' => 'Administrator']);

        // Paghimo og Admin User gamit ang Hash
        User::updateOrCreate(
            ['email' => 'admin@emergence.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'), // Gigamit na ang Hash dinhi
                'role_id' => $adminRole->id,
                'user_inactive' => 0,
            ]
        );
    }
}