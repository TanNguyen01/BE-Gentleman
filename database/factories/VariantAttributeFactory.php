<?php

namespace Database\Factories;

use App\Models\VariantAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class VariantAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = VariantAttribute::class;

    public function definition()
    {
        return [
            'variant_id' => \App\Models\Variant::factory(),
            'attribute_value_id' => \App\Models\AttributeValue::factory(),
        ];
    }
}
