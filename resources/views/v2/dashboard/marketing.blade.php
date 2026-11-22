@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Dashboard Utama</span>
    </div>
@endsection

@section('content')
<style>
    .stat-card-white {
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .stat-card-white:hover {
        transform: translateY(-2px);
    }
</style>

<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- STATISTIC CARDS STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Background Radial Glow -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 divide-y sm:divide-y-0 sm:divide-x xl:divide-x divide-white/15">
            
            <!-- 1. Ajuan Limit Kredit -->
            <a href="{{ route('ajuanlimit.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="credit-card" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-300 animate-pulse"></span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_limitkredit ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Limit Kredit</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Pengajuan menunggu</p>
                    </div>
                </div>
            </a>

            <!-- 2. Ajuan Faktur Kredit -->
            <a href="{{ route('ajuanfaktur.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="file-text" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-300 animate-pulse"></span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-amber-300 font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_ajuanfaktur ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Faktur Kredit</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Persetujuan faktur</p>
                    </div>
                </div>
            </a>

            <!-- 3. Penilaian Karyawan -->
            <a href="{{ route('penilaiankaryawan.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="users" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-sky-400/20 text-sky-300 flex items-center justify-center border border-sky-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-sky-300 animate-pulse"></span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-sky-300 font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_penilaiankaryawan ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Penilaian SDM</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Evaluasi kerja</p>
                    </div>
                </div>
            </a>

            <!-- 4. Pengajuan Izin -->
            <a href="{{ route('izinabsen.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="calendar-check" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-emerald-300 font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_pengajuan_izin ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Izin Kerja</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Pengajuan absen</p>
                    </div>
                </div>
            </a>

            <!-- 5. Lembur -->
            <a href="{{ route('lembur.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="clock" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-purple-400/20 text-purple-300 flex items-center justify-center border border-purple-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-purple-300 animate-pulse"></span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-purple-300 font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_lembur ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Lembur</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Persetujuan lembur</p>
                    </div>
                </div>
            </a>

            <!-- 6. Target Sales -->
            <div class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[128px] hover:bg-white/[0.05] transition-colors duration-200">
                <!-- Decorative Vector Arcs -->
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <!-- Background Watermark Icon -->
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="target" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <!-- Content Area -->
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-rose-400/20 text-rose-300 flex items-center justify-center border border-rose-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="target" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20 uppercase tracking-wider">Aktif</span>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ $notifikasi_target ?? 0 }}
                        </div>
                        <p class="text-xs font-bold text-white mt-1.5 leading-tight">Target Sales</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Monitoring target</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN 3 FILTER CARDS (COMPACT & MODERN NO-LABEL WITH ICONS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- CARD 1: REKAP PENJUALAN -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0">
                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Penjualan</h3>
                        <p class="text-[10px] text-slate-400 leading-none">Filter transaksi & omset</p>
                    </div>
                </div>

                <form action="#" id="formRekappenjualan" class="space-y-2">
                    @hasanyrole($roles_show_cabang)
                    <div class="relative select2-icon-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-10 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_rekappenjualan" id="kode_cabang_rekappenjualan" class="select2Kodecabangrekappenjualan w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasanyrole

                    <div class="grid grid-cols-2 gap-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="bulan" id="bulan" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="tahun" id="tahun" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btnRekappenjualan" class="w-full h-[34px] mt-1 rounded-lg bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white font-bold text-xs shadow-2xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Rekap</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CARD 2: ANALISA UMUR PIUTANG (AUP) -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-900 shrink-0">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Analisa Umur Piutang</h3>
                        <p class="text-[10px] text-slate-400 leading-none">Aging schedule & tagihan</p>
                    </div>
                </div>

                <form action="#" id="formAup" class="space-y-2">
                    @hasanyrole($roles_show_cabang)
                    <div class="relative select2-icon-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-10 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_aup" id="kode_cabang_aup" class="select2KodecabangAup w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasanyrole

                    <div class="grid grid-cols-2 gap-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal_aup" value="{{ date('Y-m-d') }}" class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Pilih Tanggal">
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="exclude" id="exclude" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="1">Exclude</option>
                                <option value="2">Include</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btnAup" class="w-full h-[34px] mt-1 rounded-lg bg-amber-600 hover:bg-amber-700 active:scale-[0.99] text-white font-bold text-xs shadow-2xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan AUP</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CARD 3: REKAP KENDARAAN -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-900 shrink-0">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Kendaraan</h3>
                        <p class="text-[10px] text-slate-400 leading-none">Monitoring logistik & armada</p>
                    </div>
                </div>

                <form action="#" id="formRekapkendaraan" class="space-y-2">
                    @hasanyrole($roles_show_cabang)
                    <div class="relative select2-icon-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-10 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_rekapkendaraan" id="kode_cabang_rekapkendaraan" class="select2Kodecabangrekapkendaraan w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasanyrole

                    <div class="grid grid-cols-2 gap-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="bulan" id="bulan_kendaraan" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="tahun" id="tahun_kendaraan" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btnRekapkendaraan" class="w-full h-[34px] mt-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-bold text-xs shadow-2xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Kendaraan</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- REKAP PERSEDIAAN (FULL WIDTH) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs">
        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 dark:border-slate-800 flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0">
                    <i data-lucide="boxes" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Persediaan Barang</h3>
                    <p class="text-[10px] text-slate-400 leading-none">Monitoring stok gudang pusat & seluruh cabang</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ Route::has('sagudangjadi.index') ? route('sagudangjadi.index') : (Route::has('laporangudangjadi.index') ? route('laporangudangjadi.index') : '#') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors">
                    <i data-lucide="archive" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>Saldo Awal Gudang</span>
                </a>
                <button type="button" onclick="loadrekappersediaan()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-50 dark:bg-brand-950/40 hover:bg-brand-100 dark:hover:bg-brand-900/60 border border-brand-200 dark:border-brand-800 text-xs font-bold text-brand-600 dark:text-brand-400 transition-colors cursor-pointer" title="Refresh Stok">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>Segarkan</span>
                </button>
            </div>
        </div>
        
        <div id="loadrekappersediaan" class="min-h-[160px] overflow-x-auto">
            <!-- AJAX injected here -->
        </div>
    </div>

</div>

<!-- MODAL POPUP FORM (100% KOMPATIBEL DENGAN V1) -->
<x-modal-form id="modal" show="loadmodal" title="" size="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const formRekappenjualan = $('#formRekappenjualan');
        const formRekapkendaraan = $('#formRekapkendaraan');
        const formAup = $('#formAup');

        // Flatpickr datepicker
        if ($('.flatpickr-date').length) {
            $('.flatpickr-date').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        }

        // Select2 initialization
        if ($('.select2Kodecabangrekappenjualan').length) {
            $('.select2Kodecabangrekappenjualan').select2({
                placeholder: 'Semua Cabang',
                allowClear: true
            });
        }

        if ($('.select2KodecabangAup').length) {
            $('.select2KodecabangAup').select2({
                placeholder: 'Semua Cabang',
                allowClear: true
            });
        }

        if ($('.select2Kodecabangrekapkendaraan').length) {
            $('.select2Kodecabangrekapkendaraan').select2({
                placeholder: 'Semua Cabang',
                allowClear: true
            });
        }

        function loading() {
            $("#loadmodal").html(`<div class="flex items-center justify-center p-8 text-center">
                <div class="inline-block w-6 h-6 border-3 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-xs font-semibold text-slate-500">Memuat data...</p>
            </div>`);
        }

        // Form Rekap Penjualan Submit Handler
        formRekappenjualan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
            const bulan = formRekappenjualan.find('#bulan').val();
            const tahun = formRekappenjualan.find('#tahun').val();
            
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning"
                });
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning"
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Penjualan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl modal-xxxl').addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekappenjualan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        // Form Rekap Kendaraan Submit Handler
        formRekapkendaraan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekapkendaraan.find('#kode_cabang_rekapkendaraan').val();
            const bulan = formRekapkendaraan.find('#bulan_kendaraan').val();
            const tahun = formRekapkendaraan.find('#tahun_kendaraan').val();
            
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning"
                });
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning"
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Kendaraan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl modal-xxxl').addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapkendaraan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        // Form AUP Submit Handler
        formAup.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formAup.find('#kode_cabang_aup').val();
            const tanggal = formAup.find('#tanggal_aup').val();
            const exclude = formAup.find('#exclude').val();
            
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning"
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Analisa Umur Piutang (AUP)');
                $("#modal").find(".modal-dialog").removeClass('modal-xl modal-xxxl').addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapaup') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        exclude: exclude,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        // Load Rekap Persediaan via AJAX
        window.loadrekappersediaan = function() {
            $("#loadrekappersediaan").html(`<div class="flex items-center justify-center p-8 text-center">
                <div class="inline-block w-6 h-6 border-3 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-xs font-semibold text-slate-500">Memuat stok persediaan...</p>
            </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaan', function() {
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        };

        loadrekappersediaan();
    });
</script>
@endpush
