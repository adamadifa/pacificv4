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
        Schema::create('hrd_bpjs_tenagakerja', function (Blueprint $table) {
            $table->char('kode_bpjs_tenagakerja', 7)->primary();
            $table->char('nik', 10);
            $table->integer('iuran');
            $table->date('tanggal_berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_bpjs_tenagakerja');
    }
};
