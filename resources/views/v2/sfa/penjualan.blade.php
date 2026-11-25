@extends('layouts.app')
@section('titlepage', 'Penjualan SFA')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">SFA</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Penjualan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Penjualan SFA</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Daftar transaksi penjualan salesman berdasarkan faktur dan periode.</p>
        </div>
    </div>

    @livewire('penjualansalesman')
</div>
@endsection

@push('myscript')
<script>
    function initSfaPenjualanPlugins() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initSfaPenjualanPlugins);
    document.addEventListener('livewire:navigated', initSfaPenjualanPlugins);
    document.addEventListener('livewire:update', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
