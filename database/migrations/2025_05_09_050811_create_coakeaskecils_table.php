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
        Schema::create('coa_kas_kecil', function (Blueprint $table) {
            $table->char('kode_akun', 6)->unique();
            $table->char('kode_cabang')->unique();
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->cascadeOnDelete();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coakeaskecils');
    }
};
