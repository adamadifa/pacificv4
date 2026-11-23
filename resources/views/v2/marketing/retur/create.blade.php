@extends('v2.layouts.app')
@section('titlepage', 'Input Retur')

@section('content')
@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('retur.index') }}" class="text-slate-400 hover:text-brand-600 transition">Retur</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Retur</span>
    </div>
@endsection

<form action="{{ route('retur.store') }}" method="POST" id="formRetur" class="space-y-4" autocomplete="off">
    @csrf

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Transaksi Retur</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Entri data retur barang, pemilihan pelanggan, faktur referensi & rincian produk.</p>
        </div>

        <a href="{{ route('retur.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- MAIN TWO COLUMN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        
        <!-- LEFT COLUMN: DOKUMEN RETUR & PELANGGAN (Col-span 3) -->
        <div class="lg:col-span-3 space-y-4">
            
            <!-- Card 1: Data Retur -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Informasi Dokumen Retur</span>
                </div>

                <!-- No Retur -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="no_retur" id="no_retur" placeholder="No. Retur (Auto/Manual)"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" placeholder="Tanggal Retur" readonly
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

                <!-- Jenis Retur -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="shuffle" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="jenis_retur" id="jenis_retur"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                            <option value="">Jenis Retur</option>
                            <option value="GB">Ganti Barang (GB)</option>
                            <option value="PF">Potongan Faktur (PF)</option>
                        </select>
                    </div>
                </div>

                <!-- No Faktur Asal -->
                <div class="field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="no_faktur" id="no_faktur"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                            <option value="">Pilih Faktur</option>
                        </select>
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
                        <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
                    </div>
                </div>

            </div>

        </div>

        <!-- RIGHT COLUMN: PRODUK & RINCIAN RETUR (Col-span 9) -->
        <div class="lg:col-span-9 space-y-4">
            
            <!-- GRAND TOTAL DISPLAY STRIP (Pacific Navy) -->
            <div class="rounded-xl bg-[#284c9a] text-white p-4 sm:p-5 shadow-2xs overflow-hidden relative">
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[10.5px] uppercase tracking-wider font-bold text-white/70 block">Total Nilai Retur</span>
                            <span class="text-xs text-white/80">Akumulasi seluruh produk yang diretur</span>
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
                    <!-- Hidden Product Properties -->
                    <input type="hidden" id="kode_harga" name="kode_harga">
                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                    <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                    <input type="hidden" id="harga_dus_produk">
                    <input type="hidden" id="harga_pack_produk">
                    <input type="hidden" id="harga_pcs_produk">
                </div>

                <!-- Grid Jumlah & Harga Produk (3 Kolom Suffix Badge: DUS, PACK, PCS) -->
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
                        </div>
                    </div>

                </div>

                <!-- Tombol Tambah Produk -->
                <div class="flex justify-end pt-1">
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
                                    <th colspan="10" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                                                <span class="text-xs font-bold text-white">Daftar Produk Permintaan Retur</span>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px]">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">NAMA BARANG</th>
                                    <th class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">DUS</th>
                                    <th class="py-2 px-2 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA</th>
                                    <th class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">PACK</th>
                                    <th class="py-2 px-2 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA</th>
                                    <th class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">PCS</th>
                                    <th class="py-2 px-2 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">SUBTOTAL</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="loadproduk" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                                <!-- Dynamic cart rows -->
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-100 dark:bg-slate-800/80 font-bold border-t border-slate-200 dark:border-slate-700">
                                    <td colspan="8" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-700 dark:text-slate-200">
                                        TOTAL
                                    </td>
                                    <td class="py-2 px-3 text-right font-mono-num font-bold text-sm text-[#284c9a] dark:text-blue-400" id="subtotal">
                                        0
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <!-- CARD 3: AGREEMENT & SUBMIT BUTTON -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                        class="agreement w-4 h-4 text-[#284c9a] rounded-sm border-slate-300 dark:border-slate-700 focus:ring-[#284c9a] cursor-pointer">
                    <label for="defaultCheck3" class="text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                        Yakin data retur ini sudah benar dan akan disimpan ?
                    </label>
                </div>

                <div id="saveButton" class="hidden">
                    <button type="submit" id="btnSimpan"
                        class="w-full h-[40px] rounded-lg bg-[#284c9a] hover:bg-blue-800 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Submit Transaksi Retur</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</form>

<!-- MODALS -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="Pilih Produk Retur" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="Edit Rincian Produk Retur" />

<!-- MODAL PELANGGAN DATATABLE (STATIC BACKDROP) -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden bg-white dark:bg-slate-900">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pilih Data Pelanggan</h4>
                </div>
                <button type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
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
        // Flatpickr
        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        // MaskMoney
        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

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

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $("#btnSimpan").html(`
                <svg class="animate-spin h-4 w-4 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Transaksi...</span>
            `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
            $("#btnSimpan").html(`
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Submit Transaksi Retur</span>
            `);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Client-side Interactive Inline Validation Helpers
        function clearFieldError($input) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
            $input.closest('.relative').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $input.closest('.field-group').find('.field-error-msg').remove();
        }

        function setFieldError($input, message) {
            clearFieldError($input);
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
            $input.closest('.relative').find('.input-prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            
            const errorHtml = `
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `;
            $input.closest('.field-group').append(errorHtml);
        }

        // Live cleanup on input/change
        $('input, select').on('input change', function() {
            clearFieldError($(this));
        });

        // DataTable Pelanggan
        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: '{{ url()->current() }}',
            bAutoWidth: false,
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    className: 'text-center py-2 px-3'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%',
                    className: 'font-mono-num font-bold text-[#284c9a] dark:text-blue-400 py-2 px-3'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%',
                    className: 'font-semibold text-slate-800 dark:text-slate-100 py-2 px-3'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%',
                    className: 'text-slate-600 dark:text-slate-300 py-2 px-3'
                },
                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '20%',
                    className: 'text-slate-600 dark:text-slate-300 py-2 px-3'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '10%',
                    className: 'text-center py-2 px-3',
                    render: function(data) {
                        if (data === 'Aktif') {
                            return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>`;
                        } else {
                            return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">NonAktif</span>`;
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    className: 'text-center py-2 px-3'
                }
            ],
            drawCallback: function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });

        // Open Modal Pelanggan on click
        $("#nama_pelanggan").on('click', function(e) {
            e.preventDefault();
            $("#modalPelanggan").modal("show");
        });

        // Cek file Foto Pelanggan
        function checkFileExistence(fileFoto) {
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            $("#foto").attr("src", foto).removeClass('hidden');
                            $("#foto_placeholder").addClass('hidden');
                        } else {
                            $("#foto").attr("src", fotoDefault).removeClass('hidden');
                            $("#foto_placeholder").addClass('hidden');
                        }
                    } else {
                        $("#foto").attr("src", fotoDefault).removeClass('hidden');
                        $("#foto_placeholder").addClass('hidden');
                    }
                }
            };
            xhr.send();
        }

        // Get Pelanggan Detail
        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Perhatian!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan!",
                            icon: "warning",
                            confirmButtonColor: '#284c9a',
                            confirmButtonText: 'Tutup'
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        $('#nama_pelanggan').val(response.data.nama_pelanggan);
                        clearFieldError($('#nama_pelanggan'));

                        $('#latitude').text(response.data.latitude || '-');
                        $('#longitude').text(response.data.longitude || '-');
                        $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan || '-');
                        $('#limit_pelanggan_text').text('Rp ' + convertToRupiah(response.data.limit_pelanggan));
                        $('#limit_pelanggan').val(response.data.limit_pelanggan);
                        $('#alamat_pelanggan').text(response.data.alamat_pelanggan || 'Alamat tidak tersedia').removeClass('italic');
                        
                        let fileFoto = response.data.foto;
                        checkFileExistence(fileFoto);

                        // Data Salesman
                        $('#kode_salesman').val(response.data.kode_salesman);
                        $('#nama_salesman').val(response.data.nama_salesman);
                        clearFieldError($('#nama_salesman'));

                        // Load Faktur
                        getListfaktur(response.data.kode_pelanggan);

                        $('#modalPelanggan').modal('hide');
                    }
                }
            });
        }

        // Pilih Pelanggan Click
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
            $("#loadproduk").html('');
            loadsubtotal();
        });

        // Get Harga Modal
        function getHarga(kode_pelanggan) {
            $("#loadmodal").html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Data Produk...</span>
                </div>
            `);
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargareturbypelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#loadmodal").html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        }

        // Pilih Produk Field Click
        $("#nama_produk").on('click', function(e) {
            e.preventDefault();
            let kode_pelanggan = $("#kode_pelanggan").val();
            if (kode_pelanggan == "") {
                setFieldError($('#nama_pelanggan'), 'Silahkan pilih pelanggan terlebih dahulu!');
                $('html, body').animate({
                    scrollTop: $("#nama_pelanggan").offset().top - 120
                }, 300);
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Pilih Data Produk Retur');
                getHarga(kode_pelanggan);
            }
        });

        // Pilih Produk Action from Modal
        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_harga = $(this).attr('kode_harga');
            let nama_pelanggan = $("#nama_pelanggan").val() || '';
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

            $("#kode_harga").val(kode_harga);
            $("#nama_produk").val(nama_produk);
            clearFieldError($("#nama_produk"));

            $("#harga_dus").val(harga_dus);
            $("#harga_pack").val(harga_pack);
            $("#harga_pcs").val(harga_pcs);

            $("#harga_dus_produk").val(harga_dus_produk);
            $("#harga_pack_produk").val(harga_pack_produk);
            $("#harga_pcs_produk").val(harga_pcs_produk);

            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);
            $("#kode_kategori_diskon").val(kode_kategori_diskon);

            // Disabled states based on package config
            if (isi_pcs_pack == "" || isi_pcs_pack === '0' || isi_pcs_pack === 0) {
                $("#jml_pack").prop('disabled', true).addClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
                $("#harga_pack").prop('disabled', true).addClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
            } else {
                $("#jml_pack").prop('disabled', false).removeClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
                $("#harga_pack").prop('disabled', false).removeClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
            }

            $("#harga_dus").prop('disabled', false);
            $("#harga_pcs").prop('disabled', false);

            $("#modal").modal("hide");
            $("#jml_dus").focus();
        });

        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            let jml_dus = Math.floor(jumlah / isi_pcs_dus);
            let sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '' && isi_pcs_pack > 0) {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                jml_pack = 0;
                sisa_pack = sisa_dus;
            }
            let jml_pcs = sisa_pack;

            return {
                "dus": jml_dus,
                "pack": jml_pack,
                "pcs": jml_pcs
            };
        }

        // Tambah Produk ke Tabel
        function addProduk() {
            var kode_harga = $("#kode_harga").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val();
            var jml_pack = $("#jml_pack").val();
            var jml_pcs = $("#jml_pcs").val();
            var harga_dus = $("#harga_dus").val();
            var harga_pack = $("#harga_pack").val();
            var harga_pcs = $("#harga_pcs").val();
            var isi_pcs_dus = $("#isi_pcs_dus").val();
            var isi_pcs_pack = $("#isi_pcs_pack").val();

            var jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            var jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            var jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            var hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            var hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            var hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            var jumlah = (jmldus * parseInt(isi_pcs_dus || 1)) + (jmlpack * parseInt(isi_pcs_pack || 0)) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;
            let index = kode_harga;

            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (parseInt(pcs) * parseInt(hargapcs));

            if (kode_harga == "") {
                setFieldError($('#nama_produk'), 'Silahkan pilih produk terlebih dahulu!');
                $("#nama_produk").focus();
                return false;
            } else if (jumlah == "" || jumlah === '0' || jumlah === 0) {
                setFieldError($('#jml_dus'), 'Jumlah kuantiti retur tidak boleh kosong!');
                $("#jml_dus").focus();
                return false;
            } else if ($('#tabelproduk').find('#index_' + index).length > 0) {
                setFieldError($('#nama_produk'), 'Produk ini sudah ada di daftar rincian!');
                return false;
            }

            let produkRow = `
                <tr id="index_${index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                        <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                        <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                        <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                        ${kode_harga}
                    </td>
                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">${nama_produk}</td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${dus > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${dus === 0 ? '-' : dus}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_dus}
                        <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                    </td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${pack > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${pack === 0 ? '-' : pack}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_pack}
                        <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                    </td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${pcs > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${pcs === 0 ? '-' : pcs}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_pcs}
                        <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                        ${convertToRupiah(subtotal)}
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button" key="${index}" class="edit w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer" title="Edit">
                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                            </button>
                            <button type="button" key="${index}" class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            $('#loadproduk').append(produkRow);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Reset inputs
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

            loadsubtotal();
        }

        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            addProduk();
        });

        // Hapus Item Produk
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let key = $(this).attr("key");
            Swal.fire({
                title: 'Hapus Produk dari Daftar ?',
                text: "Item ini akan dikeluarkan dari rincian transaksi retur.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });

        // Edit Item Produk
        let currentRow;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            currentRow = $(this).closest('tr');

            let kode_harga = currentRow.find('.kode_harga').val();
            let nama_produk = currentRow.find('td:eq(1)').text().trim();
            let jml_dus = currentRow.find('td:eq(2)').text().trim();
            let harga_dus = currentRow.find('td:eq(3)').text().trim();
            let jml_pack = currentRow.find('td:eq(4)').text().trim();
            let harga_pack = currentRow.find('td:eq(5)').text().trim();
            let jml_pcs = currentRow.find('td:eq(6)').text().trim();
            let harga_pcs = currentRow.find('td:eq(7)').text().trim();

            let kode_pelanggan = $("#kode_pelanggan").val();
            let index_old = kode_harga;

            let dataProduk = {
                'kode_pelanggan': kode_pelanggan,
                'kode_harga': kode_harga,
                'nama_produk': nama_produk,
                'jml_dus': jml_dus === '-' ? '' : jml_dus,
                'harga_dus': harga_dus,
                'jml_pack': jml_pack === '-' ? '' : jml_pack,
                'harga_pack': harga_pack,
                'jml_pcs': jml_pcs === '-' ? '' : jml_pcs,
                'harga_pcs': harga_pcs,
                'index_old': index_old
            };

            $("#loadmodaleditProduk").html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Formulir Edit...</span>
                </div>
            `);

            $.ajax({
                type: 'POST',
                url: '/retur/editproduk',
                data: {
                    _token: "{{ csrf_token() }}",
                    dataproduk: dataProduk
                },
                cache: false,
                success: function(respond) {
                    $("#modaleditProduk").modal("show");
                    $("#modaleditProduk").find(".modal-title").text("Edit Rincian Produk Retur");
                    $("#loadmodaleditProduk").html(respond);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Submit Edit Produk
        $(document).on('submit', '#formEditproduk', function(event) {
            event.preventDefault();
            let kode_harga = $(this).find("#kode_harga").val();
            let nama_produk = $(this).find("#kode_harga").find(':selected').text().trim();
            let jml_dus = $(this).find("#jml_dus").val();
            let jml_pack = $(this).find("#jml_pack").val();
            let jml_pcs = $(this).find("#jml_pcs").val();
            let harga_dus = $(this).find("#harga_dus").val();
            let harga_pack = $(this).find("#harga_pack").val();
            let harga_pcs = $(this).find("#harga_pcs").val();
            let isi_pcs_dus = $(this).find("#isi_pcs_dus").val();
            let isi_pcs_pack = $(this).find("#isi_pcs_pack").val();
            let index_old = $(this).find("#index_old").val();

            let jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            let jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            let jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            let hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            let hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            let hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            let jumlah = (jmldus * parseInt(isi_pcs_dus || 1)) + (jmlpack * parseInt(isi_pcs_pack || 0)) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;
            let index = kode_harga;

            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (parseInt(pcs) * parseInt(hargapcs));

            let newRow = `
                <tr id="index_${index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                        <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                        <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                        <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                        ${kode_harga}
                    </td>
                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">${nama_produk}</td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${dus > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${dus === 0 ? '-' : dus}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_dus}
                        <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                    </td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${pack > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${pack === 0 ? '-' : pack}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_pack}
                        <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                    </td>
                    <td class="py-1.5 px-2 text-center font-mono-num ${pcs > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300'}">
                        ${pcs === 0 ? '-' : pcs}
                    </td>
                    <td class="py-1.5 px-2 text-right font-mono-num text-slate-600 dark:text-slate-300">
                        ${harga_pcs}
                        <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        ${convertToRupiah(subtotal)}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button" key="${index}" class="edit w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer" title="Edit">
                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                            </button>
                            <button type="button" key="${index}" class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            currentRow.replaceWith(newRow);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            $("#modaleditProduk").modal("hide");
            loadsubtotal();
        });

        // Hitung Subtotal & Grand Total
        function loadsubtotal() {
            let subtotal = 0;
            let valSubtotal = $("#tabelproduk").find(".subtotal");

            valSubtotal.each(function() {
                let val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });

            $("#subtotal").text(convertToRupiah(subtotal));
            $("#grandtotal_text").text(convertToRupiah(subtotal));
        }

        // Get List Faktur by Pelanggan
        function getListfaktur(kode_pelanggan) {
            $.ajax({
                type: 'POST',
                url: '/penjualan/getfakturbypelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: kode_pelanggan
                },
                cache: false,
                success: function(respond) {
                    $("#no_faktur").html(respond);
                    clearFieldError($("#no_faktur"));
                }
            });
        }

        // Agreement Checkbox Handler
        $('.agreement').change(function() {
            if (this.checked) {
                $("#saveButton").removeClass('hidden');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } else {
                $("#saveButton").addClass('hidden');
            }
        });

        // Form Submit Validation (Interactive Inline System V2, Zero Swal Alert)
        $("#formRetur").submit(function(e) {
            let hasError = false;
            let $firstError = null;

            const tanggal = $("#tanggal").val();
            const kode_pelanggan = $("#kode_pelanggan").val();
            const kode_salesman = $("#kode_salesman").val();
            const jenis_retur = $("#jenis_retur").val();
            const no_faktur = $("#no_faktur").val();

            if (!tanggal) {
                setFieldError($('#tanggal'), 'Tanggal retur wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $('#tanggal');
            }

            if (!kode_pelanggan) {
                setFieldError($('#nama_pelanggan'), 'Pelanggan wajib dipilih');
                hasError = true;
                if (!$firstError) $firstError = $('#nama_pelanggan');
            }

            if (!kode_salesman) {
                setFieldError($('#nama_salesman'), 'Salesman wajib terisi');
                hasError = true;
                if (!$firstError) $firstError = $('#nama_salesman');
            }

            if (!jenis_retur) {
                setFieldError($('#jenis_retur'), 'Pilih jenis retur');
                hasError = true;
                if (!$firstError) $firstError = $('#jenis_retur');
            }

            if (!no_faktur) {
                setFieldError($('#no_faktur'), 'Pilih nomor faktur asal');
                hasError = true;
                if (!$firstError) $firstError = $('#no_faktur');
            }

            if ($('#loadproduk tr').length === 0) {
                setFieldError($('#nama_produk'), 'Tambahkan minimal satu rincian produk retur');
                hasError = true;
                if (!$firstError) $firstError = $('#nama_produk');
            }

            if (hasError) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 120
                }, 300);
                $firstError.focus();
                return false;
            }

            buttonDisable();
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
