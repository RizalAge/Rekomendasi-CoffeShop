<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Membuat akun Admin khusus
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@cafe.com', // Email untuk login
            'password' => Hash::make('password123'), // Password untuk login
            'role' => 'admin', // Set role langsung sebagai admin
        ]);
    }
}