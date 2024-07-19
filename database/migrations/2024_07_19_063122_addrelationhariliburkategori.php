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
        Schema::table('hrd_harilibur', function (Blueprint $table) {
            $table->foreign('kategori')->references('kode_kategori')->on('hrd_harilibur_kategori')->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_harilibur', function (Blueprint $table) {
            //
        });
    }
};
