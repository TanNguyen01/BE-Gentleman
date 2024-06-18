<?php

namespace Database\Factories;

use App\Models\AttributeName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeNameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AttributeName::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
