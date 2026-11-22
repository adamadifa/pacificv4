@extends('layouts.app')
@section('titlepage', 'Konfirmasi FSTHP')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">FSTHP</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Konfirmasi Penerimaan FSTHP</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Konfirmasi serah terima barang jadi dari bagian produksi ke bagian gudang jadi.</p>
        </div>
    </div>

    <!-- NAVIGATION TABS GUDANG JADI (IF EXISTS) -->
    @include('layouts.navigation_mutasigudangjadi')

    <!-- ALERT INFO KONFIRMASI -->
    @can('suratjalan.approve')
        <div class="flex items-start gap-3 p-3 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200/80 dark:border-sky-800 text-xs text-sky-800 dark:text-sky-300">
            <i data-lucide="info" class="w-4 h-4 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5"></i>
            <div class="space-y-0.5">
                <span class="font-bold block">Petunjuk Verifikasi Penerimaan:</span>
                <p class="text-[11px] text-sky-700 dark:text-sky-300/80 leading-relaxed">
                    Gunakan tombol <span class="font-bold text-emerald-600 dark:text-emerald-400">[Terima]</span> untuk menyetujui barang masuk ke stok gudang jadi, atau tombol <span class="font-bold text-amber-600 dark:text-amber-400">[Batalkan]</span> untuk mencabut konfirmasi.
                </p>
            </div>
        </div>
    @endcan

    <!-- TABLE & FILTER INCLUDE -->
    @include('produksi.fsthp.index')

</div>

<!-- MODAL FORM DETAIL -->
<x-modal-form id="mdldetailFsthp" size="modal-lg" show="loaddetailFsthp" title="Detail Dokumen FSTHP" />

@endsection

@push('myscript')
<script>
    $(function() {
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
