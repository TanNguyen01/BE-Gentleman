<?php

namespace Database\Factories;


use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AttributeValue::class;

    public function definition()
    {
        return [
            'attribute_id' => \App\Models\Attribute::factory(),
            'value' => $this->faker->word,
        ];
    }
}
