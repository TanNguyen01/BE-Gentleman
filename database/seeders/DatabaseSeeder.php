<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        \App\Models\Attribute::truncate();
        \App\Models\Category::truncate();
        \App\Models\Product::truncate();
        \App\Models\Variant::truncate();
        \App\Models\User::truncate();
        \App\Models\Order::truncate();
        \App\Models\OrderDetail::truncate();
        \App\Models\Bill::truncate();
        \App\Models\BillDetail::truncate();
        \App\Models\Voucher::truncate();
        \App\Models\Address::truncate();
        // --------------------------
        \App\Models\Attribute::factory()->count(5)->create();
        \App\Models\Category::factory()->count(5)->create();
        \App\Models\Product::factory()->count(20)->create();
        \App\Models\Variant::factory()->count(5)->create();
        \App\Models\User::factory()->count(10)->create();
        \App\Models\Order::factory()->count(20)->create();
        \App\Models\OrderDetail::factory()->count(50)->create();
        \App\Models\Bill::factory()->count(20)->create();
        \App\Models\BillDetail::factory()->count(40)->create();
        \App\Models\Voucher::factory()->count(10)->create();
        \App\Models\Address::factory()->count(20)->create();
        \App\Models\Role::factory()->user()->create();
        \App\Models\Role::factory()->admin()->create();
        \App\Models\User::factory()->admin()->create();
    }
}
