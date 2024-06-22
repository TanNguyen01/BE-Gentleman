<?php

namespace Database\Factories;

use App\Models\BillStory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillStoryFactory extends Factory
{
    protected $model = BillStory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bill_id' =>  \App\Models\Bill::factory(),
            'user_id' =>  \App\Models\User::factory(),
            'description' => $this->faker->text,
        ];
    }
}
