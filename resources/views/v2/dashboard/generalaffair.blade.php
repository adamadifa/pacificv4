@extends('layouts.app')
@section('titlepage', 'Dashboard General Affair')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">General Affair</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- 4 KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS & 3D ILLUSTRATIONS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. Total Armada Kendaraan -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <!-- 3D Truck Illustration from V1 -->
                <div class="absolute right-2 bottom-1 pointer-events-none select-none z-10">
                    <img src="{{ asset('assets/img/illustrations/truck2.png') }}" alt="Fleet Illustration" class="h-16 sm:h-[72px] object-contain drop-shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300">
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20">
                            Armada
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Kendaraan</p>
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ number_format($jmlkendaraan) }} <span class="text-xs font-semibold text-white/70 font-sans">Unit</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Armada aktif operasional</p>
                    </div>
                </div>
            </div>

            <!-- 2. Uji Berkala (KIR) -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 lg:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-sky-400/20 text-sky-300 flex items-center justify-center border border-sky-400/30 shrink-0 shadow-xs">
                                <i data-lucide="file-check" class="w-4 h-4"></i>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-400/20 text-sky-300 border border-sky-400/30 uppercase tracking-wider">
                                KIR
                            </span>
                        </div>
                        @if (count($kir_lewat) > 0)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-400/30 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                {{ count($kir_lewat) }} Lewat
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Uji Berkala (KIR)</p>
                        <div class="text-2xl font-black text-sky-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ count($kir_lewat) + count($kir_bulanini) }} <span class="text-xs font-semibold text-white/70 font-sans">Unit</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Perlu perpanjangan masa uji</p>
                    </div>
                </div>
            </div>

            <!-- 3. Pajak 1 Tahunan -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b sm:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs">
                                <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase tracking-wider">
                                1 Tahun
                            </span>
                        </div>
                        @if (count($pajaksatutahun_lewat) > 0)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-400/30 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                {{ count($pajaksatutahun_lewat) }} Lewat
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Pajak 1 Tahunan</p>
                        <div class="text-2xl font-black text-amber-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ count($pajaksatutahun_lewat) + count($pajaksatutahun_bulanini) }} <span class="text-xs font-semibold text-white/70 font-sans">Unit</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Perlu perpanjangan STNK</p>
                    </div>
                </div>
            </div>

            <!-- 4. Pajak 5 Tahunan -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-rose-400/20 text-rose-300 flex items-center justify-center border border-rose-400/30 shrink-0 shadow-xs">
                                <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-400/20 text-rose-300 border border-rose-400/30 uppercase tracking-wider">
                                5 Tahun
                            </span>
                        </div>
                        @if (count($pajaklimatahun_lewat) > 0)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-400/30 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                {{ count($pajaklimatahun_lewat) }} Lewat
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Pajak 5 Tahunan</p>
                        <div class="text-2xl font-black text-rose-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ count($pajaklimatahun_lewat) + count($pajaklimatahun_bulanini) }} <span class="text-xs font-semibold text-white/70 font-sans">Unit</span>
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Ganti plat kaleng & cek fisik</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN GRID: COMPLIANCE HUB (8 COLS) & CABANG FLEET (4 COLS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">

        <!-- KOLOM KIRI: COMPLIANCE & PERPANJANGAN MONITORING HUB (8 COLS) -->
        <div class="lg:col-span-8 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Tab Kategori: KIR vs Pajak 1 Tahun vs Pajak 5 Tahun -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-1 p-0.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-xs font-bold h-[34px]">
                        
                        <!-- Tab Button 1: KIR -->
                        <button type="button" class="tab-ga-category px-3 h-full rounded-md bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs transition flex items-center gap-1.5 cursor-pointer text-xs" data-target="ga-pane-kir">
                            <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                            <span>Uji KIR</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200/60 font-mono-num">
                                {{ count($kir_lewat) + count($kir_bulanini) }}
                            </span>
                        </button>

                        <!-- Tab Button 2: Pajak 1 Tahun -->
                        <button type="button" class="tab-ga-category px-3 h-full rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition flex items-center gap-1.5 cursor-pointer text-xs" data-target="ga-pane-pjs">
                            <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                            <span>Pajak 1 Thn</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/60 font-mono-num">
                                {{ count($pajaksatutahun_lewat) + count($pajaksatutahun_bulanini) }}
                            </span>
                        </button>

                        <!-- Tab Button 3: Pajak 5 Tahun -->
                        <button type="button" class="tab-ga-category px-3 h-full rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition flex items-center gap-1.5 cursor-pointer text-xs" data-target="ga-pane-pjl">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                            <span>Pajak 5 Thn</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60 font-mono-num">
                                {{ count($pajaklimatahun_lewat) + count($pajaklimatahun_bulanini) }}
                            </span>
                        </button>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        <span>Monitoring Legalitas Armada</span>
                    </div>
                </div>

                @php
                    $categories = [
                        'ga-pane-kir' => [
                            'title' => 'Uji Berkala (KIR)',
                            'date_field' => 'jatuhtempo_kir',
                            'tabs' => [
                                'kir-bulanini' => ['label' => 'Bulan Ini', 'data' => $kir_bulanini, 'default' => true],
                                'kir-lewat' => ['label' => 'Jatuh Tempo', 'data' => $kir_lewat, 'badge_danger' => true],
                                'kir-bulandepan' => ['label' => 'Bulan Depan', 'data' => $kir_bulandepan],
                                'kir-duabulan' => ['label' => '2 Bulan Ke Depan', 'data' => $kir_duabulan],
                            ]
                        ],
                        'ga-pane-pjs' => [
                            'title' => 'Pajak 1 Tahunan (STNK)',
                            'date_field' => 'jatuhtempo_pajak_satutahun',
                            'tabs' => [
                                'pjs-bulanini' => ['label' => 'Bulan Ini', 'data' => $pajaksatutahun_bulanini, 'default' => true],
                                'pjs-lewat' => ['label' => 'Jatuh Tempo', 'data' => $pajaksatutahun_lewat, 'badge_danger' => true],
                                'pjs-bulandepan' => ['label' => 'Bulan Depan', 'data' => $pajaksatutahun_bulandepan],
                                'pjs-duabulan' => ['label' => '2 Bulan Ke Depan', 'data' => $pajaksatutahun_duabulan],
                            ]
                        ],
                        'ga-pane-pjl' => [
                            'title' => 'Pajak 5 Tahunan (Plat Kaleng)',
                            'date_field' => 'jatuhtempo_pajak_limatahun',
                            'tabs' => [
                                'pjl-bulanini' => ['label' => 'Bulan Ini', 'data' => $pajaklimatahun_bulanini, 'default' => true],
                                'pjl-lewat' => ['label' => 'Jatuh Tempo', 'data' => $pajaklimatahun_lewat, 'badge_danger' => true],
                                'pjl-bulandepan' => ['label' => 'Bulan Depan', 'data' => $pajaklimatahun_bulandepan],
                                'pjl-duabulan' => ['label' => '2 Bulan Ke Depan', 'data' => $pajaklimatahun_duabulan],
                            ]
                        ],
                    ];
                @endphp

                <!-- CATEGORY PANES -->
                @foreach ($categories as $catKey => $cat)
                    <div id="{{ $catKey }}" class="tab-ga-content flex-1 flex flex-col {{ $loop->first ? '' : 'hidden' }}">
                        
                        <!-- Sub-Pill Periode Filter -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 mb-3 scrollbar-none">
                            @foreach ($cat['tabs'] as $subKey => $subTab)
                                @php
                                    $isDefault = $subTab['default'] ?? false;
                                    $isDanger = $subTab['badge_danger'] ?? false;
                                    $count = count($subTab['data']);
                                @endphp
                                <button type="button" class="tab-ga-sub shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-150 cursor-pointer {{ $isDefault ? 'bg-brand-600 text-white shadow-xs border border-brand-500' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800' }}" data-target="{{ $subKey }}">
                                    @if ($isDanger)
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    @elseif ($isDefault)
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    @endif
                                    <span>{{ $subTab['label'] }}</span>
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono-num {{ $isDefault ? 'bg-white/20 text-white' : ($isDanger ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400') }}">
                                        {{ $count }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Sub-Panels for this category -->
                        <div class="flex-1 max-h-[520px] overflow-y-auto pr-1">
                            @foreach ($cat['tabs'] as $subKey => $subTab)
                                @php
                                    $isDefault = $subTab['default'] ?? false;
                                    $dataList = $subTab['data'];
                                    $dateField = $cat['date_field'];
                                @endphp
                                
                                <div id="{{ $subKey }}" class="tab-ga-subpanel space-y-2.5 {{ $isDefault ? '' : 'hidden' }}">
                                    @if (count($dataList) > 0)
                                        @foreach ($dataList as $d)
                                            @php
                                                $jatuhtempo = $d->$dateField;
                                                $sisahari = hitungSisahari($jatuhtempo);
                                            @endphp

                                            <!-- COMPACT & ELEGANT VEHICLE COMPLIANCE CARD (NO BORDER KIRI) -->
                                            <div class="group p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-white dark:hover:bg-slate-850 hover:shadow-xs transition duration-150">
                                                <div class="flex items-start justify-between gap-3">
                                                    
                                                    <!-- Left: License Plate + Vehicle Spec -->
                                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                                        <!-- Authentic Dark License Plate Badge -->
                                                        <div class="px-2.5 py-1 rounded-md bg-slate-900 text-white dark:bg-slate-800 dark:border dark:border-slate-700 shadow-2xs font-mono font-black text-xs tracking-wider shrink-0 select-all flex items-center justify-center">
                                                            {{ $d->no_polisi }}
                                                        </div>

                                                        <!-- Vehicle details -->
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors leading-tight">
                                                                    {{ $d->merk }} {{ $d->tipe }}
                                                                </h5>
                                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold uppercase bg-slate-200/80 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                                    {{ $d->tipe_kendaraan }}
                                                                </span>
                                                            </div>

                                                            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                                                                <span class="inline-flex items-center gap-1 font-medium text-slate-600 dark:text-slate-300">
                                                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                                    Cabang {{ textUpperCase($d->kode_cabang) }}
                                                                </span>
                                                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                                                <span>Jatuh Tempo: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ formatIndo($jatuhtempo) }}</span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right: Status Badge Sisa Hari -->
                                                    <div class="shrink-0 text-right">
                                                        @if ($sisahari < 0)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/60 font-mono-num">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                                <span>{{ abs($sisahari) }} Hari Lewat</span>
                                                            </span>
                                                        @elseif ($sisahari <= 7)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/60 font-mono-num">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                                <span>{{ $sisahari }} Hari Lagi</span>
                                                            </span>
                                                        @elseif ($sisahari <= 30)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/60 font-mono-num">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                                <span>{{ $sisahari }} Hari Lagi</span>
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 font-mono-num">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                <span>{{ $sisahari }} Hari Lagi</span>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="py-12 text-center rounded-lg bg-slate-50/50 dark:bg-slate-950/30 border border-dashed border-slate-200 dark:border-slate-800">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400 mx-auto mb-2">
                                                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                                            </div>
                                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tidak ada kendaraan jatuh tempo</p>
                                            <span class="text-[11px] text-slate-400">Semua armada dalam periode ini memiliki status legalitas aktif.</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <!-- KOLOM KANAN: REKAP ARMADA PER CABANG (4 COLS) -->
        <div class="lg:col-span-4 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col justify-between h-full">
                <div>
                    <!-- Header Rekap Cabang -->
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0 shadow-2xs">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Armada per Cabang</h4>
                                <span class="text-[11px] text-slate-400 leading-none">Distribusi Unit Operasional</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-mono-num">
                            {{ count($rekapkendaraan) }} Cabang
                        </span>
                    </div>

                    <!-- Scrollable list of branches -->
                    <div class="space-y-1.5 max-h-[440px] overflow-y-auto pr-1">
                        @php
                            $total_semua = 0;
                        @endphp
                        @foreach ($rekapkendaraan as $d)
                            @php
                                $total_semua += $d->total;
                            @endphp
                            <div class="group flex items-center justify-between p-2.5 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-800 hover:border-brand-200 dark:hover:border-brand-800 transition duration-150">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 group-hover:text-brand-600 transition shrink-0">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-brand-600 dark:group-hover:text-brand-400 transition leading-tight">
                                            {{ textUpperCase($d->nama_cabang) }}
                                        </p>
                                        <span class="text-[10px] text-slate-400 leading-none">Area Distribusi</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-md text-xs font-black font-mono-num bg-[#284c9a] text-white shrink-0 shadow-2xs">
                                    {{ $d->total }} Unit
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Summary Strip -->
                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 shrink-0">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none">Total Armada</span>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 leading-none mt-0.5">Seluruh Cabang</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-brand-600 dark:text-brand-400 font-mono-num leading-tight">
                            {{ number_format($total_semua) }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400 font-sans ml-0.5">Unit</span>
                    </div>
                </div>

            </div>
        </div>

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

    // Category Tabs Switching (KIR vs Pajak 1 Thn vs Pajak 5 Thn)
    $('.tab-ga-category').click(function() {
        const target = $(this).data('target');
        
        $('.tab-ga-category')
            .removeClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs')
            .addClass('text-slate-500 dark:text-slate-400');
        $(this)
            .addClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs')
            .removeClass('text-slate-500 dark:text-slate-400');

        $('.tab-ga-content').addClass('hidden');
        $('#' + target).removeClass('hidden');

        if (window.lucide) {
            lucide.createIcons();
        }
    });

    // Sub-Period Tabs Switching within active category
    $('.tab-ga-sub').click(function() {
        const target = $(this).data('target');
        const parentPane = $(this).closest('.tab-ga-content');

        parentPane.find('.tab-ga-sub')
            .removeClass('bg-brand-600 text-white shadow-xs border-brand-500')
            .addClass('bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800');
        
        $(this)
            .addClass('bg-brand-600 text-white shadow-xs border-brand-500')
            .removeClass('bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800');

        parentPane.find('.tab-ga-subpanel').addClass('hidden');
        parentPane.find('#' + target).removeClass('hidden');

        if (window.lucide) {
            lucide.createIcons();
        }
    });
});
</script>
@endpush
