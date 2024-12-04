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
        Schema::create('marketing_pencairan_ikatan', function (Blueprint $table) {
            $table->char('kode_pencairan', 11);
            $table->date('tanggal');
            $table->char('no_pengajuan', 11);
            $table->string('keterangan');
            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('marketing_program_ikatan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairanprogramikatans');
    }
};
