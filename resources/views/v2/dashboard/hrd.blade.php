@extends('layouts.app')
@section('titlepage', 'Dashboard HRD')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">HRD</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- 4 KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS & V1 3D ILLUSTRATIONS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. Karyawan Aktif -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-2.5 bottom-1.5 pointer-events-none select-none z-10">
                    <img src="{{ asset('assets/img/illustrations/karyawan1.png') }}" alt="Karyawan Aktif" class="h-16 sm:h-[72px] object-contain drop-shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300">
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="users-round" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20">
                            Aktif
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Data Karyawan Aktif</p>
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ $status_karyawan->jml_aktif ?? 0 }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Total seluruh karyawan aktif</p>
                    </div>
                </div>
            </div>

            <!-- 2. Karyawan Tetap -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 lg:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-2.5 bottom-1.5 pointer-events-none select-none z-10">
                    <img src="{{ asset('assets/img/illustrations/karyawan2.webp') }}" alt="Karyawan Tetap" class="h-16 sm:h-[72px] object-contain drop-shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300">
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider">
                            Tetap
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Karyawan Tetap</p>
                        <div class="text-2xl font-black text-emerald-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ $status_karyawan->jml_tetap ?? 0 }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Status PKWTT</p>
                    </div>
                </div>
            </div>

            <!-- 3. Karyawan Kontrak -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b sm:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-2.5 bottom-1.5 pointer-events-none select-none z-10">
                    <img src="{{ asset('assets/img/illustrations/karyawan3.png') }}" alt="Karyawan Kontrak" class="h-16 sm:h-[72px] object-contain drop-shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300">
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs">
                            <i data-lucide="file-badge" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase tracking-wider">
                            Kontrak
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Karyawan Kontrak</p>
                        <div class="text-2xl font-black text-amber-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ $status_karyawan->jml_kontrak ?? 0 }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Status PKWT</p>
                    </div>
                </div>
            </div>

            <!-- 4. Outsourcing -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-2.5 bottom-1.5 pointer-events-none select-none z-10">
                    <img src="{{ asset('assets/img/illustrations/karyawan4.webp') }}" alt="Karyawan Outsourcing" class="h-16 sm:h-[72px] object-contain drop-shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300">
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-400/20 text-sky-300 flex items-center justify-center border border-sky-400/30 shrink-0 shadow-xs">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-400/20 text-sky-300 border border-sky-400/30 uppercase tracking-wider">
                            Mitra
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Karyawan Outsourcing</p>
                        <div class="text-2xl font-black text-sky-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            {{ $status_karyawan->jml_outsourcing ?? 0 }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Tenaga alih daya</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 3-SECTION GRID: HABIS KONTRAK | KARYAWAN ULANG TAHUN | KARYAWAN CABANG -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">

        <!-- SECTION 1: HABIS KONTRAK & EVALUASI (5 COLS) -->
        <div class="lg:col-span-5 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Tab Utama: Habis Kontrak vs Belum Penilaian -->
                <div class="flex items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-1 p-0.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-xs font-bold h-[32px]">
                        <button type="button" class="tab-hrd-main px-2.5 h-full rounded-md bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs transition flex items-center gap-1.5 cursor-pointer text-xs" data-target="tab-pane-kontrak">
                            <i data-lucide="file-badge-2" class="w-3.5 h-3.5"></i>
                            <span>Habis Kontrak</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400 border border-brand-200/60 font-mono-num">
                                {{ count($kontrak_bulanini) + count($kontrak_lewat) }}
                            </span>
                        </button>
                        <button type="button" class="tab-hrd-main px-2.5 h-full rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition flex items-center gap-1.5 cursor-pointer text-xs" data-target="tab-pane-penilaian">
                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                            <span>Belum Penilaian</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/60 font-mono-num">
                                {{ count($kontrak_belum_penilaian) }}
                            </span>
                        </button>
                    </div>

                    <span class="text-[11px] text-slate-400 hidden sm:inline-flex items-center gap-1">
                        <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                        Monitoring Masa Kerja
                    </span>
                </div>

                <!-- TAB PANE 1: KARYAWAN HABIS KONTRAK -->
                <div id="tab-pane-kontrak" class="tab-hrd-content flex-1 flex flex-col">
                    
                    <!-- Sub-Pill Periode Filter Kontrak -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 mb-3 scrollbar-none">
                        <button type="button" class="tab-kontrak-sub shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer" data-target="kontrak-lewat">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <span>Jatuh Tempo</span>
                            <span class="px-1 py-0.2 rounded text-[10px] bg-rose-50 dark:bg-rose-950/60 text-rose-600 font-mono-num">{{ count($kontrak_lewat) }}</span>
                        </button>

                        <button type="button" class="tab-kontrak-sub shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold transition-all border border-brand-500 bg-brand-600 text-white shadow-xs cursor-pointer" data-target="kontrak-bulanini">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            <span>Bulan Ini</span>
                            <span class="px-1 py-0.2 rounded text-[10px] bg-white/20 text-white font-mono-num">{{ count($kontrak_bulanini) }}</span>
                        </button>

                        <button type="button" class="tab-kontrak-sub shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer" data-target="kontrak-bulandepan">
                            <span>Bulan Depan</span>
                            <span class="px-1 py-0.2 rounded text-[10px] bg-amber-50 dark:bg-amber-950/60 text-amber-700 font-mono-num">{{ count($kontrak_bulandepan) }}</span>
                        </button>

                        <button type="button" class="tab-kontrak-sub shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer" data-target="kontrak-duabulan">
                            <span>2 Bulan</span>
                            <span class="px-1 py-0.2 rounded text-[10px] bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 font-mono-num">{{ count($kontrak_duabulan) }}</span>
                        </button>
                    </div>

                    @php
                        $subTabs = [
                            'kontrak-lewat' => $kontrak_lewat,
                            'kontrak-bulanini' => $kontrak_bulanini,
                            'kontrak-bulandepan' => $kontrak_bulandepan,
                            'kontrak-duabulan' => $kontrak_duabulan,
                        ];
                    @endphp

                    <!-- Scrollable Container untuk List Kontrak -->
                    <div class="flex-1 max-h-[520px] overflow-y-auto pr-1">
                        @foreach ($subTabs as $id => $dataList)
                            <div id="{{ $id }}" class="tab-kontrak-panel space-y-2 {{ $id == 'kontrak-bulanini' ? '' : 'hidden' }}">
                                @if (count($dataList) > 0)
                                    @foreach ($dataList as $d)
                                        @php
                                            $sisahari = hitungSisahari($d->sampai);
                                            $rawFoto = !empty($d->foto) ? (is_object($d) && method_exists($d, 'getRawOriginal') ? $d->getRawOriginal('foto') : $d->foto) : null;
                                            $fotoExists = !empty($rawFoto) && Storage::disk('public')->exists('/karyawan/' . $rawFoto);
                                        @endphp

                                        <!-- COMPACT & ELEGANT CONTRACT CARD WITH EMPLOYEE PHOTO / AVATAR -->
                                        <div class="group p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-white dark:hover:bg-slate-850 hover:shadow-xs transition duration-150">
                                            <div class="flex items-start justify-between gap-2.5">
                                                
                                                <!-- Left: Photo/Avatar + Details -->
                                                <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                                    
                                                    <!-- Employee Photo / User Icon Fallback -->
                                                    <div class="relative shrink-0 mt-0.5">
                                                        @if ($fotoExists)
                                                            <img src="{{ getfotoKaryawan($rawFoto) }}" alt="{{ $d->nama_karyawan }}" class="w-9 h-9 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs shrink-0" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                                            <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 items-center justify-center text-slate-400 shrink-0 hidden">
                                                                <i data-lucide="user" class="w-4 h-4"></i>
                                                            </div>
                                                        @else
                                                            <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0">
                                                                <i data-lucide="user" class="w-4 h-4"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Details -->
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors leading-tight">
                                                                {{ formatName($d->nama_karyawan) }}
                                                            </h5>
                                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-medium font-mono-num bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                                                {{ $d->nik }}
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- Metadata Row: Jabatan • Dept • Cabang -->
                                                        <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                                                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $d->nama_jabatan }}</span>
                                                            <span class="text-slate-300 dark:text-slate-700">•</span>
                                                            <span>{{ $d->kode_dept }}</span>
                                                            <span class="text-slate-300 dark:text-slate-700">•</span>
                                                            <span class="inline-flex items-center gap-0.5 font-medium text-slate-600 dark:text-slate-300">
                                                                <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                                {{ textUpperCase($d->nama_cabang) }}
                                                            </span>
                                                        </div>

                                                        <!-- Contract Footer: No. Kontrak & Tanggal Selesai -->
                                                        <div class="flex items-center gap-2 mt-2 pt-1.5 border-t border-slate-200/60 dark:border-slate-800/80 text-[10px] text-slate-400">
                                                            <span>No: <span class="font-mono-num text-slate-600 dark:text-slate-300 font-medium">{{ $d->no_kontrak }}</span></span>
                                                            <span class="text-slate-300 dark:text-slate-700">|</span>
                                                            <span>s/d <span class="font-semibold text-slate-700 dark:text-slate-200">{{ formatIndo($d->sampai) }}</span></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right: Sisa Hari Badge -->
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
                                    <div class="py-10 text-center rounded-lg bg-slate-50/50 dark:bg-slate-950/30 border border-dashed border-slate-200 dark:border-slate-800">
                                        <i data-lucide="info" class="w-7 h-7 text-slate-400 mx-auto mb-2 opacity-60"></i>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">Tidak ada karyawan habis kontrak</p>
                                        <span class="text-[11px] text-slate-400">Semua masa kontrak periode ini aman.</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- TAB PANE 2: KARYAWAN BELUM PENILAIAN -->
                <div id="tab-pane-penilaian" class="tab-hrd-content flex-1 flex flex-col hidden">
                    <div class="flex-1 max-h-[520px] overflow-y-auto pr-1 space-y-2">
                        @if (count($kontrak_belum_penilaian) > 0)
                            @foreach ($kontrak_belum_penilaian as $d)
                                @php
                                    $sisahari = hitungSisahari($d->sampai);
                                    $rawFoto = !empty($d->foto) ? (is_object($d) && method_exists($d, 'getRawOriginal') ? $d->getRawOriginal('foto') : $d->foto) : null;
                                    $fotoExists = !empty($rawFoto) && Storage::disk('public')->exists('/karyawan/' . $rawFoto);
                                @endphp

                                <!-- COMPACT & ELEGANT EVALUATION CARD WITH EMPLOYEE PHOTO / AVATAR -->
                                <div class="group p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-white dark:hover:bg-slate-850 hover:shadow-xs transition duration-150">
                                    <div class="flex items-start justify-between gap-2.5">
                                        
                                        <!-- Left Photo + Info -->
                                        <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                            
                                            <!-- Employee Photo / User Icon Fallback -->
                                            <div class="relative shrink-0 mt-0.5">
                                                @if ($fotoExists)
                                                    <img src="{{ getfotoKaryawan($rawFoto) }}" alt="{{ $d->nama_karyawan }}" class="w-9 h-9 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs shrink-0" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                                    <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 items-center justify-center text-slate-400 shrink-0 hidden">
                                                        <i data-lucide="user" class="w-4 h-4"></i>
                                                    </div>
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0">
                                                        <i data-lucide="user" class="w-4 h-4"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Info -->
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors leading-tight">
                                                        {{ formatName($d->nama_karyawan) }}
                                                    </h5>
                                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-medium font-mono-num bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                                        {{ $d->nik }}
                                                    </span>
                                                </div>

                                                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $d->nama_jabatan }}</span>
                                                    <span class="text-slate-300 dark:text-slate-700">•</span>
                                                    <span>{{ $d->kode_dept }}</span>
                                                    <span class="text-slate-300 dark:text-slate-700">•</span>
                                                    <span class="inline-flex items-center gap-0.5 font-medium text-slate-600 dark:text-slate-300">
                                                        <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                        {{ textUpperCase($d->nama_cabang) }}
                                                    </span>
                                                </div>

                                                <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-200/60 dark:border-slate-800/80 text-[10px] text-slate-400">
                                                    <span>No: <span class="font-mono-num text-slate-600 dark:text-slate-300 font-medium">{{ $d->no_kontrak }}</span></span>
                                                    
                                                    @can('penilaiankaryawan.create')
                                                        <a href="{{ route('penilaiankaryawan.index') }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#284c9a] hover:bg-[#1f3b77] active:scale-95 text-white font-medium text-[11px] shadow-2xs transition">
                                                            <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                                                            <span>Nilai</span>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right: Status Sisa Hari -->
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
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/60 font-mono-num">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>{{ $sisahari }} Hari Lagi</span>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="py-10 text-center rounded-lg bg-slate-50/50 dark:bg-slate-950/30 border border-dashed border-slate-200 dark:border-slate-800">
                                <i data-lucide="check-circle-2" class="w-7 h-7 text-emerald-500 mx-auto mb-2 opacity-80"></i>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Semua karyawan telah dinilai</p>
                                <span class="text-[11px] text-slate-400">Tidak ada pengajuan penilaian pending.</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: KARYAWAN ULANG TAHUN (4 COLS) -->
        <div class="lg:col-span-4 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Ulang Tahun -->
                <div class="flex items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-500 border border-amber-200 dark:border-amber-800 flex items-center justify-center shrink-0 shadow-2xs">
                            <i data-lucide="cake" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Ulang Tahun</h4>
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/60 font-mono-num">
                                    {{ count($karyawan_ulangtahun) }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400 leading-none">Hari Ini</span>
                        </div>
                    </div>

                    @if (count($karyawan_ulangtahun) > 0)
                        <button type="button" id="btnKirimUcapan" class="h-[30px] px-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-semibold text-xs shadow-2xs transition flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            <span id="btnText">Kirim Ucapan</span>
                            <span id="btnLoading" class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin hidden"></span>
                        </button>
                    @endif
                </div>

                <!-- Body / Scrollable List Ulang Tahun -->
                <div class="flex-1 max-h-[520px] overflow-y-auto pr-1 space-y-2">
                    @if (count($karyawan_ulangtahun) > 0)
                        @foreach ($karyawan_ulangtahun as $d)
                            @php
                                $umur = hitungUmur($d->tanggal_lahir);
                                $tanggal_lahir_formatted = $d->tanggal_lahir
                                    ? date('d-m-Y', strtotime($d->tanggal_lahir))
                                    : '-';
                                $rawFotoUlangTahun = !empty($d->foto) ? (is_object($d) && method_exists($d, 'getRawOriginal') ? $d->getRawOriginal('foto') : $d->foto) : null;
                                $fotoUlangTahunExists = !empty($rawFotoUlangTahun) && Storage::disk('public')->exists('/karyawan/' . $rawFotoUlangTahun);
                            @endphp
                            
                            <!-- COMPACT BIRTHDAY CARD -->
                            <div class="group p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800 hover:border-amber-300 dark:hover:border-amber-700 hover:bg-white dark:hover:bg-slate-850 hover:shadow-xs transition duration-150">
                                <div class="flex items-center gap-3">
                                    
                                    <!-- Avatar with mini Cake Badge -->
                                    <div class="relative shrink-0">
                                        @if ($fotoUlangTahunExists)
                                            <img src="{{ getfotoKaryawan($rawFotoUlangTahun) }}" alt="{{ $d->nama_karyawan }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-400/40 shadow-2xs" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                            <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 items-center justify-center text-amber-600 dark:text-amber-400 font-bold text-xs shadow-2xs hidden">
                                                <i data-lucide="user" class="w-5 h-5"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold text-xs shadow-2xs">
                                                <i data-lucide="user" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                                            <i data-lucide="cake" class="w-2 h-2"></i>
                                        </div>
                                    </div>

                                    <!-- Name & Details -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate leading-tight group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                                {{ formatName($d->nama_karyawan) }}
                                            </h5>
                                            @if ($umur !== null)
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 font-mono-num shrink-0">
                                                    {{ $umur }} Thn
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                            <span>{{ $d->nama_jabatan }}</span>
                                            <span class="text-slate-300 dark:text-slate-700">•</span>
                                            <span>{{ textUpperCase($d->nama_cabang) }}</span>
                                        </div>

                                        <div class="flex items-center justify-between mt-1 pt-1 border-t border-slate-200/60 dark:border-slate-800/80 text-[10px] text-slate-400">
                                            <span class="font-mono-num">{{ $d->nik }}</span>
                                            <span class="font-mono-num font-medium text-slate-600 dark:text-slate-300">🎂 {{ $tanggal_lahir_formatted }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-12 text-center rounded-lg bg-slate-50/50 dark:bg-slate-950/30 border border-dashed border-slate-200 dark:border-slate-800">
                            <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400 mx-auto mb-2">
                                <i data-lucide="cake-slice" class="w-4 h-4 opacity-60"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tidak ada yang berulang tahun</p>
                            <span class="text-[11px] text-slate-400">Tidak ada agenda ulang tahun hari ini.</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- SECTION 3: KARYAWAN CABANG (3 COLS) -->
        <div class="lg:col-span-3 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs transition-colors duration-200 flex flex-col h-full">
                
                <!-- Header Cabang -->
                <div class="flex items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0 shadow-2xs">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Karyawan Cabang</h4>
                            <span class="text-[11px] text-slate-400 leading-none">Distribusi SDM</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ count($karyawancabang) }}
                    </span>
                </div>

                <!-- Scrollable list of branches -->
                <div class="flex-1 max-h-[520px] overflow-y-auto pr-1 space-y-1.5">
                    @foreach ($karyawancabang as $d)
                        <div class="group flex items-center justify-between p-2 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-800 hover:border-brand-200 dark:hover:border-brand-800 transition duration-150">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 group-hover:text-brand-600 transition shrink-0">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                </div>
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate group-hover:text-brand-600 dark:group-hover:text-brand-400 transition leading-tight">
                                    {{ textUpperCase($d->nama_cabang) }}
                                </p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold font-mono-num bg-[#284c9a] text-white shrink-0 shadow-2xs">
                                {{ $d->jml_karyawancabang }}
                            </span>
                        </div>
                    @endforeach
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

    // Main Tab HRD Switching (Habis Kontrak vs Belum Penilaian)
    $('.tab-hrd-main').click(function() {
        const target = $(this).data('target');
        
        $('.tab-hrd-main')
            .removeClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs')
            .addClass('text-slate-500 dark:text-slate-400');
        $(this)
            .addClass('bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs')
            .removeClass('text-slate-500 dark:text-slate-400');

        $('.tab-hrd-content').addClass('hidden');
        $('#' + target).removeClass('hidden');

        if (window.lucide) {
            lucide.createIcons();
        }
    });

    // Sub Tab Kontrak Switching (Jatuh Tempo, Bulan Ini, Bulan Depan, 2 Bulan)
    $('.tab-kontrak-sub').click(function() {
        const target = $(this).data('target');

        $('.tab-kontrak-sub')
            .removeClass('bg-brand-600 text-white shadow-xs border-brand-500')
            .addClass('bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800');
        
        $(this)
            .addClass('bg-brand-600 text-white shadow-xs border-brand-500')
            .removeClass('bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800');

        $('.tab-kontrak-panel').addClass('hidden');
        $('#' + target).removeClass('hidden');

        if (window.lucide) {
            lucide.createIcons();
        }
    });

    // Kirim Ucapan Ulang Tahun Action
    $('#btnKirimUcapan').click(function() {
        $('#btnLoading').removeClass('hidden');
        $('#btnText').text('Mengirim...');
        setTimeout(function() {
            $('#btnLoading').addClass('hidden');
            $('#btnText').text('Kirim Ucapan');
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Ucapan Terkirim!',
                    text: 'Ucapan selamat ulang tahun telah diproses.',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                alert('Ucapan selamat ulang tahun telah diproses.');
            }
        }, 800);
    });
});
</script>
@endpush
