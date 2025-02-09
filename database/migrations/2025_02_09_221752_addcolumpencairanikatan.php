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
        Schema::table('marketing_pencairan_ikatan', function (Blueprint $table) {
            $table->dropForeign(['no_pengajuan']);
            $table->dropColumn('no_pengajuan');
            $table->char('kode_program', 7);
            $table->char('kode_cabang', 3);
            $table->foreign('kode_program')->references('kode_program')->on('program_ikatan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan', function (Blueprint $table) {
            //
        });
    }
};
