<?php

namespace Database\Seeders;


use App\Models\Attribute;
use Illuminate\Database\Seeder;


class AttributesSeeder extends Seeder
{
    public function run()
    {
       Attribute::factory()->count(20)->create();
    }
}
