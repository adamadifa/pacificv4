@extends('layouts.app')
@section('titlepage', 'Pelanggan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Pelanggan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Pelanggan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola database pelanggan outlet, rute kunjungan, dan plafon limit kredit.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('pelanggan.create')
                <button type="button" id="btnCreatePelanggan"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Pelanggan</span>
                </button>
            @endcan

            @can('pelanggan.show')
                <form action="/pelanggan/export" method="GET" id="formCetak" target="_blank" class="inline-flex items-center gap-1.5">
                    <input type="hidden" name="dari" value="{{ request('dari') }}" />
                    <input type="hidden" name="sampai" value="{{ request('sampai') }}" />
                    <input type="hidden" name="kode_cabang" value="{{ request('kode_cabang') }}" />
                    <input type="hidden" name="kode_salesman" value="{{ request('kode_salesman') }}" />
                    <input type="hidden" name="status" value="{{ request('status') }}" />

                    <button type="submit" name="cetakButton" value="1"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                        <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Cetak</span>
                    </button>

                    <button type="submit" name="exportButton" value="1"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 text-xs font-bold transition shadow-2xs cursor-pointer">
                        <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400"></i>
                        <span>Excel</span>
                    </button>
                </form>

                <button type="button" id="btnNonaktifPelanggan"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg border border-rose-200 dark:border-rose-800/80 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 text-xs font-bold transition shadow-2xs cursor-pointer">
                    <i data-lucide="user-x" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400"></i>
                    <span>Nonaktifkan Massal</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- 3 KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS & V1 3D ILLUSTRATIONS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 md:grid-cols-3">
            
            <!-- 1. Database Pelanggan -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex items-center justify-between min-h-[120px] border-b md:border-b-0 md:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.04] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full pr-2">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white border border-white/20">
                                Database
                            </span>
                            <span class="text-xs font-bold text-white/90">Database Pelanggan! 🎉</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-white font-mono-num tracking-tight mt-2 leading-none">
                            {{ formatRupiah($jmlpelanggan) }}
                        </div>
                    </div>
                    <p class="text-[11px] text-white/60 leading-none mt-2">Total keseluruhan pelanggan</p>
                </div>

                <div class="shrink-0 -mb-1 -mr-1 relative z-10">
                    <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" alt="Database Pelanggan" class="h-20 sm:h-24 object-contain pointer-events-none select-none drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>

            <!-- 2. Pelanggan Aktif -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex items-center justify-between min-h-[120px] border-b md:border-b-0 md:border-r border-white/15 hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.04] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full pr-2">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">
                                Aktif
                            </span>
                            <span class="text-xs font-bold text-white/90">Pelanggan Aktif! 🎉</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-300 font-mono-num tracking-tight mt-2 leading-none">
                            {{ formatRupiah($jmlpelangganaktif) }}
                        </div>
                    </div>
                    <p class="text-[11px] text-white/60 leading-none mt-2">Outlet aktif bertransaksi</p>
                </div>

                <div class="shrink-0 -mb-1 -mr-1 relative z-10">
                    <img src="{{ asset('assets/img/illustrations/girl-with-laptop.png') }}" alt="Pelanggan Aktif" class="h-20 sm:h-24 object-contain pointer-events-none select-none drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>

            <!-- 3. Pelanggan Non Aktif -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex items-center justify-between min-h-[120px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.04] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="relative z-10 flex flex-col justify-between h-full pr-2">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-400/20 text-rose-200 border border-rose-300/30">
                                Non Aktif
                            </span>
                            <span class="text-xs font-bold text-white/90">Pelanggan Non Aktif! 🎉</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-rose-300 font-mono-num tracking-tight mt-2 leading-none">
                            {{ formatRupiah($jmlpelanggannonaktif) }}
                        </div>
                    </div>
                    <p class="text-[11px] text-white/60 leading-none mt-2">Outlet nonaktif / suspend</p>
                </div>

                <div class="shrink-0 -mb-1 -mr-1 relative z-10">
                    <img src="{{ asset('assets/img/illustrations/inactive-customer.png') }}" alt="Pelanggan Non Aktif" class="h-20 sm:h-24 object-contain pointer-events-none select-none drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>

        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('pelanggan.index') }}" method="GET" id="filterFormPelanggan" class="space-y-2">
            
            <!-- Row 1: Periode & Status & Cabang -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                <!-- Dari Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="dari" id="dari" value="{{ request('dari') }}" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                        placeholder="Dari Tanggal">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                        placeholder="Sampai Tanggal">
                </div>

                <!-- Status Aktif / Non Aktif -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status" id="status_filter"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Cabang (Jika Berhak) -->
                @hasanyrole(config('global.roles_show_cabang'))
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_filter"
                            class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                @endhasanyrole
            </div>

            <!-- Row 2: Salesman, Kode, Nama & Action -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                <!-- Dropdown Salesman (Dynamic by Cabang) -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_salesman" id="kode_salesman_filter"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Salesman</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Input Kode Pelanggan -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="kode_pelanggan" id="kode_pelanggan" value="{{ request('kode_pelanggan') }}"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold uppercase font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Kode Pelanggan (Contoh: BDG-0001)">
                </div>

                <!-- Input Nama Pelanggan -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ request('nama_pelanggan') }}"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Cari nama toko / pelanggan...">
                </div>

                <!-- Search & Reset Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari Pelanggan</span>
                    </button>

                    @if (request()->hasAny(['dari', 'sampai', 'status', 'kode_cabang', 'kode_salesman', 'kode_pelanggan', 'nama_pelanggan']))
                        <a href="{{ route('pelanggan.index') }}"
                            class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- FULL-WIDTH CARD LIST CONTAINER (CLEAN, COMPACT, MODERN & ELEGANT) -->
    <div class="space-y-1.5 w-full">
        @forelse ($pelanggan as $d)
            <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 px-3.5 py-2 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-xs transition duration-150">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                    
                    <!-- 1. IDENTITAS PELANGGAN & TOKO (Avatar, Nama, Kode, Status, Tag, Alamat) -->
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <!-- Icon Avatar -->
                        <div class="shrink-0">
                            @if (!empty($d->foto) && Storage::disk('public')->exists('/pelanggan/' . $d->foto))
                                <img src="{{ getfotoPelanggan($d->foto) }}" alt="Foto Outlet" class="w-8 h-8 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center">
                                    <i data-lucide="store" class="w-4 h-4 text-slate-400"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Data Outlet -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap leading-tight">
                                <span class="text-xs sm:text-[13px] font-bold text-slate-800 dark:text-slate-100 truncate">
                                    {!! textCamelCase($d->nama_pelanggan) !!}
                                </span>
                                <span class="px-1.5 py-0.5 rounded font-mono-num font-bold text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    {{ $d->kode_pelanggan }}
                                </span>
                                @if ($d->status_aktif_pelanggan == '1')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 dark:text-rose-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Non Aktif
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400 flex-wrap">
                                @if(!empty($d->klasifikasi))
                                    <span class="font-semibold text-purple-600 dark:text-purple-400">
                                        {{ $d->klasifikasi }}
                                    </span>
                                    <span>•</span>
                                @endif
                                <span class="truncate max-w-sm sm:max-w-md text-slate-500 dark:text-slate-400" title="{{ $d->alamat_toko ?: $d->alamat_pelanggan }}">
                                    {{ textCamelCase($d->alamat_toko ?: $d->alamat_pelanggan) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. INFO OPERASIONAL (Cabang, Salesman, Wilayah) -->
                    <div class="hidden md:flex items-center gap-5 shrink-0 border-x border-slate-100 dark:border-slate-800 px-4 text-xs">
                        <!-- Cabang -->
                        <div class="text-center">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Cabang</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200 mt-0.5 inline-block">
                                {{ $d->kode_cabang }}
                            </span>
                        </div>
                        <!-- Salesman -->
                        <div class="min-w-[130px] max-w-[180px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Salesman</span>
                            <div class="flex items-center gap-1 text-slate-700 dark:text-slate-200 mt-0.5 truncate font-medium" title="{{ textCamelCase($d->nama_salesman) }}">
                                <i data-lucide="user-check" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ textCamelCase($d->nama_salesman) }}</span>
                            </div>
                        </div>
                        <!-- Wilayah -->
                        <div class="min-w-[120px] max-w-[170px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Wilayah</span>
                            <div class="flex items-center gap-1 text-slate-600 dark:text-slate-300 mt-0.5 truncate font-medium" title="{{ textCamelCase($d->nama_wilayah ?: '-') }}">
                                <i data-lucide="compass" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ textCamelCase($d->nama_wilayah ?: '-') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. LIMIT PIUTANG & TANGGAL -->
                    <div class="flex sm:flex-col sm:items-end justify-between text-left sm:text-right shrink-0 min-w-[120px]">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Limit Piutang</span>
                            <span class="text-xs sm:text-[13px] font-black font-mono-num text-slate-800 dark:text-slate-100">
                                {{ empty($d->limit_pelanggan) ? 'Rp 0' : formatRupiah($d->limit_pelanggan) }}
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono-num">
                            Reg: {{ date('d/m/y', strtotime($d->tanggal_register)) }}
                        </span>
                    </div>

                    <!-- 4. AKSI KOMPAK -->
                    <div class="flex items-center justify-end gap-1 shrink-0">
                        @can('pelanggan.show')
                            <a href="{{ route('pelanggan.show', Crypt::encrypt($d->kode_pelanggan)) }}"
                                class="w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/60 transition flex items-center justify-center shadow-2xs"
                                title="Lihat Detail Profil">
                                <i data-lucide="eye" class="w-3 h-3"></i>
                            </a>
                        @endcan

                        @can('pelanggan.edit')
                            <button type="button"
                                class="btnEditPelanggan w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                title="Edit Data Pelanggan"
                                data-kode="{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                            </button>
                        @endcan

                        @can('pelanggan.delete')
                            <form method="POST" action="{{ route('pelanggan.delete', Crypt::encrypt($d->kode_pelanggan)) }}"
                                class="inline-flex m-0 p-0 deleteform">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                    title="Hapus Pelanggan">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </form>
                        @endcan
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                        <i data-lucide="store" class="w-6 h-6"></i>
                    </div>
                    <p class="text-xs font-semibold">Tidak ditemukan data pelanggan yang sesuai dengan filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION (STANDARD V2 COMPONENT) -->
    @if ($pelanggan->hasPages())
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 shadow-2xs">
            {{ $pelanggan->links('v2.components.pagination') }}
        </div>
    @endif

</div>

<!-- TAILWIND MODAL CREATE PELANGGAN -->
<div id="modalCreatePelanggan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="store" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Pendaftaran Pelanggan Baru
                </h4>
            </div>
            <button type="button" class="btnCloseModalPelanggan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentCreatePelanggan" class="p-4 max-h-[85vh] overflow-y-auto">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<!-- TAILWIND MODAL EDIT PELANGGAN -->
<div id="modalEditPelanggan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Edit Data Pelanggan
                </h4>
            </div>
            <button type="button" class="btnCloseModalPelanggan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentEditPelanggan" class="p-4 max-h-[85vh] overflow-y-auto">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<!-- TAILWIND MODAL NONAKTIF PELANGGAN -->
<div id="modalNonaktifPelanggan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-rose-50/50 dark:bg-rose-950/20">
            <div class="flex items-center gap-2">
                <i data-lucide="user-x" class="w-4 h-4 text-rose-600 dark:text-rose-400"></i>
                <h4 class="text-xs font-bold text-rose-700 dark:text-rose-300 uppercase tracking-wide">
                    Nonaktifkan Pelanggan Massal (Tanpa Transaksi)
                </h4>
            </div>
            <button type="button" class="btnCloseModalPelanggan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentNonaktifPelanggan" class="p-4 max-h-[85vh] overflow-y-auto">
            <!-- Loaded via AJAX -->
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

        // Flatpickr for Filter
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Dynamic Salesman Dropdown in Search Filter
        function getsalesmanbyCabangFilter() {
            var kode_cabang = $("#kode_cabang_filter").val();
            var kode_salesman = "{{ request('kode_salesman') }}";
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
                    $("#kode_salesman_filter").html(respond);
                }
            });
        }

        getsalesmanbyCabangFilter();
        $("#kode_cabang_filter").on("change", function() {
            getsalesmanbyCabangFilter();
        });

        // Modals Management
        const $modalCreate = $("#modalCreatePelanggan");
        const $modalEdit = $("#modalEditPelanggan");
        const $modalNonaktif = $("#modalNonaktifPelanggan");

        function openModal($targetModal, $targetContent, url) {
            $targetContent.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
            $targetModal.removeClass("hidden");
            $("body").addClass("overflow-hidden");

            $targetContent.load(url, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        function closeModal() {
            $modalCreate.addClass("hidden");
            $modalEdit.addClass("hidden");
            $modalNonaktif.addClass("hidden");
            $("#modalContentCreatePelanggan").html("");
            $("#modalContentEditPelanggan").html("");
            $("#modalContentNonaktifPelanggan").html("");
            $("body").removeClass("overflow-hidden");
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
        }

        $(".btnCloseModalPelanggan").on("click", closeModal);

        // Static Backdrop: Dilarang close saat klik backdrop / luar modal
        $modalCreate.add($modalEdit).add($modalNonaktif).on("click", function(e) {
            if ($(e.target).is($(this))) {
                // Efek visual bounce / pulse halus memberitahu modal terkunci
                const $modalBox = $(this).find("> div");
                $modalBox.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $modalBox.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        // Cegah tombol keyboard Escape menutup modal secara tidak sengaja
        $(document).on("keydown", function(e) {
            if (e.key === "Escape" || e.keyCode === 27) {
                const $activeModal = $modalCreate.add($modalEdit).add($modalNonaktif).not(".hidden");
                if ($activeModal.length) {
                    e.preventDefault();
                    const $modalBox = $activeModal.find("> div");
                    $modalBox.addClass("scale-[1.015] shadow-2xl");
                    setTimeout(function() {
                        $modalBox.removeClass("scale-[1.015] shadow-2xl");
                    }, 150);
                }
            }
        });

        // Trigger Create
        $("#btnCreatePelanggan").on("click", function(e) {
            e.preventDefault();
            openModal($modalCreate, $("#modalContentCreatePelanggan"), '/pelanggan/create');
        });

        // Trigger Edit
        $(document).on("click", ".btnEditPelanggan", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            openModal($modalEdit, $("#modalContentEditPelanggan"), '/pelanggan/' + kode + '/edit');
        });

        // Trigger Nonaktif Massal
        $("#btnNonaktifPelanggan").on("click", function(e) {
            e.preventDefault();
            openModal($modalNonaktif, $("#modalContentNonaktifPelanggan"), '/pelanggan/nonaktif');
        });
    });
</script>
@endpush
