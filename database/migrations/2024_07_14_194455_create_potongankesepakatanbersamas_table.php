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
        Schema::create('hrd_kesepakatanbersama_potongan', function (Blueprint $table) {
            $table->char('no_kb', 9);
            $table->string('keterangan');
            $table->integer('jumlah');
            $table->foreign('no_kb')->references('no_kb')->on('hrd_kesepakatanbersama')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_kesepakatanbersama_potongan');
    }
};
