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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->char('kode_kendaraan', 6)->primary();
            $table->string('no_polisi', 10);
            $table->string('no_stnk')->nullable();
            $table->string('no_uji')->nullable();
            $table->string('sipa')->nullable();
            $table->string('ibm')->nullable();
            $table->string('merek', 20);
            $table->string('tipe_kendaraan', 20)->nullable();
            $table->string('tipe', 30)->nullable();
            $table->string('no_rangka', 50)->nullable();
            $table->string('no_mesin', 30)->nullable();
            $table->char('tahun_pembuatan', 4)->nullable();
            $table->string('atas_nama', 50)->nullable();
            $table->string('alamat')->nullable();
            $table->date('jatuhtempo_kir')->nullable();
            $table->date('jatuhtempo_pajak_satutahun')->nullable();
            $table->date('jatuhtempo_pajak_limatahun')->nullable();
            $table->char('kode_cabang', 3);
            $table->smallInteger('kapasitas')->nullable();
            $table->char('status_aktif_kendaraan', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
