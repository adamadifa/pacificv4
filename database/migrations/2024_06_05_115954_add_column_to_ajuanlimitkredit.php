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
        Schema::table('marketing_ajuan_limitkredit', function (Blueprint $table) {
            $table->char('status_outlet', 2)->after('jml_faktur');
            $table->char('type_outlet', 2)->after('status_outlet');
            $table->char('cara_pembayaran', 2)->after('type_outlet');
            $table->char('kepemilikan', 2)->after('cara_pembayaran');
            $table->char('lama_berjualan', 4)->after('kepemilikan');
            $table->char('lama_langganan', 4)->after('lama_berjualan');
            $table->char('jaminan', 1)->after('lama_langganan');
            $table->bigInteger('omset_toko')->after('jaminan');
            $table->char('kode_salesman', 7);
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_ajuan_limitkredit', function (Blueprint $table) {
            //
        });
    }
};
