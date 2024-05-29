<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Voucher::class;

    public function definition()
    {
        return [
            'voucher_code' => $this->faker->unique()->word,
            'discount_amount' => $this->faker->randomFloat(2, 1, 100),
            'expiration_date' => $this->faker->date(),
            'minimum_purchase' => $this->faker->randomFloat(2, 10, 100),
            'usage_limit' => $this->faker->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => $this->faker->randomElement([0,1]),
            'description' => $this->faker->sentence,
        ];
    }
}
