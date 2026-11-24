@extends('layouts.app')
@section('titlepage', 'Input Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('pembelian.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Pembelian</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Pembelian</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Pembelian Baru</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Lengkapi formulir transaksi pembelian, rincian produk, dan penetapan akun beban/aktiva.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('pembelian.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('pembelian.store') }}" method="POST" id="formPembelian">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- LEFT PANEL: DATA TRANSAKSI UTAMA (3 COLS) -->
            <div class="lg:col-span-3 space-y-3">
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Pembelian</h4>
                    </div>

                    <!-- No. Bukti -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_bukti" id="no_bukti" placeholder="No. Bukti Pembelian"
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition uppercase">
                        </div>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal" placeholder="Tanggal Pembelian" readonly
                                class="flatpickr-datepmb w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div>
                        <div class="relative select2-container-v2">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_supplier" id="kode_supplier" class="select2Kodesupplier w-full">
                                <option value="">Pilih Supplier</option>
                                @foreach ($supplier as $d)
                                    <option value="{{ $d->kode_supplier }}">{{ textUpperCase($d->nama_supplier) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Asal Ajuan -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="folder-tree" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_asal_pengajuan" id="kode_asal_pengajuan"
                                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Asal Ajuan</option>
                                @foreach ($asal_ajuan as $d)
                                    <option value="{{ $d['kode_group'] }}">{{ $d['nama_group'] }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3 h-3"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Transaksi (Tunai / Kredit) -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jenis_transaksi" id="jenis_transaksi"
                                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="T">Tunai</option>
                                <option value="K">Kredit</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3 h-3"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jatuh Tempo (Hanya bila Kredit) -->
                    <div id="containerJatuhTempo">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="jatuh_tempo" id="jatuh_tempo" placeholder="Tanggal Jatuh Tempo" readonly
                                class="flatpickr-datepmb w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        </div>
                    </div>

                    <!-- No. PO -->
                    <div>
                        <div class="relative select2-container-v2">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="no_po" id="no_po" class="select2PO w-full">
                                <option value="">Pilih Nomor Pre-Order (PO)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status PPN & Kategori Transaksi -->
                    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 space-y-2">
                        
                        <!-- PPN Segmented Cards -->
                        <div>
                            <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Faktur Pajak (PPN)</span>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="ppn" id="ppn1" value="1" class="peer hidden">
                                    <div class="h-[36px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-2 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                                        <span class="text-xs">Ya (PPN)</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="ppn" id="ppn2" value="0" checked class="peer hidden">
                                    <div class="h-[36px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-2 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i>
                                        <span class="text-xs">Tidak (Non PPN)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Kategori Transaksi Segmented Cards -->
                        <div>
                            <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kategori Transaksi</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori_transaksi" id="kat_mp" value="MP" class="peer hidden">
                                    <div class="h-[36px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <span class="text-xs">MP</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori_transaksi" id="kat_pc" value="PC" checked class="peer hidden">
                                    <div class="h-[36px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <span class="text-xs">Pacific</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori_transaksi" id="kat_pb" value="PB" class="peer hidden">
                                    <div class="h-[36px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <span class="text-xs">Pribadi</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori_transaksi" id="kat_ip" value="IP" class="peer hidden">
                                    <div class="h-[36px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                        <span class="text-xs">IP</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: DETAIL RINCIAN BARANG & TOTAL (9 COLS) -->
            <div class="lg:col-span-9 space-y-3">
                
                <!-- CARD FORM TAMBAH BARANG -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Tambah Barang ke Pembelian</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block font-semibold">Total Pembelian</span>
                            <span id="grandtotal_text" class="text-base font-black font-mono-num text-[#284c9a] dark:text-blue-400 leading-tight">0</span>
                        </div>
                    </div>

                    <!-- Baris 1: Nama Barang, Qty, Harga, Penyesuaian (No-Label) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-start">
                        
                        <!-- Nama Barang (Col-span 4) -->
                        <div class="lg:col-span-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="nama_barang" id="nama_barang" placeholder="Pilih Barang (Klik)" readonly
                                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                                <input type="hidden" id="kode_barang" name="kode_barang">
                            </div>
                        </div>

                        <!-- Qty (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="jumlah" id="jumlah" placeholder="Qty"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Harga (Col-span 3) -->
                        <div class="lg:col-span-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="harga" id="harga" placeholder="Harga Satuan"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Penyesuaian (Col-span 3) -->
                        <div class="lg:col-span-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="penyesuaian" id="penyesuaian" placeholder="Penyesuaian (opsional)"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                    </div>

                    <!-- Baris 2: Akun, Cabang, Keterangan & Tombol Tambah (No-Label) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-start">
                        
                        <!-- Akun (Col-span 4) -->
                        <div class="lg:col-span-4">
                            <div class="relative select2-container-v2">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                    <i data-lucide="book" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="kode_akun" id="kode_akun" class="select2Kodeakun w-full">
                                    <option value="">Pilih Akun Beban / Aktiva</option>
                                    @foreach ($coa as $d)
                                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Cabang (Col-span 3) -->
                        <div class="lg:col-span-3">
                            <div class="relative select2-container-v2">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                    <i data-lucide="building" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full">
                                    <option value="">Semua Cabang / Pusat</option>
                                    @foreach ($cabang as $d)
                                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Keterangan (Col-span 3) -->
                        <div class="lg:col-span-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan barang (opsional)"
                                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Tombol Tambah Barang (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <button type="button" id="btnTambahbarang"
                                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah</span>
                            </button>
                        </div>

                    </div>

                </div>

                <!-- TABEL DATA RINCIAN ITEM SEMENTARA -->
                <div id="error_rincian_barang" class="hidden p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Rincian barang pembelian tidak boleh kosong! Tambahkan minimal 1 barang ke daftar rincian di bawah.</span>
                </div>

                <div id="cardTabelBarang" class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs transition">
                    <div class="overflow-x-auto min-h-[160px]">
                        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="10" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-blue-200"></i>
                                                <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Rincian Barang Pembelian</span>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px]">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">KODE</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[160px]">NAMA BARANG</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">QTY</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[90px]">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[95px]">SUBTOTAL</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[80px]">PENY</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">TOTAL</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[120px]">AKUN</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">CABANG</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-12">#</th>
                                </tr>
                            </thead>
                            <tbody id="loadbarang" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                <!-- Dynamic rows populated via JS -->
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                                    <td colspan="6" class="py-2 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                                        Total Pembelian:
                                    </td>
                                    <td id="grandtotal" class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-slate-100 text-xs">
                                        0
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- SUBMIT & CONFIRMATION SECTION -->
                    <div class="p-3 sm:p-3.5 border-t border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="aggrement" id="defaultCheck3" class="agreement w-4 h-4 rounded text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                Yakin data transaksi pembelian sudah benar dan siap disimpan ?
                            </span>
                        </label>

                        <div id="saveButton" class="hidden pt-2 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pembelian.index') }}"
                                    class="h-[36px] px-4 inline-flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                                    Batal
                                </a>
                                <button type="submit" id="btnSimpan"
                                    class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                    <span>Simpan Transaksi Pembelian</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </form>

</div>

<!-- MODAL PILIH BARANG DARI DATATABLE -->
<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden bg-white dark:bg-slate-900">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pilih Data Barang</h4>
                </div>
                <button type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body p-4 overflow-x-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tabelbarang" width="100%">
                    <thead>
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA BARANG</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">SATUAN</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[120px]">JENIS</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[140px]">KATEGORI</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">AKSI</th>
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
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $("#formPembelian");
        let baris = 0;

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-datepmb").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        form.find("#no_bukti").on('keydown keyup', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
            }
            this.value = this.value.toUpperCase();
        });

        // Initialize Select2 dropdowns
        $('.select2Kodesupplier').select2({ placeholder: 'Pilih Supplier', allowClear: true, width: '100%' });
        $('.select2PO').select2({ placeholder: 'Pilih Nomor Pre-Order (PO)', allowClear: true, width: '100%' });
        $('.select2Kodeakun').select2({ placeholder: 'Pilih Akun', allowClear: true, width: '100%' });
        $('.select2Kodecabang').select2({ placeholder: 'Semua Cabang / Pusat', allowClear: true, width: '100%' });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $("#btnSimpan").html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan Pembelian...</span>
            `);
        }

        function resetForm() {
            form.find("#kode_barang").val("");
            form.find("#nama_barang").val("");
            form.find("#jumlah").val("");
            form.find("#harga").val("");
            form.find("#penyesuaian").val("");
            form.find('.select2Kodeakun').val('').trigger("change");
            form.find("#keterangan").val("");
            form.find('.select2Kodecabang').val('').trigger("change");
        }

        function loadTablebarang(kode_group = "000") {
            $('#tabelbarang').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'asc']],
                ajax: `/barangpembelian/${kode_group}/getbarangjson`,
                bAutoWidth: false,
                bDestroy: true,
                columns: [
                    { data: 'kode_barang', name: 'kode_barang', orderable: true, searchable: true, width: '10%' },
                    { data: 'namabarang', name: 'nama_barang', orderable: true, searchable: true, width: '40%' },
                    { data: 'satuan', name: 'satuan', orderable: true, searchable: false, width: '10%' },
                    { data: 'jenisbarang', name: 'jenisbarang', orderable: true, searchable: false, width: '20%' },
                    { data: 'nama_kategori', name: 'nama_kategori', orderable: true, searchable: false, width: '20%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '5%' }
                ]
            });
        }

        $("#nama_barang").click(function(e) {
            clearInlineError($(this));
            let kode_group = form.find("#kode_asal_pengajuan").val();
            if (!kode_group) {
                clearInlineError(form.find("#kode_asal_pengajuan"));
                showInlineError(form.find("#kode_asal_pengajuan"), "Asal Pengajuan harus dipilih terlebih dahulu!");
            } else {
                clearInlineError(form.find("#kode_asal_pengajuan"));
                loadTablebarang(kode_group);
                $("#modalBarang").modal("show");
            }
        });

        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');
            const kode_jenis_barang = $(this).attr('kode_jenis_barang');
            
            if (kode_jenis_barang == 'BB') {
                form.find("#kode_akun").val('5-1101').trigger('change');
            } else if (kode_jenis_barang == 'BT') {
                form.find("#kode_akun").val('5-1102').trigger('change');
            } else if (kode_jenis_barang == 'KM') {
                form.find("#kode_akun").val('5-1103').trigger('change');
            } else {
                form.find("#kode_akun").val('');
            }

            form.find("#kode_barang").val(kode_barang);
            form.find("#nama_barang").val(nama_barang);
            clearInlineError(form.find("#nama_barang"));
            $("#modalBarang").modal("hide");
            form.find("#jumlah").focus();
        });

        function convertNumber(number) {
            if (!number) return 0;
            let formatted = number.toString().replace(/\./g, '');
            formatted = formatted.replace(/,/g, '.');
            return parseFloat(formatted) || 0;
        }

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("border-rose-500 ring-1 ring-rose-500");
            
            const $rel = $el.closest(".relative");
            if ($rel.length && !($rel[0].className && $rel[0].className.indexOf('col-span') !== -1)) {
                $rel.find("i").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            } else {
                $el.siblings(".field-error-msg").remove();
                $el.parent().find("> .field-error-msg").remove();
                $el.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.parent().find(".field-error-msg").remove();
            }
            $el.siblings(".field-error-msg").remove();
            $el.parent().find("> .field-error-msg").remove();
            $el.parent().find(".field-error-msg").remove();
        }

        function addBarang() {
            const kode_barang = form.find("#kode_barang").val();
            const nama_barang = form.find("#nama_barang").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const penyesuaian = form.find("#penyesuaian").val() || "0";
            const dataAkun = form.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataAkun).val();
            const nama_akun = $(dataAkun).text();
            const keterangan = form.find("#keterangan").val();
            const kode_cabang = form.find("#kode_cabang").val();

            if (!kode_barang) {
                showInlineError(form.find("#nama_barang"), "Barang wajib dipilih!");
                return;
            }
            if (!jumlah || convertNumber(jumlah) === 0) {
                showInlineError(form.find("#jumlah"), "Qty barang wajib diisi!");
                return;
            }
            if (!harga || convertNumber(harga) === 0) {
                showInlineError(form.find("#harga"), "Harga satuan wajib diisi!");
                return;
            }
            if (!kode_akun) {
                showInlineError(form.find("#kode_akun"), "Akun beban/aktiva wajib dipilih!");
                return;
            }

            baris = baris + 1;
            let jml = convertNumber(jumlah);
            let hrg = convertNumber(harga);
            let peny = convertNumber(penyesuaian);
            let subtotal = parseFloat(jml) * parseFloat(hrg);
            let total = parseFloat(subtotal) + parseFloat(peny);
            let subtotalFormatted = numberFormat(subtotal, '2', ',', '.');
            let totalFormatted = numberFormat(total, '2', ',', '.');

            let bg = "";
            if ((kode_akun.substring(0, 3) == '6-1' || kode_akun.substring(0, 3) == '6-2') && kode_cabang != '') {
                bg = "bg-sky-50/70 hover:bg-sky-100/70 dark:bg-sky-950/40";
            } else {
                bg = "hover:bg-slate-50/80 dark:hover:bg-slate-800/50";
            }

            let barang = `
            <tr id="index_${baris}" class="${bg} transition-colors">
                <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                    <input type="hidden" name="harga_item[]" value="${harga}" />
                    <input type="hidden" name="penyesuaian_item[]" value="${penyesuaian}" />
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                    <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" />
                    ${kode_barang}
                </td>
                <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">${nama_barang}</td>
                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">${jumlah}</td>
                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">${harga}</td>
                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">${subtotalFormatted}</td>
                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">${penyesuaian}</td>
                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 totalharga">${totalFormatted}</td>
                <td class="py-1.5 px-3 text-[11px] text-slate-600 dark:text-slate-400 truncate max-w-[150px]" title="${nama_akun}">${nama_akun}</td>
                <td class="py-1.5 px-3 text-center font-semibold text-slate-700 dark:text-slate-300">${kode_cabang || '-'}</td>
                <td class="py-1.5 px-3 text-center">
                    <a href="#" id="index_${baris}" class="delete w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs" title="Hapus Barang">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </a>
                </td>
            </tr>`;

            $('#loadbarang').append(barang);
            $("#cardTabelBarang").removeClass("border-rose-500 ring-1 ring-rose-500");
            $("#error_rincian_barang").addClass("hidden");
            if (typeof lucide !== 'undefined') lucide.createIcons();
            calculateTotal();
            resetForm();
        }

        form.find("#btnTambahbarang").click(function(e) {
            e.preventDefault();
            addBarang();
        });

        $("#kode_asal_pengajuan").change(function() {
            clearInlineError($(this));
            resetForm();
            $("#loadbarang").html("");
            calculateTotal();
            $("#cardTabelBarang").removeClass("border-rose-500 ring-1 ring-rose-500");
            $("#error_rincian_barang").addClass("hidden");
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            $(`#${id}`).remove();
            calculateTotal();
        });

        // Toggle Agreement checkbox
        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").removeClass('hidden');
            } else {
                form.find("#saveButton").addClass('hidden');
            }
        });

        // Form Submit Validation
        form.submit(function(e) {
            const no_bukti = form.find("#no_bukti").val().trim();
            const tanggal = form.find("#tanggal").val().trim();
            const kode_supplier = form.find("#kode_supplier").val();
            const kode_asal_pengajuan = form.find("#kode_asal_pengajuan").val();
            const jenis_transaksi = form.find("#jenis_transaksi").val();
            const jatuh_tempo = form.find("#jatuh_tempo").val().trim();

            let hasError = false;

            if (!no_bukti) {
                showInlineError(form.find("#no_bukti"), "No. Bukti Pembelian wajib diisi!");
                hasError = true;
            }
            if (!tanggal) {
                showInlineError(form.find("#tanggal"), "Tanggal transaksi wajib diisi!");
                hasError = true;
            }
            if (!kode_supplier) {
                showInlineError(form.find("#kode_supplier"), "Supplier wajib dipilih!");
                hasError = true;
            }
            if (!kode_asal_pengajuan) {
                showInlineError(form.find("#kode_asal_pengajuan"), "Asal ajuan wajib dipilih!");
                hasError = true;
            }
            if (!jenis_transaksi) {
                showInlineError(form.find("#jenis_transaksi"), "Jenis transaksi wajib dipilih!");
                hasError = true;
            }
            if (jenis_transaksi == 'K' && !jatuh_tempo) {
                showInlineError(form.find("#jatuh_tempo"), "Tanggal jatuh tempo wajib diisi untuk transaksi kredit!");
                hasError = true;
            }
            if ($('#loadbarang tr').length === 0) {
                $("#cardTabelBarang").addClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_rincian_barang").removeClass("hidden");
                if (typeof lucide !== 'undefined') lucide.createIcons();
                hasError = true;
            } else {
                $("#cardTabelBarang").removeClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_rincian_barang").addClass("hidden");
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisable();
        });

        // Dynamic PO loading based on Supplier
        $('#kode_supplier').on('change', function() {
            clearInlineError($(this));
            let kode_supplier = $(this).val();
            $('#no_po').val(null).trigger('change');
            if (!kode_supplier) return;

            $('#no_po').select2({
                placeholder: 'Pilih Nomor Pre-Order (PO)',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: `/po/by-supplier/${kode_supplier}`,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return { results: data };
                    }
                }
            });
        });

        // Live cleanup on input & select
        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });

        $(document).on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
@endpush
