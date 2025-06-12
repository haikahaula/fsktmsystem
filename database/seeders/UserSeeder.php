<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Academic Head',
            'email' => 'head@example.com',
            'password' => Hash::make('password'), // Set a default password
        ]);

        User::create([
            'name' => 'Academic Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
