<?php

namespace Database\Factories;

use App\Enums\TransactionState;
use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'type' => fake()->randomElement(TransactionType::cases()),
            'amount' => fake()->randomFloat(2, 1, 10000),
            'description' => fake()->sentence(),
            'state' => TransactionState::Paid,
            'date' => fake()->dateTimeBetween('-1 year', 'today')->format('Y-m-d'),
            'expected_payment_date' => null,
            'user_id' => $user->id,
            'category_id' => Category::factory()->for($user),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => TransactionState::Pending,
            'expected_payment_date' => fake()->dateTimeBetween('today', '+3 months')->format('Y-m-d'),
        ]);
    }
}
