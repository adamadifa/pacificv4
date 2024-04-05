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
        Schema::table('gudang_jadi_mutasi', function (Blueprint $table) {
            $table->index('no_permintaan');
            $table->index('tanggal');
            $table->index('jenis_mutasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_jadi_mutasi', function (Blueprint $table) {
            $table->dropIndex('gudang_jadi_mutasi_no_permintaan_index');
            $table->dropIndex('gudang_jadi_mutasi_tanggal_index');
            $table->dropIndex('gudang_jadi_mutasi_jenis_mutasi_index');
        });
    }
};
