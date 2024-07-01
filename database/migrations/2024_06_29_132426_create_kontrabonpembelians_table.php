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
        Schema::create('pembelian_kontrabon', function (Blueprint $table) {
            $table->char('no_kontrabon', 13)->primary();
            $table->string('no_dokumen', 30);
            $table->char('kode_supplier', 6);
            $table->date('tanggal');
            $table->char('kategori', 2);
            $table->char('jenis_bayar', 2);
            $table->bigInteger('id_user');
            $table->char('status')->default(0);
            $table->foreign('kode_supplier')->references('kode_supplier')->on('supplier')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_kontrabon');
    }
};
