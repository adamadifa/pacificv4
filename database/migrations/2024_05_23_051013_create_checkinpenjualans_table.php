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
        Schema::create('marketing_penjualan_checkin', function (Blueprint $table) {
            $table->char('kode_checkin', 10)->primary();
            $table->char('kode_salesman', 7);
            $table->date('tanggal');
            $table->char('kode_pelanggan', 13);
            $table->timestamp('checkin_time');
            $table->timestamp('checkout_time')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_checkin');
    }
};
