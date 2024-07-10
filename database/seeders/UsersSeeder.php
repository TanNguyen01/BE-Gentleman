<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->admin()->create();
        }

        User::factory()->count(10)->create();

        User::query()->create([
            "email" => "admin1@gmail.com",
            "name" => "admin1",
            "password" => Hash::make('123456'),
            "role_id" => 0
        ]);

        User::query()->create([
            "email" => "admin2@gmail.com",
            "name" => "admin2",
            "password" => Hash::make('123456'),
            "role_id" => 0
        ]);

        User::query()->create([
            "email" => "user1@gmail.com",
            "name" => "user1",
            "password" => Hash::make('123456'),
            "role_id" => 1
        ]);

        User::query()->create([
            "email" => "user2@gmail.com",
            "name" => "user2",
            "password" => Hash::make('123456'),
            "role_id" => 1
        ]);
    }
}
