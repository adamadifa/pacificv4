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
            $table->char('print_tagihan', 1)->default('0')->after('kode_lhp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->dropColumn('print_tagihan');
        });
    }
};
