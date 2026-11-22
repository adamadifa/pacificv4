@extends('layouts.app')
@section('titlepage', 'Detail Saldo Awal Rekening')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sarekening.index') }}" class="text-slate-400 hover:text-brand-600 transition">Saldo Awal Rekening</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Detail Saldo Awal Rekening</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Rincian saldo awal per rekening bank untuk kode transaksi <span class="font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ $saldo_awal->kode_saldo_awal }}</span>.</p>
        </div>

        <a href="{{ route('sarekening.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- SUMMARY INFO STRIP (3 BOXES) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
        <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Kode Saldo Awal</span>
            <div class="flex items-center gap-2 mt-1">
                <i data-lucide="hash" class="w-3.5 h-3.5 text-brand-600"></i>
                <span class="text-xs font-black font-mono-num text-slate-900 dark:text-white">{{ $saldo_awal->kode_saldo_awal }}</span>
            </div>
        </div>

        <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Tanggal Berlaku</span>
            <div class="flex items-center gap-2 mt-1">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ formatIndo($saldo_awal->tanggal) }}</span>
            </div>
        </div>

        <div class="p-3 rounded-xl bg-[#284c9a] text-white shadow-2xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-white/70 block leading-tight">Total Saldo Bank</span>
            @php
                $total_show = $saldo_awal->details->sum('jumlah');
            @endphp
            <div class="flex items-center gap-2 mt-1">
                <i data-lucide="wallet" class="w-3.5 h-3.5 text-amber-300"></i>
                <span class="text-xs font-black font-mono-num text-amber-300">Rp {{ formatAngka($total_show) }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL TABLE CARD WITH SEAMLESS UNIFIED HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- Table with Seamless Integrated Header -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans">
                <thead>
                    <!-- Row 1: Integrated Title -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="3" class="px-4 py-3.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="landmark" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Saldo Bank</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    {{ count($saldo_awal->details) }} Rekening
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles Seamlessly Flowing Below -->
                    <tr class="bg-[#284c9a] text-white text-[11px] border-none">
                        <th class="py-3 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-3 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BANK & NOMOR REKENING</th>
                        <th class="py-3 px-4 text-right font-bold uppercase tracking-wider w-60 text-white/90 border-none">SALDO AWAL (RP)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $total = 0; @endphp
                    @foreach ($saldo_awal->details as $d)
                        @php $total += $d->jumlah; @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-2 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Nama Bank & Rekening -->
                            <td class="py-2 px-3.5 border-r border-slate-100 dark:border-slate-800/60">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[11px] shrink-0 border border-slate-200/60 dark:border-slate-700">
                                        {{ substr($d->bank->nama_bank, 0, 3) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block leading-tight">{{ $d->bank->nama_bank }}</span>
                                        <span class="text-[10px] font-mono-num text-slate-400 block leading-tight">{{ $d->bank->no_rekening }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Saldo Awal -->
                            <td class="py-2 px-3.5 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400 text-xs">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/80 border-t-2 border-slate-200 dark:border-slate-700">
                    <tr>
                        <th colspan="2" class="py-2.5 px-3.5 text-center font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            TOTAL SALDO AWAL BANK
                        </th>
                        <th class="py-2.5 px-3.5 text-right font-mono-num font-black text-xs text-brand-700 dark:text-brand-300">
                            Rp {{ formatAngka($total) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@endpush
