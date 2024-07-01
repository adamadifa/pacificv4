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
        Schema::create('coa_departemen', function (Blueprint $table) {
            $table->id();
            $table->char('kode_dept', 3);
            $table->char('kode_akun', 6);
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa_departemen');
    }
};
