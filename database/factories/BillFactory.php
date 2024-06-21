<?php

namespace Database\Factories;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Bill::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'recipient_phone' => $this->faker->phoneNumber,
            'recipient_address' => $this->faker->address,
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'pay' => $this->faker->word,
            'status' => $this->faker->randomElement(['Pending', 'Paid', 'Confirm','Shipping', 'Done', 'Cancel'])
        ];
    }
}
