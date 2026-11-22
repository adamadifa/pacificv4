@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Cabang</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Gudang Cabang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak dokumen resmi persediaan barang, mutasi DPB, dan rekonsiliasi produk jadi cabang.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[460px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3.5 sm:p-4 flex flex-col gap-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Pilihan Laporan</span>

                <!-- 1. Lap. Persediaan GS -->
                @can('gc.goodstok')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-goodstok"
                        data-title="Laporan Persediaan Good Stok"
                        data-desc="Laporan pergerakan dan mutasi kartu persediaan Good Stok (GS) cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Persediaan GS</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Kartu stok barang baik (GS)</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan

                <!-- 2. Lap. Persediaan BS -->
                @can('gc.badstok')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-badstok"
                        data-title="Laporan Persediaan Bad Stok"
                        data-desc="Laporan pergerakan dan mutasi kartu persediaan Bad Stok (BS) / Reject cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package-x" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Persediaan BS</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Kartu stok barang rusak (BS)</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 3. Rekap Persediaan -->
                @can('gc.rekappersediaan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappersediaan"
                        data-title="Laporan Rekap Persediaan"
                        data-desc="Rekapitulasi saldo awal, penerimaan, pengeluaran, dan saldo akhir gudang cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Persediaan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekapitulasi stok & saldo</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 4. Mutasi DPB -->
                @can('gc.mutasidpb')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-mutasidpb"
                        data-title="Laporan Mutasi DPB"
                        data-desc="Laporan pergerakan pengambilan dan mutasi Daftar Pengambilan Barang (DPB) cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Mutasi DPB</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pengambilan barang salesman</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 5. Rekonsiliasi BJ -->
                @can('gc.rekonsiliasibj')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekonsiliasibj"
                        data-title="Laporan Rekonsiliasi Barang Jadi"
                        data-desc="Laporan pencocokan data penjualan, retur, dan promosi barang jadi cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekonsiliasi BJ</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penjualan, retur & promosi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

            </div>

            <!-- RIGHT FORM CONTAINER -->
            <div class="flex-1 p-5 sm:p-6 bg-white dark:bg-slate-900">
                
                <!-- ACTIVE FORM HEADER STRIP (Clean & Proportional) -->
                <div class="pb-4 mb-5 border-b border-slate-100 dark:border-slate-800">
                    <h4 id="activeReportTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Laporan Persediaan Good Stok
                    </h4>
                    <p id="activeReportDesc" class="text-xs text-slate-400 mt-1">
                        Laporan pergerakan dan mutasi kartu persediaan Good Stok (GS) cabang.
                    </p>
                </div>

                <!-- TAB PANES -->
                <div class="tab-content">
                    
                    @can('gc.goodstok')
                        <div id="pane-goodstok" class="laporan-pane">
                            @include('v2.gudangcabang.laporan.goodstok')
                        </div>
                    @endcan

                    @can('gc.badstok')
                        <div id="pane-badstok" class="laporan-pane hidden">
                            @include('v2.gudangcabang.laporan.badstok')
                        </div>
                    @endcan

                    @can('gc.rekappersediaan')
                        <div id="pane-rekappersediaan" class="laporan-pane hidden">
                            @include('v2.gudangcabang.laporan.rekappersediaan')
                        </div>
                    @endcan

                    @can('gc.mutasidpb')
                        <div id="pane-mutasidpb" class="laporan-pane hidden">
                            @include('v2.gudangcabang.laporan.mutasidpb')
                        </div>
                    @endcan

                    @can('gc.rekonsiliasibj')
                        <div id="pane-rekonsiliasibj" class="laporan-pane hidden">
                            @include('v2.gudangcabang.laporan.rekonsiliasibj')
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

        // Tab Switcher Handling
        $('.laporan-tab-btn').on('click', function() {
            const $this = $(this);
            if ($this.hasClass('active')) return;

            // Reset all tabs
            $('.laporan-tab-btn').each(function() {
                $(this).removeClass('active bg-[#284c9a] text-white shadow-xs')
                    .addClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60 hover:bg-slate-100 dark:hover:bg-slate-800');
                $(this).find('.tab-icon').removeClass('bg-white/20 text-white')
                    .addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
                $(this).find('.tab-desc').removeClass('text-white/75').addClass('text-slate-400 dark:text-slate-500');
                $(this).find('.tab-chevron').removeClass('text-white/80').addClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');
            });

            // Activate clicked tab
            $this.addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60 hover:bg-slate-100 dark:hover:bg-slate-800');
            $this.find('.tab-icon').addClass('bg-white/20 text-white')
                .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
            $this.find('.tab-desc').addClass('text-white/75').removeClass('text-slate-400 dark:text-slate-500');
            $this.find('.tab-chevron').addClass('text-white/80').removeClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');

            // Update header info
            $('#activeReportTitle').text($this.data('title'));
            $('#activeReportDesc').text($this.data('desc'));

            // Show corresponding pane
            const targetPane = $this.data('target');
            $('.laporan-pane').addClass('hidden');
            $(targetPane).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
</script>
@endpush
