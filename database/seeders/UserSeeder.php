<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Bandung',
            'email' => 'admin@bandung.com',
            'password' => Hash::make('password123'),
        ]);
        
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
        ]);
        
        // Tambahkan beberapa user dummy
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@test.com",
                'password' => Hash::make('password123'),
            ]);
        }
    }
}