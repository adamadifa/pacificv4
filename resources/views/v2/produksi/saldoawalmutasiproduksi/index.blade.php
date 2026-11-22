@extends('layouts.app')
@section('titlepage', 'Saldo Awal Mutasi Produksi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Produksi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Saldo Awal Mutasi</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Saldo Awal Mutasi Produksi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data cut-off saldo awal mutasi barang jadi produksi per periode bulan.</p>
        </div>

        @can('samutasiproduksi.create')
            <a href="{{ route('samutasiproduksi.create') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Saldo Awal</span>
            </a>
        @endcan
    </div>

    <!-- NAVIGATION TABS (MUTASI PRODUKSI) -->
    @include('layouts.navigation_mutasiproduksi')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('samutasiproduksi.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Bulan -->
            <div class="relative flex-1 w-full sm:w-48">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan" id="bulan"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}" {{ request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                            {{ $d['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Tahun -->
            <div class="relative flex-1 w-full sm:w-40">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun" id="tahun"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ (request('tahun') ?? date('Y')) == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                @if (request('bulan') || (request('tahun') && request('tahun') != date('Y')))
                    <a href="{{ route('samutasiproduksi.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH PACIFIC NAVY HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                <thead>
                    <!-- Row 1: Title Strip (colspan=6) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clipboard-list" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Saldo Awal Mutasi Produksi</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ count($saldo_awal) }} Dokumen
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">KODE SALDO AWAL</th>
                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">BULAN</th>
                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">TAHUN</th>
                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">TANGGAL</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 dark:text-slate-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-1.5 px-4 font-bold text-brand-600 dark:text-brand-400 font-mono-num">
                                {{ $d->kode_saldo_awal }}
                            </td>
                            <td class="py-1.5 px-4 text-center font-semibold text-slate-700 dark:text-slate-200">
                                {{ $nama_bulan[$d->bulan] ?? $d->bulan }}
                            </td>
                            <td class="py-1.5 px-4 text-center font-mono-num text-slate-600 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>
                            <td class="py-1.5 px-4 text-center text-slate-600 dark:text-slate-300 font-mono-num">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('samutasiproduksi.show')
                                        <button type="button" class="showSaldoawal w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}"
                                            title="Lihat Detail Saldo Awal">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('samutasiproduksi.delete')
                                        <form method="POST" action="{{ route('samutasiproduksi.delete', Crypt::encrypt($d->kode_saldo_awal)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Hapus Saldo Awal">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data saldo awal yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL FORM DETAIL SALDO AWAL -->
<x-modal-form id="mdldetailSaldoawal" size="modal-lg" show="loaddetailSaldoawal" title="Detail Saldo Awal Mutasi Produksi" />

@endsection

@push('myscript')
<script>
    $(function() {
        $(".showSaldoawal").click(function(e) {
            e.preventDefault();
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            $('#mdldetailSaldoawal').modal("show");
            $("#loaddetailSaldoawal").html(`
                <div class="p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat rincian saldo awal...</span>
                </div>
            `);
            $("#loaddetailSaldoawal").load('/samutasiproduksi/' + kode_saldo_awal + '/show', function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush
