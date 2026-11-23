@extends('layouts.app')
@section('titlepage', 'Input Penjualan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('penjualan.index') }}" class="text-slate-400 hover:text-brand-600 transition">Penjualan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Penjualan</span>
    </div>
@endsection

@section('content')
<form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan" class="space-y-4" autocomplete="off">
    @csrf
    <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
    <input type="hidden" name="max_kredit" id="max_kredit">
    <input type="hidden" name="status_pajak_faktur" id="status_pajak_faktur_input" value="0">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Transaksi Penjualan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Entri data faktur, pemilihan pelanggan, item barang, diskon bertingkat & pembayaran.</p>
        </div>

        <a href="{{ route('penjualan.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- MAIN TWO COLUMN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        
        <!-- LEFT COLUMN: FAKTUR & PELANGGAN (Col-span 3) -->
        <div class="lg:col-span-3 space-y-4">
            
            <!-- Card 1: Data Faktur -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Informasi Dokumen Faktur</span>
                </div>

                <!-- No Faktur -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="no_faktur" id="no_faktur" placeholder="No. Faktur"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" placeholder="Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                    </div>
                </div>

                <!-- Pelanggan -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" placeholder="Pelanggan" readonly
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                    <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                    <input type="hidden" id="kode_cabang_pelanggan" name="kode_cabang_pelanggan">
                </div>

                <!-- Salesman -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="nama_salesman" id="nama_salesman" placeholder="Salesman" readonly
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-not-allowed">
                    </div>
                    <input type="hidden" name="kode_salesman" id="kode_salesman">
                </div>

                <!-- Keterangan -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                            <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
                        </div>
                        <textarea name="keterangan" id="keterangan" rows="3" placeholder="Keterangan"
                            class="w-full pl-8 pr-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"></textarea>
                    </div>
                </div>

                <!-- Status Sampel -->
                <div class="pt-0.5 flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="status_sampel" id="status_sampel" value="1"
                            class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500/20 cursor-pointer">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Status Sampel</span>
                    </label>
                </div>

                <!-- Alert Siklus Pembayaran COD -->
                <div class="rounded-lg border border-blue-200 dark:border-blue-900/60 bg-blue-50/80 dark:bg-blue-950/40 p-2.5 hidden" id="alert_siklus_pembayaran">
                    <div class="flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5"></i>
                        <div class="text-[11px] text-blue-900 dark:text-blue-300">
                            <strong class="font-bold block">Siklus Bayar COD:</strong>
                            <span>Transaksi ini wajib dilunasi saat serah terima barang.</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Card 2: Profil & Status Pelanggan -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="user-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Profil &amp; Limit Pelanggan</span>
                </div>

                <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center relative">
                    <img src="" alt="Foto Pelanggan" id="foto" class="w-full h-full object-cover hidden" onerror="$(this).addClass('hidden'); $('#foto_placeholder').removeClass('hidden');">
                    <div id="foto_placeholder" class="text-slate-400 flex flex-col items-center justify-center gap-1">
                        <i data-lucide="image" class="w-6 h-6"></i>
                        <span class="text-[10px] font-semibold">Foto Pelanggan</span>
                    </div>
                </div>

                <p id="alamat_pelanggan" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed min-h-[1.5rem] italic">
                    Pilih pelanggan untuk melihat rincian alamat dan limit kredit...
                </p>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">No. HP</span>
                        <span id="no_hp_pelanggan" class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">-</span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Titik Koordinat</span>
                        <span class="font-mono-num text-[11px] text-slate-600 dark:text-slate-400">
                            <span id="latitude">-</span>, <span id="longitude">-</span>
                        </span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Limit Piutang</span>
                        <span id="limit_pelanggan_text" class="font-mono-num font-bold text-emerald-600 dark:text-emerald-400">-</span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Sisa Piutang</span>
                        <span id="sisa_piutang_text" class="font-mono-num font-bold text-rose-600 dark:text-rose-400">-</span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Faktur Kredit Belum Lunas</span>
                        <span id="jmlfaktur_kredit" class="font-mono-num font-bold text-amber-600 dark:text-amber-400">-</span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Siklus Pembayaran</span>
                        <span id="siklus_pembayaran_text" class="font-semibold text-slate-700 dark:text-slate-300">-</span>
                    </div>
                    <div class="py-1.5 flex items-center justify-between">
                        <span class="text-slate-400">Saldo Voucher Tersedia</span>
                        <span id="saldo_voucher_text" class="font-mono-num font-bold text-blue-600 dark:text-blue-400">-</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- RIGHT COLUMN: PRODUK & PEMBAYARAN (Col-span 9) -->
        <div class="lg:col-span-9 space-y-4">
            
            <!-- GRAND TOTAL DISPLAY STRIP (Pacific Navy) -->
            <div class="rounded-xl bg-[#284c9a] text-white p-4 sm:p-5 shadow-2xs overflow-hidden relative">
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[10.5px] uppercase tracking-wider font-bold text-white/70 block">Grand Total Transaksi</span>
                            <span class="text-xs text-white/80">Total tagihan netto yang harus dibayar</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl sm:text-4xl md:text-5xl font-black font-mono-num tracking-tight" id="grandtotal_text">0</span>
                    </div>
                </div>
            </div>

            <!-- CARD INPUT RINCIAN PRODUK -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="package-plus" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Tambah Rincian Produk</span>
                </div>

                <!-- Input Produk Selector -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="nama_produk" id="nama_produk" placeholder="Pilih Produk (Klik di sini)..." readonly
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                    <input type="hidden" id="kode_harga" name="kode_harga">
                    <input type="hidden" id="kode_produk" name="kode_produk">
                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                    <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                </div>

                <!-- Grid Jumlah & Harga Produk -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    
                    <!-- DUS -->
                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="box" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="jml_dus" id="jml_dus" placeholder="0"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                            <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                                DUS
                            </span>
                        </div>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="harga_dus" id="harga_dus" placeholder="Harga / Dus"
                                class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            <input type="hidden" id="harga_dus_produk">
                        </div>
                    </div>

                    <!-- PACK -->
                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="box" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="jml_pack" id="jml_pack" placeholder="0"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                            <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                                PACK
                            </span>
                        </div>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="harga_pack" id="harga_pack" placeholder="Harga / Pack"
                                class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            <input type="hidden" id="harga_pack_produk">
                        </div>
                    </div>

                    <!-- PCS -->
                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="box" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="jml_pcs" id="jml_pcs" placeholder="0"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                            <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                                PCS
                            </span>
                        </div>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="harga_pcs" id="harga_pcs" placeholder="Harga / Pcs"
                                class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            <input type="hidden" id="harga_pcs_produk">
                        </div>
                    </div>

                </div>

                <!-- Checkbox Promosi & Tombol Tambah Produk -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="status_promosi" id="status_promosi" value="1"
                            class="status_promosi w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500/20 cursor-pointer">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Promosi</span>
                    </label>

                    <button type="button" id="tambahproduk"
                        class="w-full sm:w-auto h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Produk</span>
                    </button>
                </div>

                <!-- TABEL KERANJANG PRODUK (~36px) -->
                <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden mt-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabelproduk">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="10" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="shopping-cart" class="w-4 h-4 text-blue-200"></i>
                                            <span class="text-xs font-bold text-white">Daftar Produk Masuk Faktur</span>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px] border-none text-center">
                                    <th rowspan="2" class="py-2.5 px-3 text-left">KODE</th>
                                    <th rowspan="2" class="py-2.5 px-3 text-left">NAMA BARANG</th>
                                    <th colspan="6" class="py-1.5 px-3 border-b border-blue-900/60 font-bold uppercase tracking-wider text-white">KUANTITAS &amp; HARGA SATUAN</th>
                                    <th rowspan="2" class="py-2.5 px-3 text-right">SUBTOTAL</th>
                                    <th rowspan="2" class="py-2.5 px-3 text-center w-16">AKSI</th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[10px] border-none">
                                    <th class="py-1.5 px-2 text-center font-bold text-white/90">DUS</th>
                                    <th class="py-1.5 px-2 text-right font-bold text-white/90">HARGA</th>
                                    <th class="py-1.5 px-2 text-center font-bold text-white/90">PACK</th>
                                    <th class="py-1.5 px-2 text-right font-bold text-white/90">HARGA</th>
                                    <th class="py-1.5 px-2 text-center font-bold text-white/90">PCS</th>
                                    <th class="py-1.5 px-2 text-right font-bold text-white/90">HARGA</th>
                                </tr>
                            </thead>
                            <tbody id="loadproduk" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                                <!-- Populated dynamically by jQuery cart engine -->
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-semibold text-xs border-t border-slate-200 dark:border-slate-700">
                                <tr>
                                    <td colspan="8" class="py-2 px-3 text-right uppercase font-bold text-slate-700 dark:text-slate-300">SUBTOTAL TRANSAKSI</td>
                                    <td class="py-2 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100" id="subtotal">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <!-- GRID 4 KOLOM: DISKON, POTIS, PENYESUAIAN & PEMBAYARAN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
                
                <!-- Kolom 1: Potongan Diskon -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    <div class="flex items-center gap-1.5 pb-1.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <i data-lucide="percent" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                        <span>Potongan Diskon</span>
                    </div>

                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">AIDA</span>
                            <input type="text" name="potongan_aida" id="potongan_aida" readonly placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">SWAN</span>
                            <input type="text" name="potongan_swan" id="potongan_swan" readonly placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">STICK</span>
                            <input type="text" name="potongan_stick" id="potongan_stick" readonly placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">SAMBAL</span>
                            <input type="text" name="potongan_sambal" id="potongan_sambal" readonly placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Potongan Istimewa -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    <div class="flex items-center gap-1.5 pb-1.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                        <span>Potongan Khusus</span>
                    </div>

                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">AIDA</span>
                            <input type="text" name="potis_aida" id="potis_aida" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">SWAN</span>
                            <input type="text" name="potis_swan" id="potis_swan" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">STICK</span>
                            <input type="text" name="potis_stick" id="potis_stick" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Penyesuaian -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    <div class="flex items-center gap-1.5 pb-1.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                        <span>Penyesuaian</span>
                    </div>

                    <div class="space-y-1.5">
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">AIDA</span>
                            <input type="text" name="peny_aida" id="peny_aida" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">SWAN</span>
                            <input type="text" name="peny_swan" id="peny_swan" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                        <div class="relative flex rounded-lg shadow-2xs">
                            <span class="inline-flex items-center w-16 px-2 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[10.5px]">STICK</span>
                            <input type="text" name="peny_stick" id="peny_stick" placeholder="0"
                                class="money w-full h-[32px] px-2 text-right font-bold font-mono-num text-xs rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        </div>
                    </div>
                </div>

                <!-- Kolom 4: Pembayaran & Simpan -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    <div class="flex items-center gap-1.5 pb-1.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                        <span>Pembayaran</span>
                    </div>

                    <div class="space-y-1.5">
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jenis_transaksi" id="jenis_transaksi"
                                class="w-full h-[34px] pl-8 pr-3 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                                <option value="">Jenis Transaksi</option>
                                <option value="T">TUNAI</option>
                                <option value="K">KREDIT</option>
                            </select>
                        </div>

                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="grandtotal" id="grandtotal" readonly placeholder="Grand Total"
                                class="money w-full h-[34px] pl-8 pr-2 text-right font-black font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400">
                        </div>

                        <div id="jenis_bayar_tunai">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="badge-dollar-sign" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="jenis_bayar" id="jenis_bayar"
                                    class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                                    <option value="">Jenis Bayar</option>
                                    <option value="TN">CASH</option>
                                    <option value="TR">TRANSFER</option>
                                </select>
                            </div>
                        </div>

                        <div id="titipan">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="circle-dollar-sign" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="titipan" id="titipan" placeholder="Titipan"
                                    class="money w-full h-[34px] pl-8 pr-2 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <div id="voucher_tunai">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="voucher" id="voucher" placeholder="Voucher"
                                    class="money w-full h-[34px] pl-8 pr-2 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <button type="submit" id="btnSimpan"
                            class="w-full h-[36px] mt-2 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>Submit</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</form>

<!-- MODALS -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="Pilih Produk" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="Edit Rincian Produk" />

<!-- MODAL PELANGGAN DATATABLE -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pilih Data Pelanggan</h4>
                </div>
                <button type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabelpelanggan">
                    <thead>
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white">NO.</th>
                            <th class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white">KODE</th>
                            <th class="py-2.5 px-3 min-w-[200px] font-bold uppercase tracking-wider text-white">NAMA PELANGGAN</th>
                            <th class="py-2.5 px-3 min-w-[150px] font-bold uppercase tracking-wider text-white">SALESMAN</th>
                            <th class="py-2.5 px-3 min-w-[140px] font-bold uppercase tracking-wider text-white">WILAYAH</th>
                            <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white">STATUS</th>
                            <th class="py-2.5 px-3 text-center w-14 font-bold uppercase tracking-wider text-white">#</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script type="text/javascript">
    $(document).ready(function() {

        const kode_cabang_user = '{{ Auth::user()->kode_cabang }}';
        let kode_cabang_pelanggan = '';
        let kode_pel = '';
        // alert(kode_cabang_user);
        let jmlfakturbelumlunas = 0;
        let jmlfakturmax = 0;
        let saldo_voucher = 0;

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: '{{ url()->current() }}',
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center font-medium text-slate-500 dark:text-slate-400',
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: false,
                    searchable: false,
                    className: 'font-mono-num font-semibold text-slate-700 dark:text-slate-200',
                    width: '12%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: false,
                    searchable: true,
                    className: 'font-semibold text-slate-800 dark:text-slate-100',
                    width: '28%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: false,
                    searchable: true,
                    className: 'text-slate-600 dark:text-slate-300',
                    width: '20%'
                },
                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: false,
                    searchable: false,
                    className: 'text-slate-600 dark:text-slate-300',
                    width: '18%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '10%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '7%'
                }
            ],
            language: {
                search: "",
                searchPlaceholder: "Cari nama pelanggan / salesman...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ pelanggan",
                infoEmpty: "Menampilkan 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "«",
                    previous: "‹",
                    next: "›",
                    last: "»"
                }
            },
            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-rose-50 text-rose-600 font-semibold dark:bg-rose-950/40 dark:text-rose-400");
                }
            }
        });

        // Autofocus search filter input when modal Pelanggan is opened
        $('#modalPelanggan').on('shown.bs.modal', function() {
            const $searchInput = $(this).find('.dataTables_filter input');
            $searchInput.focus();
            setTimeout(function() {
                $searchInput.focus().select();
            }, 50);
        });

        $('#modalPelanggan').on('hidden.bs.modal', function() {
            $('#nama_pelanggan').blur();
        });

        $("#nama_pelanggan").on('click keydown', function(e) {
            if (e.type === 'click' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $("#modalPelanggan").modal("show");
            }
        });



        //Cek file Foto Pelanggan
        function checkFileExistence(fileFoto) {
            if (!fileFoto) {
                $("#foto").attr("src", "").addClass("hidden");
                $("#foto_placeholder").removeClass("hidden");
                return;
            }
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url('/storage/pelanggan') }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            $("#foto").attr("src", foto + '?t=' + new Date().getTime()).removeClass("hidden");
                            $("#foto_placeholder").addClass("hidden");
                        } else {
                            $("#foto").attr("src", fotoDefault).removeClass("hidden");
                            $("#foto_placeholder").addClass("hidden");
                        }
                    } else {
                        $("#foto").attr("src", fotoDefault).removeClass("hidden");
                        $("#foto_placeholder").addClass("hidden");
                    }
                }
            };
            xhr.send();
        }

        //GetPiutang

        function getPiutang(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    $("#sisa_piutang_text").text(convertToRupiah(response.data));
                    $("#sisa_piutang").val(response.data);
                    buttonEnable();
                }
            });
        }


        function getFakturkredit(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getFakturkredit`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    const unpaid_faktur = response.data.unpaid_faktur;
                    const max_faktur = response.data.jml_faktur;
                    const siklus_pembayaran = response.data.siklus_pembayaran;
                    jmlfakturbelumlunas = unpaid_faktur;
                    jmlfakturmax = max_faktur;

                    console.log(jmlfakturbelumlunas);
                    console.log(jmlfakturmax);

                    // if (unpaid_faktur >= max_faktur && siklus_pembayaran === '0') {
                    //     SwalWarning("nama_pelanggan", "Melebihi Maksimal Faktur Kredit");
                    //     $("#no_faktur").val("");
                    //     $("#tanggal").val("");
                    //     $("#nama_pelanggan").val("");
                    //     $("#kode_pelanggan").val("");
                    //     $("#kode_salesman").val("");
                    //     $("#nama_salesman").val("");
                    //     $('#latitude').text("");
                    //     $('#longitude').text("");
                    //     $('#no_hp_pelanggan').text("");
                    //     $('#limit_pelanggan_text').text("");
                    //     $('#limit_pelanggan').val("");
                    //     $('#alamat_pelanggan').text("");
                    //     $('#sisa_piutang_text').text("");
                    //     $("#jmlfaktur_kredit").text("");
                    //     $("#siklus_pembayaran_text").text("-");
                    //     let fileFoto = "notfound.jpg";
                    //     checkFileExistence(fileFoto);
                    //     //Data Salesman
                    // } else {
                    //     $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
                    //     $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    //     $("#max_kredit").val(response.data.jml_faktur);
                    // }

                    $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
                    $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    $("#max_kredit").val(response.data.jml_faktur);

                    if (response.data.siklus_pembayaran === '1') {
                        $("#siklus_pembayaran_text").html('<span class="badge bg-warning text-dark">COD</span> <span class="d-block text-muted" style="font-size: 10px; font-weight: 500;">(Lunas saat barang turun)</span>');
                        $("#alert_siklus_pembayaran").show();
                    } else if (response.data.siklus_pembayaran === '0') {
                        $("#siklus_pembayaran_text").html('<span class="badge bg-primary">Reguler (Mingguan)</span>');
                        $("#alert_siklus_pembayaran").hide();
                    } else {
                        $("#siklus_pembayaran_text").html('<span class="badge bg-secondary">Biasa</span>');
                        $("#alert_siklus_pembayaran").hide();
                    }

                    buttonEnable();
                }
            });
        }


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="inline-block animate-spin h-3.5 w-3.5 border-2 border-white border-t-transparent rounded-full me-2" role="status"></div>
            Loading...
         `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`<i data-lucide="send" class="w-3.5 h-3.5"></i><span>Submit</span>`);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        //Get Pelanggan
        function getPelanggan(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        kode_pel = response.data.kode_pelanggan;
                        $('#kode_cabang_pelanggan').val(response.data.kode_cabang);
                        kode_cabang_pelanggan = response.data.kode_cabang;
                        //alert(kode_cabang_pelanggan);
                        $('#nama_pelanggan').val(response.data.nama_pelanggan);
                        $('#latitude').text(response.data.latitude);
                        $('#longitude').text(response.data.longitude);
                        $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan);
                        $('#limit_pelanggan_text').text(convertToRupiah(response.data
                            .limit_pelanggan));
                        $('#limit_pelanggan').val(response.data.limit_pelanggan);
                        $('#alamat_pelanggan').text(response.data.alamat_pelanggan);
                        let fileFoto = response.data.foto;
                        checkFileExistence(fileFoto);
                        //Data Salesman
                        $('#kode_salesman').val(response.data.kode_salesman);
                        $('#nama_salesman').val(response.data.nama_salesman);
                        $("#saldo_voucher_text").text(response.saldo_voucher);
                        saldo_voucher = response.saldo_voucher
                        //Get Piutang
                        getPiutang(kode_pelanggan);
                        //Get FaktuR Kredit
                        getFakturkredit(kode_pelanggan);
                        generatenofaktur();
                        //open modal
                        $('#modalPelanggan').modal('hide');
                        buttonEnable();
                    }

                }
            });
        }
        //Pilih Pelanggan
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
            $("#loadproduk").html('');
            $("#potongan_swan").val(0);
            $("#potongan_aida").val(0);
            $("#potongan_sp").val(0);
            $("#potongan_stick").val(0);
            $("#potongan_sambal").val(0);
            loadsubtotal();

        });


        //GetProduk
        function getHarga(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargabypelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    buttonEnable();
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelharga')) {
                        $('#tabelharga').DataTable().destroy();
                    }
                    $("#loadmodal").html(response);
                }
            });
        }
        //Pilih Produk
        $("#nama_produk").on('click', function(e) {
            e.preventDefault();
            let kode_pelanggan = $("#kode_pelanggan").val();
            if (kode_pelanggan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Pelanggan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_pelanggan").focus();
                    },
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Data Produk');
                getHarga(kode_pelanggan);
            }
        });

        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_harga = $(this).attr('kode_harga');
            let kode_produk = $(this).attr('kode_produk');
            let nama_pelanggan = $("#nama_pelanggan").val();
            let nama_produk = $(this).attr('nama_produk');
            let harga_dus = $(this).attr('harga_dus');
            let harga_pack = $(this).attr('harga_pack');
            let harga_pcs = $(this).attr('harga_pcs');

            let harga_dus_produk = $(this).attr('harga_dus');
            let harga_pack_produk = $(this).attr('harga_pack');
            let harga_pcs_produk = $(this).attr('harga_pcs');

            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');

            let kode_kategori_diskon = $(this).attr('kode_kategori_diskon');
            if ($('#status_promosi').is(":checked")) {
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            }
            $("#kode_harga").val(kode_harga);
            $("#kode_produk").val(kode_produk);
            $("#nama_produk").val(nama_produk);
            $("#harga_dus").val(harga_dus);
            $("#harga_pack").val(harga_pack);
            $("#harga_pcs").val(harga_pcs);

            $("#harga_dus_produk").val(harga_dus_produk);
            $("#harga_pack_produk").val(harga_pack_produk);
            $("#harga_pcs_produk").val(harga_pcs_produk);


            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);

            $("#kode_kategori_diskon").val(kode_kategori_diskon);


            //Disabled Harga
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#jml_pack").prop('disabled', false);
            }
            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB') || kode_cabang_user ==
                'PST') {
                $("#harga_dus").prop('disabled', false);
                if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                    $("#harga_pack").prop('disabled', true);
                } else {
                    $("#harga_pack").prop('disabled', false);
                }
                $("#harga_pcs").prop('disabled', false);
            } else {
                $("#harga_dus").prop('disabled', true);
                $("#harga_pack").prop('disabled', true);
                $("#harga_pcs").prop('disabled', true);
            }

            $("#modal").modal("hide");
        });


        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            let jml_dus = Math.floor(jumlah / isi_pcs_dus);
            let sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '') {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                jml_pack = 0;
                sisa_pack = sisa_dus;
            }
            let jml_pcs = sisa_pack;


            let data = {
                "dus": jml_dus,
                "pack": jml_pack,
                "pcs": jml_pcs
            };

            return data;
        }


        function addProduk() {
            var kode_harga = $("#kode_harga").val();
            var kode_produk = $("#kode_produk").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val();
            var jml_pack = $("#jml_pack").val();
            var jml_pcs = $("#jml_pcs").val();
            var harga_dus = $("#harga_dus").val();
            var harga_pack = $("#harga_pack").val();
            var harga_pcs = $("#harga_pcs").val();
            var isi_pcs_dus = $("#isi_pcs_dus").val();
            var isi_pcs_pack = $("#isi_pcs_pack").val();
            var kode_kategori_diskon = $("#kode_kategori_diskon").val();


            if ($('#status_promosi').is(":checked")) {
                var status_promosi = $("#status_promosi").val();
            } else {
                var status_promosi = 0;
            }

            var jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            var jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            var jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            var hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            var hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            var hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            var jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_harga + status_promosi;

            let bgcolor = "";
            let promosiBadge = "";
            if (status_promosi == '1') {
                bgcolor = "row-promosi bg-warning";
                promosiBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-white/25 text-white ml-1.5">PROMOSI</span>`;
                var hargadus = 0;
                var hargapack = 0;
                var hargapcs = 0;
                var harga_dus = 0;
                var harga_pack = 0;
                var harga_pcs = 0;
            } else {
                bgcolor = "border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/60 dark:hover:bg-slate-800/40";
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));


            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if ($('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },

                });
            } else {
                let produk = `
                    <tr id="index_${index}" class="${bgcolor} transition">
                        <td class="py-1.5 px-3 text-left font-mono-num font-semibold text-slate-700 dark:text-slate-200">
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" class="isi_pcs_dus" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" class="isi_pcs_pack" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td class="py-1.5 px-3 text-left font-semibold text-slate-800 dark:text-slate-100">
                            <span class="nama-produk-text">${nama_produk}</span>${promosiBadge}
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${pack===0 ? '' :pack}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${pcs===0 ? '' :pcs}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="text-center py-1.5 px-3">
                           <div class="flex items-center justify-center gap-1">
                              <a href="#" key="${index}" class="edit inline-flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition shadow-2xs" title="Edit">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                              </a>
                              <a href="#" key="${index}" class="delete inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition shadow-2xs" title="Hapus">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                              </a>
                           </div>
                        </td>
                    </tr>
                `;

                //append to table
                $('#loadproduk').append(produk);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                $("#kode_harga").val("");
                $("#nama_produk").val("");
                $("#jml_dus").val("");
                $("#jml_pack").val("");
                $("#jml_pcs").val("");
                $("#harga_dus").val("");
                $("#harga_pack").val("");
                $("#harga_pcs").val("");

                $("#harga_dus_produk").val("");
                $("#harga_pack_produk").val("");
                $("#harga_pcs_produk").val("");
                $("#status_promosi").prop('checked', false);

                loadsubtotal();


            }

        }

        $("#status_promosi").change(function() {
            let harga_dus = $("#harga_dus_produk").val();
            let harga_pack = $("#harga_pack_produk").val();
            let harga_pcs = $("#harga_pcs_produk").val();
            if (this.checked) {
                $("#harga_dus").val(0);
                $("#harga_pack").val(0);
                $("#harga_pcs").val(0);
            } else {
                $("#harga_dus").val(harga_dus);
                $("#harga_pack").val(harga_pack);
                $("#harga_pcs").val(harga_pcs);
            }
        });
        //Tambah Item Produk
        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            addProduk();
        });


        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let key = $(this).attr("key");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(`#index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });



        let currentRow;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_harga = currentRow.find('.kode_harga').val();
            let kode_produk = currentRow.find('.kode_produk').val();
            let nama_produk = currentRow.find('.nama-produk-text').length ? currentRow.find('.nama-produk-text').text().trim() : currentRow.find('td:eq(1)').text().trim();
            let jml_dus = currentRow.find('td:eq(2)').text();
            let harga_dus = currentRow.find('td:eq(3)').text();
            let jml_pack = currentRow.find('td:eq(4)').text();
            let harga_pack = currentRow.find('td:eq(5)').text();
            let jml_pcs = currentRow.find('td:eq(6)').text();
            let harga_pcs = currentRow.find('td:eq(7)').text();
            let subtotal = currentRow.find('td:eq(8)').text();
            let kode_pelanggan = $("#kode_pelanggan").val();
            let status_promosi = currentRow.find('.status_promosi').val();
            let isi_pcs_dus = currentRow.find('.isi_pcs_dus').val();
            let isi_pcs_pack = currentRow.find('.isi_pcs_pack').val();
            let kode_kategori_diskon = currentRow.find('.kode_kategori_diskon').val();
            let index_old = kode_harga + "" + status_promosi;
            console.log(kode_harga);
            console.log(status_promosi);
            console.log(index_old);
            //alert(status_promosi);
            let dataProduk = {
                'kode_pelanggan': kode_pelanggan,
                'kode_harga': kode_harga,
                'kode_produk': kode_produk,
                'nama_produk': nama_produk,
                'jml_dus': jml_dus,
                'harga_dus': harga_dus,
                'jml_pack': jml_pack,
                'harga_pack': harga_pack,
                'jml_pcs': jml_pcs,
                'harga_pcs': harga_pcs,
                'status_promosi': status_promosi,
                'index_old': index_old,
                'isi_pcs_dus': isi_pcs_dus,
                'isi_pcs_pack': isi_pcs_pack,
                'kode_kategori_diskon': kode_kategori_diskon
            };
            $.ajax({
                type: 'POST',
                url: '/penjualan/editproduk',
                data: {
                    _token: "{{ csrf_token() }}",
                    dataproduk: dataProduk
                },
                cache: false,
                success: function(respond) {
                    $("#modaleditProduk").modal("show");
                    $("#modaleditProduk").find(".modal-title").text("Edit Produk");
                    $("#loadmodaleditProduk").html(respond);
                }
            });
        });

        $(document).on('submit', '#formEditproduk', function(event) {
            event.preventDefault();
            let kode_harga = $(this).find("#kode_harga").val();
            let kode_produk = $(this).find("#kode_produk").val();
            let nama_produk = $(this).find("#nama_produk_label").val() || $(this).find("#nama_produk").val();
            let jml_dus = $(this).find("#jml_dus").val();
            let jml_pack = $(this).find("#jml_pack").val();
            let jml_pcs = $(this).find("#jml_pcs").val();
            let harga_dus = $(this).find("#harga_dus").val();
            let harga_pack = $(this).find("#harga_pack").val();
            let harga_pcs = $(this).find("#harga_pcs").val();
            let isi_pcs_dus = $(this).find("#isi_pcs_dus").val();
            let isi_pcs_pack = $(this).find("#isi_pcs_pack").val();
            let kode_kategori_diskon = $(this).find("#kode_kategori_diskon").val();
            let index_old = $(this).find("#index_old").val();
            let status_promosi;
            if ($(this).find('#status_promosi_edit').is(':checked')) {
                status_promosi = 1;
            } else {
                status_promosi = 0;
            }

            let jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            let jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            let jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            let hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            let hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            let hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            let jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) +
                jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_harga + status_promosi;
            console.log(index_old);
            let bgcolor = "";
            let promosiBadge = "";
            if (status_promosi == '1') {
                bgcolor = "row-promosi bg-warning";
                promosiBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-white/25 text-white ml-1.5">PROMOSI</span>`;
                hargadus = 0;
                hargapack = 0;
                hargapcs = 0;
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                bgcolor = "border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/60 dark:hover:bg-slate-800/40";
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(
                hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));

            let newRow = `
                    <tr id="index_${index}" class="${bgcolor} transition">
                        <td class="py-1.5 px-3 text-left font-mono-num font-semibold text-slate-700 dark:text-slate-200">
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="${status_promosi}" class="status_promosi"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" class="isi_pcs_dus" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" class="isi_pcs_pack" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td class="py-1.5 px-3 text-left font-semibold text-slate-800 dark:text-slate-100">
                            <span class="nama-produk-text">${nama_produk}</span>${promosiBadge}
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${pack===0 ? '' :pack}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="py-1.5 px-2 text-center font-mono-num text-slate-700 dark:text-slate-300">
                           ${pcs===0 ? '' :pcs}
                        </td>
                        <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="text-center py-1.5 px-3">
                           <div class="flex items-center justify-center gap-1">
                              <a href="#" key="${index}" class="edit inline-flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition shadow-2xs" title="Edit">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                              </a>
                              <a href="#" key="${index}" class="delete inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition shadow-2xs" title="Hapus">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                              </a>
                           </div>
                        </td>
                    </tr>
                `;
            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jml_dus").focus();
                    },
                });
            } else if (index != index_old && $('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else {
                currentRow.replaceWith(newRow);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                $("#modaleditProduk").modal("hide");
            }
            loadsubtotal();
        });


        function loadsubtotal() {
            let subtotal = 0;
            let valSubtotal = $("#tabelproduk").find(".subtotal");

            valSubtotal.each(function() {
                let val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });

            $("#subtotal").text(convertToRupiah(subtotal));
            hitungdiskonAida();
            hitungdiskonSwan();
            hitungdiskonStick();
            hitungdiskonSC();
            hitungdiskonSP();
            calculateGrandtotal();
        }



        // Function to calculate total quantity based on category
        function calculateTotalQuantityByCategory(category) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_kategori_diskon').val() === category) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('td:eq(2)').text());
                    }
                }
            });

            return totalQuantity;
        }

        function calculateTotalQuantityByProduct(kode_produk) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_produk').val() === kode_produk) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('td:eq(2)').text());
                    }
                }
            });
            console.log(kode_produk + ': ' + totalQuantity);
            // console.log(category + ': ' + totalQuantity);
            return totalQuantity || 0;
        }

        function calculateDiscount(totalQuantity, category) {
            let discount = 0;
            let discount_tunai = 0;
            let total_discount = 0;
            let nama_pelanggan = $("#nama_pelanggan").val();
            let jenis_transaksi = $("#jenis_transaksi").val();
            // Define discount rules based on quantity range and category
            const discountRules = <?php echo $diskon; ?>;

            // Find the applicable discount rule based on total quantity and category
            for (let i = 0; i < discountRules.length; i++) {
                if (totalQuantity >= discountRules[i].min_qty &&
                    totalQuantity <= discountRules[i].max_qty &&
                    category === discountRules[i].kode_kategori_diskon) {
                    if (jenis_transaksi === 'T') {
                        discount = totalQuantity * discountRules[i].diskon;
                        discount_tunai = totalQuantity * discountRules[i].diskon_tunai;
                        total_discount = discount + discount_tunai;
                    } else {
                        total_discount = totalQuantity * discountRules[i].diskon;
                    }

                    if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                        total_discount = 0;
                    }
                    break;
                }
            }

            return total_discount;
        }

        $("#jenis_transaksi").change(function() {
            loadsubtotal();
            showhidetunai();
            showhidekredit();
        });

        function handleStatusSampel() {
            if ($("#status_sampel").is(":checked")) {
                $("#jenis_transaksi").val("T").trigger("change");
                $("#jenis_bayar").val("TN").trigger("change");
                $("#jenis_transaksi").prop("disabled", true);
                $("#jenis_bayar").prop("disabled", true);
            } else {
                $("#jenis_transaksi").prop("disabled", false);
                $("#jenis_bayar").prop("disabled", false);
            }
        }

        $("#status_sampel").change(function() {
            handleStatusSampel();
        });

        handleStatusSampel();

        function hitungdiskonAida() {
            let totalQuantity = calculateTotalQuantityByCategory('D002');
            let diskon = calculateDiscount(totalQuantity, 'D002');
            $("#potongan_aida").val(convertToRupiah(diskon));
            return diskon;
        }

        function hitungdiskonProductBP500() {
            let totalQuantity = calculateTotalQuantityByProduct('BP500');
            let diskon = totalQuantity * 2000;
            return diskon;

        }


        function hitungdiskonSPPP500() {
            let totalQuantity = calculateTotalQuantityByCategory('D008');
            let diskon = calculateDiscount(totalQuantity, 'D008');
            return diskon;

        }

        function hitungdiskonSPPP1000() {
            let blacklist_pelanggan = [
                'BGR-06675',
                'BGR-06827',
                'BGR-06854',
                'BKI-00068',
                'BKI-00122',
                'BKI-00633',
                'BKI-00841',
                'BKI-00869',
                'BKI-00959',
                'BKI-00982',
                'BKI-01041',
                'BKI-01108',
                'BKI-01163',
                'BKI-01164',
                'BKI-01196',
                'BKI-01198',
                'BKI-01199',
                'BKI-01222',
                'BKI-01223',
                'BKI-01234',
                'BKI-01277',
                'BKI-01282',
                'BKI-01283',
                'BKI-01292',
                'BKI-01299',
                'BKI-01383',
                'BKI-01387',
                'BGR-06648',
                'BKI-00052',
                'BKI-00100',
                'BKI-00110',
                'BKI-00113',
                'BKI-00116',
                'BKI-00140',
                'BKI-00268',
                'BKI-00293',
                'BKI-00295',
                'BKI-00315',
                'BKI-00341',
                'BKI-00350',
                'BKI-00351',
                'BKI-00353',
                'BKI-00370',
                'BKI-00381',
                'BKI-00384',
                'BKI-00450',
                'BKI-00552',
                'BKI-00604',
                'BKI-00615',
                'BKI-00671',
                'BKI-00800',
                'BKI-00813',
                'BKI-00821',
                'BKI-00831',
                'BKI-00858',
                'BKI-00871',
                'BKI-00872',
                'BKI-00879',
                'BKI-00884',
                'BKI-00985',
                'BKI-01006',
                'BKI-01026',
                'BKI-01052',
                'BKI-01081',
                'BKI-01111',
                'BKI-01112',
                'BKI-01119',
                'BKI-01151',
                'BKI-01171',
                'BKI-01172',
                'BKI-01202',
                'BKI-01203',
                'BKI-01278',
                'BKI-01287',
                'BKI-01291',
                'BKI-01295',
                'BKI-01296',
                'BKI-01297',
                'BKI-01375',
                'BKI-01376',
                'BKI-01377',
                'BKI-01386',
                'BKI-01388',
                'BKI-01390',
                'BKI-01392',
                'BKI-01394',
                'BKI-01400',
                'BGR-06669',
                'BGR-06759',
                'BKI-00007',
                'BKI-00008',
                'BKI-00029',
                'BKI-00121',
                'BKI-00258',
                'BKI-00367',
                'BKI-00518',
                'BKI-00585',
                'BKI-00686',
                'BKI-00735',
                'BKI-00773',
                'BKI-00777',
                'BKI-00902',
                'BKI-00911',
                'BKI-00912',
                'BKI-00917',
                'BKI-00936',
                'BKI-00973',
                'BKI-00979',
                'BKI-01023',
                'BKI-01040',
                'BKI-01049',
                'BKI-01187',
                'BKI-01193',
                'BKI-01194',
                'BKI-01201',
                'BKI-01236',
                'BKI-01238',
                'BKI-01240',
                'BKI-01242',
                'BKI-01261',
                'BKI-01266',
                'BKI-01285',
                'BKI-01294',
                'BGR-07636',
                'BRG-07591',
                'BRG-07480',
                'BRG-07465',
                'BRG-07650',
                'BRG-07982',
                'BRG-07984',
                'BRG-07751',
                'BRG-07638',
                'BRG-07597',
                'BRG-07643',
                'BRG-07966',
                'BRG-07809',
                'BRG-07693',
                'BRG-07784',
                'BKI-01419'
            ];
            let totalQuantity = calculateTotalQuantityByCategory('D009');
            let diskon = calculateDiscount(totalQuantity, 'D009');
            if (blacklist_pelanggan.includes(kode_pel)) {
                diskon = 0;
            }
            return diskon;

        }


        function hitungdskonSAOSME() {
            const kode_cabang_diskon_saosme = ['BTN', 'CRB','SKB','TSM','BKI','KLT','PWK','BDG'];
            let totalQuantity = calculateTotalQuantityByCategory('D010');
            let diskon = calculateDiscount(totalQuantity, 'D010');
            // Baris berikut memeriksa apakah kode_cabang_pelanggan termasuk dalam daftar kode_cabang_diskon_saosme.
            // Jika iya, maka nilai diskon tetap (tidak diubah). Sebenarnya, penugasan diskon = diskon; tidak melakukan perubahan apapun,
            // sehingga baris ini hanya sebagai placeholder atau untuk menandai bahwa diskon hanya berlaku untuk cabang tertentu.
            if (kode_cabang_diskon_saosme.includes(kode_cabang_pelanggan)) {
                diskon = diskon;
                //alert('YES');
            } else {
                diskon = 0;
                //alert('NO');
            }

            //alert(diskon);
            return diskon;

        }

        function hitungdiskonSwan() {
            let totalQuantity = calculateTotalQuantityByCategory('D001');
            let diskon = calculateDiscount(totalQuantity, 'D001');
            let diskonbp500 = hitungdiskonProductBP500();
            let diskonSPPP500 = hitungdiskonSPPP500();
            let diskonSPPP1000 = hitungdiskonSPPP1000();
            let diskonSAOSME = hitungdskonSAOSME();
            let totaldiskon = parseInt(diskon) + parseInt(diskonbp500) + parseInt(diskonSPPP500) + parseInt(
                diskonSPPP1000) + parseInt(
                diskonSAOSME);
            $("#potongan_swan").val(convertToRupiah(totaldiskon));
            return totaldiskon;
        }

        function hitungdiskonStick() {
            let blacklist_pelanggan = [];

            let totalQuantity = calculateTotalQuantityByCategory('D003');
            let diskon = calculateDiscount(totalQuantity, 'D003');

            if (blacklist_pelanggan.includes(kode_pel)) {
                diskon = 0;
            }

            $("#potongan_stick").val(convertToRupiah(diskon));
        }



        function hitungdiskonSP() {
            let totalQuantity = calculateTotalQuantityByCategory('D004');
            let diskon = calculateDiscount(totalQuantity, 'D004');
            $("#potongan_sp").val(convertToRupiah(diskon));
        }


        function hitungdiskonSC() {
            let totalQuantity = calculateTotalQuantityByCategory('D005');
            let diskon = calculateDiscount(totalQuantity, 'D005');
            $("#potongan_sambal").val(convertToRupiah(diskon));
        }


        function calculateGrandtotal() {
            const subtotalVal = $("#subtotal").text();
            const subtotal = subtotalVal != "" ? parseInt(subtotalVal.replace(/\./g, '')) : 0;
            const potonganSwanVal = $("#potongan_swan").val();
            const potongan_swan = potonganSwanVal != "" ? parseInt(potonganSwanVal.replace(/\./g, '')) : 0;

            const potonganAidaVal = $("#potongan_aida").val();
            const potongan_aida = potonganAidaVal != "" ? parseInt(potonganAidaVal.replace(/\./g, '')) : 0;

            const potonganStickVal = $("#potongan_stick").val();
            const potongan_stick = potonganStickVal != "" ? parseInt(potonganStickVal.replace(/\./g, '')) : 0;

            const potonganSambalVal = $("#potongan_sambal").val();
            const potongan_sambal = potonganSambalVal != "" ? parseInt(potonganSambalVal.replace(/\./g, '')) :
                0;

            const total_potongan = parseInt(potongan_swan) + parseInt(potongan_aida) + parseInt(
                potongan_stick) + parseInt(potongan_sambal);

            //Potongan Istimewa
            const potisAidaVal = $("#potis_aida").val();
            const potis_aida = potisAidaVal != "" ? parseInt(potisAidaVal.replace(/\./g, '')) : 0;

            const potisSwanVal = $("#potis_swan").val();
            const potis_swan = potisSwanVal != "" ? parseInt(potisSwanVal.replace(/\./g, '')) : 0;

            const potisStickVal = $("#potis_stick").val();
            const potis_stick = potisStickVal != "" ? parseInt(potisStickVal.replace(/\./g, '')) : 0;

            const total_potongan_istimewa = parseInt(potis_aida) + parseInt(potis_swan) + parseInt(potis_stick);

            //Penyesuaian
            const penyAidaVal = $("#peny_aida").val();
            const peny_aida = penyAidaVal != "" ? parseInt(penyAidaVal.replace(/\./g, '')) : 0;

            const penySwanVal = $("#peny_swan").val();
            const peny_swan = penySwanVal != "" ? parseInt(penySwanVal.replace(/\./g, '')) : 0;

            const penyStickVal = $("#peny_stick").val();
            const peny_stick = penyStickVal != "" ? parseInt(penyStickVal.replace(/\./g, '')) : 0;

            const total_penyesuaian = parseInt(peny_aida) + parseInt(peny_swan) + parseInt(peny_stick);



            const grandtotal = parseInt(subtotal) - parseInt(total_potongan) - parseInt(
                total_potongan_istimewa) - parseInt(total_penyesuaian);
            $("#grandtotal_text").text(convertToRupiah(grandtotal));
            $("#grandtotal").val(convertToRupiah(grandtotal));
            console.log(grandtotal);
        }

        $("#potongan_aida, #potongan_swan, #potongan_stick, #potongan_sambal, #potis_aida, #potis_swan, #potis_stick, #peny_aida, #peny_swan, #peny_stick ")
            .on('keyup keydown', function() {
                calculateGrandtotal();
            });

        function showhidetunai() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'T') {
                $("#jenis_bayar_tunai").show();
                $("#voucher_tunai").show();
            } else {
                $("#jenis_bayar_tunai").hide();
                $("#voucher_tunai").hide();
            }
        }

        function showhidekredit() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'K') {
                $("#titipan").show();
            } else {
                $("#titipan").hide();
            }
        }

        showhidetunai();
        showhidekredit();



        let isTaxConfirmed = false;
        $("#formPenjualan").submit(function(e) {
            // e.preventDefault();
            const no_faktur = $("#no_faktur").val();
            const tanggal = $("#tanggal").val();
            const kode_pelanggan = $("#kode_pelanggan").val();
            const kode_salesman = $("#kode_salesman").val();
            const sisa_piutang_raw = $("#sisa_piutang").val();
            const sisa_piutang = (sisa_piutang_raw !== "" && !isNaN(sisa_piutang_raw)) ? parseInt(sisa_piutang_raw) : 0;
            const gt = $("#grandtotal").val();
            const grandtotal = gt != "" ? parseInt(gt.replace(/\./g, '')) : 0;
            const totalPiutang = sisa_piutang + grandtotal;
            let limit_pelanggan = $("#limit_pelanggan").val() != "" ? parseInt($("#limit_pelanggan").val()) : 0;
            // alert(limit_pelanggan);
            const siklus_pembayaran = String($("#siklus_pembayaran").val() || '0');
            const max_kredit = $("#max_kredit").val();
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
            const keterangan = $("#keterangan").val();
            const voucher_val = $("#voucher").val();
            const voucher = voucher_val ? parseInt(voucher_val.replace(/\./g, '')) || 0 : 0;
            if (no_faktur == '') {
                SwalWarning('no_faktur', 'No. Faktur Tidak Boleh Kosong');
                return false;
            } else if (tanggal == '') {
                SwalWarning('tanggal', 'Tanggal Tidak Boleh Kosong');
                return false;
            } else if (kode_pelanggan == "") {
                SwalWarning('nama_pelanggan', 'Pelanggan Tidak Boleh Kosong');
                return false;
            } else if (kode_salesman == "") {
                SwalWarning('nama_salesman', 'Salesman Tidak Boleh Kosong');
                return false;
            } else if ($('#loadproduk tr').length == 0) {
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "") {
                SwalWarning('jenis_transaksi', 'Jenis Transaksi Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "T" && jenis_bayar == "") {
                SwalWarning('jenis_bayar', 'Jenis Bayar Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "K" && limit_pelanggan <= 0) {
                SwalWarning('nama_pelanggan', 'Pelanggan belum memiliki limit kredit, silahkan ajukan limit terlebih dahulu !');
                return false;
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '0' && totalPiutang > limit_pelanggan) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '1' && grandtotal > limit_pelanggan) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (jenis_transaksi == "K" && jmlfakturbelumlunas >= jmlfakturmax) {
                SwalWarning('keterangan', 'Melebihi Batas Jumlah Faktur Kredit !');
                return false;
            } else if (voucher > saldo_voucher) {
                SwalWarning('voucher', 'Melebihi Saldo Voucher !');
                return false;
            } else if (jenis_transaksi == "K" && sisa_piutang > 0 && keterangan == "") {
                SwalWarning('keterangan', 'Keterangan Harus Diisi !');
                return false;
            } else {
                if (!isTaxConfirmed) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Pilih Status Pajak',
                        text: 'Apakah transaksi ini menggunakan Faktur Pajak?',
                        icon: 'question',
                        showDenyButton: true,
                        confirmButtonText: 'Faktur Pajak',
                        denyButtonText: 'Non Faktur Pajak',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            confirmButton: 'inline-flex items-center justify-center h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer',
                            denyButton: 'inline-flex items-center justify-center h-[36px] px-4 rounded-lg bg-slate-600 hover:bg-slate-700 text-white text-xs font-bold transition shadow-xs cursor-pointer'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#status_pajak_faktur_input").val(1);
                            isTaxConfirmed = true;
                            $("#jenis_transaksi").prop("disabled", false);
                            $("#jenis_bayar").prop("disabled", false);
                            buttonDisable();
                            $("#formPenjualan").submit();
                        } else if (result.isDenied) {
                            $("#status_pajak_faktur_input").val(0);
                            isTaxConfirmed = true;
                            $("#jenis_transaksi").prop("disabled", false);
                            $("#jenis_bayar").prop("disabled", false);
                            buttonDisable();
                            $("#formPenjualan").submit();
                        }
                    });
                    return false;
                }
            }
        });

        function generatenofaktur() {
            var tanggal = $("#tanggal").val();
            var kode_salesman = $("#kode_salesman").val();
            buttonDisable();
            $.ajax({
                type: 'POST',
                url: '/penjualan/generatenofaktur',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    buttonEnable();
                    if (respond !== '0') {
                        $("#no_faktur").val(respond);
                        // $("#no_faktur").prop('readonly', true);
                        console.log(respond);
                    }

                }
            });
        }

        $("#tanggal,#kode_salesman").change(function() {
            generatenofaktur();
        });
    });
</script>
@endpush