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
        Schema::create('hrd_presensi_log_error', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 50);
            $table->date('tanggal');
            $table->dateTime('jam');
            $table->string('status_presensi', 10);
            $table->string('lokasi')->nullable();
            $table->text('error_message');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_presensi_log_error');
    }
};
