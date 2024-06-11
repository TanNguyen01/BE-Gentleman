<?php

namespace Database\Factories;

use App\Models\CustomAttributeVariant;
use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomAttributeVariant>
 */
class CustomVariantAttributeFactory extends Factory
{
    protected $model = CustomAttributeVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'variant_id' => Variant::factory(),
            'attribute_id' => Attribute::factory(),
        ];
    }
}
