<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'avatar' => $this->faker->imageUrl(),
            'role_id' => $this->faker->randomElement([0, 1]),
            'status' => $this->faker->randomElement([0, 1]),
            'number' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Define the admin state.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Hash m?t kh?u
                'avatar' => 'path/to/admin-avatar.png', // Ðý?ng d?n ð?n avatar n?u có
                'role_id' => 1, // Giá tr? role_id 1 là admin
                'status' => 1,
                'number' => '1234567890'
            ];
        });
    }
}
