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
        Schema::table('cabang', function (Blueprint $table) {
            $table->foreign('kode_regional')->references('kode_regional')
                ->on('regional')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->index('kode_regional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropForeign('kode_regional');
            $table->dropIndex('kode_regional');
        });
    }
};
