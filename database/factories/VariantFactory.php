<?php

namespace Database\Factories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Variant::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            //'attribute_id' => \App\Models\Attribute::factory(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'price_promotional' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
