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
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->char('kode_akun_potongan', 6)->default('4-2201')->after('kode_akun');
            $table->char('kode_akun_penyesuaian', 6)->default('4-2202')->after('kode_akun_potongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->dropColumn('kode_akun_potongan');
            $table->dropColumn('kode_akun_penyesuaian');
        });
    }
};
