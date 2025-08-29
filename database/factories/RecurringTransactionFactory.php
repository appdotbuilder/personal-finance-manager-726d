<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecurringTransaction>
 */
class RecurringTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);
        $frequency = fake()->randomElement(['daily', 'weekly', 'monthly', 'yearly']);
        $startDate = fake()->dateTimeBetween('-1 month', 'now');
        
        $descriptions = [
            'income' => ['Monthly Salary', 'Weekly Freelance', 'Rental Income'],
            'expense' => ['Monthly Rent', 'Weekly Groceries', 'Monthly Utilities', 'Annual Insurance']
        ];
        
        return [
            'type' => $type,
            'amount' => fake()->randomFloat(2, 50, 2000),
            'description' => fake()->randomElement($descriptions[$type]),
            'frequency' => $frequency,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => fake()->optional()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'next_due_date' => $this->calculateNextDueDate($startDate, $frequency),
            'is_active' => fake()->boolean(85),
        ];
    }

    /**
     * Calculate the next due date based on frequency.
     *
     * @param  \DateTime  $startDate
     * @param  string  $frequency
     * @return string
     */
    protected function calculateNextDueDate($startDate, $frequency): string
    {
        $date = clone $startDate;
        
        switch ($frequency) {
            case 'daily':
                $date->modify('+1 day');
                break;
            case 'weekly':
                $date->modify('+1 week');
                break;
            case 'monthly':
                $date->modify('+1 month');
                break;
            case 'yearly':
                $date->modify('+1 year');
                break;
        }
        
        return $date->format('Y-m-d');
    }

    /**
     * Indicate that the recurring transaction is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}