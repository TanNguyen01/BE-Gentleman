<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Variant;

class VariantsSeeder extends Seeder
{
    public function run()
    {
        Variant::factory()->count(20)->create();
    }
}
