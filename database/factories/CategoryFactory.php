<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $incomeCategories = ['Salary', 'Freelance', 'Investment Returns', 'Business Income', 'Side Hustle'];
        $expenseCategories = ['Food & Dining', 'Transportation', 'Entertainment', 'Bills & Utilities', 'Shopping', 'Healthcare', 'Education'];
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'];
        
        $type = fake()->randomElement(['income', 'expense']);
        $categories = $type === 'income' ? $incomeCategories : $expenseCategories;
        
        return [
            'name' => fake()->randomElement($categories),
            'type' => $type,
            'color' => fake()->randomElement($colors),
            'description' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the category is for income.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'name' => fake()->randomElement(['Salary', 'Freelance', 'Investment Returns', 'Business Income']),
        ]);
    }

    /**
     * Indicate that the category is for expenses.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'name' => fake()->randomElement(['Food & Dining', 'Transportation', 'Entertainment', 'Shopping']),
        ]);
    }
}