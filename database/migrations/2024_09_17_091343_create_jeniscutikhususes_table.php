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
        Schema::create('hrd_jeniscuti_khusus', function (Blueprint $table) {
            $table->char('kode_cuti_khusus', 3);
            $table->string('nama_cuti_khusus');
            $table->smallInteger('lama_hari');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_jeniscuti_khusus');
    }
};
