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
        Schema::table('marketing_pencairan_ikatan_detail_2026', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('jumlah', 'realisasi');
            $table->renameColumn('total_reward', 'reward');
            
            // Drop unused columns
            $table->dropColumn(['qty_tunai', 'qty_kredit', 'reward_tunai', 'reward_kredit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan_detail_2026', function (Blueprint $table) {
             // Rename back
             $table->renameColumn('realisasi', 'jumlah');
             $table->renameColumn('reward', 'total_reward');
             
             // Add dropped columns back
            $table->integer('qty_tunai')->default(0);
            $table->integer('qty_kredit')->default(0);
            $table->double('reward_tunai', 18, 2)->default(0);
            $table->double('reward_kredit', 18, 2)->default(0);
        });
    }
};
