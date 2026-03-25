<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vacation_periods', function (Blueprint $table) {
            $table->integer('school_year')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacation_periods', function (Blueprint $table) {
            $table->integer('school_year')->nullable(false)->change();
        });
    }
};
