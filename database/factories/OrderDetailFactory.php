<?php

namespace Database\Factories;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OrderDetail::class;

    public function definition()
    {
        return [
            'variant_id' => \App\Models\Variant::factory(),
            'order_id' => \App\Models\Order::factory(),
            'status' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'voucher_id' => \App\Models\Voucher::factory(),
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
