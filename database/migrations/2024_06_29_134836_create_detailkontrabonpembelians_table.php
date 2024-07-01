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
        Schema::create('pembelian_kontrabon_detail', function (Blueprint $table) {
            $table->char('no_kontrabon', 13);
            $table->char('no_bukti', 17);
            $table->double('jumlah', 13, 2);
            $table->string('keterangan')->nullable();
            $table->foreign('no_kontrabon')->references('no_kontrabon')->on('pembelian_kontrabon')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('no_bukti')->references('no_bukti')->on('pembelian')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_kontrabon_detail');
    }
};
