@extends('layouts.app')
@section('titlepage', 'Target Komisi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Komisi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Target Komisi</span>
    </div>
@endsection

@section('content')
@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $user = auth()->user();
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
    $level_user = $user->roles->pluck('name')[0] ?? '';
    $nextlevel = '';
    if ($level_user == 'regional sales manager') {
        $nextlevel = 'gm marketing';
    } elseif ($level_user == 'gm marketing') {
        $nextlevel = 'direktur';
    }
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Target Komisi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola alokasi, persetujuan berjenjang, dan penyesuaian target komisi salesman per cabang.</p>
        </div>

        @can('targetkomisi.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Target</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS TARGET KOMISI -->
    @include('v2.layouts.navigation_targetkomisi')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('targetkomisi.index') }}" method="GET" id="formSearch" class="space-y-2">
            
            @if ($hasAccessAllCabang)
                <!-- Baris 1: Filter Regional & Cabang (Khusus Akses Multi Cabang) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <!-- Filter Regional -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="map" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_regional_search" id="kode_regional_search"
                            class="select2Koderegionalsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Regional</option>
                            @foreach ($regional as $r)
                                <option value="{{ $r->kode_regional }}" {{ Request('kode_regional_search') == $r->kode_regional ? 'selected' : '' }}>
                                    {{ $r->kode_regional }} - {{ textUpperCase($r->nama_regional) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Cabang -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search"
                            class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <!-- Baris Filter Parameter: Posisi Ajuan, Bulan, Tahun, Status & Submit -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Posisi Ajuan (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="posisi_ajuan" id="posisi_ajuan"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Posisi Ajuan</option>
                        @foreach ($roles_approve_targetkomisi as $role)
                            <option value="{{ $role }}" {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                {{ textUpperCase($role) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Bulan (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="bulan" id="bulan_filter"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Tahun (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="tahun" id="tahun_filter"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}"
                                {{ !empty(Request('tahun')) ? (Request('tahun') == $t ? 'selected' : '') : (date('Y') == $t ? 'selected' : '') }}>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Status (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status" id="status_filter"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset (Col-span 2) -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['kode_regional_search', 'kode_cabang_search', 'posisi_ajuan', 'bulan', 'tahun', 'status']))
                        <a href="{{ route('targetkomisi.index') }}"
                            class="h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium transition flex items-center justify-center cursor-pointer shrink-0"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="crosshair" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Target Komisi</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($targetkomisi, 'total') ? number_format($targetkomisi->total(), 0, ',', '.') : number_format($targetkomisi->count(), 0, ',', '.') }} Dokumen
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">BULAN</th>
                        <th class="py-2 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">TAHUN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">POSISI AJUAN</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2 px-3 text-center w-36 font-bold uppercase tracking-wider text-white/90 border-none">TANGGAL</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($targetkomisi as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <!-- Kode -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/60 dark:border-blue-900/50">
                                    {{ $d->kode_target }}
                                </span>
                            </td>

                            <!-- Bulan -->
                            <td class="py-1.5 px-3 text-center font-semibold text-slate-800 dark:text-slate-100">
                                {{ $nama_bulan[$d->bulan] ?? $d->bulan }}
                            </td>

                            <!-- Tahun -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>

                            <!-- Cabang -->
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    <i data-lucide="building-2" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ textUpperCase($d->nama_cabang) }}</span>
                                </span>
                            </td>

                            <!-- Posisi Ajuan -->
                            <td class="py-1.5 px-3 text-center">
                                @php
                                    $roleBadge = match ($d->role) {
                                        'regional sales manager' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border-sky-200/70 dark:border-sky-800/60',
                                        'gm marketing' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border-indigo-200/70 dark:border-indigo-800/60',
                                        'direktur' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-200/70 dark:border-emerald-800/60',
                                        default => 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold border {{ $roleBadge }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span>{{ textCamelCase($d->role ?? 'Draft') }}</span>
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '0')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-500 animate-pulse"></i>
                                        <span>Pending</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60">
                                        <i data-lucide="check-check" class="w-3 h-3 text-emerald-500"></i>
                                        <span>Disetujui</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 text-[11px]">
                                {{ !empty($d->created_at) ? date('d M Y H:i', strtotime($d->created_at)) : '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- 1. Tombol Approve / Cancel -->
                                    @can('targetkomisi.approve')
                                        @if ($d->status_disposisi == '0' || $level_user == 'regional sales manager')
                                            <button type="button" class="btnApprove w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/50 dark:hover:bg-blue-900 dark:text-blue-300 flex items-center justify-center transition cursor-pointer"
                                                kode_target="{{ Crypt::encrypt($d->kode_target) }}" title="Proses Persetujuan">
                                                <i data-lucide="send" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            @if ($level_user == 'direktur' && $d->status_disposisi == '1')
                                                <form method="POST" action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}" class="inline-flex m-0 p-0 cancelform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnCancel w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900 dark:text-rose-300 flex items-center justify-center transition cursor-pointer"
                                                        title="Batalkan Persetujuan">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @elseif ($d->status_ajuan == '0' && $d->role == $nextlevel)
                                                <form method="POST" action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}" class="inline-flex m-0 p-0 cancelform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnCancel w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900 dark:text-rose-300 flex items-center justify-center transition cursor-pointer"
                                                        title="Batalkan Ajuan">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan

                                    <!-- 2. Tombol Edit -->
                                    @can('targetkomisi.edit')
                                        @if (
                                            ($d->id_pengirim == auth()->user()->id && !in_array($level_user, $roles_approve_targetkomisi)) ||
                                            $level_user == 'super admin' ||
                                            $level_user == 'regional sales manager' ||
                                            (in_array($level_user, $roles_approve_targetkomisi) && $d->status_disposisi == '0') ||
                                            (in_array($level_user, $roles_approve_targetkomisi) && $d->id_pengirim == auth()->user()->id && $d->status_ajuan == '0')
                                        )
                                            <button type="button" class="btnEdit w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900 dark:text-amber-300 flex items-center justify-center transition cursor-pointer"
                                                kode_target="{{ Crypt::encrypt($d->kode_target) }}" title="Edit Target">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    <!-- 3. Tombol Detail -->
                                    @can('targetkomisi.show')
                                        <button type="button" class="btnShow w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:hover:bg-sky-900 dark:text-sky-300 flex items-center justify-center transition cursor-pointer"
                                            kode_target="{{ Crypt::encrypt($d->kode_target) }}" title="Lihat Rincian Target">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- 4. Tombol Edit Posisi (Super Admin) -->
                                    @if ($level_user == 'super admin')
                                        <button type="button" class="btnEditPosisi w-6 h-6 rounded-md bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:hover:bg-indigo-900 dark:text-indigo-300 flex items-center justify-center transition cursor-pointer"
                                            kode_target="{{ Crypt::encrypt($d->kode_target) }}" title="Edit Posisi Ajuan">
                                            <i data-lucide="settings" class="w-3 h-3"></i>
                                        </button>
                                    @endif

                                    <!-- 5. Tombol Hapus -->
                                    @can('targetkomisi.delete')
                                        @if ($d->id_pengirim == auth()->user()->id)
                                            <form method="POST" action="{{ route('targetkomisi.delete', Crypt::encrypt($d->kode_target)) }}" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900 dark:text-rose-300 flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus Target">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada data target komisi yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($targetkomisi->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $targetkomisi->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- STANDAR V2 MODAL CONTAINER (STATIC BACKDROP, EXPLICIT CLOSE, ZERO LAYOUT SHIFT) -->
<div id="modalTargetkomisi" class="fixed inset-0 z-50 hidden overflow-y-auto modal-wrapper" aria-labelledby="modalTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Gelap -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity modal-backdrop-v2"></div>

    <div class="flex min-h-screen items-center justify-center p-3 sm:p-4 text-center">
        <div id="modalDialog"
            class="relative w-full max-w-7xl transform rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all duration-200 border border-slate-200/80 dark:border-slate-800 overflow-hidden flex flex-col max-h-[92vh]">
            
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 sm:px-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div id="modalIconContainer" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="target" id="modalHeaderIcon" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-none">
                            Formulir Target Komisi
                        </h3>
                        <p id="modalSubtitle" class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 leading-none">
                            Kelola data target komisi salesman
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable Container) -->
            <div id="modalBodyContent" class="p-4 sm:p-5 overflow-y-auto flex-1">
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat data...</span>
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

        // Initialize Select2 in Filter
        const initFilterSelect2 = function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2Koderegionalsearch').select2({
                    placeholder: 'Semua Regional',
                    allowClear: true,
                    width: '100%'
                });
                $('.select2Kodecabangsearch').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    width: '100%'
                });
            }
        };
        initFilterSelect2();

        // Modal Variables
        const $modal = $('#modalTargetkomisi');
        const $modalDialog = $('#modalDialog');
        const $modalTitle = $('#modalTitle');
        const $modalSubtitle = $('#modalSubtitle');
        const $modalContent = $('#modalBodyContent');

        function openModal(title, subtitle, widthClass, iconName) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass('max-w-md max-w-lg max-w-xl max-w-2xl max-w-3xl max-w-4xl max-w-5xl max-w-6xl max-w-7xl max-w-[95vw]')
                        .addClass(widthClass || 'max-w-7xl');
            
            if (iconName && typeof lucide !== 'undefined') {
                $('#modalHeaderIcon').attr('data-lucide', iconName);
                lucide.createIcons();
            }

            loadingModal();
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.html('');
        }

        function loadingModal() {
            $modalContent.html(`
                <div class="py-14 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            `);
        }

        function triggerLockAnimation() {
            $modalDialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $modalDialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop Handling
        $modal.on('click', function(e) {
            if ($(e.target).is($modal) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation();
            }
        });

        $(document).on('keydown', function(e) {
            if (!$modal.hasClass('hidden') && e.key === 'Escape') {
                triggerLockAnimation();
            }
        });

        // Close explicit
        $modal.on('click', '.btn-close-modal', function(e) {
            e.preventDefault();
            closeModal();
        });

        // 1. Buat Target (Create)
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Buat Target Komisi', 'Input alokasi target komisi salesman baru', 'max-w-7xl', 'plus-circle');
            $.ajax({
                url: "{{ route('targetkomisi.create') }}",
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
                            <p class="text-xs font-bold">Gagal memuat form tambah target komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 2. Edit Target
        $('.btnEdit').on('click', function(e) {
            e.preventDefault();
            const kodeTarget = $(this).attr('kode_target');
            openModal('Edit Target Komisi', 'Penyesuaian target komisi salesman', 'max-w-[95vw]', 'edit-3');
            $.ajax({
                url: `/targetkomisi/${kodeTarget}/edit`,
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
                            <p class="text-xs font-bold">Gagal memuat form edit target komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 3. Detail Target (Show)
        $('.btnShow').on('click', function(e) {
            e.preventDefault();
            const kodeTarget = $(this).attr('kode_target');
            openModal('Rincian Target Komisi', 'Rincian target, histori realisasi, dan pencapaian', 'max-w-[95vw]', 'eye');
            $.ajax({
                url: `/targetkomisi/${kodeTarget}/show`,
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
                            <p class="text-xs font-bold">Gagal memuat data detail target komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 4. Approve Target
        $('.btnApprove').on('click', function(e) {
            e.preventDefault();
            const kodeTarget = $(this).attr('kode_target');
            openModal('Persetujuan Target Komisi', 'Verifikasi dan teruskan persetujuan dokumen target', 'max-w-[95vw]', 'send');
            $.ajax({
                url: `/targetkomisi/${kodeTarget}/approve`,
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
                            <p class="text-xs font-bold">Gagal memuat form persetujuan target komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 5. Edit Posisi Ajuan (Super Admin)
        $('.btnEditPosisi').on('click', function(e) {
            e.preventDefault();
            const kodeTarget = $(this).attr('kode_target');
            openModal('Edit Posisi Ajuan', 'Ubah status atau posisi alur persetujuan dokumen', 'max-w-md', 'settings');
            $.ajax({
                url: `/targetkomisi/${kodeTarget}/editposisi`,
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
                            <p class="text-xs font-bold">Gagal memuat form edit posisi ajuan.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 6. SweetAlert Konfirmasi Cancel Persetujuan
        $('.btnCancel').on('click', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Batalkan Persetujuan?',
                    text: 'Dokumen target komisi akan ditarik kembali dari tingkatan ajuan saat ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.submit();
                    }
                });
            } else {
                if (confirm('Batalkan persetujuan dokumen target komisi ini?')) {
                    $form.submit();
                }
            }
        });

        // 7. SweetAlert Konfirmasi Hapus Target
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Dokumen Target?',
                    text: 'Data target komisi yang dihapus tidak dapat dipulihkan kembali.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.submit();
                    }
                });
            } else {
                if (confirm('Yakin ingin menghapus target komisi ini?')) {
                    $form.submit();
                }
            }
        });

    });
</script>
@endpush
