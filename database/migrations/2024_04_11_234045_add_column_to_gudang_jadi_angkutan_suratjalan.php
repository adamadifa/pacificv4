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
        Schema::table('gudang_jadi_angkutan_suratjalan', function (Blueprint $table) {
            $table->char('kode_angkutan', 4)->after('no_polisi');
            $table->char('kode_tujuan', 3)->after('kode_angkutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_jadi_angkutan_suratjalan', function (Blueprint $table) {
            $table->dropColumn('kode_angkutan');
            $table->dropColumn('kode_tujuan');
        });
    }
};
