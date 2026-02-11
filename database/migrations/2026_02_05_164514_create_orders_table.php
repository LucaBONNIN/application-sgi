<?php

use App\Models\Budget;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Service::class)->constrained();
            $table->foreignIdFor(Supplier::class)->constrained();
            $table->foreignIdFor(Budget::class)->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('status');
            $table->text('description')->nullable();
            $table->string('quotation_path')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
