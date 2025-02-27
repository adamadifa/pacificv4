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
        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->smallInteger('voucher_reward')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makrketing_penjualan_historibayar', function (Blueprint $table) {
            $table->dropColumn('voucher_reward');
        });
    }
};
