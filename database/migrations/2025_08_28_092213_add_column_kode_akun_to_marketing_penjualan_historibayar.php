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
            // Menambahkan kolom kode_akun untuk keterangan tambahan piutang dagang
            $table->char('kode_akun', 6)->default('1-1401')->comment('Piutang Dagang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->dropColumn('kode_akun');
        });
    }
};
