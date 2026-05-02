<?php

namespace Database\Seeders;

use App\Models\VacationPeriod;
use Illuminate\Database\Seeder;

class VacationPeriodSeeder extends Seeder
{
    /**
     * Seed vacation periods for Zone C (Paris).
     */
    public function run(): void
    {
        $periods = [
            // 2025-2026
            ['name' => 'Vacances de la Toussaint 2025', 'start_date' => '2025-10-18', 'end_date' => '2025-11-03', 'school_year' => 2025],
            ['name' => 'Vacances de Noël 2025', 'start_date' => '2025-12-20', 'end_date' => '2026-01-05', 'school_year' => 2025],
            ['name' => "Vacances d'hiver 2026", 'start_date' => '2026-02-21', 'end_date' => '2026-03-09', 'school_year' => 2025],
            ['name' => 'Vacances de printemps 2026', 'start_date' => '2026-04-18', 'end_date' => '2026-05-04', 'school_year' => 2025],
            ['name' => "Vacances d'été 2026", 'start_date' => '2026-07-04', 'end_date' => '2026-08-31', 'school_year' => 2025],

            // 2026-2027
            ['name' => 'Vacances de la Toussaint 2026', 'start_date' => '2026-10-17', 'end_date' => '2026-11-02', 'school_year' => 2026],
            ['name' => 'Vacances de Noël 2026', 'start_date' => '2026-12-19', 'end_date' => '2027-01-04', 'school_year' => 2026],
            ['name' => "Vacances d'hiver 2027", 'start_date' => '2027-02-06', 'end_date' => '2027-02-22', 'school_year' => 2026],
            ['name' => 'Vacances de printemps 2027', 'start_date' => '2027-04-03', 'end_date' => '2027-04-19', 'school_year' => 2026],
            ['name' => "Vacances d'été 2027", 'start_date' => '2027-07-03', 'end_date' => '2027-08-31', 'school_year' => 2026],
        ];

        foreach ($periods as $period) {
            VacationPeriod::firstOrCreate(
                ['name' => $period['name']],
                $period,
            );
        }
    }
}
