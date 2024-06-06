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
        Schema::create('marketing_ajuan_fakturkredit', function (Blueprint $table) {
            $table->char('no_pengajuan', 10)->primary();
            $table->date('tanggal');
            $table->char('kode_pelanggan', 13);
            $table->char('kode_salesman', 7);
            $table->smallInteger('jml_faktur');
            $table->char('cod', 1);
            $table->string('keterangan');
            $table->bigInteger('id_user');
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_ajuan_fakturkredit');
    }
};
