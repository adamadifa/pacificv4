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
        Schema::create('tickets_update_data', function (Blueprint $table) {
            $table->char('kode_pengajuan', 10)->primary();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->smallInteger('kategori');
            $table->string('no_bukti');
            $table->smallInteger('gm')->nullable();
            $table->char('status');
            $table->bigInteger('id_user');
            $table->bigInteger('id_approval')->nullable();
            $table->text('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_update_data');
    }
};
