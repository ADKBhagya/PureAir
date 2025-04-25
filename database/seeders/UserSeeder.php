<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Web Master
        User::create([
            'full_name' => 'Web Master',
            'email' => 'webmaster@pureair.com',
            'password' => Hash::make('webmaster123'),
            'role' => 'web_master',
            'status' => 'Active',
        ]);

        // Monitoring Admin
        User::create([
            'full_name' => 'Monitoring Admin',
            'email' => 'admin@pureair.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'Active',
        ]);
    }
}
