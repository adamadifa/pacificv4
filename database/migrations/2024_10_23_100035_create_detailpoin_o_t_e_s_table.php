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
        Schema::create('hrd_poin_ote_detail', function (Blueprint $table) {
            $table->char('kode_ote', 9);
            $table->char('nik', 9);
            $table->decimal('poin', 10, 2);
            $table->foreign('kode_ote')->references('kode_ote')->on('hrd_poin_ote')->onDelete('cascade');
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_poin_ote_detail');
    }
};
