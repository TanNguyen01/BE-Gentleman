<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\AttributeName;

class AttributesNameSeeder extends Seeder
{
    public function run()
    {
        AttributeName::factory()->count(20)->create();
    }
}
