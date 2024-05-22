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
        Schema::create('marketing_penjualan_movefaktur', function (Blueprint $table) {
            $table->id();
            $table->char('no_faktur', 14);
            $table->date('tanggal');
            $table->char('kode_salesman_lama', 7);
            $table->char('kode_salesman_baru', 7);
            $table->timestamps();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_salesman_lama')->references('kode_salesman')->on('salesman')->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_salesman_baru')->references('kode_salesman')->on('salesman')->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_movefaktur');
    }
};
