@extends('layouts.app')
@section('titlepage', 'Ajuan Faktur Kredit')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ajuan Faktur Kredit</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Ajuan Faktur Kredit</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data transaksi pengajuan dan persetujuan faktur kredit pelanggan.</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            @can('ajuanfaktur.config')
                <a href="{{ route('ajuanfakturconfig.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer">
                    <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                    <span>Konfigurasi</span>
                </a>
            @endcan

            @can('ajuanfaktur.create')
                <button type="button" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Ajukan Faktur</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    @include('v2.layouts.navigation_ajuanmarketing')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('ajuanfaktur.index') }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Tanggal Dari -->
            <div class="lg:col-span-2 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Dari Tanggal" readonly>
            </div>

            <!-- Tanggal Sampai -->
            <div class="lg:col-span-2 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Sampai Tanggal" readonly>
            </div>

            <!-- Cabang (Jika User Memiliki Akses Multi-Cabang) -->
            @hasanyrole(config('global.roles_show_cabang') ?? config('global.roles_access_all_cabang'))
                <div class="lg:col-span-2 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search"
                        class="select2Cabang w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endhasanyrole

            <!-- Nama Pelanggan -->
            <div class="{{ auth()->user()->hasAnyRole(config('global.roles_show_cabang') ?? config('global.roles_access_all_cabang')) ? 'lg:col-span-2' : 'lg:col-span-4' }} relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ request('nama_pelanggan') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nama Pelanggan">
            </div>

            <!-- Posisi Ajuan -->
            <div class="lg:col-span-2 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="posisi_ajuan" id="posisi_ajuan"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Posisi</option>
                    @foreach ($roles_approve_ajuanfakturkredit as $role)
                        <option value="{{ $role }}" {{ request('posisi_ajuan') == $role ? 'selected' : '' }}>
                            {{ textUpperCase($role) }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Status -->
            <div class="lg:col-span-1 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status" id="status"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Status</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="lg:col-span-1 flex items-center">
                <button type="submit"
                    class="w-full h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sm:hidden">Cari</span>
                </button>
            </div>
        </form>
    </div>

    <!-- DATA TABLE SECTION (STANDAR V2: PACIFIC NAVY DUAL-TIER THEAD, ~36PX ROW HEIGHT) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Table Title Strip (Pacific Navy #284c9a) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="12" class="px-3.5 py-2 font-bold uppercase tracking-wider text-xs border-none">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="receipt" class="w-4 h-4 text-blue-200"></i>
                                    <span>Daftar Ajuan Faktur Kredit</span>
                                </div>
                                <span class="text-[11px] font-normal font-mono-num text-white/70">
                                    Total: {{ method_exists($ajuanfaktur, 'total') ? number_format($ajuanfaktur->total(), 0, ',', '.') : number_format($ajuanfaktur->count(), 0, ',', '.') }} Pengajuan
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles (Darker Navy #1e3a78) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 sticky left-0 z-20 bg-[#1e3a78] shadow-[1px_0_0_0_rgba(255,255,255,0.1)]">NO. PENGAJUAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-24">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 min-w-[180px]">PELANGGAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 min-w-[140px]">SALESMAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-24">CABANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 w-28">LIMIT</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-20">JML FAKTUR</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-16">COD</th>
                        <th class="py-2 px-3 font-medium text-white/90 min-w-[180px] max-w-[260px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-24">POSISI</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-24">STATUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 sticky right-0 z-20 bg-[#1e3a78] shadow-[-1px_0_0_0_rgba(255,255,255,0.1)] w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($ajuanfaktur as $d)
                        @php
                            // Role Badge Styling
                            $posisi_ajuan_lower = strtolower($d->posisi_ajuan ?? '');
                            $text_role = $d->posisi_ajuan ? textUpperCase($d->posisi_ajuan) : '-';
                            $posisiColor = 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700';

                            if ($posisi_ajuan_lower == 'sales marketing manager') {
                                $posisiColor = 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-900';
                                $text_role = 'SMM';
                            } elseif ($posisi_ajuan_lower == 'regional sales manager') {
                                $posisiColor = 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400 border border-blue-200 dark:border-blue-900';
                                $text_role = 'RSM';
                            } elseif ($posisi_ajuan_lower == 'gm marketing') {
                                $posisiColor = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900';
                                $text_role = 'GM';
                            } elseif ($posisi_ajuan_lower == 'direktur') {
                                $posisiColor = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900';
                                $text_role = 'DIR';
                            } else {
                                if (empty($d->posisi_ajuan) && $d->status == '1') {
                                    $posisiColor = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900';
                                    $text_role = 'DIR';
                                } elseif (empty($d->posisi_ajuan) && $d->status == '0') {
                                    $posisiColor = 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700';
                                    $text_role = 'BELUM DIATUR';
                                }
                            }

                            // Approval & Cancel Permission Checks
                            $can_approve = false;
                            $can_cancel = false;
                            $roles_arr = $roles_approve_ajuanfakturkredit ?? [];

                            if ($d->status == '0') {
                                if ($level_user == 'operation manager') {
                                    if ($d->posisi_ajuan == 'sales marketing manager') {
                                        $can_approve = true;
                                    }
                                    $currentIndex = array_search('sales marketing manager', $roles_arr);
                                    if ($currentIndex !== false && isset($roles_arr[$currentIndex + 1])) {
                                        $nextRoleFromSmm = $roles_arr[$currentIndex + 1];
                                        if ($d->posisi_ajuan == $nextRoleFromSmm) {
                                            $can_cancel = true;
                                        }
                                    }
                                } else {
                                    if ($d->posisi_ajuan == $level_user) {
                                        $can_approve = true;
                                    }
                                    $currentIndex = array_search($level_user, $roles_arr);
                                    if ($currentIndex !== false && isset($roles_arr[$currentIndex + 1])) {
                                        $nextRole = $roles_arr[$currentIndex + 1];
                                        if ($d->posisi_ajuan == $nextRole) {
                                            $can_cancel = true;
                                        }
                                    }
                                }
                            } elseif ($d->status == '1') {
                                if ($level_user == 'operation manager') {
                                    $lastRole = !empty($roles_arr) ? end($roles_arr) : '';
                                    if ($lastRole == 'sales marketing manager') {
                                        $can_cancel = true;
                                    }
                                } else {
                                    $lastRole = !empty($roles_arr) ? end($roles_arr) : '';
                                    if ($level_user == $lastRole) {
                                        $can_cancel = true;
                                    }
                                }
                            } elseif ($d->status == '2') {
                                if ($d->posisi_ajuan == $level_user || auth()->user()->hasRole('super admin')) {
                                    $can_cancel = true;
                                }
                            }

                            if (auth()->user()->hasRole('super admin')) {
                                if ($d->status == '0') {
                                    $can_approve = true;
                                }
                                $can_cancel = true;
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            
                            <!-- 1. No Pengajuan (Sticky Left) -->
                            <td class="py-1.5 px-3 sticky left-0 z-10 bg-white dark:bg-slate-900 shadow-[1px_0_0_0_rgba(226,232,240,0.8)] dark:shadow-[1px_0_0_0_rgba(30,41,59,0.8)]">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                    {{ $d->no_pengajuan }}
                                </span>
                            </td>

                            <!-- 2. Tanggal -->
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-400 font-mono-num text-[11px]">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- 3. Pelanggan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->nama_pelanggan }}
                            </td>

                            <!-- 4. Salesman -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->nama_salesman ?? '-' }}
                            </td>

                            <!-- 5. Cabang -->
                            <td class="py-1.5 px-3 text-center font-bold text-slate-600 dark:text-slate-400">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10.5px]">
                                    {{ $d->kode_cabang ?? $d->nama_cabang ?? '-' }}
                                </span>
                            </td>

                            <!-- 6. Limit Pelanggan -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                {{ number_format($d->limit_pelanggan, 0, ',', '.') }}
                            </td>

                            <!-- 7. Jumlah Faktur -->
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->jumlah_faktur }}
                            </td>

                            <!-- 8. COD -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->siklus_pembayaran == '1')
                                    <span class="inline-flex items-center justify-center text-emerald-600 dark:text-emerald-400" title="Cash on Delivery (Turun Barang Selanjutnya)">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center text-slate-300 dark:text-slate-600" title="Reguler">
                                        <i data-lucide="minus-circle" class="w-4 h-4"></i>
                                    </span>
                                @endif
                            </td>

                            <!-- 9. Keterangan -->
                            <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 text-[11px] truncate max-w-[240px]" title="{{ $d->keterangan }}">
                                {{ $d->keterangan ?: '-' }}
                            </td>

                            <!-- 10. Posisi -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md text-[10.5px] font-bold {{ $posisiColor }}">
                                    {{ $text_role }}
                                </span>
                            </td>

                            <!-- 11. Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status === '0')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/60">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        Pending
                                    </span>
                                @elseif ($d->status == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/60">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        Disetujui
                                    </span>
                                @elseif ($d->status == '2')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- 12. Aksi (Sticky Right) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 shadow-[-1px_0_0_0_rgba(226,232,240,0.8)] dark:shadow-[-1px_0_0_0_rgba(30,41,59,0.8)]">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- Approve Button -->
                                    @can('ajuanfaktur.approve')
                                        @if ($can_approve)
                                            <button type="button" class="btnApprove w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/50 dark:text-emerald-400 inline-flex items-center justify-center transition cursor-pointer"
                                                data-no-pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" title="Persetujuan Ajuan">
                                                <i data-lucide="send" class="w-3 h-3"></i>
                                            </button>
                                        @endif

                                        <!-- Cancel Button -->
                                        @if ($can_cancel)
                                            <form method="POST" class="inline-flex m-0 p-0 cancelform" action="{{ route('ajuanfaktur.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnCancelSubmit w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                                                    title="Batalkan Persetujuan">
                                                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    <!-- Delete Button -->
                                    @can('ajuanfaktur.delete')
                                        @if ($d->status == '0' || auth()->user()->hasRole('super admin'))
                                            <form method="POST" class="inline-flex m-0 p-0 deleteform" action="{{ route('ajuanfaktur.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus Ajuan">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    <!-- Edit Posisi (Super Admin) -->
                                    @if ($level_user == 'super admin')
                                        <button type="button" class="btnEdit w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900/50 dark:text-amber-400 inline-flex items-center justify-center transition cursor-pointer"
                                            data-no-pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" title="Edit Posisi Ajuan">
                                            <i data-lucide="edit" class="w-3 h-3"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada data pengajuan faktur kredit</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($ajuanfaktur->hasPages())
            <div class="px-3.5 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $ajuanfaktur->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MAIN MODAL DIALOG V2 (STATIC BACKDROP, BOUNCE ANIMATION, CLEAN HEADER) -->
<div id="modalAjuanFaktur" class="fixed inset-0 z-[1055] hidden transition-opacity duration-200">
    <div class="modal-backdrop-v2 fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div class="modal-dialog relative w-full !max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[92vh]">
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 id="modalAjuanFakturTitle" class="text-xs font-bold text-slate-800 dark:text-slate-100">Ajuan Faktur Kredit</h4>
                        <p id="modalAjuanFakturSubtitle" class="text-[10px] text-slate-400">Pengajuan dan persetujuan penambahan faktur</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalAjuanFakturContent" class="p-3 sm:p-3.5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PELANGGAN LOOKUP (DATA TABLE V2) -->
<div id="modalPelanggan" class="fixed inset-0 z-[1060] hidden transition-opacity duration-200">
    <div class="modal-backdrop-v2 fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div class="modal-dialog relative w-full !max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Pilih Data Pelanggan</h4>
                        <p class="text-[10px] text-slate-400">Pilih pelanggan untuk pengajuan faktur kredit</p>
                    </div>
                </div>
                <button type="button" class="btn-close-pelanggan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-3 sm:p-4 overflow-y-auto">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabelpelanggan">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white">NO</th>
                                <th class="py-2 px-3 w-28 font-bold uppercase tracking-wider text-white">KODE</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white">NAMA PELANGGAN</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white">SALESMAN</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white">WILAYAH</th>
                                <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white">STATUS</th>
                                <th class="py-2 px-3 text-center w-16 font-bold uppercase tracking-wider text-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900"></tbody>
                    </table>
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

        // Flatpickr Filter Init
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 Cabang
        $('.select2Cabang').select2({
            placeholder: "Semua Cabang",
            allowClear: true,
            width: '100%'
        });

        // Modal Elements
        const $modal = $('#modalAjuanFaktur');
        const $modalDialog = $modal.find('.modal-dialog');
        const $modalContent = $('#modalAjuanFakturContent');
        const $modalTitle = $('#modalAjuanFakturTitle');
        const $modalSubtitle = $('#modalAjuanFakturSubtitle');

        const $modalPelanggan = $('#modalPelanggan');
        const $modalPelangganDialog = $modalPelanggan.find('.modal-dialog');

        function openModal(title, subtitle, sizeClass) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass(function(index, className) {
                return (className.match(/(^|\s)\S*max-w-\S+/g) || []).join(' ');
            });
            $modalDialog.addClass(sizeClass || '!max-w-xl');
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.html(`
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            `);
        }

        function triggerLockAnimation($el) {
            $el.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $el.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Backdrop Click Lock
        $modal.on('click', function(e) {
            if ($(e.target).is($modal) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation($modalDialog);
            }
        });

        $modalPelanggan.on('click', function(e) {
            if ($(e.target).is($modalPelanggan) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation($modalPelangganDialog);
            }
        });

        // Escape Key Lock
        $(document).on('keydown', function(e) {
            if (!$modalPelanggan.hasClass('hidden') && e.key === 'Escape') {
                triggerLockAnimation($modalPelangganDialog);
            } else if (!$modal.hasClass('hidden') && e.key === 'Escape') {
                triggerLockAnimation($modalDialog);
            }
        });

        // Close explicit
        $modal.on('click', '.btn-close-modal', function(e) {
            e.preventDefault();
            closeModal();
        });

        $modalPelanggan.on('click', '.btn-close-pelanggan', function(e) {
            e.preventDefault();
            $modalPelanggan.addClass('hidden');
        });

        // 1. Click Create Ajuan Faktur
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Buat Ajuan Faktur Kredit', 'Input permohonan penambahan batas faktur kredit pelanggan', '!max-w-xl');
            $.ajax({
                url: '/ajuanfaktur/create',
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat formulir pengajuan faktur.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 2. Click Approve Ajuan Faktur
        $('.btnApprove').on('click', function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).data('no-pengajuan');
            openModal('Persetujuan Ajuan Faktur Kredit', 'Verifikasi dan persetujuan pengajuan faktur', '!max-w-lg');
            $.ajax({
                url: `/ajuanfaktur/${no_pengajuan}/approve`,
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat rincian persetujuan ajuan faktur.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 3. Click Edit Posisi (Super Admin)
        $('.btnEdit').on('click', function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).data('no-pengajuan');
            openModal('Edit Posisi Ajuan', 'Penyesuaian role posisi approval ajuan faktur', '!max-w-md');
            $.ajax({
                url: `/ajuanfaktur/${no_pengajuan}/edit`,
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat data posisi ajuan.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 4. Cancel Confirm
        $('.btnCancelSubmit').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Batalkan Persetujuan?',
                text: 'Status ajuan akan dikembalikan ke role sebelumnya atau menunggu persetujuan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // 5. Lookup Modal Pelanggan DataTable
        let tablePelangganInitialized = false;
        $(document).on('click', '#kode_pelanggan_search', function(e) {
            e.preventDefault();
            $modalPelanggan.removeClass('hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            if (!tablePelangganInitialized) {
                $('#tabelpelanggan').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[2, 'asc']],
                    ajax: "{{ route('pelanggan.getpelangganjson') }}",
                    bAutoWidth: false,
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center py-2 px-3 text-slate-400 font-mono-num text-[11px]' },
                        { data: 'kode_pelanggan', name: 'pelanggan.kode_pelanggan', orderable: true, searchable: true, className: 'py-2 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400' },
                        { data: 'nama_pelanggan', name: 'pelanggan.nama_pelanggan', orderable: true, searchable: true, className: 'py-2 px-3 font-semibold text-slate-800 dark:text-slate-100' },
                        { data: 'nama_salesman', name: 'salesman.nama_salesman', orderable: true, searchable: true, className: 'py-2 px-3 text-slate-600 dark:text-slate-300' },
                        { data: 'nama_wilayah', name: 'wilayah.nama_wilayah', orderable: true, searchable: true, className: 'py-2 px-3 text-slate-600 dark:text-slate-300' },
                        { data: 'status_pelanggan', name: 'status_pelanggan', orderable: false, searchable: false, className: 'py-2 px-3 text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'py-2 px-3 text-center' }
                    ],
                    rowCallback: function(row, data) {
                        if (data.status_pelanggan == "NonAktif") {
                            $(row).addClass('bg-rose-50/50 dark:bg-rose-950/20 text-rose-600');
                        }
                    }
                });
                tablePelangganInitialized = true;
            }
        });

    });
</script>
@endpush
