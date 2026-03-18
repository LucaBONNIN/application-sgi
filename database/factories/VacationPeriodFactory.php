<?php

namespace Database\Factories;

use App\Models\VacationPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VacationPeriod>
 */
class VacationPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', '+6 months');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(7, 16) . ' days');

        return [
            'name' => fake()->words(3, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'school_year' => (int) date('Y'),
        ];
    }
}
