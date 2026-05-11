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
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->index(['tanggal', 'no_faktur'], 'idx_penjualan_tanggal_nofaktur');
        });

        Schema::table('marketing_penjualan_movefaktur', function (Blueprint $table) {
            $table->index(['no_faktur', 'id'], 'idx_movefaktur_nofaktur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->dropIndex('idx_penjualan_tanggal_nofaktur');
        });

        Schema::table('marketing_penjualan_movefaktur', function (Blueprint $table) {
            $table->dropIndex('idx_movefaktur_nofaktur_id');
        });
    }
};
