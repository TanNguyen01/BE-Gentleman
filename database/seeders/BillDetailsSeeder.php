<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BillDetail;

class BillDetailsSeeder extends Seeder
{
    public function run()
    {
        BillDetail::factory()->count(20)->create();
    }
}
