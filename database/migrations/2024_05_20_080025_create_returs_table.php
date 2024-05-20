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
        Schema::create('marketing_retur', function (Blueprint $table) {
            $table->char('no_retur', 13)->primary();
            $table->date('tanggal');
            $table->char('no_faktur', 13);
            $table->char('no_ref', 13);
            $table->char('jenis_retur', 2);
            $table->bigInteger('id_user');
            $table->timestamps();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_retur');
    }
};
