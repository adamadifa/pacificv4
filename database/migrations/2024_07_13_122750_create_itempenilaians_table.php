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
        Schema::create('hrd_penilaian_item', function (Blueprint $table) {
            $table->char('kode_item', 3)->primary();
            $table->string('item_penilaian');
            $table->char('kode_kategori', 3);
            $table->char('jenis_kompetensi', 3);
            $table->char('kode_doc', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penilaian_item');
    }
};
