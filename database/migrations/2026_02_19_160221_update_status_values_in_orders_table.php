<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::table('orders')->where('status', '0')->update(['status' => 'sent']);
            DB::table('orders')->where('status', '1')->update(['status' => 'sent']);
            DB::table('orders')->where('status', '2')->update(['status' => 'processing']);
            DB::table('orders')->where('status', '3')->update(['status' => 'ordered']);
            DB::table('orders')->where('status', '4')->update(['status' => 'received']);
            DB::table('orders')->where('status', '5')->update(['status' => 'cancelled']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::table('orders')->where('status', 'sent')->update(['status' => '1']);
            DB::table('orders')->where('status', 'processing')->update(['status' => '2']);
            DB::table('orders')->where('status', 'ordered')->update(['status' => '3']);
            DB::table('orders')->where('status', 'received')->update(['status' => '4']);
            DB::table('orders')->where('status', 'cancelled')->update(['status' => '5']);
        });
    }
};
