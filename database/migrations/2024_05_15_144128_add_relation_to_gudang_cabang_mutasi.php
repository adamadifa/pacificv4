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
        Schema::table('gudang_cabang_mutasi', function (Blueprint $table) {
            $table->foreign('jenis_mutasi')->references('kode_jenis_mutasi')->on('gudang_cabang_jenis_mutasi')->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_cabang_mutasi', function (Blueprint $table) {
            //
        });
    }
};
