@extends('layouts.app')
@section('titlepage', 'Barang Masuk Gudang Logistik')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Logistik</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Barang Masuk</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Barang Masuk Gudang Logistik</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data penerimaan barang masuk ke gudang logistik.</p>
        </div>

        @can('barangmasukgl.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Barang Masuk</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS -->
    @include('layouts.navigation_mutasigudanglogistik')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('barangmasukgudanglogistik.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Tanggal Dari -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Dari Tanggal" readonly>
            </div>

            <!-- Tanggal Sampai -->
            <div class="lg:col-span-4 relative">
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

            <!-- Tombol Aksi -->
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="lg:hidden">Cari</span>
                </button>

                @if (request('dari') || request('sampai') || request('no_bukti_search'))
                    <a href="{{ route('barangmasukgudanglogistik.index') }}"
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
                    <!-- Row 1: Title Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="package-plus" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Data Barang Masuk Gudang Logistik</span>
                                </div>
                                <span class="text-[11px] font-normal text-white/80">Total: {{ method_exists($barangmasuk, 'total') ? $barangmasuk->total() : $barangmasuk->count() }} Bukti</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">NO. BUKTI</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">SUPPLIER</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-32">TGL PEMBELIAN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-32">TGL DITERIMA</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-slate-700 dark:text-slate-200">
                    @forelse ($barangmasuk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a]">
                                {{ $d->no_bukti }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->nama_supplier ?: '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center text-slate-500 dark:text-slate-400 font-mono-num">
                                {{ !empty($d->tanggal_pembelian) ? DateToIndo($d->tanggal_pembelian) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-semibold text-slate-700 dark:text-slate-300 font-mono-num">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('barangmasukgl.show')
                                        <button type="button" class="btnShow w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Detail">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('barangmasukgl.edit')
                                        @if (empty($d->tanggal_pembelian))
                                            <button type="button" class="btnEdit w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Edit">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('barangmasukgl.delete')
                                        <form method="POST" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('barangmasukgudanglogistik.delete', Crypt::encrypt($d->no_bukti)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data barang masuk yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangmasuk->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $barangmasuk->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL FORM MODAL-XL -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="Barang Masuk Gudang Logistik" />

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
            $("#modal .modal-title span").text("Tambah Data Barang Masuk");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangmasukgudanglogistik/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnShow", function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Detail Barang Masuk");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangmasukgudanglogistik/${no_bukti}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnEdit", function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Edit Barang Masuk");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/barangmasukgudanglogistik/${no_bukti}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush