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
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->foreign('kode_wilayah')
                ->references('kode_wilayah')
                ->on('wilayah')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_salesman')
                ->references('kode_salesman')
                ->on('salesman')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_cabang')
                ->references('kode_cabang')
                ->on('cabang')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->index('kode_wilayah');
            $table->index('kode_salesman');
            $table->index('kode_cabang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {

            $table->dropForeign('pelanggan_kode_cabang_foreign');
            $table->dropForeign('pelanggan_kode_salesman_foreign');
            $table->dropForeign('pelanggan_kode_wilayah_foreign');
            $table->dropIndex('pelanggan_kode_wilayah_index');
            $table->dropIndex('pelanggan_kode_salesman_index');
            $table->dropIndex('pelanggan_kode_cabang_index');
        });
    }
};
