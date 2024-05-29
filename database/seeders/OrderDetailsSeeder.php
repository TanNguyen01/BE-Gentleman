<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class OrderDetailsSeeder extends Seeder
{
    public function run()
    {
        OrderDetail::factory()->count(20)->create();
    }
}
