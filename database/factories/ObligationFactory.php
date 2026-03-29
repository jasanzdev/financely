<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Obligation>
 */
class ObligationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'amount' => fake()->randomFloat(2, 10, 5000),
            'description' => fake()->sentence(),
            'limit_day' => fake()->numberBetween(1, 28),
            'is_active' => true,
            'user_id' => User::factory(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
