@extends('layouts.app')
@section('titlepage', 'Laporan Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pembelian</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Pembelian</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak laporan resmi pembelian, pembayaran, hutang supplier, dan rekap PO.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[460px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5 overflow-y-auto max-h-[640px]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                @can('pb.pembelian')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-pembelian"
                        data-title="Laporan Pembelian"
                        data-desc="Laporan rekapitulasi transaksi pembelian barang dan faktur supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Pembelian</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Transaksi & faktur</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.pembayaran')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-pembayaran"
                        data-title="Laporan Pembayaran"
                        data-desc="Laporan histori pembayaran hutang dan transaksi kas/bank supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Pembayaran</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Histori bayar kas/bank</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.rekapsupplier')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapsupplier"
                        data-title="Rekap Pembelian Supplier"
                        data-desc="Rekapitulasi total transaksi pembelian per rekanan supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Supplier</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Total per supplier</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.rekappembelian')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappembelian"
                        data-title="Rekap Pembelian"
                        data-desc="Rekapitulasi nilai dan kuantum pembelian per jenis barang atau supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Pembelian</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Nilai & kuantum barang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.kartuhutang')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartuhutang"
                        data-title="Laporan Kartu Hutang"
                        data-desc="Kartu mutasi saldo hutang dagang dan hutang lainnya per supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Kartu Hutang</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Mutasi saldo hutang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.auh')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-auh"
                        data-title="Analisa Umur Hutang (AUH)"
                        data-desc="Analisa jatuh tempo dan aging schedule saldo hutang supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">AUH</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Analisa umur hutang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.bahankemasan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-bahankemasan"
                        data-title="Laporan Bahan & Kemasan"
                        data-desc="Rekapitulasi transaksi pembelian bahan baku, bahan tambahan, dan kemasan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Bahan & Kemasan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Bahan baku & kemasan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.rekapbahankemasan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapbahankemasan"
                        data-title="Rekap Kemasan / Supplier"
                        data-desc="Laporan rekapitulasi pembelian barang kemasan per supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Kemasan / Supplier</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Kemasan per supplier</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.jurnalkoreksi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-jurnalkoreksi"
                        data-title="Laporan Jurnal Koreksi"
                        data-desc="Daftar rekonsiliasi dan penyesuaian jurnal koreksi pembelian.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Jurnal Koreksi</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penyesuaian transaksi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.rekapakun')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapakun"
                        data-title="Rekap Akun Pembelian"
                        data-desc="Rekapitulasi alokasi akun pembukuan dan pembebanan biaya pembelian.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Akun</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Alokasi akun jurnal</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('pb.rekapkontrabon')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapkontrabon"
                        data-title="Rekap Kontra Bon"
                        data-desc="Daftar dokumen kontra bon dan tanda terima tagihan supplier.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="file-check-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Kontra Bon</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Status dokumen KB</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                    data-target="#pane-rekappo"
                    data-title="Rekap Pre-Order (PO)"
                    data-desc="Rekapitulasi penerbitan purchase order (PO) barang dan status pemesanan.">
                    <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap PO</span>
                        <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Purchase order barang</span>
                    </div>
                    <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                </button>
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="flex-1 p-5 sm:p-6 bg-white dark:bg-slate-900 flex flex-col justify-start min-w-0">
                
                <!-- Active Report Header Info (Clean, Rule 4 Compliant: No Blue Dot Bullet, No Format Badge) -->
                <div class="flex items-start justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 id="activeReportTitle" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Laporan Pembelian</h4>
                        <p id="activeReportDesc" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Laporan rekapitulasi transaksi pembelian barang dan faktur supplier.</p>
                    </div>
                </div>

                <!-- Form Panes -->
                <div class="laporan-panes-wrapper">
                    @can('pb.pembelian')
                        <div class="laporan-panel hidden" id="pane-pembelian">
                            @include('v2.pembelian.laporan.pembelian')
                        </div>
                    @endcan

                    @can('pb.pembayaran')
                        <div class="laporan-panel hidden" id="pane-pembayaran">
                            @include('v2.pembelian.laporan.pembayaran')
                        </div>
                    @endcan

                    @can('pb.rekapsupplier')
                        <div class="laporan-panel hidden" id="pane-rekapsupplier">
                            @include('v2.pembelian.laporan.rekapsupplier')
                        </div>
                    @endcan

                    @can('pb.rekappembelian')
                        <div class="laporan-panel hidden" id="pane-rekappembelian">
                            @include('v2.pembelian.laporan.rekappembelian')
                        </div>
                    @endcan

                    @can('pb.kartuhutang')
                        <div class="laporan-panel hidden" id="pane-kartuhutang">
                            @include('v2.pembelian.laporan.kartuhutang')
                        </div>
                    @endcan

                    @can('pb.auh')
                        <div class="laporan-panel hidden" id="pane-auh">
                            @include('v2.pembelian.laporan.auh')
                        </div>
                    @endcan

                    @can('pb.bahankemasan')
                        <div class="laporan-panel hidden" id="pane-bahankemasan">
                            @include('v2.pembelian.laporan.bahankemasan')
                        </div>
                    @endcan

                    @can('pb.rekapbahankemasan')
                        <div class="laporan-panel hidden" id="pane-rekapbahankemasan">
                            @include('v2.pembelian.laporan.rekapbahankemasan')
                        </div>
                    @endcan

                    @can('pb.jurnalkoreksi')
                        <div class="laporan-panel hidden" id="pane-jurnalkoreksi">
                            @include('v2.pembelian.laporan.jurnalkoreksi')
                        </div>
                    @endcan

                    @can('pb.rekapakun')
                        <div class="laporan-panel hidden" id="pane-rekapakun">
                            @include('v2.pembelian.laporan.rekapakun')
                        </div>
                    @endcan

                    @can('pb.rekapkontrabon')
                        <div class="laporan-panel hidden" id="pane-rekapkontrabon">
                            @include('v2.pembelian.laporan.rekapkontrabon')
                        </div>
                    @endcan

                    <div class="laporan-panel hidden" id="pane-rekappo">
                        @include('v2.pembelian.laporan.rekappo')
                    </div>
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

        // Activate the first available tab automatically
        const $firstTab = $('.laporan-tab-btn').first();
        if ($firstTab.length) {
            activateTab($firstTab);
        }

        function activateTab($tab) {
            const target = $tab.data('target');
            const title = $tab.data('title');
            const desc = $tab.data('desc');

            // Reset all tab styles
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

            // Set active tab styles
            $tab
                .addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $tab.find('.tab-icon')
                .addClass('bg-white/20 text-white')
                .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400');
            $tab.find('.tab-desc')
                .addClass('text-white/75')
                .removeClass('text-slate-400 dark:text-slate-500');
            $tab.find('.tab-chevron')
                .addClass('text-white/80 opacity-100')
                .removeClass('text-slate-300 dark:text-slate-600 opacity-0');

            $('#activeReportTitle').text(title);
            $('#activeReportDesc').text(desc);

            $('.laporan-panel').addClass('hidden');
            $(target).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Tab Switching Click
        $('.laporan-tab-btn').on('click', function() {
            activateTab($(this));
        });
    });
</script>
@endpush
