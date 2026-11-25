@extends('v2.layouts.app')
@section('titlepage', 'Kontrabon Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pembelian</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kontrabon Pembelian</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Kontrabon Pembelian</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen dan pemrosesan data kontra bon transaksi pembelian.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('kontrabonpmb.create')
                <a href="{{ route('kontrabonpmb.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Kontra Bon</span>
                </a>
            @endcan
        </div>
    </div>

    @include('v2.pembelian.kontrabon.kontrabon')

</div>
@endsection
