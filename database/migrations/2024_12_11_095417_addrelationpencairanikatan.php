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
        Schema::table('marketing_pencairan_ikatan_detail', function (Blueprint $table) {
            $table->foreign('kode_pencairan')->references('kode_pencairan')->on('marketing_pencairan_ikatan')->onDelete('cascade');
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan_detail', function (Blueprint $table) {
            //
        });
    }
};
