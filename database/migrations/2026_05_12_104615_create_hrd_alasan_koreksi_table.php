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
        Schema::create('hrd_alasan_koreksi', function (Blueprint $table) {
            $table->id();
            $table->string('alasan');
            $table->boolean('status_denda')->default(0); // 0: Tidak, 1: Ya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_alasan_koreksi');
    }
};
