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
        Schema::create('coa', function (Blueprint $table) {
            $table->char('kode_akun', 6)->primary();
            $table->string('nama_akun');
            $table->char('sub_akun', 6)->nullable();
            $table->smallInteger('level')->nullable();
            $table->char('jenis_akun', 1)->nullable();
            $table->char('kode_kategori', 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
