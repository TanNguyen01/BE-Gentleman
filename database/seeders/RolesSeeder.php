<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::insertOrIgnore([
            ['id' => 0, 'name' => 'user'],
            ['id' => 1, 'name' => 'admin'],
        ]);
    }
}
