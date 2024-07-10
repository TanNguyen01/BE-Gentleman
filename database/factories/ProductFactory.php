<?php

// database/factories/ProductFactory.php
namespace Database\Factories;

use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique($maxRetries = 20000)->word . '_' . $this->faker->unique()->randomNumber(),
            'category_id' => \App\Models\Category::factory(),
            'sale_id' => \App\Models\Sale::factory(),
            'brand' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence,
        ];
    }
}
