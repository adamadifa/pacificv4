@extends('layouts.app')
@section('titlepage', 'Form Serah Terima Hasil Produksi (FSTHP)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Produksi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">FSTHP</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Form Serah Terima Hasil Produksi (FSTHP)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data serah terima barang jadi dari unit produksi ke bagian gudang jadi.</p>
        </div>

        @can('fsthp.create')
            <button type="button" id="btncreateFsthp"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah FSTHP</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS (MUTASI PRODUKSI) -->
    @include('layouts.navigation_mutasiproduksi')

    <!-- TABLE & FILTER INCLUDE -->
    @include('produksi.fsthp.index')

</div>

<!-- MODAL FORM CREATE & DETAIL -->
<x-modal-form id="mdlcreateFsthp" size="modal-lg" show="loadcreateFsthp" title="Tambah FSTHP (Serah Terima Produksi)" />
<x-modal-form id="mdldetailFsthp" size="modal-lg" show="loaddetailFsthp" title="Detail Dokumen FSTHP" />

@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateFsthp").click(function(e) {
            e.preventDefault();
            $('#mdlcreateFsthp').modal("show");
            $("#loadcreateFsthp").html(`
                <div class="p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat formulir tambah FSTHP...</span>
                </div>
            `);
            $("#loadcreateFsthp").load('/fsthp/create', function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });

        $(".showFsthp").click(function(e) {
            e.preventDefault();
            var no_mutasi = $(this).attr("no_mutasi");
            $('#mdldetailFsthp').modal("show");
            $("#loaddetailFsthp").html(`
                <div class="p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat rincian FSTHP...</span>
                </div>
            `);
            $("#loaddetailFsthp").load('/fsthp/' + no_mutasi + '/show', function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush
