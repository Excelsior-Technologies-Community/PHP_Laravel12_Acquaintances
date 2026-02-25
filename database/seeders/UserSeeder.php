<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'User One',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('123456')
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('123456')
        ]);
    }
}