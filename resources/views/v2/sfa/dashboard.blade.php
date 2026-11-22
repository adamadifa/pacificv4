@extends('layouts.app')
@section('titlepage', 'Dashboard SFA')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Dashboard SFA</span>
    </div>
@endsection

@section('content')
@php
    $namabulan = config('global.nama_bulan');
    $namaBulanPilihan = $namabulan[(int)$bulan] ?? 'Bulan ' . $bulan;

    // Hitung ringkasan metrik statistik
    $totalAktifitasSmm = 0;
    $smmAktifCount = 0;
    foreach ($users as $u) {
        $userTotal = 0;
        for ($k = 1; $k <= $jmlhari; $k++) {
            $userTotal += (int)($u->{"tgl_$k"} ?? 0);
        }
        $totalAktifitasSmm += $userTotal;
        if ($userTotal > 0) {
            $smmAktifCount++;
        }
    }

    $totalAktifitasRsm = 0;
    $rsmAktifCount = 0;
    foreach ($rsm as $r) {
        $rsmTotal = 0;
        for ($k = 1; $k <= $jmlhari; $k++) {
            $rsmTotal += (int)($r->{"tgl_$k"} ?? 0);
        }
        $totalAktifitasRsm += $rsmTotal;
        if ($rsmTotal > 0) {
            $rsmAktifCount++;
        }
    }

    $avgAktifitasHarian = $jmlhari > 0 ? round($totalAktifitasSmm / $jmlhari, 1) : 0;
    $isBulanIni = ((int)$bulan === (int)date('m') && (int)$tahun === (int)date('Y'));
    $todayDate = (int)date('j');
@endphp

<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- STATISTIC CARDS STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Background Radial Glow -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 divide-y sm:divide-y-0 sm:divide-x divide-white/15">
            
            <!-- 1. Total Aktifitas SMM -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="activity" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ number_format($totalAktifitasSmm, 0, ',', '.') }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Total Kunjungan SMM</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Bulan {{ $namaBulanPilihan }}</p>
                    </div>
                </div>
            </div>

            <!-- 2. Personil SMM Aktif -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="users" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ $smmAktifCount }} <span class="text-xs text-white/60 font-normal">/ {{ count($users) }}</span>
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">SMM Beraktifitas</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">{{ count($users) > 0 ? round(($smmAktifCount / count($users)) * 100) : 0 }}% tingkat keaktifan</p>
                    </div>
                </div>
            </div>

            <!-- 3. Rata-rata Aktifitas/Hari -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="trending-up" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-cyan-400/20 text-cyan-300 flex items-center justify-center border border-cyan-400/30 shrink-0 shadow-xs">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ $avgAktifitasHarian }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Rata-rata / Hari</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Total kunjungan harian</p>
                    </div>
                </div>
            </div>

            <!-- 4. Total Aktifitas RSM -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="briefcase" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ number_format($totalAktifitasRsm, 0, ',', '.') }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Total Kunjungan RSM</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">{{ $rsmAktifCount }} dari {{ count($rsm) }} RSM aktif</p>
                    </div>
                </div>
            </div>

            <!-- 5. Personil RSM Terdaftar -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="shield-check" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ count($rsm) }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Regional Sales Manager</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Wilayah regional aktif</p>
                    </div>
                </div>
            </div>

            <!-- 6. Jumlah Hari Periode -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 transition-all duration-300 pointer-events-none">
                    <i data-lucide="calendar" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-lg bg-indigo-400/20 text-indigo-300 flex items-center justify-center border border-indigo-400/30 shrink-0 shadow-xs">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ $jmlhari }} <span class="text-xs text-white/60 font-normal">Hari</span>
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Hari Dalam Periode</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">{{ $tahun }} &bull; {{ $namaBulanPilihan }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN MATRIX CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200">
        
        <!-- CARD HEADER & COMPACT NO-LABEL FILTER -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
            
            <!-- Title & Info -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-500/20 shrink-0 shadow-2xs">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                        Matriks Presensi & Kunjungan Lapangan SFA
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug mt-0.5">
                        Rekap aktivitas harian personil SMM, RSM, dan GM Marketing &bull; Periode {{ $namaBulanPilihan }} {{ $tahun }}
                    </p>
                </div>
            </div>

            <!-- Compact No-Label Filter Form (User Rule 4) -->
            <form action="{{ URL::current() }}" method="GET" class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                
                <!-- Select Bulan (No-Label with Lucide Prefix) -->
                <div class="relative w-36">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="bulan" id="bulan" class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all appearance-none cursor-pointer">
                        <option value="">Pilih Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}"
                                {{ (date('m') == $d['kode_bulan'] && empty(request('bulan'))) || request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Select Tahun (No-Label with Lucide Prefix) -->
                <div class="relative w-28">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="tahun" id="tahun" class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all appearance-none cursor-pointer">
                        <option value="">Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}"
                                {{ (date('Y') == $t && empty(request('tahun'))) || request('tahun') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Submit Button (Uniform h-[34px], rounded-lg) -->
                <button type="submit" class="h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs hover:shadow-xs transition-all active:scale-95 shrink-0">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Tampilkan</span>
                </button>
            </form>

        </div>

        <!-- LEGEND STRIP -->
        <div class="flex items-center justify-between gap-3 flex-wrap mb-3 px-1 text-xs text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-4 h-4 rounded-md bg-emerald-500/20 border border-emerald-500/40 text-emerald-700 dark:text-emerald-300 font-bold font-mono-num text-[10px] flex items-center justify-center">1</span>
                    Ada Kunjungan (Klik untuk rincian)
                </span>
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-4 h-4 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-400 font-mono-num text-[10px] flex items-center justify-center">-</span>
                    Tidak Ada Log
                </span>
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    Hari Ini (Aktif)
                </span>
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-400/80"></span>
                    Hari Minggu
                </span>
            </div>
            <div class="text-[11px] font-mono-num text-slate-400">
                Total {{ count($users) + count($rsm) + count($gm) }} Personil
            </div>
        </div>

        <!-- MATRIX TABLE WRAPPER WITH STICKY HEADERS & STICKY COLUMNS -->
        <div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800 scrollbar-thin max-h-[620px] overflow-y-auto relative">
            <table class="w-full text-xs text-left border-collapse min-w-max">
                
                <!-- STICKY THEAD -->
                <thead class="sticky top-0 z-30 shadow-xs">
                    
                    <!-- Row 1: Group Headers -->
                    <tr class="bg-[#284c9a] text-white text-[11px]">
                        <th rowspan="2" class="sticky left-0 bg-[#284c9a] z-40 px-3.5 py-2.5 font-bold uppercase tracking-wider text-left border-r border-white/20 whitespace-nowrap min-w-[190px] shadow-[2px_0_6px_-2px_rgba(0,0,0,0.25)]">
                            Nama Personil
                        </th>
                        <th rowspan="2" class="sticky left-[190px] bg-[#284c9a] z-40 px-3 py-2.5 font-bold uppercase tracking-wider text-center border-r border-white/20 whitespace-nowrap min-w-[95px] shadow-[2px_0_6px_-2px_rgba(0,0,0,0.25)]">
                            Cabang / Reg
                        </th>
                        <th colspan="{{ $jmlhari }}" class="px-3 py-2 text-center font-bold uppercase tracking-wider text-white border-b border-white/20 text-xs">
                            Kalender Bulan {{ $namaBulanPilihan }} {{ $tahun }}
                        </th>
                        <th rowspan="2" class="sticky right-0 bg-[#1e3a78] z-40 px-3 py-2.5 font-bold uppercase tracking-wider text-center border-l border-white/20 whitespace-nowrap min-w-[75px] shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.25)]">
                            Total
                        </th>
                    </tr>

                    <!-- Row 2: Date Columns (1..jmlhari) -->
                    <tr class="bg-[#284c9a] text-white text-[10px]">
                        @php
                            $curDate = $start_date;
                        @endphp
                        @while (strtotime($curDate) <= strtotime($end_date))
                            @php
                                $dNum = (int)date('j', strtotime($curDate));
                                $dayOfWeek = (int)date('w', strtotime($curDate)); // 0 = Minggu
                                $isSunday = ($dayOfWeek === 0);
                                $isToday = ($isBulanIni && $dNum === $todayDate);
                            @endphp
                            <th class="px-1.5 py-1.5 text-center font-bold border-r border-white/10 min-w-[34px] {{ $isToday ? 'bg-amber-400 text-slate-900 font-black' : ($isSunday ? 'bg-rose-500/30 text-rose-200' : 'text-white/90') }}"
                                title="{{ Carbon\Carbon::parse($curDate)->isoFormat('dddd, D MMMM Y') }}">
                                <div class="flex flex-col items-center">
                                    <span class="leading-none">{{ str_pad($dNum, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[8px] uppercase tracking-tighter opacity-75 leading-none mt-0.5">
                                        {{ substr(Carbon\Carbon::parse($curDate)->isoFormat('dd'), 0, 1) }}
                                    </span>
                                </div>
                            </th>
                            @php
                                $curDate = date('Y-m-d', strtotime('+1 day', strtotime($curDate)));
                            @endphp
                        @endwhile
                    </tr>
                </thead>

                <!-- TBODY -->
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    
                    <!-- SECTION 1: SALES MARKETING MANAGER (SMM) -->
                    <tr class="bg-slate-50/90 dark:bg-slate-800/80">
                        <td colspan="{{ $jmlhari + 3 }}" class="px-3.5 py-1.5 font-black uppercase text-[10px] tracking-wider text-slate-600 dark:text-slate-300">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-brand-600"></i>
                                <span>Sales Marketing Manager (SMM) &bull; {{ count($users) }} Personil</span>
                            </div>
                        </td>
                    </tr>

                    @forelse ($users as $d)
                        @php
                            $userTotalKunjungan = 0;
                            for ($k = 1; $k <= $jmlhari; $k++) {
                                $userTotalKunjungan += (int)($d->{"tgl_$k"} ?? 0);
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors group">
                            
                            <!-- Sticky Col 1: Nama -->
                            <td class="sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3.5 py-2 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-[10px] shrink-0">
                                        {{ substr($d->name ?? 'U', 0, 2) }}
                                    </div>
                                    <span class="truncate max-w-[150px]" title="{{ $d->name }}">{{ $d->name }}</span>
                                </div>
                            </td>

                            <!-- Sticky Col 2: Cabang -->
                            <td class="sticky left-[190px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3 py-2 text-center border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-mono-num uppercase">
                                    {{ $d->kode_cabang ?? '-' }}
                                </span>
                            </td>

                            <!-- Date Columns -->
                            @php
                                $start = $start_date;
                                $i = 1;
                            @endphp
                            @while (strtotime($start) <= strtotime($end_date))
                                @php
                                    $val = !empty($d->{"tgl_$i"}) ? (int)$d->{"tgl_$i"} : 0;
                                    $dayOfWeek = (int)date('w', strtotime($start));
                                    $isSunday = ($dayOfWeek === 0);
                                @endphp
                                <td class="px-1 py-1.5 text-center border-r border-slate-100 dark:border-slate-800/80 {{ $isSunday ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                                    @if ($val > 0)
                                        <button type="button" 
                                            class="detail inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-500/15 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 font-black font-mono-num hover:bg-emerald-600 hover:text-white transition-all shadow-2xs hover:scale-105 cursor-pointer text-xs"
                                            id_user="{{ $d->id }}" 
                                            tanggal="{{ $start }}"
                                            title="{{ $val }} aktifitas pada {{ $start }} (Klik untuk detail)">
                                            {{ $val }}
                                        </button>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-700 font-mono-num text-[11px]">-</span>
                                    @endif
                                </td>
                                @php
                                    $i++;
                                    $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                @endphp
                            @endwhile

                            <!-- Sticky Col 4: Total Bulan -->
                            <td class="sticky right-0 bg-slate-50/90 dark:bg-slate-800/90 group-hover:bg-slate-100 dark:group-hover:bg-slate-700/80 z-20 px-2 py-2 text-center border-l border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md font-black font-mono-num text-xs {{ $userTotalKunjungan > 0 ? 'bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60' : 'text-slate-400' }}">
                                    {{ $userTotalKunjungan }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $jmlhari + 3 }}" class="py-8 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-1"></i>
                                <span class="text-xs font-semibold">Tidak ada data SMM pada periode ini</span>
                            </td>
                        </tr>
                    @endforelse

                    <!-- SECTION 2: REGIONAL SALES MANAGER (RSM) & GM MARKETING -->
                    @if ($user->hasRole(['super admin', 'direktur', 'gm marketing', 'gm administrasi']))
                        
                        <!-- RSM GROUP HEADER -->
                        <tr class="bg-indigo-50/80 dark:bg-indigo-950/40 border-t-2 border-indigo-200 dark:border-indigo-800">
                            <td colspan="{{ $jmlhari + 3 }}" class="px-3.5 py-1.5 font-black uppercase text-[10px] tracking-wider text-indigo-700 dark:text-indigo-300">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-indigo-600"></i>
                                    <span>Regional Sales Manager (RSM) &bull; {{ count($rsm) }} Personil</span>
                                </div>
                            </td>
                        </tr>

                        @foreach ($rsm as $d)
                            @php
                                $rsmTotalKunjungan = 0;
                                for ($k = 1; $k <= $jmlhari; $k++) {
                                    $rsmTotalKunjungan += (int)($d->{"tgl_$k"} ?? 0);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors group">
                                
                                <!-- Sticky Col 1: Nama -->
                                <td class="sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3.5 py-2 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-[10px] shrink-0">
                                            {{ substr($d->name ?? 'R', 0, 2) }}
                                        </div>
                                        <span class="truncate max-w-[150px]" title="{{ $d->name }}">{{ $d->name }}</span>
                                    </div>
                                </td>

                                <!-- Sticky Col 2: Regional -->
                                <td class="sticky left-[190px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3 py-2 text-center border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-mono-num uppercase">
                                        {{ $d->nama_regional ?? $d->kode_cabang ?? '-' }}
                                    </span>
                                </td>

                                <!-- Date Columns -->
                                @php
                                    $start = $start_date;
                                    $i = 1;
                                @endphp
                                @while (strtotime($start) <= strtotime($end_date))
                                    @php
                                        $val = !empty($d->{"tgl_$i"}) ? (int)$d->{"tgl_$i"} : 0;
                                        $dayOfWeek = (int)date('w', strtotime($start));
                                        $isSunday = ($dayOfWeek === 0);
                                    @endphp
                                    <td class="px-1 py-1.5 text-center border-r border-slate-100 dark:border-slate-800/80 {{ $isSunday ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                                        @if ($val > 0)
                                            <button type="button" 
                                                class="detail inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-500/15 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 font-black font-mono-num hover:bg-emerald-600 hover:text-white transition-all shadow-2xs hover:scale-105 cursor-pointer text-xs"
                                                id_user="{{ $d->id }}" 
                                                tanggal="{{ $start }}"
                                                title="{{ $val }} aktifitas pada {{ $start }} (Klik untuk detail)">
                                                {{ $val }}
                                            </button>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-700 font-mono-num text-[11px]">-</span>
                                        @endif
                                    </td>
                                    @php
                                        $i++;
                                        $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                    @endphp
                                @endwhile

                                <!-- Sticky Col 4: Total Bulan -->
                                <td class="sticky right-0 bg-slate-50/90 dark:bg-slate-800/90 group-hover:bg-slate-100 dark:group-hover:bg-slate-700/80 z-20 px-2 py-2 text-center border-l border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md font-black font-mono-num text-xs {{ $rsmTotalKunjungan > 0 ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60' : 'text-slate-400' }}">
                                        {{ $rsmTotalKunjungan }}
                                    </span>
                                </td>

                            </tr>
                        @endforeach

                        <!-- GM MARKETING GROUP HEADER -->
                        <tr class="bg-amber-50/80 dark:bg-amber-950/40 border-t-2 border-amber-200 dark:border-amber-800">
                            <td colspan="{{ $jmlhari + 3 }}" class="px-3.5 py-1.5 font-black uppercase text-[10px] tracking-wider text-amber-700 dark:text-amber-300">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>GM Marketing &bull; {{ count($gm) }} Personil</span>
                                </div>
                            </td>
                        </tr>

                        @foreach ($gm as $d)
                            @php
                                $gmTotalKunjungan = 0;
                                for ($k = 1; $k <= $jmlhari; $k++) {
                                    $gmTotalKunjungan += (int)($d->{"tgl_$k"} ?? 0);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors group">
                                
                                <!-- Sticky Col 1: Nama -->
                                <td class="sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3.5 py-2 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-[10px] shrink-0">
                                            {{ substr($d->name ?? 'G', 0, 2) }}
                                        </div>
                                        <span class="truncate max-w-[150px]" title="{{ $d->name }}">{{ $d->name }}</span>
                                    </div>
                                </td>

                                <!-- Sticky Col 2: Cabang -->
                                <td class="sticky left-[190px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-20 px-3 py-2 text-center border-r border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-mono-num uppercase">
                                        {{ $d->kode_cabang ?? '-' }}
                                    </span>
                                </td>

                                <!-- Date Columns -->
                                @php
                                    $start = $start_date;
                                    $i = 1;
                                @endphp
                                @while (strtotime($start) <= strtotime($end_date))
                                    @php
                                        $val = !empty($d->{"tgl_$i"}) ? (int)$d->{"tgl_$i"} : 0;
                                        $dayOfWeek = (int)date('w', strtotime($start));
                                        $isSunday = ($dayOfWeek === 0);
                                    @endphp
                                    <td class="px-1 py-1.5 text-center border-r border-slate-100 dark:border-slate-800/80 {{ $isSunday ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                                        @if ($val > 0)
                                            <button type="button" 
                                                class="detail inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-500/15 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 font-black font-mono-num hover:bg-emerald-600 hover:text-white transition-all shadow-2xs hover:scale-105 cursor-pointer text-xs"
                                                id_user="{{ $d->id }}" 
                                                tanggal="{{ $start }}"
                                                title="{{ $val }} aktifitas pada {{ $start }} (Klik untuk detail)">
                                                {{ $val }}
                                            </button>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-700 font-mono-num text-[11px]">-</span>
                                        @endif
                                    </td>
                                    @php
                                        $i++;
                                        $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                    @endphp
                                @endwhile

                                <!-- Sticky Col 4: Total Bulan -->
                                <td class="sticky right-0 bg-slate-50/90 dark:bg-slate-800/90 group-hover:bg-slate-100 dark:group-hover:bg-slate-700/80 z-20 px-2 py-2 text-center border-l border-slate-200/80 dark:border-slate-800 whitespace-nowrap shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.08)]">
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md font-black font-mono-num text-xs {{ $gmTotalKunjungan > 0 ? 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60' : 'text-slate-400' }}">
                                        {{ $gmTotalKunjungan }}
                                    </span>
                                </td>

                            </tr>
                        @endforeach

                    @endif

                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- MODAL FORM UNTUK DETAIL AKTIFITAS SFA -->
<x-modal-form id="modal" show="loadmodal" title="Detail Aktifitas SFA" size="modal-lg" />

@endsection

@push('myscript')
<script>
    $(function() {
        // Klik sel matriks untuk memuat detail aktifitas modal
        $(document).on('click', '.detail', function() {
            let id_user = $(this).attr('id_user');
            let tanggal = $(this).attr('tanggal');
            
            $("#modal").modal("show");
            $("#loadmodal").html(`
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                    <span class="text-xs font-semibold">Memuat data aktifitas kunjungan...</span>
                </div>
            `);

            $("#loadmodal").load('/aktifitassmm/' + id_user + '/' + tanggal + '/getdetailaktifitas', function(response, status, xhr) {
                if (status === "error") {
                    $("#loadmodal").html(`
                        <div class="p-6 text-center text-xs text-rose-500 font-bold bg-rose-50 dark:bg-rose-950/40 rounded-lg border border-rose-200 dark:border-rose-800">
                            Gagal memuat detail aktifitas (${xhr.status}: ${xhr.statusText})
                        </div>
                    `);
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush
