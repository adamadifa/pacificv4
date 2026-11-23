@extends('layouts.app')
@section('titlepage', 'Penjualan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Penjualan</span>
    </div>
@endsection

@section('content')
@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $user = auth()->user();
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Penjualan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data transaksi penjualan barang, faktur, surat jalan, dan penerimaan pesanan.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('penjualan.cetakfaktur')
                <button type="button" id="btnCetakSuratjalan"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak Banyak Surat Jalan</span>
                </button>
            @endcan
            @can('penjualan.create')
                <a href="{{ route('penjualan.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Penjualan</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- INFORMATIONAL STRIP & LEGEND CARD -->
    <div class="rounded-xl border border-blue-200/80 dark:border-blue-900/50 bg-gradient-to-r from-blue-50/80 via-white to-blue-50/50 dark:from-blue-950/30 dark:via-slate-900 dark:to-blue-950/20 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-[#284c9a]/10 dark:bg-blue-400/10 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>
                <div class="space-y-0.5 text-xs">
                    <div class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1.5">
                        <span>Petunjuk &amp; Indikator Status Transaksi</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        Gunakan tombol aksi <span class="font-bold text-rose-600 dark:text-rose-400">File X</span> untuk membatalkan faktur, dan tombol <span class="font-bold text-amber-600 dark:text-amber-400">Sliders</span> untuk meng-generate nomor faktur Pre-Order (PO).
                    </p>
                </div>
            </div>

            <!-- STATUS LEGEND BADGES -->
            <div class="flex flex-wrap items-center gap-1.5 pt-2 md:pt-0 border-t md:border-t-0 border-slate-200/80 dark:border-slate-800">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span>Faktur Batal</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>Ajuan Batal</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-900/60 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span>Faktur Pajak</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/60 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Sampel</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200 dark:border-sky-900/60 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                    <span>Pre-Order (PO)</span>
                </span>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('penjualan.index') }}" method="GET" id="formSearch" class="space-y-2">
            
            <!-- Baris 1: Periode & Cabang & Salesman -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Dari (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                <!-- Sampai (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                @if ($hasAccessAllCabang)
                    <!-- Cabang (Col-span 3) -->
                    <div class="lg:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search"
                            class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Salesman (Col-span 3) -->
                    <div class="lg:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_salesman_search" id="kode_salesman_search"
                            class="select2Kodesalesmansearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Salesman</option>
                        </select>
                    </div>
                @else
                    <!-- Salesman Full Width (Col-span 6) -->
                    <div class="lg:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_salesman_search" id="kode_salesman_search"
                            class="select2Kodesalesmansearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Salesman</option>
                        </select>
                    </div>
                @endif

            </div>

            <!-- Baris 2: Parameter Transaksi & Pencarian Pelanggan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Status PO (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status_po" id="status_po"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Status PO</option>
                        <option value="1" {{ Request('status_po') === '1' ? 'selected' : '' }}>PO</option>
                        <option value="0" {{ Request('status_po') === '0' ? 'selected' : '' }}>Non PO</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Status Pajak Faktur (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status_pajak_faktur_search" id="status_pajak_faktur_search"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pajak Faktur</option>
                        <option value="1" {{ Request('status_pajak_faktur_search') === '1' ? 'selected' : '' }}>Faktur Pajak</option>
                        <option value="0" {{ Request('status_pajak_faktur_search') === '0' ? 'selected' : '' }}>Non Faktur Pajak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- No Faktur (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_faktur_search" id="no_faktur_search" value="{{ Request('no_faktur_search') }}" placeholder="No. Faktur"
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Kode Pelanggan (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="kode_pelanggan_search" id="kode_pelanggan_search" value="{{ Request('kode_pelanggan_search') }}" placeholder="Kode Pelanggan"
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Nama Pelanggan (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_pelanggan_search" id="nama_pelanggan_search" value="{{ Request('nama_pelanggan_search') }}" placeholder="Nama Pelanggan"
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Tombol Aksi (Col-span 2) -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search', 'kode_salesman_search', 'status_po', 'status_pajak_faktur_search', 'no_faktur_search', 'kode_pelanggan_search', 'nama_pelanggan_search']))
                        <a href="{{ route('penjualan.index') }}"
                            class="h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium transition flex items-center justify-center cursor-pointer shrink-0"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px, FROZEN FAKTUR & AKSI) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="10" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Data Transaksi Penjualan</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($penjualan, 'total') ? number_format($penjualan->total(), 0, ',', '.') : number_format($penjualan->count(), 0, ',', '.') }} Transaksi
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky left-0 z-20 bg-[#1e3a78] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)]">NO. FAKTUR</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">NAMA PELANGGAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">CABANG</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">PKP</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">SALESMAN</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">TOTAL NETTO</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">JT</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">STATUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky right-0 z-20 bg-[#1e3a78] shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.3)]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($penjualan as $d)
                        @php
                            $total_netto = $d->total_bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                            
                            // Visual State Styling (V2 Tailwind Harmony)
                            if ($d->status_batal == '1') {
                                $rowBg = 'bg-rose-50/70 hover:bg-rose-100/70 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 text-rose-950 dark:text-rose-200';
                                $cellBg = 'bg-rose-50/90 dark:bg-rose-950/50';
                            } elseif ($d->status_batal == '2') {
                                $rowBg = 'bg-amber-50/70 hover:bg-amber-100/70 dark:bg-amber-950/30 dark:hover:bg-amber-900/40 text-amber-950 dark:text-amber-200';
                                $cellBg = 'bg-amber-50/90 dark:bg-amber-950/50';
                            } elseif ($d->status_pajak_faktur == '1') {
                                $rowBg = 'bg-purple-50/70 hover:bg-purple-100/70 dark:bg-purple-950/30 dark:hover:bg-purple-900/40 text-purple-950 dark:text-purple-200';
                                $cellBg = 'bg-purple-50/90 dark:bg-purple-950/50';
                            } elseif ($d->status_sampel == '1') {
                                $rowBg = 'bg-emerald-50/70 hover:bg-emerald-100/70 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-950 dark:text-emerald-200';
                                $cellBg = 'bg-emerald-50/90 dark:bg-emerald-950/50';
                            } elseif (substr($d->no_faktur, 3, 2) == 'PR') {
                                $rowBg = 'bg-sky-50/70 hover:bg-sky-100/70 dark:bg-sky-950/30 dark:hover:bg-sky-900/40 text-sky-950 dark:text-sky-200';
                                $cellBg = 'bg-sky-50/90 dark:bg-sky-950/50';
                            } else {
                                $rowBg = 'bg-white hover:bg-slate-50/80 dark:bg-slate-900 dark:hover:bg-slate-800/50 text-slate-700 dark:text-slate-200';
                                $cellBg = 'bg-white dark:bg-slate-900';
                            }
                        @endphp
                        <tr class="{{ $rowBg }} transition group">
                            
                            <!-- 1. No Faktur (Frozen Left) -->
                            <td class="py-1.5 px-3 sticky left-0 z-10 {{ $cellBg }} group-hover:bg-slate-100/80 dark:group-hover:bg-slate-800/80 transition-colors shadow-[2px_0_5px_-2px_rgba(0,0,0,0.08)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.4)]">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono-num font-bold text-[11px] text-[#284c9a] dark:text-blue-400">
                                        {{ $d->no_faktur }}
                                    </span>
                                    @if (substr($d->no_faktur, 3, 2) == 'PR')
                                        <span class="inline-flex items-center px-1 py-0.2 rounded text-[9.5px] font-bold bg-sky-100 dark:bg-sky-900/60 text-sky-700 dark:text-sky-300">
                                            PO
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. Tanggal -->
                            <td class="py-1.5 px-3 font-medium text-slate-600 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- 3. Nama Pelanggan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 max-w-[200px] truncate" title="{{ $d->nama_pelanggan }}">
                                {{ $d->nama_pelanggan }}
                            </td>

                            <!-- 4. Cabang -->
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    <i data-lucide="building-2" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ textUpperCase($d->nama_cabang) }}</span>
                                </span>
                            </td>

                            <!-- 5. PKP -->
                            <td class="py-1.5 px-3 text-center">
                                @if(!empty($d->kode_pt_pkp))
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                        {{ textUpperCase($d->kode_pt_pkp) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 text-xs">-</span>
                                @endif
                            </td>

                            <!-- 6. Salesman -->
                            <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($d->nama_salesman) }}
                            </td>

                            <!-- 7. Total Netto -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ formatAngka($total_netto) }}
                            </td>

                            <!-- 8. Jenis Transaksi (JT) -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->jenis_transaksi == 'T')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-md font-bold text-[10.5px] bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800" title="Tunai">
                                        T
                                    </span>
                                @elseif($d->jenis_transaksi == 'K')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-md font-bold text-[10.5px] bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800" title="Kredit">
                                        K
                                    </span>
                                @endif
                            </td>

                            <!-- 9. Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_batal == 0)
                                    @if ($d->total_bayar == $total_netto)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Lunas</span>
                                        </span>
                                    @elseif ($d->total_bayar > $total_netto)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400 border border-blue-200 dark:border-blue-900/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>Lebih</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Belum Lunas</span>
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-200 border border-rose-300 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        <span>Batal</span>
                                    </span>
                                @endif
                            </td>

                            <!-- 10. Aksi (Frozen Right) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 {{ $cellBg }} group-hover:bg-slate-100/80 dark:group-hover:bg-slate-800/80 transition-colors shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.08)] dark:shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.4)]">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- Edit -->
                                    @can('penjualan.edit')
                                        <a href="/penjualan/{{ \Crypt::encrypt($d->no_faktur) }}/edit"
                                            class="w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            title="Edit Transaksi">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </a>
                                    @endcan

                                    <!-- Detail -->
                                    @can('penjualan.show')
                                        <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}"
                                            class="w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                            title="Detail Transaksi">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </a>

                                        <!-- Dropdown Menu Cetak Faktur & SJ -->
                                        <div class="relative inline-block text-left print-dropdown-container">
                                            <button type="button" class="btnTogglePrintDropdown w-6 h-6 rounded-md bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 flex items-center justify-center transition cursor-pointer"
                                                title="Cetak Dokumen">
                                                <i data-lucide="printer" class="w-3 h-3"></i>
                                            </button>
                                            <div class="printDropdownMenu hidden absolute right-0 mt-1 w-44 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl py-1 z-50 text-left">
                                                @can('penjualan.cetakfaktur')
                                                    <a href="{{ route('penjualan.cetakfaktur', Crypt::encrypt($d->no_faktur)) }}" target="_blank"
                                                        class="flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition whitespace-nowrap">
                                                        <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-400"></i>
                                                        <span>Cetak Faktur</span>
                                                    </a>
                                                @endcan
                                                @can('penjualan.cetaksuratjalan')
                                                    <a href="{{ route('penjualan.cetaksuratjalan', [1, Crypt::encrypt($d->no_faktur)]) }}" target="_blank"
                                                        class="flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition whitespace-nowrap">
                                                        <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-400"></i>
                                                        <span>Surat Jalan 1</span>
                                                    </a>
                                                    <a href="{{ route('penjualan.cetaksuratjalan', [2, Crypt::encrypt($d->no_faktur)]) }}" target="_blank"
                                                        class="flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition whitespace-nowrap">
                                                        <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-400"></i>
                                                        <span>Surat Jalan 2</span>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    @endcan

                                    <!-- Batal Faktur -->
                                    @can('penjualan.batalfaktur')
                                        <button type="button" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}"
                                            class="btnBatal w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                            title="Batalkan Faktur">
                                            <i data-lucide="file-x" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Set Cabang PKP -->
                                    @can('penjualan.setpkp')
                                        <button type="button" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}"
                                            class="btnSetpkp w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                            title="Tentukan Cabang PKP">
                                            <i data-lucide="building" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Generate Faktur (PO PR) -->
                                    @can('penjualan.edit')
                                        @if (substr($d->no_faktur, 3, 2) == 'PR')
                                            <a href="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/generatefaktur"
                                                class="w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                                title="Generate No. Faktur Resmi">
                                                <i data-lucide="sliders-horizontal" class="w-3 h-3"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    <!-- Visit Pelanggan -->
                                    @can('visitpelanggan.create')
                                        @if (!empty($d->kode_visit))
                                            <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center" title="Sudah Visit">
                                                <i data-lucide="check-check" class="w-3 h-3"></i>
                                            </span>
                                        @else
                                            <button type="button" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}"
                                                class="btnVisit w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                                title="Input Visit Pelanggan">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    <!-- Hapus -->
                                    @can('penjualan.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Transaksi">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                        <i data-lucide="inbox" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tidak ada data transaksi penjualan yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION CONTAINER -->
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $penjualan->links('v2.components.pagination') }}
        </div>
    </div>

</div>

<!-- MODAL CONTAINER (STATIC BACKDROP & CLEAN HEADER STANDAR V2) -->
<div id="modalPenjualan" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
    <div id="modalPenjualanDialog" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden transform transition-all duration-150 my-auto">
        
        <!-- Header Modal Bersih (Anti Solid Background) -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                </div>
                <h3 id="modalPenjualanTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-none">
                    Form Penjualan
                </h3>
            </div>
            <button type="button" id="btnModalClose"
                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body / Content Container -->
        <div id="modalPenjualanContent" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto">
            <!-- Loading State Placeholder -->
            <div class="flex flex-col items-center justify-center py-12 gap-3">
                <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Formulir...</span>
            </div>
        </div>

    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Flatpickr Filter
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Initialize Select2 Filter
        const $select2Cabang = $('.select2Kodecabangsearch');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        const $select2Salesman = $('.select2Kodesalesmansearch');
        if ($select2Salesman.length) {
            $select2Salesman.select2({
                placeholder: 'Semua Salesman',
                allowClear: true,
                width: '100%'
            });
        }

        // Dynamic Salesman loader by Cabang
        function getsalesmanbyCabang() {
            const kode_cabang = $("#kode_cabang_search").val();
            const kode_salesman = "{{ Request('kode_salesman_search') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_search").html('<option value="">Semua Salesman</option>' + respond);
                    if ($select2Salesman.length) {
                        $select2Salesman.trigger('change.select2');
                    }
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang_search").on('change', function() {
            getsalesmanbyCabang();
        });

        // Print Dropdown Toggle Handlers with Z-Index Elevation
        $(document).on('click', '.btnTogglePrintDropdown', function(e) {
            e.stopPropagation();
            const $cell = $(this).closest('td');
            const $row = $(this).closest('tr');
            const $menu = $(this).siblings('.printDropdownMenu');
            const isCurrentlyOpen = !$menu.hasClass('hidden');

            // Reset all dropdowns and z-indices across all sticky cells
            $('.printDropdownMenu').addClass('hidden');
            $('td.sticky').removeClass('!z-40');
            $('tr').removeClass('!z-30 relative');

            if (!isCurrentlyOpen) {
                $menu.removeClass('hidden');
                $cell.addClass('!z-40');
                $row.addClass('!z-30 relative');

                // Smart dropup vs dropdown detection
                const rect = this.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const $tableWrapper = $(this).closest('.overflow-x-auto');
                const tableWrapperBottom = $tableWrapper.length ? $tableWrapper[0].getBoundingClientRect().bottom : window.innerHeight;
                const spaceBelowInTable = tableWrapperBottom - rect.bottom;

                if (spaceBelow < 150 || spaceBelowInTable < 140) {
                    $menu.addClass('bottom-full mb-1 mt-0').removeClass('top-full mt-1');
                } else {
                    $menu.removeClass('bottom-full mb-1 mt-0').addClass('top-full mt-1');
                }
            }
        });

        // Close dropdown when clicking a print item
        $(document).on('click', '.printDropdownMenu a', function() {
            $('.printDropdownMenu').addClass('hidden');
            $('td.sticky').removeClass('!z-40');
            $('tr').removeClass('!z-30 relative');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.print-dropdown-container').length) {
                $('.printDropdownMenu').addClass('hidden');
                $('td.sticky').removeClass('!z-40');
                $('tr').removeClass('!z-30 relative');
            }
        });

        // Modal Management & Static Backdrop Handlers
        const $modal = $("#modalPenjualan");
        const $dialog = $("#modalPenjualanDialog");
        const $title = $("#modalPenjualanTitle");
        const $content = $("#modalPenjualanContent");

        function showLoading() {
            $content.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Data...</span>
                </div>
            `);
        }

        function openModal(titleText) {
            $title.text(titleText);
            showLoading();
            $modal.removeClass("hidden").addClass("flex");
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
        }

        // Explicit Close Button
        $("#btnModalClose").on('click', function() {
            closeModal();
        });

        // Static Backdrop (Anti-Tutup Sembarangan & Bounce Feedback)
        $modal.on('click', function(e) {
            if ($(e.target).is($modal)) {
                $dialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $dialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        // Prevent ESC from closing
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                e.preventDefault();
                $dialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $dialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        // Trigger Cetak Banyak Surat Jalan
        $("#btnCetakSuratjalan").on('click', function(e) {
            e.preventDefault();
            openModal("Cetak Banyak Surat Jalan");
            $content.load(`/penjualan/filtersuratjalan`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Batal Faktur
        $(document).on('click', '.btnBatal', function(e) {
            e.preventDefault();
            const no_faktur = $(this).attr('no_faktur');
            openModal("Konfirmasi Pembatalan Faktur");
            $content.load(`/penjualan/${no_faktur}/batalfaktur`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Set PKP
        $(document).on('click', '.btnSetpkp', function(e) {
            e.preventDefault();
            const no_faktur = $(this).attr('no_faktur');
            openModal("Penetapan Cabang Kena Pajak (PKP)");
            $content.load(`/penjualan/${no_faktur}/setpkp`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Visit Pelanggan
        $(document).on('click', '.btnVisit', function(e) {
            e.preventDefault();
            const no_faktur = $(this).attr('no_faktur');
            openModal("Input Visit Pelanggan");
            $content.load(`/visitpelanggan/${no_faktur}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

    });
</script>
@endpush
