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
        Schema::create('keuangan_belumsetor_detail', function (Blueprint $table) {
            $table->char('kode_belumsetor', 12);
            $table->char('kode_salesman', 7);
            $table->integer('jumlah');
            $table->foreign('kode_belumsetor')->references('kode_belumsetor')->on('keuangan_belumsetor')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_belumsetor_detail');
    }
};
