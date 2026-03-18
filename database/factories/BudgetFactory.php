<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'year' => (int) date('Y'),
            'amount' => fake()->numberBetween(100000, 1000000),
        ];
    }
}
