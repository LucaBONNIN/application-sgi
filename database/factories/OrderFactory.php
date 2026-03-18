<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
            'supplier_id' => Supplier::factory(),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'description' => fake()->sentence(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'estimated_delivery_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }

    public function sent(): static
    {
        return $this->state(['status' => OrderStatus::Sent]);
    }

    public function processing(): static
    {
        return $this->state(['status' => OrderStatus::Processing]);
    }

    public function ordered(): static
    {
        return $this->state(['status' => OrderStatus::Ordered]);
    }

    public function received(): static
    {
        return $this->state(['status' => OrderStatus::Received]);
    }

    public function closed(): static
    {
        return $this->state(['status' => OrderStatus::Closed]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => OrderStatus::Cancelled]);
    }
}
