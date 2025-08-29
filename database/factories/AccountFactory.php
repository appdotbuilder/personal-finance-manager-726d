<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['bank', 'e_wallet', 'cash', 'credit_card', 'investment'];
        
        return [
            'name' => fake()->words(2, true) . ' Account',
            'type' => fake()->randomElement($types),
            'balance' => fake()->randomFloat(2, 0, 10000),
            'currency' => 'USD',
            'description' => fake()->optional()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }

    /**
     * Indicate that the account has a high balance.
     */
    public function highBalance(): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => fake()->randomFloat(2, 10000, 100000),
        ]);
    }

    /**
     * Indicate that the account is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}