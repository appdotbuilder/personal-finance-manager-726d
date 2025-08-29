<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $investments = ['Apple Stock', 'Tesla Stock', 'Bitcoin', 'Real Estate Fund', 'S&P 500 ETF', 'Government Bonds'];
        $initialValue = fake()->randomFloat(2, 500, 10000);
        $changePercent = fake()->randomFloat(2, -30, 50) / 100;
        
        return [
            'name' => fake()->randomElement($investments),
            'type' => fake()->randomElement(['stocks', 'bonds', 'crypto', 'real_estate', 'mutual_funds', 'other']),
            'initial_value' => $initialValue,
            'current_value' => $initialValue * (1 + $changePercent),
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the investment is profitable.
     */
    public function profitable(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_value' => $attributes['initial_value'] * fake()->randomFloat(2, 1.1, 2.0),
        ]);
    }

    /**
     * Indicate that the investment is at a loss.
     */
    public function atLoss(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_value' => $attributes['initial_value'] * fake()->randomFloat(2, 0.5, 0.9),
        ]);
    }
}