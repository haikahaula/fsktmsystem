<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // condition
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]
        );
        User::firstOrCreate(
            ['email' => 'head@gmail.com'], // condition
            [
                'name' => 'Academic Head',
                'password' => Hash::make('password'),
                'role_id' => 2,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Academic Staff',
                'password' => Hash::make('password'),
                'role_id' => 3,
            ]
        );
    }
}
