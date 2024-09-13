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
        Schema::create('worksheetom_validasiretur_detail', function (Blueprint $table) {
            $table->char('no_retur', 13);
            $table->char('kode_item', 3);
            $table->foreign('no_retur')->references('no_retur')->on('marketing_retur')->restrictOnDelete()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheetom_validasiretur_detail');
    }
};
