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
        Schema::create('hrd_insentif', function (Blueprint $table) {
            $table->char('kode_insentif', 7)->primary();
            $table->char('nik', 10);
            $table->integer('iu_masakerja');
            $table->integer('iu_lembur');
            $table->integer('iu_penempatan');
            $table->integer('iu_kpi');
            $table->integer('im_ruanglingkup');
            $table->integer('im_penempatan');
            $table->integer('im_kinerja');
            $table->date('tanggal_berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_insentif');
    }
};
