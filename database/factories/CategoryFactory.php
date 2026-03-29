<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
