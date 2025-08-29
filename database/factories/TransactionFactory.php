<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense', 'transfer']);
        $descriptions = [
            'income' => ['Salary Payment', 'Freelance Project', 'Investment Dividend', 'Bonus Payment'],
            'expense' => ['Grocery Shopping', 'Gas Station', 'Restaurant Bill', 'Online Purchase', 'Utility Bill'],
            'transfer' => ['Account Transfer', 'Savings Transfer', 'Internal Transfer']
        ];
        
        return [
            'type' => $type,
            'amount' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->randomElement($descriptions[$type]),
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }

    /**
     * Indicate that the transaction is income.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'amount' => fake()->randomFloat(2, 100, 5000),
            'description' => fake()->randomElement(['Salary Payment', 'Freelance Project', 'Investment Dividend']),
        ]);
    }

    /**
     * Indicate that the transaction is an expense.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'amount' => fake()->randomFloat(2, 5, 500),
            'description' => fake()->randomElement(['Grocery Shopping', 'Gas Station', 'Restaurant Bill']),
        ]);
    }
}