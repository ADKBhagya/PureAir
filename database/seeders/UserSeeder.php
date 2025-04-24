<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'full_name' => 'Web Master',
            'email' => 'webmaster@pureair.com',
            'password' => Hash::make('password123'),
            'role' => 'web_master',
            'status' => true
        ]);

        User::create([
            'full_name' => 'Admin User',
            'email' => 'admin@pureair.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => true
        ]);
    }
}
