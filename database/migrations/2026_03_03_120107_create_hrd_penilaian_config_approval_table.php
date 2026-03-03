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
        Schema::create('hrd_penilaian_config_approval', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dept', 5)->nullable();
            $table->string('kode_cabang', 3)->nullable();
            $table->string('kategori_jabatan', 2)->nullable();
            $table->string('kode_jabatan', 3)->nullable();
            $table->text('roles'); // JSON array of roles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penilaian_config_approval');
    }
};
