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
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            $table->datetime('jam_keluar')->after('kode_cabang');
            $table->datetime('jam_kembali')->nullable()->after('jam_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            //
        });
    }
};
