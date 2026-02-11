<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un fournisseur réel
        Supplier::create([
            'name' => 'Go Tronic',
            'email' => 'contact@gotronic.fr',
            'phone' => '03.24.27.93.42',
            'address' => '35 ter route nationale, 08110 Blagny',
            'siret' => '438 306 680 00028',
        ]);

        // Ajout de faux fournisseurs pour la variété
        Supplier::factory(5)->create();
    }
}
