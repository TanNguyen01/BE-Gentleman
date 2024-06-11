<?php

namespace Database\Seeders;

use App\Models\CustomAttributeVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Variant;

class CustomVariantAttributeSeeder extends Seeder
{
    public function run()
    {
        CustomAttributeVariant::factory()->count(20)->create();
    }
}
