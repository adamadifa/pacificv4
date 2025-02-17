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
            $table->integer('qty_tunai')->after('jumlah');
            $table->integer('qty_kredit')->after('qty_tunai');
            $table->integer('reward_tunai')->after('qty_kredit');
            $table->integer('reward_kredit')->after('reward_tunai');
            $table->integer('total_reward')->after('reward_kredit');
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
