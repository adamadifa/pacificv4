@extends('layouts.app')
@section('titlepage', 'Barang Keluar Gudang Bahan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Bahan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Barang Keluar</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Barang Keluar Gudang Bahan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data pengeluaran dan pemakaian bahan dari gudang bahan.</p>
        </div>

        @can('barangkeluargb.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Barang Keluar</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS -->
    @include('layouts.navigation_mutasigudangbahan')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('barangkeluargudangbahan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Tanggal Dari -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Dari Tanggal" readonly>
            </div>

            <!-- Tanggal Sampai -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Sampai Tanggal" readonly>
            </div>

            <!-- No. Bukti -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_bukti_search" id="no_bukti_search" value="{{ request('no_bukti_search') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari No. Bukti...">
            </div>

            <!-- Jenis Pengeluaran -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_jenis_pengeluaran_search" id="kode_jenis_pengeluaran_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Jenis Pengeluaran</option>
                    @foreach ($list_jenis_pengeluaran as $d)
                        <option value="{{ $d['kode_jenis_pengeluaran'] }}" {{ request('kode_jenis_pengeluaran_search') == $d['kode_jenis_pengeluaran'] ? 'selected' : '' }}>
                            {{ $d['jenis_pengeluaran'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="lg:hidden">Cari</span>
                </button>

                @if (request('dari') || request('sampai') || request('no_bukti_search') || request('kode_jenis_pengeluaran_search'))
                    <a href="{{ route('barangkeluargudangbahan.index') }}"
                        class="inline-flex items-center justify-center h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition"
                        title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
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
                                    <i data-lucide="package-minus" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Data Barang Keluar Gudang Bahan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($barangkeluar, 'total') ? $barangkeluar->total() : $barangkeluar->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NO. BUKTI</th>
                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">TANGGAL</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">JENIS</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($barangkeluar as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 dark:text-slate-400">
                                {{ $loop->iteration + ($barangkeluar->currentPage() - 1) * $barangkeluar->perPage() }}
                            </td>
                            <td class="py-1.5 px-4 font-bold text-brand-600 dark:text-brand-400 font-mono-num">
                                {{ $d->no_bukti }}
                            </td>
                            <td class="py-1.5 px-4 text-center text-slate-600 dark:text-slate-300 font-mono-num">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <td class="py-1.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    {{ $jenis_pengeluaran[$d->kode_jenis_pengeluaran] ?? $d->kode_jenis_pengeluaran }}
                                </span>
                            </td>
                            <td class="py-1.5 px-4 text-slate-700 dark:text-slate-300">
                                @if ($d->kode_jenis_pengeluaran == 'CBG')
                                    <span class="font-bold text-[#284c9a] dark:text-blue-400">{{ textUpperCase($d->nama_cabang) }}</span>
                                @elseif ($d->kode_jenis_pengeluaran == 'PRD')
                                    <span>Unit {{ $d->keterangan }}</span>
                                @else
                                    <span>{{ $d->keterangan ?: '-' }}</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('barangkeluargb.edit')
                                        <button type="button" class="btnEdit w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                            no_bukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                            title="Edit Barang Keluar">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('barangkeluargb.show')
                                        <button type="button" class="btnShow w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                            no_bukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                            title="Lihat Detail">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('barangkeluargb.delete')
                                        <form method="POST" action="{{ route('barangkeluargudangbahan.delete', Crypt::encrypt($d->no_bukti)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Hapus Data">
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
                                    <span class="text-xs font-semibold">Tidak ada data barang keluar yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangkeluar->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $barangkeluar->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL FORM MODAL-XL -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="Barang Keluar Gudang Bahan" />

@endsection

@push('myscript')
<script>
    $(function() {
        const loadingHtml = `
            <div class="p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-semibold">Memuat formulir...</span>
            </div>
        `;

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Tambah Data Barang Keluar");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangkeluargudangbahan/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnShow", function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Detail Barang Keluar");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangkeluargudangbahan/${no_bukti}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnEdit", function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Edit Barang Keluar");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangkeluargudangbahan/${no_bukti}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
