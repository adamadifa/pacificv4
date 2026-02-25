<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('coa', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
            $table->char('sub_akun', 8)->nullable()->change();
        });

        Schema::table('coa_cabang', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('coa_departemen', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('keuangan_ledger', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('pembelian_jurnalkoreksi', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('coa_kas_kecil', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('accounting_costratio', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->char('kode_akun', 8)->nullable()->change();
        });

        Schema::table('keuangan_kaskecil', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
            $table->char('kode_akun_potongan', 8)->change();
            $table->char('kode_akun_penyesuaian', 8)->change();
        });

        Schema::table('marketing_retur', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
            $table->char('kode_akun_piutang_dagang', 8)->change();
        });

        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->char('kode_akun', 8)->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('coa', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
            $table->char('sub_akun', 6)->nullable()->change();
        });

        Schema::table('coa_cabang', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('coa_departemen', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('keuangan_ledger', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('pembelian_jurnalkoreksi', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('coa_kas_kecil', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('accounting_costratio', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->char('kode_akun', 6)->nullable()->change();
        });

        Schema::table('keuangan_kaskecil', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
            $table->char('kode_akun_potongan', 6)->change();
            $table->char('kode_akun_penyesuaian', 6)->change();
        });

        Schema::table('marketing_retur', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
            $table->char('kode_akun_piutang_dagang', 6)->change();
        });

        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->char('kode_akun', 6)->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
