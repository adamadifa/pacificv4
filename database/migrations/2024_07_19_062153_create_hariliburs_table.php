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
        Schema::create('hrd_harilibur', function (Blueprint $table) {
            $table->char('kode_libur', 7)->primary();
            $table->date('tanggal');
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->smallInteger('kategori');
            $table->string('keterangan');
            $table->date('tanggal_limajam')->nullable();
            $table->date('tanggal_diganti')->nullable();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_harilibur');
    }
};
