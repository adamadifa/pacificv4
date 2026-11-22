@extends('layouts.app')
@section('titlepage', 'Surat Jalan Gudang Jadi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Surat Jalan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Surat Jalan Gudang Jadi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pengelolaan dan pengiriman surat jalan barang jadi dari pusat ke cabang.</p>
        </div>

        @can('suratjalan.create')
            <a href="{{ route('permintaankiriman.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Surat Jalan</span>
            </a>
        @endcan
    </div>

    <!-- PETUNJUK PENERIMAAN (JIKA ROLE MEMILIKI HAK APPROVE) -->
    @can('suratjalan.approve')
        <div class="rounded-xl border border-sky-200 dark:border-sky-800/60 bg-sky-50/70 dark:bg-sky-950/30 p-3 flex items-start gap-3">
            <div class="w-7 h-7 rounded-lg bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0 mt-0.5">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>
            <div class="text-xs text-sky-900 dark:text-sky-200 space-y-1">
                <p class="font-bold">Informasi Penerimaan Dokumen</p>
                <p class="text-[11px] text-sky-700 dark:text-sky-300">
                    Gunakan icon centang <span class="inline-flex items-center px-1.5 py-0.2 rounded bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-bold text-[10px]">Terima</span> untuk memproses penerimaan surat jalan, atau icon putar kembali <span class="inline-flex items-center px-1.5 py-0.2 rounded bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 font-bold text-[10px]">Batal</span> untuk membatalkan penerimaan.
                </p>
            </div>
        </div>
    @endcan

    <!-- NAVIGATION TABS MUTASI GUDANG JADI -->
    @include('layouts.navigation_mutasigudangjadi')

    <!-- INDEX CONTENT (FILTER & TABLE) -->
    @include('v2.gudangjadi.suratjalan.index')

</div>

<!-- MODAL FORM V2 -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection

@push('myscript')
<script src="{{ asset('assets/js/pages/suratjalan.js') }}"></script>
@endpush
