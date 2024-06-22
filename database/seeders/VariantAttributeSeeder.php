<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    public function run()
    {
        VariantAttribute::factory()->count(20)->create();
    }
}
