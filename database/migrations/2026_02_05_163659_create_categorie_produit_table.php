<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorie_produit', function (Blueprint $table) {
            $table->id();

            $table->string('titre de la categorie');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie_produit');
    }
};
