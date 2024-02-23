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
        Schema::table('buffer_stok_detail', function (Blueprint $table) {
            $table->foreign('kode_buffer_stok')
                ->references('kode_buffer_stok')
                ->on('buffer_stok')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buffer_stok_detail', function (Blueprint $table) {
            $table->dropForeign('buffer_stok_detail_kode_buffer_stok_foreign');
        });
    }
};
