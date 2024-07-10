<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BillStory;

class BillStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BillStory::factory()->count(10)->create();
    }
}
