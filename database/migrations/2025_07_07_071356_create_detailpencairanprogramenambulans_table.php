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
        Schema::create('marketing_pencairan_ikatan_enambulan_detail', function (Blueprint $table) {
            $table->string('kode_pencairan', 11);
            $table->string('kode_pelanggan', 13);
            $table->integer('jumlah');
            $table->integer('qty_tunai');
            $table->integer('qty_kredit');
            $table->integer('reward_tunai');
            $table->integer('reward_kredit');
            $table->integer('total_reward');
            $table->string('bukti_transfer', 255)->nullable();
            $table->smallInteger('status_pencairan')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pencairan_ikatan_enambulan_detail');
    }
};
