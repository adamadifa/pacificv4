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
        Schema::create('marketing_program_ikatan_enambulan_detail', function (Blueprint $table) {
            $table->char('no_pengajuan', 11);
            $table->char('no_pengajuan_programikatan', 11);
            $table->char('kode_pelanggan', 13);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_program_ikatan_enambulan_detail');
    }
};
