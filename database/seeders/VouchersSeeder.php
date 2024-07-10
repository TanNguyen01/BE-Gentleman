<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VouchersSeeder extends Seeder
{
    public function run()
    {
        Voucher::factory()->count(20)->create();
    }
}
