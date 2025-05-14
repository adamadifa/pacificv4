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
        Schema::table('coa', function (Blueprint $table) {
            $table->char('kode_transaksi', 6)->nullable();
            $table->char('kode_cabang', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coa', function (Blueprint $table) {
            $table->dropColumn('kode_transaksi');
            $table->dropColumn('kode_cabang');
        });
    }
};
