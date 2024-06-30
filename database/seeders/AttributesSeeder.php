<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributesSeeder extends Seeder
{
    public function run()
    {
        Attribute::factory()->count(20)->create();
    }
}
