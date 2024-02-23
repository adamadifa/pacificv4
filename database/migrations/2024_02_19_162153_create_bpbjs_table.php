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
        Schema::create('produksi_mutasi', function (Blueprint $table) {
            $table->string('no_mutasi', 25)->primary();
            $table->date('tanggal_mutasi');
            $table->string('in_out', 3);
            $table->string('jenis_mutasi', 9);
            $table->char('status', 1);
            $table->char('unit', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_mutasi');
    }
};
