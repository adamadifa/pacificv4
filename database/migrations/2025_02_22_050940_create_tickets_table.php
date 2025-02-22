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
        Schema::create('tickets', function (Blueprint $table) {
            $table->char('kode_pengajuan', 10)->primary();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->smallInteger('gm')->nullable();
            $table->smallInteger('dirut')->nullable();
            $table->char('status');
            $table->bigInteger('id_user');
            $table->date('tanggal_selesai')->nullable();
            $table->bigInteger('id_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
