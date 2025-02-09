<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data using DELETE instead of TRUNCATE
        //\App\Models\AttributeName::query()->delete();
        \App\Models\Category::query()->delete();
        \App\Models\Product::query()->delete();
        \App\Models\Variant::query()->delete();
        \App\Models\User::query()->delete();
        \App\Models\Bill::query()->delete();
        \App\Models\BillDetail::query()->delete();
        \App\Models\Voucher::query()->delete();
        \App\Models\Post::query()->delete();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed new data
        \App\Models\Attribute::factory()->count(2)->create();
        \App\Models\Category::factory()->count(5)->create();
        \App\Models\Product::factory()->count(20)->create();
        \App\Models\Variant::factory()->count(5)->create();
        \App\Models\User::factory()->count(10)->create();
       \App\Models\Bill::factory()->count(20)->create();
        \App\Models\BillDetail::factory()->count(40)->create();
        \App\Models\Voucher::factory()->count(10)->create();
        \App\Models\Post::factory()->count(10)->create();
        \App\Models\User::factory()->admin()->create();
        \App\Models\AttributeValue::factory()->count(20)->create();
       // \App\Models\ProductAttribute::factory()->count(20)->create();
        \App\Models\VariantAttribute::factory()->count(20)->create();
        \App\Models\BillStory::factory()->count(20)->create();
    }
}
