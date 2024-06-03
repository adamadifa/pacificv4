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
        Schema::create('marketing_ajuan_limitkredit_disposisi', function (Blueprint $table) {
            $table->char('kode_disposisi', 16)->primary();
            $table->char('no_pengajuan', 13);
            $table->bigInteger('id_pengirim');
            $table->bigInteger('id_penerima');
            $table->string('uraian_analisa');
            $table->char('status', 1);
            $table->timestamps();
            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('marketing_ajuan_limitkredit')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_ajuan_limitkredit_disposisi');
    }
};
