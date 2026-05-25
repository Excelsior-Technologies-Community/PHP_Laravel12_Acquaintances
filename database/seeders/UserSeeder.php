<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'User One', 'email' => 'user1@gmail.com'],
            ['name' => 'User Two', 'email' => 'user2@gmail.com'],
            ['name' => 'User Three', 'email' => 'user3@gmail.com'],
            ['name' => 'User Four', 'email' => 'user4@gmail.com'],
            ['name' => 'User Five', 'email' => 'user5@gmail.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('123456')
            ]);
        }
    }
}