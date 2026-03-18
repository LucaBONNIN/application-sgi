<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste définie pour avoir des données réalistes pour la démo
        $categories = ['Informatique', 'Papeterie', 'Laboratoire', 'Mobilier', 'Produits Chimiques'];

        foreach ($categories as $cat) {
            Category::factory()->create([
                'name' => $cat,
                'slug' => Str::slug($cat),
            ]);
        }
    }
}
