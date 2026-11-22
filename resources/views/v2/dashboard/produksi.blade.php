@extends('layouts.app')
@section('titlepage', 'Dashboard Produksi')

@php
    $tahunSekarang = date('Y');
    $bulanSekarang = date('m');

    $total_produksi_tahun = \App\Models\Detailmutasiproduksi::join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi')
        ->where('jenis_mutasi', 'BPBJ')
        ->whereYear('tanggal_mutasi', $tahunSekarang)
        ->sum('jumlah') ?? 0;

    $total_produksi_bulan = \App\Models\Detailmutasiproduksi::join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi')
        ->where('jenis_mutasi', 'BPBJ')
        ->whereYear('tanggal_mutasi', $tahunSekarang)
        ->whereMonth('tanggal_mutasi', $bulanSekarang)
        ->sum('jumlah') ?? 0;

    $bulanBerjalan = max(1, (int) date('n'));
    $rata_produksi_bulan = round($total_produksi_tahun / $bulanBerjalan);

    $total_produk_aktif = \App\Models\Detailmutasiproduksi::distinct('kode_produk')->count('kode_produk') ?? 0;
@endphp

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Produksi</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- 4 KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS & WATERMARK) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. Total Output Produksi (Tahun Ini) -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-3 bottom-2 pointer-events-none select-none text-white/[0.08] group-hover:text-white/[0.14] transition-colors">
                    <i data-lucide="factory" class="w-16 h-16"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20 font-mono-num">
                            Tahun {{ $tahunSekarang }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Output Produksi</p>
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ number_format($total_produksi_tahun) }} <span class="text-xs font-semibold text-white/70 font-sans">Dus</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Realisasi BPBJ tahun berjalan</p>
                    </div>
                </div>
            </div>

            <!-- 2. Produksi Bulan Ini -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 lg:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs">
                            <i data-lucide="package-check" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider">
                            Bulan Ini
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Produksi Bulan Ini</p>
                        <div class="text-2xl font-black text-emerald-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ number_format($total_produksi_bulan) }} <span class="text-xs font-semibold text-white/70 font-sans">Dus</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Output periode {{ date('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- 3. Rata-rata Output Bulanan -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b sm:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-400/20 text-sky-300 flex items-center justify-center border border-sky-400/30 shrink-0 shadow-xs">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-400/20 text-sky-300 border border-sky-400/30 uppercase tracking-wider">
                            Rata-Rata
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Rata-rata / Bulan</p>
                        <div class="text-2xl font-black text-sky-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ number_format($rata_produksi_bulan) }} <span class="text-xs font-semibold text-white/70 font-sans">Dus</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Kapasitas output bulanan</p>
                    </div>
                </div>
            </div>

            <!-- 4. Varian Produk Aktif -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase tracking-wider">
                            SKU
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Varian Produk Aktif</p>
                        <div class="text-2xl font-black text-amber-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ $total_produk_aktif }} <span class="text-xs font-semibold text-white/70 font-sans">Produk</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Dihasilkan di lini produksi</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- UPPER ROW: PERMINTAAN PRODUKSI (4 COLS) & REKAP HASIL PRODUKSI (8 COLS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">

        <!-- KOLOM KIRI: PERMINTAAN PRODUKSI & REALISASI (4 COLS / lg:col-span-4) -->
        <div class="lg:col-span-4 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Permintaan Produksi -->
                <div class="flex items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0 shadow-2xs">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Permintaan Produksi</h4>
                            <span class="text-[11px] text-slate-400 leading-none">Rasio Realisasi vs OMAN</span>
                        </div>
                    </div>
                </div>

                <!-- Compact Filter Controls (No-Label, Icon Prefix, h-[34px], rounded-lg) -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <!-- Bulan Filter -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan_realisasi" id="bulan_realisasi" class="w-full h-[34px] pl-8 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            @foreach ($list_bulan as $b)
                                <option value="{{ $b['kode_bulan'] }}" {{ (int)$b['kode_bulan'] == (int)date('m') ? 'selected' : '' }}>
                                    {{ $b['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>

                    <!-- Tahun Filter -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun_realisasi" id="tahun_realisasi" class="w-full h-[34px] pl-8 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>
                                    Tahun {{ $t }}
                                </option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>
                </div>

                <!-- AJAX Target Container: Realisasi Permintaan Produksi -->
                <div id="loadrealisasipermintaanproduksi" class="flex-1">
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <div class="w-7 h-7 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                        <span class="text-xs">Memuat data permintaan produksi...</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: REKAP HASIL PRODUKSI 12 BULAN (8 COLS / lg:col-span-8) -->
        <div class="lg:col-span-8 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Rekap Hasil Produksi -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-100 dark:border-sky-900 shrink-0 shadow-2xs">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Hasil Produksi (BPBJ)</h4>
                            <span class="text-[11px] text-slate-400 leading-none">Matriks Distribusi Bulanan & Akumulasi Tahunan</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Tahun Filter Rekap (No-Label, Icon Prefix, h-[34px], rounded-lg) -->
                        <div class="relative w-36">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="tahun_hasil_produksi" id="tahun_hasil_produksi" class="w-full h-[34px] pl-8 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>
                                        Tahun {{ $t }}
                                    </option>
                                @endfor
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3 h-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matrix Table Container with Sticky Product Column & Scroll -->
                <div class="flex-1 overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800 scrollbar-thin max-h-[460px] overflow-y-auto relative">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="sticky top-0 z-30">
                            <tr class="bg-[#284c9a] text-white text-[11px]">
                                <th rowspan="2" class="sticky left-0 bg-[#284c9a] z-40 px-3 py-2.5 font-bold uppercase tracking-wider text-left border-r border-white/20 whitespace-nowrap min-w-[125px] shadow-[2px_0_6px_-2px_rgba(0,0,0,0.25)]">
                                    Produk
                                </th>
                                <th colspan="12" class="px-2 py-1.5 text-center font-bold uppercase tracking-wider text-white/90 border-b border-white/20 text-[10px]">
                                    Distribusi Bulan Produksi
                                </th>
                                <th rowspan="2" class="sticky right-0 bg-[#1e3a78] z-40 px-3 py-2.5 font-bold uppercase tracking-wider text-right border-l border-white/20 whitespace-nowrap min-w-[105px] shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.25)]">
                                    Total (Thn)
                                </th>
                            </tr>
                            <tr class="bg-[#284c9a] text-white text-[10px]">
                                @for ($i = 1; $i <= 12; $i++)
                                    @php
                                        $isBulanIni = (int)$i === (int)date('n');
                                    @endphp
                                    <th class="px-2 py-1.5 text-center font-bold {{ $isBulanIni ? 'bg-white/15 text-amber-300' : 'text-white/90' }} border-r border-white/10 min-w-[66px]">
                                        <div class="flex items-center justify-center gap-1">
                                            <span>{{ $nama_bulan_singkat[$i] }}</span>
                                            @if ($isBulanIni)
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            @endif
                                        </div>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody id="loadrekaphasilproduksi" class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            <tr>
                                <td colspan="14" class="py-12 text-center text-slate-400">
                                    <div class="w-6 h-6 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                                    <span>Memuat matriks hasil produksi...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <!-- LOWER ROW: GRAFIK TREN HASIL PRODUKSI (FULL 12 COLS) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200">
        
        <!-- Header Grafik -->
        <div class="flex flex-wrap items-center justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900 shrink-0 shadow-2xs">
                    <i data-lucide="line-chart" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Grafik Tren Hasil Produksi</h4>
                    <span class="text-[11px] text-slate-400 leading-none">Fluktuasi output produksi per produk sepanjang tahun</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-mono-num">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Output BPBJ
                </span>
            </div>
        </div>

        <!-- Fixed chart container (ApexCharts renders directly here, no HTML inject) -->
        <div id="loadingGrafikProduksi" class="flex flex-col items-center justify-center py-12 text-slate-400">
            <div class="w-8 h-8 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2"></div>
            <span class="text-xs">Memuat grafik hasil produksi...</span>
        </div>
        <div id="chartHasilProduksi" class="w-full" style="min-height:380px;display:none;"></div>
        <div id="grafikErrorProduksi" class="hidden p-6 text-center text-rose-500 text-xs">Gagal memuat grafik hasil produksi.</div>

    </div>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // Stored chart instance so we can destroy/re-render on year change
    var chartHasilProduksiInstance = null;

    // Helper: detect dark theme
    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

    // Helper: format angka menjadi singkat (k / M / B)
    function fmtQty(val) {
        if (val >= 1000000) return (val / 1000000).toFixed(1) + 'M';
        if (val >= 1000)    return (val / 1000).toFixed(1) + 'k';
        return val;
    }

    // Palet warna untuk banyak produk
    var productColors = [
        '#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6',
        '#06b6d4','#f97316','#ec4899','#14b8a6','#a855f7',
        '#84cc16','#0ea5e9','#f43f5e','#22c55e','#eab308'
    ];

    // Function to load Realisasi Permintaan Produksi
    function loadrealisasipermintaanproduksi() {
        const bulan = $("#bulan_realisasi").val();
        const tahun = $("#tahun_realisasi").val();
        
        $("#loadrealisasipermintaanproduksi").html(`
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <div class="w-7 h-7 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                <span class="text-xs">Memuat data permintaan produksi...</span>
            </div>
        `);

        $.ajax({
            type: "POST",
            url: "{{ route('permintaanproduksi.getrealisasi') }}",
            data: {
                _token: "{{ csrf_token() }}",
                bulan: bulan,
                tahun: tahun
            },
            cache: false,
            success: function(respond) {
                $("#loadrealisasipermintaanproduksi").html(respond);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#loadrealisasipermintaanproduksi").html(`
                    <div class="p-4 rounded-lg bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-center">
                        <p class="text-xs font-semibold text-rose-600 dark:text-rose-400">Gagal memuat data permintaan produksi.</p>
                    </div>
                `);
            }
        });
    }

    // Function to load Rekap Hasil Produksi (Table)
    function loadrekaphasilproduksi() {
        const tahun = $("#tahun_hasil_produksi").val();
        
        $("#loadrekaphasilproduksi").html(`
            <tr>
                <td colspan="14" class="py-12 text-center text-slate-400">
                    <div class="w-6 h-6 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                    <span>Memuat matriks hasil produksi...</span>
                </td>
            </tr>
        `);

        $.ajax({
            type: "POST",
            url: "{{ route('bpbj.getrekaphasilproduksi') }}",
            data: {
                _token: "{{ csrf_token() }}",
                tahun: tahun
            },
            cache: false,
            success: function(respond) {
                $("#loadrekaphasilproduksi").html(respond);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#loadrekaphasilproduksi").html(`
                    <tr>
                        <td colspan="14" class="py-6 text-center text-rose-500 text-xs">
                            Gagal memuat matriks hasil produksi.
                        </td>
                    </tr>
                `);
            }
        });
    }

    // ─── GRAFIK HASIL PRODUKSI (ApexCharts client-side, same style as Cash In) ──
    function loadgrafikhasilproduksi() {
        const tahun = $("#tahun_hasil_produksi").val();
        const dark  = isDark();

        // Show spinner, hide chart + error
        $("#loadingGrafikProduksi").show();
        $("#chartHasilProduksi").hide();
        $("#grafikErrorProduksi").addClass("hidden");

        // Destroy previous instance if exists
        if (chartHasilProduksiInstance) {
            chartHasilProduksiInstance.destroy();
            chartHasilProduksiInstance = null;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('bpbj.getgrafikhasilproduksijson') }}",
            data: {
                _token: "{{ csrf_token() }}",
                tahun: tahun
            },
            dataType: "json",
            cache: false,
            success: function(res) {
                $("#loadingGrafikProduksi").hide();

                if (!res.status || !res.series || res.series.length === 0) {
                    $("#chartHasilProduksi").hide();
                    $("#grafikErrorProduksi")
                        .removeClass("hidden")
                        .html('<i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i><p>Tidak ada data produksi untuk tahun ' + tahun + '</p>');
                    if (window.lucide) { lucide.createIcons(); }
                    return;
                }

                $("#chartHasilProduksi").show();

                // Assign colors from palette (cycle if > 15 produk)
                var colors = res.series.map(function(_, i) {
                    return productColors[i % productColors.length];
                });

                var options = {
                    series: res.series,
                    chart: {
                        type: 'line',
                        height: 390,
                        fontFamily: 'inherit',
                        background: 'transparent',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false
                            }
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 600
                        }
                    },
                    colors: colors,
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 2.5
                    },
                    markers: {
                        size: 3,
                        strokeColors: dark ? '#0f172a' : '#fff',
                        strokeWidth: 2,
                        hover: { size: 6 }
                    },
                    xaxis: {
                        categories: res.categories,
                        labels: {
                            style: {
                                colors: dark ? '#94a3b8' : '#64748b',
                                fontSize: '11px',
                                fontWeight: 600
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks:  { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) { return fmtQty(val); },
                            style: {
                                colors: dark ? '#94a3b8' : '#64748b',
                                fontSize: '11px',
                                fontWeight: 600
                            }
                        }
                    },
                    grid: {
                        borderColor: dark ? '#334155' : '#f1f5f9',
                        strokeDashArray: 4,
                        padding: { top: 10, right: 20, bottom: 0, left: 10 }
                    },
                    legend: {
                        show: true,
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        labels: {
                            colors: dark ? '#94a3b8' : '#64748b'
                        },
                        markers: { width: 10, height: 10, radius: 3 },
                        itemMargin: { horizontal: 8, vertical: 4 }
                    },
                    tooltip: {
                        theme: dark ? 'dark' : 'light',
                        y: {
                            formatter: function(val) {
                                return new Intl.NumberFormat('id-ID').format(val) + ' unit';
                            }
                        }
                    }
                };

                chartHasilProduksiInstance = new ApexCharts(
                    document.querySelector("#chartHasilProduksi"),
                    options
                );
                chartHasilProduksiInstance.render();
            },
            error: function() {
                $("#loadingGrafikProduksi").hide();
                $("#chartHasilProduksi").hide();
                $("#grafikErrorProduksi")
                    .removeClass("hidden")
                    .html("Gagal memuat grafik hasil produksi.");
            }
        });
    }

    // Initial Trigger
    loadrealisasipermintaanproduksi();
    loadrekaphasilproduksi();
    loadgrafikhasilproduksi();

    // Event Listeners
    $("#bulan_realisasi, #tahun_realisasi").change(function() {
        loadrealisasipermintaanproduksi();
    });

    $("#tahun_hasil_produksi").change(function() {
        loadrekaphasilproduksi();
        loadgrafikhasilproduksi();
    });
});
</script>
@endpush
