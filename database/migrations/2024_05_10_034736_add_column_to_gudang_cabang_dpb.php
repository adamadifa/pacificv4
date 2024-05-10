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
        Schema::table('gudang_cabang_dpb', function (Blueprint $table) {
            $table->char('jenis_perhitungan', 1)->default('Q')->after('tanggal_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_cabang_dpb', function (Blueprint $table) {
            $table->dropColumn('jenis_perhitungan');
        });
    }
};
