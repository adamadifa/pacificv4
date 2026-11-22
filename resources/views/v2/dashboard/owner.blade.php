@extends('layouts.app')
@section('titlepage', 'Dashboard Owner')

@php
    $tanggal_formatted = \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y');
@endphp

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Owner</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- FILTER TANGGAL (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG, FLATPICKR) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 shadow-2xs transition-colors duration-200">
        <form action="{{ URL::current() }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5">
            <!-- Date Input with Flatpickr -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Pilih Tanggal" readonly>
            </div>

            <!-- Submit Filter Button -->
            <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Tampilkan Data</span>
            </button>
        </form>
    </div>

    <!-- 4 EXECUTIVE KPI METRIC STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS & WATERMARK) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <!-- Subtle Ambient Background Pattern -->
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. Total Saldo Awal (Gabungan) -->
            <div class="btn-detail-saldoawal-all relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.06] transition-colors duration-200 group cursor-pointer" title="Klik untuk melihat rincian saldo awal gabungan">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-3 bottom-2 pointer-events-none select-none text-white/[0.08] group-hover:text-white/[0.14] transition-colors">
                    <i data-lucide="wallet" class="w-16 h-16"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white/90 border border-white/15">
                            Saldo Gabungan
                        </span>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Saldo Awal</p>
                        <div class="text-xl font-black text-white font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp {{ formatAngka($total_saldo_awal) }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Bank & Kas Besar • Klik detail</p>
                    </div>
                </div>
            </div>

            <!-- 2. Total Penerimaan (Gabungan) -->
            <div class="btn-detail-mutasi-all relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b lg:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.06] transition-colors duration-200 group cursor-pointer" data-jenis="K" title="Klik untuk melihat rincian penerimaan gabungan">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-3 bottom-2 pointer-events-none select-none text-white/[0.08] group-hover:text-white/[0.14] transition-colors">
                    <i data-lucide="arrow-down-left" class="w-16 h-16"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs">
                            <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-400/20">
                            Cash In
                        </span>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Penerimaan</p>
                        <div class="text-xl font-black text-emerald-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp {{ formatAngka($total_penerimaan) }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Pemasukan harian • Klik detail</p>
                    </div>
                </div>
            </div>

            <!-- 3. Total Pengeluaran (Gabungan) -->
            <div class="btn-detail-mutasi-all relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] border-b sm:border-b-0 sm:border-r border-white/15 hover:bg-white/[0.06] transition-colors duration-200 group cursor-pointer" data-jenis="D" title="Klik untuk melihat rincian pengeluaran gabungan">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-3 bottom-2 pointer-events-none select-none text-white/[0.08] group-hover:text-white/[0.14] transition-colors">
                    <i data-lucide="arrow-up-right" class="w-16 h-16"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/20 text-rose-300 flex items-center justify-center border border-rose-400/30 shrink-0 shadow-xs">
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/15 text-rose-300 border border-rose-400/20">
                            Cash Out
                        </span>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Pengeluaran</p>
                        <div class="text-xl font-black text-rose-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp {{ formatAngka($total_pengeluaran) }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Pengeluaran harian • Klik detail</p>
                    </div>
                </div>
            </div>

            <!-- 4. Total Net / Saldo Akhir (Gabungan) -->
            <div class="relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[132px] hover:bg-white/[0.04] transition-colors duration-200 group">
                <svg class="absolute right-0 bottom-0 w-28 h-28 text-white/[0.05] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>

                <div class="absolute right-3 bottom-2 pointer-events-none select-none text-white/[0.08] group-hover:text-white/[0.14] transition-colors">
                    <i data-lucide="scale" class="w-16 h-16"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full pr-14 sm:pr-16">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-300/30 shrink-0 shadow-xs">
                            <i data-lucide="scale" class="w-4 h-4"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/15 text-amber-300 border border-amber-300/20">
                            Net Position
                        </span>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium text-white/75 leading-none">Total Net (Saldo Akhir)</p>
                        <div class="text-xl font-black text-amber-300 font-mono-num tracking-tight mt-1.5 leading-none">
                            Rp {{ formatAngka($total_net) }}
                        </div>
                        <p class="text-[11px] text-white/55 leading-none mt-1">Posisi akhir hari ini</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- DUAL FINANCIAL BREAKDOWN CARDS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">
        
        <!-- CARD 1: REKENING BANK OPERASIONAL (NON-KAS BESAR) -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col justify-between">
            <div>
                <!-- Header Card -->
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0 shadow-2xs">
                            <i data-lucide="landmark" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekening Bank Operasional</h4>
                            <span class="text-[11px] text-slate-400 leading-none">Akumulasi seluruh rekening bank (exclude Kas Besar)</span>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 border border-brand-200/60">
                        Bank Accounts
                    </span>
                </div>

                <!-- Financial Items List -->
                <div class="space-y-2.5">
                    
                    <!-- Saldo Awal Bank -->
                    <div class="btn-detail-saldoawal flex items-center justify-between p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-slate-200/60 dark:border-slate-800 transition cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">Saldo Awal Bank</span>
                                <span class="text-[10px] text-slate-400 block">Klik untuk rincian per bank</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-slate-900 dark:text-white">Rp {{ formatAngka($saldo_awal) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                    <!-- Penerimaan Bank -->
                    <div class="btn-detail-mutasi flex items-center justify-between p-3 rounded-lg bg-emerald-50/40 dark:bg-emerald-950/20 hover:bg-emerald-50/80 dark:hover:bg-emerald-950/40 border border-emerald-200/50 dark:border-emerald-800/40 transition cursor-pointer group" data-jenis="K">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Penerimaan Bank</span>
                                <span class="text-[10px] text-slate-400 block">Total setoran & transfer masuk</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-emerald-600 dark:text-emerald-400">+ Rp {{ formatAngka($penerimaan) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                    <!-- Pengeluaran Bank -->
                    <div class="btn-detail-mutasi flex items-center justify-between p-3 rounded-lg bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/80 dark:hover:bg-rose-950/40 border border-rose-200/50 dark:border-rose-800/40 transition cursor-pointer group" data-jenis="D">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Pengeluaran Bank</span>
                                <span class="text-[10px] text-slate-400 block">Total biaya & transfer keluar</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-rose-600 dark:text-rose-400">- Rp {{ formatAngka($pengeluaran) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-rose-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Net Saldo Akhir Bank Summary Box -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-2xs">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight block">Net. Saldo Akhir Bank</span>
                            <span class="text-[10px] text-slate-400 block">Posisi riil saldo bank</span>
                        </div>
                    </div>
                    <span class="font-mono-num font-black text-base text-brand-600 dark:text-brand-400">Rp {{ formatAngka($net) }}</span>
                </div>
            </div>
        </div>

        <!-- CARD 2: REKAP KAS BESAR (BK070 - CASH ON HAND) -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200 flex flex-col justify-between">
            <div>
                <!-- Header Card -->
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-900 shrink-0 shadow-2xs">
                            <i data-lucide="vault" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Kas Besar (BK070)</h4>
                            <span class="text-[11px] text-slate-400 leading-none">Arus kas tunai fisik cabang & pusat</span>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60">
                        Cash on Hand
                    </span>
                </div>

                <!-- Financial Items List -->
                <div class="space-y-2.5">
                    
                    <!-- Saldo Awal Kas Besar -->
                    <div class="btn-detail-saldoawal-kb flex items-center justify-between p-3 rounded-lg bg-slate-50/70 dark:bg-slate-950/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-slate-200/60 dark:border-slate-800 transition cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Saldo Awal Kas Besar</span>
                                <span class="text-[10px] text-slate-400 block">Klik untuk rincian per cabang</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-slate-900 dark:text-white">Rp {{ formatAngka($saldo_awal_kb) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-amber-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                    <!-- Penerimaan Kas Besar -->
                    <div class="btn-detail-mutasi-kb flex items-center justify-between p-3 rounded-lg bg-emerald-50/40 dark:bg-emerald-950/20 hover:bg-emerald-50/80 dark:hover:bg-emerald-950/40 border border-emerald-200/50 dark:border-emerald-800/40 transition cursor-pointer group" data-jenis="K">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Penerimaan Kas Besar</span>
                                <span class="text-[10px] text-slate-400 block">Setoran tunai & penerimaan kas</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-emerald-600 dark:text-emerald-400">+ Rp {{ formatAngka($penerimaan_kb) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                    <!-- Pengeluaran Kas Besar -->
                    <div class="btn-detail-mutasi-kb flex items-center justify-between p-3 rounded-lg bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/80 dark:hover:bg-rose-950/40 border border-rose-200/50 dark:border-rose-800/40 transition cursor-pointer group" data-jenis="D">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Pengeluaran Kas Besar</span>
                                <span class="text-[10px] text-slate-400 block">Biaya operasional kas tunai</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono-num font-bold text-sm text-rose-600 dark:text-rose-400">- Rp {{ formatAngka($pengeluaran_kb) }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-rose-600 group-hover:translate-x-0.5 transition-all"></i>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Net Saldo Akhir Kas Besar Summary Box -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-2xs">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight block">Net. Saldo Akhir Kas Besar</span>
                            <span class="text-[10px] text-slate-400 block">Posisi riil fisik kas tunai</span>
                        </div>
                    </div>
                    <span class="font-mono-num font-black text-base text-amber-600 dark:text-amber-400">Rp {{ formatAngka($net_kb) }}</span>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- TAILWIND MODAL DETAIL KEUNGAN -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modalTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" id="modalBackdrop"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-4xl border border-slate-200 dark:border-slate-800">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 bg-[#284c9a] text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-blue-200"></i>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider" id="modalTitle">Detail Keuangan</h3>
                </div>
                <button type="button" id="btnModalClose" class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 sm:p-5 max-h-[75vh] overflow-y-auto scrollbar-thin" id="modalBody">
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                    <span class="text-xs">Memuat detail keuangan...</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex justify-end">
                <button type="button" id="btnModalCloseFooter" class="h-[34px] px-4 rounded-lg bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // Initialize Flatpickr Datepicker
    if ($('.flatpickr-date').length && typeof flatpickr !== 'undefined') {
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            defaultDate: '{{ $tanggal }}'
        });
    }

    // Modal helpers
    function openModal(title) {
        $("#modalTitle").text(title);
        $("#modalBody").html(`
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <div class="w-7 h-7 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                <span class="text-xs">Memuat data...</span>
            </div>
        `);
        $("#modalDetail").removeClass("hidden");
        $("body").addClass("overflow-hidden");
        if (window.lucide) { lucide.createIcons(); }
    }

    function closeModal() {
        $("#modalDetail").addClass("hidden");
        $("body").removeClass("overflow-hidden");
    }

    $("#btnModalClose, #btnModalCloseFooter, #modalBackdrop").on("click", function() {
        closeModal();
    });

    // Close on ESC key
    $(document).on("keydown", function(e) {
        if (e.key === "Escape" && !$("#modalDetail").hasClass("hidden")) {
            closeModal();
        }
    });

    // 1. Detail Saldo Awal Bank
    $(".btn-detail-saldoawal").on("click", function() {
        const tanggal = $("#tanggal").val();
        openModal("Detail Saldo Awal Rekening Bank (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailsaldoawal') }}",
            type: "GET",
            data: { tanggal: tanggal },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian saldo awal bank.</div>');
            }
        });
    });

    // 2. Detail Saldo Awal Gabungan (All)
    $(".btn-detail-saldoawal-all").on("click", function() {
        const tanggal = $("#tanggal").val();
        openModal("Detail Saldo Awal Gabungan Bank & Kas Besar (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailsaldoawal') }}",
            type: "GET",
            data: { 
                tanggal: tanggal,
                all: 1
            },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian saldo awal gabungan.</div>');
            }
        });
    });

    // 3. Detail Mutasi Bank (Penerimaan / Pengeluaran)
    $(".btn-detail-mutasi").on("click", function() {
        const tanggal = $("#tanggal").val();
        const jenis = $(this).data("jenis");
        const title = jenis === "K" ? "Detail Penerimaan Rekening Bank" : "Detail Pengeluaran Rekening Bank";

        openModal(title + " (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailmutasi') }}",
            type: "GET",
            data: { 
                tanggal: tanggal,
                jenis: jenis
            },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian mutasi rekening bank.</div>');
            }
        });
    });

    // 4. Detail Mutasi Gabungan (All)
    $(".btn-detail-mutasi-all").on("click", function() {
        const tanggal = $("#tanggal").val();
        const jenis = $(this).data("jenis");
        const title = jenis === "K" ? "Detail Penerimaan Gabungan (Bank + Kas Besar)" : "Detail Pengeluaran Gabungan (Bank + Kas Besar)";

        openModal(title + " (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailmutasi') }}",
            type: "GET",
            data: { 
                tanggal: tanggal,
                jenis: jenis,
                all: 1
            },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian mutasi gabungan.</div>');
            }
        });
    });

    // 5. Detail Saldo Awal Kas Besar
    $(".btn-detail-saldoawal-kb").on("click", function() {
        const tanggal = $("#tanggal").val();
        openModal("Detail Saldo Awal Kas Besar (BK070) (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailsaldoawalkb') }}",
            type: "GET",
            data: { tanggal: tanggal },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian saldo kas besar.</div>');
            }
        });
    });

    // 6. Detail Mutasi Kas Besar
    $(".btn-detail-mutasi-kb").on("click", function() {
        const tanggal = $("#tanggal").val();
        const jenis = $(this).data("jenis");
        const title = jenis === "K" ? "Detail Penerimaan Kas Besar (BK070)" : "Detail Pengeluaran Kas Besar (BK070)";

        openModal(title + " (" + tanggal + ")");

        $.ajax({
            url: "{{ route('dashboard.getdetailmutasikb') }}",
            type: "GET",
            data: { 
                tanggal: tanggal,
                jenis: jenis
            },
            success: function(response) {
                $("#modalBody").html(response);
                if (window.lucide) { lucide.createIcons(); }
            },
            error: function() {
                $("#modalBody").html('<div class="p-6 text-center text-rose-500 text-xs font-semibold">Gagal memuat rincian mutasi kas besar.</div>');
            }
        });
    });
});
</script>
@endpush
