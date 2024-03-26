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
        Schema::create('marketing_permintaan_kiriman', function (Blueprint $table) {
            $table->char('no_permintaan', 18)->primary();
            $table->date('tanggal');
            $table->char('kode_cabang', 3);
            $table->text('keterangan');
            $table->char('status');
            $table->char('kode_salesman', 7)->nullable();
            $table->bigInteger('id_user');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_permintaan_kiriman');
    }
};
