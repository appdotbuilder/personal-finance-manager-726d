<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debt>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 50, 5000);
        $paidAmount = fake()->boolean(70) ? fake()->randomFloat(2, 0, $amount * 0.8) : 0;
        
        return [
            'type' => fake()->randomElement(['debt', 'receivable']),
            'person_name' => fake()->name(),
            'amount' => $amount,
            'paid_amount' => $paidAmount,
            'description' => fake()->optional()->sentence(),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'is_paid' => $paidAmount >= $amount,
        ];
    }

    /**
     * Indicate that the debt is fully paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['amount'],
            'is_paid' => true,
        ]);
    }

    /**
     * Indicate that the debt is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => 0,
            'is_paid' => false,
        ]);
    }
}