<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Hash mật khẩu
            'avatar' => 'path/to/admin-avatar.png', // Đường dẫn đến avatar nếu có
            'role_id' => 1, // Giả sử role_id 1 là admin
            'status' => 'active',
            'number' => '1234567890'
        ]);

        User::factory()->count(20)->create();
    }
}
