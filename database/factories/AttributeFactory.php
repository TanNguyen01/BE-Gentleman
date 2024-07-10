<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Attribute::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique($maxRetries = 20000)->word . '_' . $this->faker->unique()->randomNumber(),
        ];
    }
}
