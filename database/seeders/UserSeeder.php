<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Web Master account
        User::create([
            'full_name' => 'Web Master',
            'email' => 'webmaster@pureair.com',
            'password' => Hash::make('password123'),
            'role' => 'web_master',
            'status' => true,
        ]);

        // Monitoring Admin account
        User::create([
            'full_name' => 'Monitoring Admin',
            'email' => 'admin@pureair.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => true,
        ]);
    }
}
