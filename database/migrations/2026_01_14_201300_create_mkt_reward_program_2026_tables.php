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
        Schema::create('mkt_reward_program_2026', function (Blueprint $table) {
            $table->id();
            $table->char('kode_program', 7);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('kode_program')->references('kode_program')->on('program_ikatan')->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('mkt_reward_program_detail_2026', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_id')->constrained('mkt_reward_program_2026')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('qty_dari');
            $table->integer('qty_sampai');
            $table->integer('reward_minus')->default(0);
            $table->integer('reward_tidak_minus')->default(0);
            $table->integer('reward_ach_target')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mkt_reward_program_detail_2026');
        Schema::dropIfExists('mkt_reward_program_2026');
    }
};
