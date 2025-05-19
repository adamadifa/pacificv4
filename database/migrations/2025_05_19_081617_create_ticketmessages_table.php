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
        Schema::create('tickets_messages', function (Blueprint $table) {
            $table->id();
            $table->char('kode_pengajuan', 10);
            $table->bigInteger('id_user');
            $table->text('message');
            $table->foreign('kode_pengajuan')->references('kode_pengajuan')->on('tickets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_messages');
    }
};
