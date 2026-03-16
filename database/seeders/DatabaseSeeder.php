<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Independant
            ShieldSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ServiceSeeder::class, // also creates Budgets
            UserSeeder::class, // depends on ServiceSeeder
            OrderSeeder::class, // depends on everything else, also creates OrderLines
        ]);
    }
}
