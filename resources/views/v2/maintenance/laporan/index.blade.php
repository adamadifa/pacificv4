@extends('layouts.app')
@section('titlepage', 'Laporan Maintenance')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Maintenance</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-3xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Maintenance</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak laporan rekapitulasi mutasi dan pemakaian bahan bakar maintenance.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="flex flex-col md:flex-row min-h-[380px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-64 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                @can('mtc.bahanbakar')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-bahanbakar"
                        data-title="Laporan Bahan Bakar"
                        data-desc="Rekapitulasi mutasi, penerimaan, dan pengeluaran bahan bakar maintenance bulanan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="fuel" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Bahan Bakar</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Mutasi bahan bakar</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="flex-1 p-5 sm:p-6 bg-white dark:bg-slate-900 flex flex-col justify-start min-w-0">
                
                <!-- Active Report Header Info (CLEAN, NO BLUE DOT BULLET) -->
                <div class="flex items-start justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 id="activeReportTitle" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Laporan Bahan Bakar</h4>
                        <p id="activeReportDesc" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Rekapitulasi mutasi, penerimaan, dan pengeluaran bahan bakar maintenance bulanan.</p>
                    </div>
                </div>

                <!-- Form Panes -->
                <div class="laporan-panes-wrapper">
                    @can('mtc.bahanbakar')
                        <div class="laporan-panel" id="pane-bahanbakar">
                            @include('v2.maintenance.laporan.bahanbakar')
                        </div>
                    @endcan
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Tab Switching
        $('.laporan-tab-btn').on('click', function() {
            const $this = $(this);
            const target = $this.data('target');
            const title = $this.data('title');
            const desc = $this.data('desc');

            // Reset tab styles
            $('.laporan-tab-btn')
                .removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $('.laporan-tab-btn .tab-icon')
                .removeClass('bg-white/20 text-white')
                .addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400');
            $('.laporan-tab-btn .tab-desc')
                .removeClass('text-white/75')
                .addClass('text-slate-400 dark:text-slate-500');
            $('.laporan-tab-btn .tab-chevron')
                .removeClass('text-white/80 opacity-100')
                .addClass('text-slate-300 dark:text-slate-600 opacity-0');

            // Active tab styles
            $this
                .addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $this.find('.tab-icon')
                .addClass('bg-white/20 text-white')
                .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400');
            $this.find('.tab-desc')
                .addClass('text-white/75')
                .removeClass('text-slate-400 dark:text-slate-500');
            $this.find('.tab-chevron')
                .addClass('text-white/80 opacity-100')
                .removeClass('text-slate-300 dark:text-slate-600 opacity-0');

            $('#activeReportTitle').text(title);
            $('#activeReportDesc').text(desc);

            $('.laporan-panel').addClass('hidden');
            $(target).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
</script>
@endpush
