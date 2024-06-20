<?php

namespace Database\Factories;

use App\Models\BillDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillDetail>
 */
class BillDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BillDetail::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
            'attribute' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'bill_id' => \App\Models\Bill::factory(),
            'voucher' => $this->faker->word,
            'image'  => $this->faker->imageUrl(),
        ];
    }
}
