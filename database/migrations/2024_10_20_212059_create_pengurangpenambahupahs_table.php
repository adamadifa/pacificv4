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
        Schema::create('hrd_penyesuaian_upah', function (Blueprint $table) {
            $table->char('kode_gaji', 8);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penyesuaian_upah');
    }
};
