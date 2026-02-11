<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $users = User::all();

        if ($users->isEmpty() || $suppliers->isEmpty()) return;

        foreach ($users as $user) {
            foreach ($user->services as $service) {
                Order::factory(2)
                    ->for($user)
                    ->for($service)
                    ->for($suppliers->random())
                    ->create()
                    ->each(function ($order) use ($categories) {
                        OrderLine::factory(rand(2, 4))
                            ->for($order)
                            ->for($categories->random())
                            ->create();
                    });
            }
        }
    }
}
