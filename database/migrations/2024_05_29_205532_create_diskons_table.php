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
        Schema::create('produk_diskon', function (Blueprint $table) {
            $table->id();
            $table->char('kode_kategori_diskon', 4);
            $table->smallInteger('min_qty');
            $table->smallInteger('max_qty');
            $table->integer('diskon');
            $table->integer('diskon_tunai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_diskon');
    }
};
