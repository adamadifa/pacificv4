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
        Schema::table('gudang_cabang_jenis_mutasi', function (Blueprint $table) {
            $table->char('kategori', 3);
            $table->smallInteger('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_cabang_jenis_mutasi', function (Blueprint $table) {
            //
        });
    }
};
