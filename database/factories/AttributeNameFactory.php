<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\VariantAttribute;
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
