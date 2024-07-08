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
        Schema::table('gudang_jadi_angkutan_kontrabon', function (Blueprint $table) {
            $table->dropColumn('keterangan');
            $table->char('kode_angkutan', 4)->after('tanggal');
            $table->foreign('kode_angkutan')->references('kode_angkutan')->on('angkutan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_jadi_angkutan_kontrabon', function (Blueprint $table) {
            //
        });
    }
};
