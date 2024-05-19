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
        Schema::create('marketing_komisi_target_disposisi', function (Blueprint $table) {
            $table->char('kode_disposisi', 16)->primary();
            $table->char('kode_target', 9);
            $table->bigInteger('id_pengirim');
            $table->bigInteger('id_penerima');
            $table->string('catatan');
            $table->char('status', 1);
            $table->timestamps();
            $table->foreign('kode_target')->references('kode_target')->on('marketing_komisi_target')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_komisi_target_disposisi');
    }
};
