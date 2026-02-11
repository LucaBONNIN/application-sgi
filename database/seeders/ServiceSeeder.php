<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Mathématiques',
            'Sciences Physiques',
            'SVT',
            'Lettres',
            'Vie Scolaire',
            'Administration'
        ];

        foreach ($services as $name) {
            $service = Service::create(['name' => $name]);

            // Création automatique du budget 2026 associé
            Budget::create([
                'service_id' => $service->id,
                'year' => 2026,
                'amount' => 500000, // 5000
            ]);
        }
    }
}
