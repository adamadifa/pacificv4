@extends('layouts.app')
@section('titlepage', 'Reject Gudang Jadi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Reject</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Reject Gudang Jadi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pencatatan dan pemantauan produk reject (rusak/tidak layak) di gudang jadi.</p>
        </div>

        @can('rejectgudangjadi.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Reject</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS -->
    @include('layouts.navigation_mutasigudangjadi')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('rejectgudangjadi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Dari Tanggal -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Sampai Tanggal -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Tombol Cari -->
            <div class="lg:col-span-2 flex items-center">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Header Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Mutasi Reject</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($reject, 'total') ? $reject->total() : count($reject) }} data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-44">NO. REJECT</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">TANGGAL</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($reject as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_mutasi }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                    <span>Reject</span>
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('rejectgudangjadi.edit')
                                        <button type="button"
                                            class="btnEdit w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                            title="Edit Data"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('rejectgudangjadi.show')
                                        <button type="button"
                                            class="btnShow w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-[#284c9a] dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                            title="Lihat Detail"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('rejectgudangjadi.delete')
                                        <form method="POST" action="{{ route('rejectgudangjadi.delete', Crypt::encrypt($d->no_mutasi)) }}"
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
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="trash-2" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Data Reject tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($reject, 'hasPages') && $reject->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $reject->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL FORM & DETAIL (STANDAR V2 STATIC BACKDROP & CLEAN HEADER) -->
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="Data Reject Gudang Jadi" />

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        const loadingHtml = `
            <div class="p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-semibold">Memuat formulir...</span>
            </div>
        `;

        $("#btnCreate").on("click", function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Tambah Data Reject");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/rejectgudangjadi/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnEdit", function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr("no_mutasi");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Edit Data Reject");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/rejectgudangjadi/${no_mutasi}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on("click", ".btnShow", function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr("no_mutasi");
            $("#modal").modal("show");
            $("#modal .modal-title span").text("Detail Data Reject");
            $("#loadmodal").html(loadingHtml);
            $("#loadmodal").load(`/rejectgudangjadi/${no_mutasi}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
