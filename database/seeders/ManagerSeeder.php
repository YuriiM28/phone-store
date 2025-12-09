<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Иван',
            'email' => 'ivan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '+7 (999) 123-45-67',
            'address' => '-',
            'city' => 'Санкт-Петербург',
            'postal_code' => '123456',
        ]);

        User::create([
            'name' => 'Юрий',
            'email' => 'yuriy@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+7 (999) 765-43-21',
            'address' => '-',
            'city' => 'Санкт-Петербург',
            'postal_code' => '654321',
        ]);
    }
}