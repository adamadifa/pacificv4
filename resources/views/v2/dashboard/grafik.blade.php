@extends('layouts.app')
@section('titlepage', 'Grafik Cash In')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <style>
        .stat-card-white {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease;
        }
        .stat-card-white:hover {
            transform: translateY(-2px);
        }
        .apexcharts-tooltip {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
        }
        .dark .apexcharts-tooltip {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        .dark .apexcharts-tooltip-title {
            background: #1e293b !important;
            border-bottom: 1px solid #334155 !important;
            color: #f8fafc !important;
        }
    </style>
@endsection

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Grafik Cash In</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- FILTER CONTROL CARD (COMPACT NO-LABEL WITH ICONS & ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 shadow-2xs transition-colors duration-200">
        <form id="formFilterGrafik" class="grid grid-cols-1 md:grid-cols-12 gap-2.5 items-center">
            
            <!-- Mode Tampilan (Segmented Pill Control, No-Label, H-[34px], Rounded-lg) -->
            <div class="md:col-span-12 lg:col-span-4">
                <div class="grid grid-cols-3 p-0.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 h-[34px] text-xs font-bold">
                    <label class="cursor-pointer text-center flex items-center justify-center m-0">
                        <input type="radio" name="mode" value="bulan" class="peer sr-only" checked>
                        <span class="w-full h-full py-1 px-2 rounded-md text-slate-500 dark:text-slate-400 peer-checked:bg-white dark:peer-checked:bg-slate-900 peer-checked:text-brand-600 dark:peer-checked:text-brand-400 peer-checked:shadow-2xs transition-all flex items-center justify-center gap-1.5 text-xs font-bold">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>By Bulan</span>
                        </span>
                    </label>
                    <label class="cursor-pointer text-center flex items-center justify-center m-0">
                        <input type="radio" name="mode" value="range_bulan" class="peer sr-only">
                        <span class="w-full h-full py-1 px-2 rounded-md text-slate-500 dark:text-slate-400 peer-checked:bg-white dark:peer-checked:bg-slate-900 peer-checked:text-brand-600 dark:peer-checked:text-brand-400 peer-checked:shadow-2xs transition-all flex items-center justify-center gap-1.5 text-xs font-bold">
                            <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                            <span>Range</span>
                        </span>
                    </label>
                    <label class="cursor-pointer text-center flex items-center justify-center m-0">
                        <input type="radio" name="mode" value="tahun" class="peer sr-only">
                        <span class="w-full h-full py-1 px-2 rounded-md text-slate-500 dark:text-slate-400 peer-checked:bg-white dark:peer-checked:bg-slate-900 peer-checked:text-brand-600 dark:peer-checked:text-brand-400 peer-checked:shadow-2xs transition-all flex items-center justify-center gap-1.5 text-xs font-bold">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>By Tahun</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Filter Tahun (Mode: By Bulan, No-Label, Icon, H-[34px], Rounded-lg) -->
            <div class="md:col-span-6 lg:col-span-2" id="wrapTahun">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="tahun" id="tahun" class="w-full h-[34px] pl-8 pr-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @for ($t = date('Y'); $t >= $start_year; $t--)
                            <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>Tahun {{ $t }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Filter Bulan (Mode: By Bulan, No-Label, Icon, H-[34px], Rounded-lg) -->
            <div class="md:col-span-6 lg:col-span-2" id="wrapBulan">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="bulan" id="bulan" class="w-full h-[34px] pl-8 pr-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        <option value="">Semua Bulan (12 Bln)</option>
                        @foreach ($list_bulan as $b)
                            <option value="{{ $b['kode_bulan'] }}">{{ $b['nama_bulan'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filter Range Bulan (Mode: Range Bulan - Dynamic Wrap, No-Label, Icon, H-[34px], Rounded-lg) -->
            <div class="md:col-span-12 lg:col-span-4 hidden" id="wrapRangeBulan">
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative flex gap-1">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-arrow-down" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="dari_bulan" id="dari_bulan" class="w-full h-[34px] pl-8 pr-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                @foreach ($list_bulan as $b)
                                    <option value="{{ $b['kode_bulan'] }}" {{ $b['kode_bulan'] == 1 ? 'selected' : '' }}>
                                        {{ substr($b['nama_bulan'], 0, 3) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <select name="dari_tahun" id="dari_tahun" class="w-20 h-[34px] px-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @for ($t = date('Y'); $t >= $start_year; $t--)
                                <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="relative flex gap-1">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-arrow-up" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="sampai_bulan" id="sampai_bulan" class="w-full h-[34px] pl-8 pr-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                @foreach ($list_bulan as $b)
                                    <option value="{{ $b['kode_bulan'] }}" {{ $b['kode_bulan'] == (int) date('m') ? 'selected' : '' }}>
                                        {{ substr($b['nama_bulan'], 0, 3) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <select name="sampai_tahun" id="sampai_tahun" class="w-20 h-[34px] px-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @for ($t = date('Y'); $t >= $start_year; $t--)
                                <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Cabang (No-Label, Icon, Select2, H-[34px], Rounded-lg) -->
            <div class="md:col-span-6 lg:col-span-2">
                <div class="relative select2-icon-wrapper">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang" class="w-full h-[34px] pl-8 pr-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 select2">
                        @hasanyrole($roles_show_cabang)
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        @else
                            <option value="{{ auth()->user()->kode_cabang }}">{{ auth()->user()->kode_cabang }}</option>
                        @endhasanyrole
                    </select>
                </div>
            </div>

            <!-- Tombol Aksi Muat Data (H-[34px], Rounded-lg) -->
            <div class="md:col-span-6 lg:col-span-2">
                <button type="button" id="btnTampilkan" class="w-full h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white font-bold text-xs shadow-2xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>Muat Data</span>
                </button>
            </div>

        </form>
    </div>

    <!-- 4 KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. Total Cash In -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[128px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] transition-all duration-300 pointer-events-none">
                    <i data-lucide="wallet" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <span id="kpiPeriodeBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20">
                            Tahun {{ date('Y') }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Cash In</p>
                        <div id="kpiTotalCashIn" class="text-2xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp 0
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Histori Bayar Penjualan</p>
                    </div>
                </div>
            </div>

            <!-- 2. Rata-rata Cash In -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[128px] border-b lg:border-b-0 lg:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] transition-all duration-300 pointer-events-none">
                    <i data-lucide="calculator" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="calculator" class="w-4 h-4"></i>
                        </div>
                        <span id="kpiRataRataLabel" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20">
                            Rata-rata / Bulan
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Rata-rata Cash In</p>
                        <div id="kpiRataRata" class="text-2xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp 0
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Rerata periode aktif</p>
                    </div>
                </div>
            </div>

            <!-- 3. Top Cabang -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[128px] border-b sm:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-emerald-300/[0.08] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-emerald-300/[0.12] group-hover:text-emerald-300/[0.18] transition-all duration-300 pointer-events-none">
                    <i data-lucide="building-2" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider">
                            Top Cabang
                        </span>
                    </div>
                    <div class="mt-3">
                        <p id="kpiTopCabangNama" class="text-xs font-bold text-white truncate leading-none">-</p>
                        <div id="kpiTopCabangTotal" class="text-2xl font-black text-emerald-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp 0
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Kontributor terbesar</p>
                    </div>
                </div>
            </div>

            <!-- 4. Top Salesman -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-amber-300/[0.08] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-amber-300/[0.12] group-hover:text-amber-300/[0.18] transition-all duration-300 pointer-events-none">
                    <i data-lucide="trophy" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs">
                            <i data-lucide="trophy" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase tracking-wider">
                            Top Sales
                        </span>
                    </div>
                    <div class="mt-3">
                        <p id="kpiTopSalesNama" class="text-xs font-bold text-white truncate leading-none">-</p>
                        <div id="kpiTopSalesTotal" class="text-2xl font-black text-amber-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp 0
                        </div>
                        <p id="kpiTopSalesCabang" class="text-[11px] text-white/55 leading-none mt-1 truncate">Salesman berprestasi</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CHART 1: TREN CASH IN KESELURUHAN -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs relative overflow-hidden transition-colors duration-200">
        <!-- Loading Overlay -->
        <div id="loadingTren" class="hidden absolute inset-0 z-20 backdrop-blur-xs bg-white/75 dark:bg-slate-900/75 flex flex-col items-center justify-center gap-2">
            <div class="w-7 h-7 border-3 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Memuat grafik tren...</span>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 id="titleTrenChart" class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4 text-brand-600"></i>
                    <span>Tren Cash In Keseluruhan</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Perkembangan realisasi Cash In berdasarkan pembayaran penjualan</p>
            </div>
            <span id="badgeSubTitleTren" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-100 dark:border-brand-900 font-mono-num">
                <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                <span>Realisasi Cash In</span>
            </span>
        </div>

        <div id="chartTrenCashIn" class="w-full min-h-[340px]"></div>
    </div>

    <!-- CHART 2: TREN CASH IN PER SALESMAN (LINE CHART) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs hover:shadow-xs relative overflow-hidden transition-all duration-200">
        <!-- Loading Overlay -->
        <div id="loadingSalesLine" class="hidden absolute inset-0 z-20 backdrop-blur-xs bg-white/75 dark:bg-slate-900/75 flex flex-col items-center justify-center gap-2">
            <div class="w-7 h-7 border-3 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Memuat tren per sales...</span>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3.5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="line-chart" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                        <span>Tren Cash In Per Salesman</span>
                    </h3>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                        <span id="badgeSalesCount">Multi-Line Realisasi</span>
                    </span>
                </div>
                <p id="subTitleSalesLine" class="text-xs text-slate-400 mt-1">Perbandingan tren realisasi Cash In antar salesman sepanjang periode</p>
            </div>

            <!-- Toolbar Controls -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Quick Filter Actions -->
                <div class="flex items-center p-0.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 h-[34px] text-xs font-bold">
                    <button type="button" id="btnFocusTop3" class="px-2.5 h-full rounded-md text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-white dark:hover:bg-slate-900 transition flex items-center gap-1 cursor-pointer">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span>Fokus Top 3</span>
                    </button>
                    <button type="button" id="btnResetSeries" class="px-2.5 h-full rounded-md text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-white dark:hover:bg-slate-900 transition flex items-center gap-1 cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span>Semua</span>
                    </button>
                </div>

                <!-- Limit Dropdown (H-[34px], Rounded-lg, Prefix Icon) -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    </div>
                    <select id="limit_sales" class="h-[34px] pl-8 pr-2.5 rounded-lg bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer">
                        <option value="5">Top 5 Sales</option>
                        <option value="10" selected>Top 10 Sales</option>
                        <option value="15">Top 15 Sales</option>
                        <option value="all">Semua Sales</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Micro Info Hint -->
        <div class="flex items-center justify-between gap-2 mb-2 text-xs text-slate-400">
            <div class="flex items-center gap-1.5">
                <i data-lucide="mouse-pointer-click" class="w-3.5 h-3.5 text-brand-500 shrink-0"></i>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Arahkan kursor pada legenda untuk highlight garis, atau klik nama salesman untuk sembunyikan/tampilkan.</span>
            </div>
        </div>

        <div id="chartSalesCashIn" class="w-full min-h-[390px]"></div>
    </div>

    <!-- ROW BREAKDOWN: CASH IN CABANG & LEADERBOARD SALESMAN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- KOLOM KIRI: CASH IN CABANG -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs relative overflow-hidden flex flex-col justify-between transition-colors duration-200">
            <!-- Loading Overlay -->
            <div id="loadingCabang" class="hidden absolute inset-0 z-20 backdrop-blur-xs bg-white/75 dark:bg-slate-900/75 flex flex-col items-center justify-center gap-2">
                <div class="w-7 h-7 border-3 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Memuat data cabang...</span>
            </div>

            <div>
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="building-2" class="w-4 h-4 text-emerald-500"></i>
                            <span>Cash In Cabang</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">Perbandingan Cash In per wilayah cabang</p>
                    </div>

                    <!-- Toggle Pills: Grafik vs Tabel (Rounded-lg, H-[34px]) -->
                    <div class="flex items-center p-0.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-bold" id="cabangTabsToggle">
                        <button type="button" data-target="tabCabangGrafik" class="tab-btn-cabang px-2.5 py-1 rounded-md bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs transition cursor-pointer">
                            Grafik
                        </button>
                        <button type="button" data-target="tabCabangTabel" class="tab-btn-cabang px-2.5 py-1 rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition cursor-pointer">
                            Tabel
                        </button>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div id="tabCabangGrafik" class="tab-content-cabang">
                    <div id="chartCabangCashIn" class="w-full min-h-[380px]"></div>
                </div>

                <div id="tabCabangTabel" class="tab-content-cabang hidden">
                    <div class="overflow-x-auto overflow-y-auto max-h-[380px] scrollbar-thin">
                        <table class="w-full text-left font-sans text-xs">
                            <thead class="sticky top-0 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="py-2.5 px-3 w-10 text-center">#</th>
                                    <th class="py-2.5 px-3">Cabang</th>
                                    <th class="py-2.5 px-3 text-right">Total Cash In</th>
                                    <th class="py-2.5 px-3 text-center w-36">Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyCabang" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                <!-- Injected via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: LEADERBOARD SALESMAN -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs relative overflow-hidden flex flex-col justify-between transition-colors duration-200">
            <!-- Loading Overlay -->
            <div id="loadingSalesTable" class="hidden absolute inset-0 z-20 backdrop-blur-xs bg-white/75 dark:bg-slate-900/75 flex flex-col items-center justify-center gap-2">
                <div class="w-7 h-7 border-3 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Memuat ranking sales...</span>
            </div>

            <div>
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="trophy" class="w-4 h-4 text-amber-500"></i>
                            <span>Leaderboard Salesman</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">Peringkat kontribusi total perolehan salesman</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                        <span>Ranking Total</span>
                    </span>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-[380px] scrollbar-thin">
                    <table class="w-full text-left font-sans text-xs">
                        <thead class="sticky top-0 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="py-2.5 px-3 w-12 text-center">Rank</th>
                                <th class="py-2.5 px-3">Salesman</th>
                                <th class="py-2.5 px-3">Cabang</th>
                                <th class="py-2.5 px-3 text-right">Total Cash In</th>
                                <th class="py-2.5 px-3 text-center w-32">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySales" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            <!-- Injected via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // Initialize Select2 for Cabang
    if ($.fn.select2) {
        $('#kode_cabang').select2({
            width: '100%'
        });
    }

    // Number format helper functions
    function formatRupiah(val) {
        if (!val || isNaN(val)) return 'Rp 0';
        return 'Rp ' + Math.round(val).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatShortRupiah(val) {
        if (!val || isNaN(val)) return 'Rp 0';
        const abs = Math.abs(val);
        if (abs >= 1.0e+12) return (val / 1.0e+12).toFixed(1) + " T";
        if (abs >= 1.0e+9)  return (val / 1.0e+9).toFixed(1) + " M";
        if (abs >= 1.0e+6)  return (val / 1.0e+6).toFixed(1) + " Jt";
        if (abs >= 1.0e+3)  return (val / 1.0e+3).toFixed(1) + " Rb";
        return val;
    }

    // Tab toggle Cabang (Grafik vs Tabel)
    $('.tab-btn-cabang').click(function() {
        const target = $(this).data('target');
        $('.tab-btn-cabang').removeClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs').addClass('text-slate-500 dark:text-slate-400');
        $(this).addClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs').removeClass('text-slate-500 dark:text-slate-400');
        
        $('.tab-content-cabang').addClass('hidden');
        $('#' + target).removeClass('hidden');

        if (target === 'tabCabangGrafik' && chartCabang) {
            chartCabang.render();
        }
    });

    // Toggle wrap filter when switching mode
    $('input[name="mode"]').change(function() {
        const mode = $(this).val();
        if (mode === 'tahun') {
            $('#wrapTahun').addClass('hidden');
            $('#wrapBulan').addClass('hidden');
            $('#wrapRangeBulan').addClass('hidden');
        } else if (mode === 'range_bulan') {
            $('#wrapTahun').addClass('hidden');
            $('#wrapBulan').addClass('hidden');
            $('#wrapRangeBulan').removeClass('hidden');
        } else {
            $('#wrapTahun').removeClass('hidden');
            $('#wrapBulan').removeClass('hidden');
            $('#wrapRangeBulan').addClass('hidden');
        }
        loadDataGrafik();
    });

    // Chart instances
    let chartTren = null;
    let chartCabang = null;
    let chartSales = null;

    // Helper to get dark mode active state
    function isDarkTheme() {
        return document.documentElement.classList.contains('dark');
    }

    // Load data from server
    function loadDataGrafik() {
        const mode = $('input[name="mode"]:checked').val();
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const dari_bulan = $('#dari_bulan').val();
        const dari_tahun = $('#dari_tahun').val();
        const sampai_bulan = $('#sampai_bulan').val();
        const sampai_tahun = $('#sampai_tahun').val();
        const kode_cabang = $('#kode_cabang').val();
        const limit_sales = $('#limit_sales').val() || '10';

        // Show loading overlays
        $('#loadingTren, #loadingCabang, #loadingSalesLine, #loadingSalesTable').removeClass('hidden');
        $('#btnTampilkan').prop('disabled', true).addClass('opacity-75');

        $.ajax({
            type: "POST",
            url: "{{ route('dashboard.grafik.getcashin') }}",
            data: {
                _token: "{{ csrf_token() }}",
                mode: mode,
                tahun: tahun,
                bulan: bulan,
                dari_bulan: dari_bulan,
                dari_tahun: dari_tahun,
                sampai_bulan: sampai_bulan,
                sampai_tahun: sampai_tahun,
                kode_cabang: kode_cabang,
                limit_sales: limit_sales
            },
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    renderKPI(res.kpi);
                    renderChartTren(res.tren);
                    renderChartCabang(res.cabang);
                    renderChartSales(res.sales);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading grafik data:", error);
            },
            complete: function() {
                $('#loadingTren, #loadingCabang, #loadingSalesLine, #loadingSalesTable').addClass('hidden');
                $('#btnTampilkan').prop('disabled', false).removeClass('opacity-75');
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
        });
    }

    // Render KPI metrics
    function renderKPI(kpi) {
        $('#kpiPeriodeBadge').text(kpi.periode_title);
        $('#kpiTotalCashIn').text(kpi.total_cashin_formatted);
        $('#kpiRataRataLabel').text(kpi.rata_rata_label);
        $('#kpiRataRata').text(kpi.rata_rata_formatted);

        if (kpi.top_cabang) {
            $('#kpiTopCabangNama').text(kpi.top_cabang.nama_cabang);
            $('#kpiTopCabangTotal').text(kpi.top_cabang.total_formatted);
        } else {
            $('#kpiTopCabangNama').text('-');
            $('#kpiTopCabangTotal').text('Rp 0');
        }

        if (kpi.top_sales) {
            $('#kpiTopSalesNama').text(kpi.top_sales.nama_salesman);
            $('#kpiTopSalesCabang').text(kpi.top_sales.nama_cabang);
            $('#kpiTopSalesTotal').text(kpi.top_sales.total_formatted);
        } else {
            $('#kpiTopSalesNama').text('-');
            $('#kpiTopSalesCabang').text('Salesman berprestasi');
            $('#kpiTopSalesTotal').text('Rp 0');
        }
    }

    // Render Chart 1: Tren Cash In Keseluruhan
    function renderChartTren(data) {
        $('#titleTrenChart span').text(data.title);

        const dark = isDarkTheme();
        const options = {
            series: [{
                name: 'Cash In',
                data: data.series
            }],
            chart: {
                type: 'area',
                height: 340,
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
            colors: ['#2563eb'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            markers: {
                size: 4,
                colors: ['#2563eb'],
                strokeColors: dark ? '#0f172a' : '#fff',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    style: {
                        colors: dark ? '#94a3b8' : '#64748b',
                        fontSize: '11px',
                        fontWeight: 600
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
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
            tooltip: {
                theme: dark ? 'dark' : 'light',
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartTren) {
            chartTren.updateOptions(options);
        } else {
            chartTren = new ApexCharts(document.querySelector("#chartTrenCashIn"), options);
            chartTren.render();
        }
    }

    // Render Chart 2: Cash In Cabang (Bar Chart & Table)
    function renderChartCabang(data) {
        // Render Tabel Cabang
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                tbody += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-2.5 px-3 font-mono-num font-bold text-slate-400 text-center">${idx + 1}</td>
                        <td class="py-2.5 px-3 font-semibold text-slate-800 dark:text-slate-100">${item.nama_cabang}</td>
                        <td class="py-2.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">${item.formatted}</td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center justify-between text-[11px] font-mono-num font-semibold text-slate-500 mb-1">
                                <span>${item.persentase}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-brand-500 h-full rounded-full transition-all duration-500" style="width: ${item.persentase}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody = '<tr><td colspan="4" class="text-center py-6 text-slate-400">Tidak ada data ditemukan</td></tr>';
        }
        $('#tbodyCabang').html(tbody);

        // Render ApexChart Cabang
        const dark = isDarkTheme();
        const options = {
            series: [{
                name: 'Cash In',
                data: data.series
            }],
            chart: {
                type: 'bar',
                height: 380,
                fontFamily: 'inherit',
                background: 'transparent',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '55%',
                    distributed: false
                }
            },
            colors: ['#10b981'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    rotate: -45,
                    style: {
                        colors: dark ? '#94a3b8' : '#64748b',
                        fontSize: '11px',
                        fontWeight: 600
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
                    style: {
                        colors: dark ? '#94a3b8' : '#64748b',
                        fontSize: '11px',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                borderColor: dark ? '#334155' : '#f1f5f9',
                strokeDashArray: 4
            },
            tooltip: {
                theme: dark ? 'dark' : 'light',
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartCabang) {
            chartCabang.updateOptions(options);
        } else {
            chartCabang = new ApexCharts(document.querySelector("#chartCabangCashIn"), options);
            chartCabang.render();
        }
    }

    // Render Chart 3: Salesman Multi-Line Chart & Leaderboard
    function renderChartSales(data) {
        if (data.is_limited) {
            $('#subTitleSalesLine').text('Tren pergerakan realisasi Cash In top salesman sepanjang periode');
        } else {
            $('#subTitleSalesLine').text('Tren pergerakan realisasi Cash In salesman pada cabang sepanjang periode');
        }

        // Render Tabel Leaderboard Salesman
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                let rankBadge = '';
                if (idx === 0) {
                    rankBadge = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-400 text-slate-900 font-black text-xs shadow-xs">1</span>';
                } else if (idx === 1) {
                    rankBadge = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-300 text-slate-800 font-black text-xs shadow-xs">2</span>';
                } else if (idx === 2) {
                    rankBadge = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-600 text-white font-black text-xs shadow-xs">3</span>';
                } else {
                    rankBadge = `<span class="font-mono-num font-bold text-slate-400 text-xs">${idx + 1}</span>`;
                }

                tbody += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-2.5 px-3 text-center">${rankBadge}</td>
                        <td class="py-2.5 px-3 font-bold text-slate-800 dark:text-slate-100">${item.nama_salesman}</td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                ${item.nama_cabang}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num font-bold text-amber-600 dark:text-amber-400">${item.formatted}</td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center justify-between text-[11px] font-mono-num font-semibold text-slate-500 mb-1">
                                <span>${item.persentase}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: ${item.persentase}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody = '<tr><td colspan="5" class="text-center py-6 text-slate-400">Tidak ada data ditemukan</td></tr>';
        }
        $('#tbodySales').html(tbody);

        // Render ApexChart Multi-Line Sales
        const dark = isDarkTheme();
        const lineSeries = (data.line_series && data.line_series.length > 0) ? data.line_series : [];
        
        // Update dynamic series badge count
        if (lineSeries.length > 0) {
            $('#badgeSalesCount').text(lineSeries.length + ' Salesman Terpilih');
        } else {
            $('#badgeSalesCount').text('0 Salesman');
        }

        const options = {
            series: lineSeries,
            chart: {
                type: 'line',
                height: 390,
                fontFamily: 'inherit',
                background: 'transparent',
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 0,
                    blur: 6,
                    opacity: 0.12
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 850,
                    animateGradually: {
                        enabled: true,
                        delay: 130
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 400
                    }
                }
            },
            colors: [
                '#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899',
                '#06b6d4', '#f97316', '#14b8a6', '#6366f1', '#e11d48',
                '#0284c7', '#84cc16', '#d97706', '#9333ea', '#64748b'
            ],
            stroke: {
                curve: 'smooth',
                width: 2.3,
                lineCap: 'round'
            },
            markers: {
                size: 0, // Clean lines by default without dot clutter
                strokeWidth: 2.5,
                strokeColors: dark ? '#0f172a' : '#fff',
                hover: {
                    size: 6,
                    sizeOffset: 3
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.15,
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'darken',
                        value: 0.35,
                    }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '11px',
                fontWeight: 600,
                labels: {
                    colors: dark ? '#cbd5e1' : '#475569'
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12,
                    offsetX: -3
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 6
                },
                onItemClick: {
                    toggleDataSeries: true
                },
                onItemHover: {
                    highlightDataSeries: true
                }
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    style: {
                        colors: dark ? '#94a3b8' : '#64748b',
                        fontSize: '11px',
                        fontWeight: 600
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
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
                padding: { top: 10, right: 20, bottom: 5, left: 10 }
            },
            tooltip: {
                theme: dark ? 'dark' : 'light',
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartSales) {
            chartSales.updateOptions(options, true, true);
        } else {
            chartSales = new ApexCharts(document.querySelector("#chartSalesCashIn"), options);
            chartSales.render();
        }
    }

    // Quick Action: Fokus Top 3 Sales
    $('#btnFocusTop3').click(function() {
        if (!chartSales || !chartSales.w || !chartSales.w.globals) return;
        const seriesNames = chartSales.w.globals.seriesNames;
        seriesNames.forEach((name, idx) => {
            if (idx < 3) {
                chartSales.showSeries(name);
            } else {
                chartSales.hideSeries(name);
            }
        });
    });

    // Quick Action: Reset & Tampilkan Semua Sales
    $('#btnResetSeries').click(function() {
        if (!chartSales || !chartSales.w || !chartSales.w.globals) return;
        const seriesNames = chartSales.w.globals.seriesNames;
        seriesNames.forEach((name) => {
            chartSales.showSeries(name);
        });
    });

    // Event listeners
    $('#btnTampilkan').click(function() {
        loadDataGrafik();
    });

    $('#tahun, #bulan, #dari_bulan, #dari_tahun, #sampai_bulan, #sampai_tahun, #kode_cabang, #limit_sales').change(function() {
        loadDataGrafik();
    });

    // Theme toggle observer: update charts when mode changes
    $('#navThemeToggleBtn').click(function() {
        setTimeout(function() {
            if (chartTren) chartTren.updateOptions({ theme: { mode: isDarkTheme() ? 'dark' : 'light' } });
            if (chartCabang) chartCabang.updateOptions({ theme: { mode: isDarkTheme() ? 'dark' : 'light' } });
            if (chartSales) chartSales.updateOptions({ theme: { mode: isDarkTheme() ? 'dark' : 'light' } });
        }, 50);
    });

    // Initial load
    loadDataGrafik();
});
</script>
@endpush
