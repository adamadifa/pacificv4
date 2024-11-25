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
        Schema::create('marketing_program_pencairan_detail', function (Blueprint $table) {
            $table->char('kode_pencairan', 11);
            $table->char('kode_pelanggan', 13);
            $table->integer('jumlah');
            $table->integer('diskon_reguler');
            $table->integer('diskon_kumulatif');
            $table->timestamps();
            $table->foreign('kode_pencairan')->references('kode_pencairan')->on('marketing_program_pencairan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailpencairans');
    }
};
