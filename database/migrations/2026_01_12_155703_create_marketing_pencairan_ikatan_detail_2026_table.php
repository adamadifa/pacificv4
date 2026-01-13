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
        Schema::create('marketing_pencairan_ikatan_detail_2026', function (Blueprint $table) {
            $table->char('kode_pencairan', 11);
            $table->char('kode_pelanggan', 13);
            $table->double('jumlah', 18, 2);
            $table->integer('qty_tunai')->default(0);
            $table->integer('qty_kredit')->default(0);
            $table->double('reward_tunai', 18, 2)->default(0);
            $table->double('reward_kredit', 18, 2)->default(0);
            $table->double('total_reward', 18, 2)->default(0);
            $table->char('status_pencairan', 1)->default('0');
            $table->timestamps();
            
            $table->foreign('kode_pencairan')->references('kode_pencairan')->on('marketing_pencairan_ikatan_2026')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pencairan_ikatan_detail_2026');
    }
};
