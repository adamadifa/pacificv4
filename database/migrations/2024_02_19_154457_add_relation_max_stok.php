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
        Schema::table('max_stok_detail', function (Blueprint $table) {
            $table->foreign('kode_max_stok')
                ->references('kode_max_stok')
                ->on('max_stok')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('max_stok_detail', function (Blueprint $table) {
            $table->dropForeign('max_stok_detail_kode_max_stok_foreign');
        });
    }
};
