<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavingsGoal>
 */
class SavingsGoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $goals = ['Emergency Fund', 'Vacation', 'New Car', 'House Down Payment', 'Wedding', 'Education Fund'];
        $targetAmount = fake()->randomFloat(2, 1000, 50000);
        $currentAmount = fake()->randomFloat(2, 0, $targetAmount * 0.8);
        
        return [
            'name' => fake()->randomElement($goals),
            'target_amount' => $targetAmount,
            'current_amount' => $currentAmount,
            'target_date' => fake()->optional()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'description' => fake()->optional()->sentence(),
            'is_completed' => $currentAmount >= $targetAmount,
        ];
    }

    /**
     * Indicate that the savings goal is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_amount' => $attributes['target_amount'],
            'is_completed' => true,
        ]);
    }

    /**
     * Indicate that the savings goal just started.
     */
    public function justStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_amount' => 0,
            'is_completed' => false,
        ]);
    }
}