<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bill;

class BillsSeeder extends Seeder
{
    public function run()
    {
        Bill::factory()->count(20)->create();
    }
}
