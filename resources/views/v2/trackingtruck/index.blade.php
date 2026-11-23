@extends('layouts.app')
@section('titlepage', 'Tracking Truck')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Tracking Truck</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Tracking Truck & Monitoring GPS</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pemantauan posisi armada secara langsung (Tracksolid) dan rekapitulasi data perjalanan kendaraan.</p>
        </div>

        <!-- SEGMENTED TABS PILLS -->
        <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 shrink-0" role="tablist">
            <button type="button" 
                class="tab-btn px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs" 
                id="btn-tab-live" data-target="#live-gps">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                <span>Live GPS (Tracksolid)</span>
            </button>
            <button type="button" 
                class="tab-btn px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white" 
                id="btn-tab-rekap" data-target="#rekap-laporan">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                <span>Rekap Perjalanan per Tanggal</span>
            </button>
        </div>
    </div>

    <!-- TAB CONTENTS -->
    <div class="tab-content w-full">
        
        <!-- TAB 1: LIVE GPS (TRACKSOLID) -->
        <div class="tab-pane-custom space-y-3" id="live-gps">
            
            <!-- AKSES LOGIN TRACKSOLID BANNER -->
            <div class="rounded-xl border border-blue-200/80 dark:border-blue-900/50 bg-gradient-to-r from-blue-50/90 via-white to-blue-50/60 dark:from-blue-950/40 dark:via-slate-900 dark:to-blue-950/20 p-3 sm:px-4 sm:py-3 shadow-2xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#284c9a]/10 dark:bg-blue-400/10 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Akses Login Tracksolid</h4>
                            <p class="text-[11px] text-slate-400">Salin kredensial untuk masuk ke dasbor GPS pada frame di bawah.</p>
                        </div>
                    </div>

                    <!-- KREDENSIAL STRIP -->
                    <div class="flex flex-wrap items-center gap-3">
                        
                        <!-- Username -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Username:</span>
                            <div class="relative flex items-center">
                                <input type="text" id="track_user" value="cvmakmurpermata" readonly
                                    class="h-[30px] w-36 px-2.5 text-xs font-mono-num font-bold rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 select-all">
                                <button type="button" onclick="copyText('track_user')" title="Salin Username"
                                    class="h-[30px] px-2.5 rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer flex items-center justify-center">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Password:</span>
                            <div class="relative flex items-center">
                                <input type="password" id="track_pass" value="Makmurpermata@160" readonly
                                    class="h-[30px] w-40 px-2.5 text-xs font-mono-num font-bold rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 select-all">
                                <button type="button" onclick="togglePass()" title="Tampilkan Password"
                                    class="h-[30px] px-2 border-y border-slate-200 dark:border-slate-700 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer flex items-center justify-center">
                                    <i data-lucide="eye" id="eye_icon" class="w-3.5 h-3.5"></i>
                                </button>
                                <button type="button" onclick="copyText('track_pass')" title="Salin Password"
                                    class="h-[30px] px-2.5 rounded-r-lg border border-slate-200 dark:border-slate-700 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer flex items-center justify-center">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Help badge & Open in New Tab -->
                        <div class="flex items-center gap-2">
                            <span class="hidden 2xl:inline-flex items-center gap-1 text-[11px] text-slate-400 italic">
                                <i data-lucide="help-circle" class="w-3.5 h-3.5 text-amber-500"></i>
                                <span>Tempelkan pada form di bawah</span>
                            </span>
                            <a href="https://www.tracksolidpro.com/resource/dev/index.html?t=247023#/live" target="_blank"
                                class="inline-flex items-center gap-1.5 h-[30px] px-2.5 rounded-lg border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-900 hover:bg-blue-50 dark:hover:bg-slate-800 text-[#284c9a] dark:text-blue-400 text-xs font-bold transition shadow-2xs">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                <span>Tab Baru</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- IFRAME TRACKSOLID (EXPLICIT VIEWPORT HEIGHT) -->
            <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-slate-900 shadow-2xs w-full" style="height: calc(100vh - 225px); min-height: 650px;">
                <iframe src="https://www.tracksolidpro.com/resource/dev/index.html?t=247023#/live" 
                    style="width: 100%; height: 100%; min-height: 650px; border: 0;"
                    allowfullscreen>
                </iframe>
            </div>

        </div>

        <!-- TAB 2: REKAP LAPORAN PERJALANAN GPS -->
        <div class="tab-pane-custom space-y-3.5 hidden" id="rekap-laporan">
            
            <!-- METRIC STRIP PACIFIC NAVY (STANDAR V2) -->
            <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs hover:shadow-md transition">
                <!-- SVG Background Arc Pattern -->
                <div class="absolute inset-0 pointer-events-none opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 800 200" fill="none" preserveAspectRatio="none">
                        <path d="M-100 250 C 200 50, 400 300, 900 100" stroke="white" stroke-width="60" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="relative grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 divide-x-0 lg:divide-x divide-white/15">
                    
                    <!-- 1. Total Kendaraan -->
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Total Kendaraan</span>
                            <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                                {{ $reports->unique('device_name')->count() }} <span class="text-xs font-semibold text-white/75 font-sans">Unit</span>
                            </div>
                            <span class="text-[10px] text-white/55 block">Unit terdata di filter</span>
                        </div>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                            <i data-lucide="truck" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                    </div>

                    <!-- 2. Total Jarak Tempuh -->
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Total Jarak Tempuh</span>
                            <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                                {{ number_format($reports->sum('total_mileage'), 2, ',', '.') }} <span class="text-xs font-semibold text-white/75 font-sans">km</span>
                            </div>
                            <span class="text-[10px] text-white/55 block">Jarak tempuh periode</span>
                        </div>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                            <i data-lucide="milestone" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                    </div>

                    <!-- 3. Total Konsumsi BBM -->
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Total Konsumsi BBM</span>
                            <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                                {{ number_format($reports->sum('total_fuel_consumption'), 2, ',', '.') }} <span class="text-xs font-semibold text-white/75 font-sans">L</span>
                            </div>
                            <span class="text-[10px] text-white/55 block">BBM terpakai periode</span>
                        </div>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                            <i data-lucide="fuel" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                    </div>

                    <!-- 4. Rata-Rata Kecepatan -->
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Rata-Rata Kecepatan</span>
                            <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                                {{ number_format($reports->avg('average_speed'), 2, ',', '.') }} <span class="text-xs font-semibold text-white/75 font-sans">km/h</span>
                            </div>
                            <span class="text-[10px] text-white/55 block">Kecepatan rata-rata unit</span>
                        </div>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                            <i data-lucide="gauge" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FILTER SECTION (NO-LABEL, H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs">
                <form action="{{ route('trackingtruck.index') }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                    
                    <!-- Tanggal Mulai (Col 3) -->
                    <div class="lg:col-span-3 relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="start_date" id="start_date" value="{{ $start_date }}"
                            class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                            placeholder="Tanggal Mulai" readonly>
                    </div>

                    <!-- Tanggal Selesai (Col 3) -->
                    <div class="lg:col-span-3 relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="end_date" id="end_date" value="{{ $end_date }}"
                            class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                            placeholder="Tanggal Selesai" readonly>
                    </div>

                    <!-- Dropdown Kendaraan (Col 4) -->
                    <div class="lg:col-span-4 relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="search" id="search"
                            class="select2Kendaraan w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                            <option value="">Semua Kendaraan</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->no_polisi }}" {{ $search == $v->no_polisi ? 'selected' : '' }}>
                                    {{ $v->no_polisi }} [{{ $v->merek }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons (Col 2) -->
                    <div class="lg:col-span-2 flex items-center justify-end gap-1.5">
                        <button type="submit" title="Terapkan Filter"
                            class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Cari</span>
                        </button>

                        <button type="button" id="btnOpenImport" title="Import File Excel"
                            class="h-[34px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                            <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Import</span>
                        </button>

                        <button type="button" onclick="confirmDeletePeriod()" title="Hapus Periode Terfilter"
                            class="h-[34px] w-[34px] rounded-lg border border-rose-200 dark:border-rose-900/60 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer shrink-0">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                </form>

                <form action="{{ route('trackingtruck.delete-period') }}" method="POST" id="formDeletePeriod" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="start_date" value="{{ $start_date }}">
                    <input type="hidden" name="end_date" value="{{ $end_date }}">
                </form>
            </div>

            <!-- DATA TABLE V2 (KOMPAK ~36PX, 2-BARIS THEAD, PACIFIC NAVY) -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <!-- Row 1: Header Strip Pacific Navy (Colspan 11) -->
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="11" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="truck" class="w-4 h-4 text-blue-200"></i>
                                            <span class="font-bold text-xs uppercase tracking-wider text-white">Daftar Rekap Perjalanan GPS</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/10 text-white/90 uppercase tracking-wider font-mono-num">
                                            TOTAL: {{ $reports->count() }} DATA PERJALANAN
                                        </span>
                                    </div>
                                </th>
                            </tr>

                            <!-- Row 2: Header Kolom Deep Navy -->
                            <tr class="bg-[#1e3a78] text-white border-t border-white/10">
                                <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90 w-12">NO</th>
                                <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">TANGGAL</th>
                                <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90">DEVICE NAME</th>
                                <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">IMEI</th>
                                <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">MODEL</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">MILEAGE (km)</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">FUEL USED (L)</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">FUEL/100 (L)</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">AVG SPEED (km/h)</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">MAX SPEED (km/h)</th>
                                <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90 w-20">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($reports as $r)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="py-1.5 px-3 text-center font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ formatIndo($r->tanggal) }}</td>
                                    <td class="py-1.5 px-3 text-left font-bold text-[#284c9a] dark:text-blue-400">{{ $r->device_name }}</td>
                                    <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $r->imei }}</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                                            {{ $r->model }}
                                        </span>
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($r->total_mileage, 2, ',', '.') }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-emerald-600 dark:text-emerald-400">
                                        {{ $r->total_fuel_consumption > 0 ? number_format($r->total_fuel_consumption, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-300">
                                        {{ $r->fuel_ratio > 0 ? number_format($r->fuel_ratio, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-200">
                                        {{ number_format($r->average_speed, 2, ',', '.') }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                        {{ number_format($r->max_speed, 2, ',', '.') }}
                                    </td>
                                    <td class="py-1.5 px-3 text-center">
                                        <button type="button" 
                                            class="show-details w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/50 dark:text-blue-400 inline-flex items-center justify-center transition cursor-pointer mx-auto shadow-2xs"
                                            data-device-name="{{ $r->device_name }}" 
                                            data-tanggal="{{ $r->tanggal }}"
                                            title="Lihat Detail Log Perjalanan">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="database" class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2 stroke-1"></i>
                                            <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum Ada Data Rekap Perjalanan</h4>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan filter tanggal atau import file Excel detail perjalanan GPS.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- MODAL IMPORT GPS (STANDAR V2) -->
<div id="modalImportGPS" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="modal-backdrop-custom fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6">
        <div class="modal-dialog-box relative bg-white dark:bg-slate-900 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-md w-full border border-slate-200/80 dark:border-slate-800">
            
            <!-- Modal Header Bersih -->
            <div class="px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Import Excel Detail Perjalanan</h4>
                        <p class="text-[10px] text-slate-400">Unggah file data log perjalanan GPS</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('trackingtruck.import') }}" method="POST" enctype="multipart/form-data" id="formImport">
                @csrf
                <div class="p-4 space-y-3">
                    
                    <!-- Alert Notice Info -->
                    <div class="rounded-lg bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/60 p-2.5 flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5"></i>
                        <div class="text-[11px] text-slate-600 dark:text-slate-300 space-y-0.5">
                            <p>Sistem secara otomatis mendeteksi identitas kendaraan (Device Name & IMEI) dari file Excel.</p>
                            <p class="text-amber-600 dark:text-amber-400 font-semibold">Tentukan kendaraan cadangan (fallback) di bawah jika tidak otomatis terdeteksi.</p>
                        </div>
                    </div>

                    <!-- No Polisi Fallback -->
                    <div class="field-group">
                        <label for="device_name_manual" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            No Polisi / Kendaraan (Fallback)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="device_name_manual" id="device_name_manual"
                                class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                                <option value="">-- Pilih Kendaraan Fallback --</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->no_polisi }}">{{ $v->no_polisi }} [{{ $v->merek }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- IMEI Fallback -->
                    <div class="field-group">
                        <label for="imei_manual" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            IMEI (Fallback / Opsional)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="imei_manual" id="imei_manual" placeholder="Contoh: 863539031234567"
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>

                    <!-- File Input -->
                    <div class="field-group">
                        <label for="file" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            File Excel Detail (.xlsx, .xls, .csv) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#284c9a] hover:file:bg-blue-100 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 p-1">
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
                    <button type="button" class="btn-close-modal h-[32px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitImport"
                        class="h-[32px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>Mulai Import</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL DETAIL PERJALANAN (STANDAR V2) -->
<div id="modalDetailPerjalanan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="modal-backdrop-custom fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6">
        <div class="modal-dialog-box relative bg-white dark:bg-slate-900 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-5xl w-full border border-slate-200/80 dark:border-slate-800">
            
            <!-- Modal Header Bersih -->
            <div class="px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="route" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Detail Perjalanan GPS</h4>
                        <p class="text-[10px] text-slate-400">Rincian log riwayat rute, kecepatan, dan konsumsi BBM</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-4" id="detailPerjalananContent">
                <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat detail perjalanan...</span>
                </div>
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

        // Flatpickr
        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d"
            });
        }

        // Tab Switching logic
        function switchTab(targetId) {
            $('.tab-btn').removeClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                          .addClass('text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white');
            $(`.tab-btn[data-target="${targetId}"]`).addClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                                                  .removeClass('text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white');
            
            $('.tab-pane-custom').addClass('hidden');
            $(targetId).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        $('.tab-btn').on('click', function() {
            var target = $(this).data('target');
            switchTab(target);
            window.location.hash = target;
        });

        // Auto switch tab if URL has hash or if query params (filter applied)
        var hash = window.location.hash;
        if (hash === '#rekap-laporan' || hash === '#live-gps') {
            switchTab(hash);
        } else {
            @if(Request::has('start_date') || Request::has('end_date') || Request::has('search'))
                switchTab('#rekap-laporan');
            @endif
        }

        // Modal Helpers (Static Backdrop & Shake animation)
        function openModal($modal) {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal($modal) {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
        }

        function shakeModal($modal) {
            const $box = $modal.find('.modal-dialog-box');
            $box.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $box.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Open Import Modal
        $('#btnOpenImport').on('click', function() {
            openModal($('#modalImportGPS'));
        });

        // Close Modal Handlers (Explicit Close Only)
        $(document).on('click', '.btn-close-modal', function() {
            const $modal = $(this).closest('.fixed.inset-0');
            closeModal($modal);
        });

        // Backdrop click protection (Static Backdrop)
        $('.modal-backdrop-custom').on('click', function() {
            const $modal = $(this).closest('.fixed.inset-0');
            shakeModal($modal);
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                const $activeModal = $('.fixed.inset-0:not(.hidden)');
                if ($activeModal.length) {
                    shakeModal($activeModal);
                }
            }
        });

        // Detail Perjalanan Loading via AJAX
        $('.show-details').on('click', function() {
            var deviceName = $(this).data('device-name');
            var tanggal = $(this).data('tanggal');
            
            $('#detailPerjalananContent').html(`
                <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat detail perjalanan...</span>
                </div>
            `);
            
            openModal($('#modalDetailPerjalanan'));
            
            $.get('{{ url("/trackingtruck/detail") }}', {
                device_name: deviceName,
                tanggal: tanggal
            }, function(data) {
                $('#detailPerjalananContent').html(data);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $('#detailPerjalananContent').html(`
                    <div class="p-4 rounded-lg bg-rose-50 text-rose-600 text-xs font-semibold text-center">
                        Gagal memuat data detail perjalanan. Silakan coba kembali.
                    </div>
                `);
            });
        });

        // Double submit prevention for Import
        $('#formImport').on('submit', function() {
            const $btn = $('#btnSubmitImport');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mengimpor...</span>
            `);
        });
    });

    // Copy to clipboard helper
    function copyText(id) {
        var copyText = document.getElementById(id);
        var originalType = copyText.type;
        
        copyText.type = "text";
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        copyText.type = originalType;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Tersalin!',
                text: 'Kredensial berhasil disalin ke clipboard',
                timer: 1200,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    }

    // Toggle password visibility
    function togglePass() {
        var x = document.getElementById("track_pass");
        var icon = document.getElementById("eye_icon");
        if (x.type === "password") {
            x.type = "text";
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            x.type = "password";
            icon.setAttribute('data-lucide', 'eye');
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Confirm Delete Period with Swal
    function confirmDeletePeriod() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: "Hapus Laporan Filtered?",
                text: "Semua data GPS kendaraan pada rentang tanggal filter saat ini akan dihapus dan tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus Data!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDeletePeriod').submit();
                }
            });
        } else {
            if (confirm("Hapus semua data GPS pada periode filter saat ini?")) {
                document.getElementById('formDeletePeriod').submit();
            }
        }
    }
</script>
@endpush
