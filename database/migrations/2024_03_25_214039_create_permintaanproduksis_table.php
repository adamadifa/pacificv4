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
        Schema::create('produksi_permintaan', function (Blueprint $table) {
            $table->char('no_permintaan', 6)->primary();
            $table->char('kode_oman', 6);
            $table->date('tanggal_permintaan');
            $table->char('status', 1);
            $table->foreign('kode_oman')->references('kode_oman')->on('marketing_oman')
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
        Schema::dropIfExists('produksi_permintaan');
    }
};
