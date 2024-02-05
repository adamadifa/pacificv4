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
        Schema::create('salesman', function (Blueprint $table) {
            $table->char('kode_salesman', 7)->primary();
            $table->string('nama_salesman', 50);
            $table->string('alamat_salesman', 100)->nullable();
            $table->string('no_hp_salesman', 13)->nullable();
            $table->char('kode_kategori_salesman', 2);
            $table->char('status_komisi_salesman', 1);
            $table->char('status_aktif_salesman', 1);
            $table->char('kode_cabang', 3);
            $table->string('marker', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesman');
    }
};
