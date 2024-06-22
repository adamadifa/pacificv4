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
        Schema::table('keuangan_pjp_rencanacicilan', function (Blueprint $table) {
            $table->foreign('no_pinjaman')->references('no_pinjaman')->on('keuangan_pjp')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_pjp_rencanacicilan', function (Blueprint $table) {
            //
        });
    }
};
