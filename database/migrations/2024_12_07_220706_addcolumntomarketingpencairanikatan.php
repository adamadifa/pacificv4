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
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            $table->char('budget', 3)->after('no_pengajuan');
            $table->char('metode_pembayaran', 2)->after('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            $table->dropColumn('budget');
            $table->dropColumn('metode_pembayaran');
        });
    }
};
