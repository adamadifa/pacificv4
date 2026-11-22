@extends('layouts.app')
@section('titlepage', 'Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Karyawan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola database profil karyawan, penempatan cabang, masa kerja, dan kontrol absensi mobile.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('karyawan.create')
                <button type="button" id="btnCreateKaryawan"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Karyawan</span>
                </button>
            @endcan

            @role('super admin')
                <a href="{{ route('karyawan.resetsession') }}"
                    class="btnResetAllSession inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg border border-rose-200 dark:border-rose-800/80 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 text-xs font-bold transition shadow-2xs">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400"></i>
                    <span>Reset All Session</span>
                </a>
            @endrole
        </div>
    </div>

    <!-- STATISTIC CARDS STRIP (Solid Pacific Navy with V1 3D Illustrations) -->
    <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs hover:shadow-md transition duration-200">
        <!-- Ambient Radial Glow and Wave Ornaments -->
        <div class="absolute -right-8 -bottom-8 pointer-events-none text-white/[0.06]">
            <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="100" cy="100" r="80" stroke-dasharray="8 6" />
                <circle cx="100" cy="100" r="50" />
                <circle cx="100" cy="100" r="20" />
            </svg>
        </div>
        <div class="absolute left-1/4 -top-10 w-44 h-44 bg-white/[0.04] rounded-full blur-2xl pointer-events-none"></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-white/15 relative z-10">
            <!-- 1. Total Karyawan -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Total Karyawan</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight">
                        {{ number_format(method_exists($karyawan, 'total') ? $karyawan->total() : $karyawan->count(), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Database seluruh personel</span>
                </div>
                <div class="shrink-0 pl-2">
                    <img src="{{ asset('assets/img/illustrations/karyawan1.png') }}" alt="Total Karyawan"
                        class="h-12 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                </div>
            </div>

            <!-- 2. Karyawan Aktif -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Karyawan Aktif</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight">
                        {{ number_format(\App\Models\Karyawan::where('status_aktif_karyawan', '1')->count(), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Personel aktif bekerja</span>
                </div>
                <div class="shrink-0 pl-2">
                    <img src="{{ asset('assets/img/illustrations/karyawan2.webp') }}" alt="Karyawan Aktif"
                        class="h-12 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                </div>
            </div>

            <!-- 3. Karyawan Tetap (PKWTT) -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Karyawan Tetap</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight">
                        {{ number_format(\App\Models\Karyawan::where('status_aktif_karyawan', '1')->where('status_karyawan', 'T')->count(), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Status perjanjian tetap</span>
                </div>
                <div class="shrink-0 pl-2">
                    <img src="{{ asset('assets/img/illustrations/karyawan3.png') }}" alt="Karyawan Tetap"
                        class="h-12 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                </div>
            </div>

            <!-- 4. Karyawan Kontrak (PKWT) -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Karyawan Kontrak</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight">
                        {{ number_format(\App\Models\Karyawan::where('status_aktif_karyawan', '1')->where('status_karyawan', 'K')->count(), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Status perjanjian kontrak</span>
                </div>
                <div class="shrink-0 pl-2">
                    <img src="{{ asset('assets/img/illustrations/karyawan4.webp') }}" alt="Karyawan Kontrak"
                        class="h-12 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT, ROUNDED-LG, H-[34PX]) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('karyawan.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Filter Cabang (Role Check) -->
                @hasanyrole(config('global.roles_show_cabang'))
                    <div class="lg:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search"
                            class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                @endhasanyrole

                <!-- Input Cari Nama Karyawan -->
                <div class="{{ auth()->user()->hasAnyRole(config('global.roles_show_cabang')) ? 'lg:col-span-3' : 'lg:col-span-4' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_karyawan" id="nama_karyawan_search" value="{{ request('nama_karyawan') }}"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Cari nama atau NIK karyawan...">
                </div>

                <!-- Filter Departemen -->
                <div class="{{ auth()->user()->hasAnyRole(config('global.roles_show_cabang')) ? 'lg:col-span-2' : 'lg:col-span-3' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $d)
                            <option value="{{ $d->kode_dept }}" {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                {{ $d->nama_dept }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Filter Group -->
                <div class="{{ auth()->user()->hasAnyRole(config('global.roles_show_cabang')) ? 'lg:col-span-2' : 'lg:col-span-3' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_group" id="kode_group_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Group</option>
                        @foreach ($group as $g)
                            <option value="{{ $g->kode_group }}" {{ request('kode_group') == $g->kode_group ? 'selected' : '' }}>
                                {{ $g->nama_group }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Search & Reset Buttons -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    @if (request()->hasAny(['kode_cabang_search', 'nama_karyawan', 'kode_dept', 'kode_group']))
                        <a href="{{ route('karyawan.index') }}"
                            class="inline-flex items-center justify-center gap-1 h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- FULL-WIDTH CARD LIST CONTAINER (KOMPAK, MODERN & ELEGAN SEPERTI /PELANGGAN) -->
    <div class="space-y-1.5 w-full">
        @forelse ($karyawan as $d)
            @php
                $tgl_masuk = !empty($d->tanggal_masuk) ? \Carbon\Carbon::parse($d->tanggal_masuk) : null;
                if ($tgl_masuk) {
                    $diff = $tgl_masuk->diff(\Carbon\Carbon::now());
                    $masa_kerja = $diff->y . ' Th ' . $diff->m . ' Bln';
                } else {
                    $masa_kerja = '-';
                }
                $isNonAktif = $d->status_aktif_karyawan === '0' || $d->status_aktif_karyawan == 0 || $d->status_aktif_karyawan != 1;
            @endphp
            <div class="rounded-lg border {{ $isNonAktif ? 'border-rose-200 dark:border-rose-800/80 bg-rose-50/70 dark:bg-rose-950/30 hover:border-rose-300 dark:hover:border-rose-700' : 'border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700' }} px-3.5 py-2 hover:shadow-xs transition duration-150">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2.5">
                    
                    <!-- 1. IDENTITAS KARYAWAN (Avatar, Nama, NIK, Status Aktif, Jabatan & Departemen) -->
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <!-- Avatar Karyawan with Status Indicator Dot -->
                        <div class="shrink-0 relative">
                            @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . $d->getRawOriginal('foto')))
                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Foto Karyawan"
                                    class="w-9 h-9 rounded-lg object-cover border {{ $isNonAktif ? 'border-rose-200 dark:border-rose-800' : 'border-slate-200 dark:border-slate-700' }} shadow-2xs">
                            @else
                                <div class="w-9 h-9 rounded-lg {{ $isNonAktif ? 'bg-rose-100/70 dark:bg-rose-900/40 text-rose-400 border border-rose-200 dark:border-rose-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200/80 dark:border-slate-700' }} flex items-center justify-center shadow-2xs">
                                    <i data-lucide="user" class="w-4 h-4 {{ $isNonAktif ? 'text-rose-400' : 'text-slate-400' }}"></i>
                                </div>
                            @endif
                            <span class="w-2.5 h-2.5 rounded-full absolute -top-0.5 -right-0.5 border-2 {{ $isNonAktif ? 'border-rose-100 dark:border-rose-950 bg-rose-500' : 'border-white dark:border-slate-900 bg-emerald-500' }}"
                                title="{{ $isNonAktif ? 'Status: Nonaktif' : 'Status: Aktif' }}"></span>
                        </div>

                        <!-- Data Diri & Jabatan -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap leading-tight">
                                <span class="text-xs sm:text-[13px] font-bold text-slate-900 dark:text-white truncate">
                                    {!! textCamelCase($d->nama_karyawan) !!}
                                </span>
                                <span class="px-1.5 py-0.5 rounded font-mono-num font-bold text-[10px] {{ $isNonAktif ? 'bg-rose-100/80 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800/60' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700' }}">
                                    {{ $d->nik }}
                                </span>
                                @if (!$isNonAktif)
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
                                <span class="font-semibold text-brand-600 dark:text-brand-400 truncate max-w-[180px]" title="{{ $d->nama_jabatan }}">
                                    {{ $d->nama_jabatan }}
                                </span>
                                <span>•</span>
                                <span class="text-slate-600 dark:text-slate-300 font-medium truncate max-w-[180px]" title="{{ $d->nama_dept }}">
                                    {{ $d->nama_dept }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. INFO STRUKTUR OPERASIONAL (Cabang, Group, Klasifikasi) -->
                    <div class="hidden lg:flex items-center gap-5 shrink-0 border-x {{ $isNonAktif ? 'border-rose-200/60 dark:border-rose-900/40' : 'border-slate-100 dark:border-slate-800' }} px-4 text-xs">
                        <!-- Cabang -->
                        <div class="text-center min-w-[55px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Cabang</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200 mt-0.5 inline-block font-mono-num">
                                {{ $d->kode_cabang }}
                            </span>
                        </div>
                        <!-- Group -->
                        <div class="min-w-[100px] max-w-[140px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Group</span>
                            <div class="flex items-center gap-1 text-slate-700 dark:text-slate-200 mt-0.5 truncate font-medium" title="{{ $d->nama_group ?? '-' }}">
                                <i data-lucide="users" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $d->nama_group ?? '-' }}</span>
                            </div>
                        </div>
                        <!-- Klasifikasi -->
                        <div class="min-w-[90px] max-w-[130px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Klasifikasi</span>
                            <div class="flex items-center gap-1 text-slate-600 dark:text-slate-300 mt-0.5 truncate font-medium" title="{{ $d->klasifikasi ?? '-' }}">
                                <i data-lucide="tag" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $d->klasifikasi ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. MASA KERJA & STATUS KEPEGAWAIAN -->
                    <div class="flex sm:flex-col sm:items-end justify-between text-left sm:text-right shrink-0 min-w-[120px]">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Masa Kerja</span>
                            <span class="text-xs sm:text-[13px] font-black font-mono-num text-slate-800 dark:text-slate-100">
                                {{ $masa_kerja }}
                            </span>
                        </div>
                        <div class="mt-0.5">
                            @if ($d->status_karyawan == 'T')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                    Tetap
                                </span>
                            @elseif($d->status_karyawan == 'K')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60">
                                    Kontrak
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    Outsource
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 4. KONTROL KEAMANAN & AKSI KOMPAK -->
                    <div class="flex items-center justify-end gap-1 shrink-0">
                        <!-- Lock / Unlock Location -->
                        @can('karyawan.unlocklocation')
                            <a href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}"
                                class="w-6 h-6 rounded-md {{ $d->lock_location == 1 ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400' }} hover:opacity-80 transition flex items-center justify-center shadow-2xs"
                                title="{{ $d->lock_location == 1 ? 'Location Unlocked (Bebas Absen)' : 'Location Locked (Terkunci Radius Cabang)' }}">
                                @if ($d->lock_location == 1)
                                    <i data-lucide="lock-open" class="w-3 h-3"></i>
                                @else
                                    <i data-lucide="lock" class="w-3 h-3"></i>
                                @endif
                            </a>
                        @endcan

                        <!-- Reset Session (Super Admin) -->
                        @role('super admin')
                            <a href="{{ route('karyawan.resetsession', ['nik' => Crypt::encrypt($d->nik)]) }}"
                                class="btnResetSession w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center shadow-2xs"
                                title="Reset Sesi Login Karyawan">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                            </a>
                        @endrole

                        <!-- Detail (Show) -->
                        @can('karyawan.show')
                            <a href="{{ route('karyawan.show', Crypt::encrypt($d->nik)) }}"
                                class="w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/60 transition flex items-center justify-center shadow-2xs"
                                title="Lihat Detail Profil">
                                <i data-lucide="eye" class="w-3 h-3"></i>
                            </a>
                        @endcan

                        <!-- Edit -->
                        @can('karyawan.edit')
                            <button type="button"
                                data-nik="{{ Crypt::encrypt($d->nik) }}"
                                class="btnEditKaryawan w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                title="Edit Data Karyawan">
                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                            </button>
                        @endcan

                        <!-- Delete -->
                        @can('karyawan.delete')
                            <form method="POST" action="{{ route('karyawan.delete', Crypt::encrypt($d->nik)) }}"
                                class="inline-flex m-0 p-0 deleteform">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                    title="Hapus Karyawan">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </form>
                        @endcan
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-semibold">Tidak ada data karyawan yang ditemukan</span>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION SECTION -->
    @if ($karyawan->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl overflow-hidden">
            {{ $karyawan->links('v2.components.pagination') }}
        </div>
    @endif

</div>

<!-- TAILWIND MODAL KARYAWAN (CREATE & EDIT) -->
<div id="modalKaryawan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 id="modalTitleKaryawan" class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Karyawan
                </h4>
            </div>
            <button type="button" class="btnCloseModalKaryawan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentKaryawan" class="p-4 max-h-[85vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
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

        const $modal = $("#modalKaryawan");
        const $modalContent = $("#modalContentKaryawan");
        const $modalTitle = $("#modalTitleKaryawan");

        function openModal(title, url) {
            $modalTitle.text(title);
            $modalContent.html(`
                <div class="p-8 flex flex-col items-center justify-center gap-3 text-slate-500">
                    <svg class="animate-spin h-6 w-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat formulir...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            $("body").addClass("overflow-hidden");

            $modalContent.load(url, function(response, status) {
                if (status === "error") {
                    $modalContent.html(`
                        <div class="p-6 text-center text-rose-500 text-xs font-semibold">
                            Gagal memuat data. Silakan coba kembali.
                        </div>
                    `);
                } else {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
            $modalContent.html("");
            $("body").removeClass("overflow-hidden");
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
        }

        // Close on explicit X button only
        $(".btnCloseModalKaryawan").on("click", closeModal);
        $(document).on("click", ".btnCancelModal", closeModal);

        // Trigger Create
        $("#btnCreateKaryawan").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Karyawan Baru", "{{ route('karyawan.create') }}");
        });

        // Trigger Edit
        $(document).on("click", ".btnEditKaryawan", function(e) {
            e.preventDefault();
            const nik = $(this).data("nik");
            openModal("Edit Data Karyawan", "/karyawan/" + nik + "/edit");
        });

        // Confirmation for Reset All Session
        $(".btnResetAllSession").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Reset Semua Session?',
                    text: 'Seluruh session login karyawan mobile akan dikeluarkan secara paksa.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Reset Semua',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'v2-delete-modal',
                        confirmButton: 'inline-flex items-center justify-center h-[34px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-xs cursor-pointer',
                        cancelButton: 'inline-flex items-center justify-center h-[34px] px-4 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 transition shadow-2xs cursor-pointer ml-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            } else {
                if (confirm('Reset seluruh session karyawan?')) {
                    window.location.href = url;
                }
            }
        });
    });
</script>
@endpush
