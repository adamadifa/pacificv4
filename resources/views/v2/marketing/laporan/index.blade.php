@extends('layouts.app')
@section('titlepage', 'Laporan Marketing')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Marketing</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak dokumen resmi penjualan, piutang, kas besar, dan analisa performa tim marketing.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS (STANDAR V2) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[540px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5 max-h-[720px] overflow-y-auto sidebar-scroll-container">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                <!-- 1. Penjualan -->
                @can('mkt.penjualan')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-penjualan"
                        data-title="Laporan Penjualan"
                        data-desc="Laporan rincian transaksi penjualan barang, faktur standar, PO, dan format satu baris.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Penjualan</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Faktur & rincian penjualan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan

                <!-- 2. Rekap Penjualan -->
                @can('mkt.rekappenjualan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappenjualan"
                        data-title="Laporan Rekap Penjualan"
                        data-desc="Rekapitulasi penjualan, retur, qty, produk, dan analisa penjualan multi tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Penjualan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap penjualan & multi tahun</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 3. Kas Besar -->
                @can('mkt.kasbesar')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kasbesar"
                        data-title="Laporan Kas Besar"
                        data-desc="Rekapitulasi dan rincian penerimaan kas besar harian, tunai, titipan, transfer, dan giro.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Kas Besar</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penerimaan kas & setoran</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 4. Retur -->
                @can('mkt.retur')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-retur"
                        data-title="Laporan Retur Penjualan"
                        data-desc="Laporan pengembalian barang retur penggantian, potong faktur, dan rincian produk retur.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Retur Penjualan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pengembalian barang & retur</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 5. Tunai Kredit -->
                @can('mkt.tunaikredit')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-tunaikredit"
                        data-title="Laporan Tunai Kredit"
                        data-desc="Laporan perbandingan dan rekap transaksi pembayaran tunai versus kredit.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Tunai Kredit</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Komparasi tunai & kredit</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 6. Kartu Piutang -->
                @can('mkt.kartupiutang')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartupiutang"
                        data-title="Laporan Kartu Piutang"
                        data-desc="Laporan mutasi kartu piutang pelanggan, saldo awal, pembayaran berjalan, dan saldo akhir.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Kartu Piutang</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Mutasi piutang pelanggan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 7. AUP -->
                @can('mkt.aup')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-aup"
                        data-title="Laporan Analisa Umur Piutang (AUP)"
                        data-desc="Analisa umur piutang pelanggan berdasarkan kategori hari keterlambatan jatuh tempo.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">AUP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Analisa umur piutang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 8. Lebih 1 Faktur -->
                @can('mkt.lebihsatufaktur')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-lebihsatufaktur"
                        data-title="Laporan Lebih dari 1 Faktur"
                        data-desc="Daftar pelanggan yang memiliki tagihan lebih dari satu faktur belum lunas.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Lebih 1 Faktur</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Tagihan ganda pelanggan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 9. DPP -->
                @can('mkt.dpp')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-dpp"
                        data-title="Laporan DPP"
                        data-desc="Daftar penerimaan piutang (DPP) hasil penagihan salesman per tanggal.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="receipt" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">DPP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Daftar penerimaan piutang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 10. DPPP -->
                @can('mkt.dppp')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-dppp"
                        data-title="Laporan DPPP"
                        data-desc="Daftar penerimaan pembayaran piutang (DPPP) per cabang dan salesman.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="file-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">DPPP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penerimaan pembayaran piutang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 11. Omset Pelanggan -->
                @can('mkt.omsetpelanggan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-omsetpelanggan"
                        data-title="Laporan Omset Pelanggan"
                        data-desc="Laporan perolehan total omset penjualan bruto dan netto per pelanggan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Omset Pelanggan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Omset penjualan pelanggan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 12. Rekap Pelanggan -->
                @can('mkt.rekappelanggan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappelanggan"
                        data-title="Laporan Rekap Pelanggan"
                        data-desc="Rekapitulasi status data pelanggan aktif dan tidak aktif per salesman.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Pelanggan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Status data pelanggan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 13. Rekap Kendaraan -->
                @can('mkt.rekapkendaraan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapkendaraan"
                        data-title="Laporan Rekap Kendaraan"
                        data-desc="Rekapitulasi muatan armada pengiriman dan distribusi barang penjualan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Kendaraan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Distribusi muatan armada</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 14. Rekap Wilayah -->
                @can('mkt.rekapwilayah')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapwilayah"
                        data-title="Laporan Rekap Wilayah"
                        data-desc="Rekapitulasi penjualan dan sebaran pelanggan berdasarkan wilayah rayon cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Wilayah</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Sebaran wilayah rayon</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 15. Analisa Transaksi -->
                @can('mkt.analisatransaksi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-analisatransaksi"
                        data-title="Laporan Analisa Transaksi"
                        data-desc="Analisis frekuensi pembelian dan pola histori transaksi pelanggan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Analisa Transaksi</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pola pembelian outlet</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 16. Tunai Transfer -->
                @can('mkt.tunaitransfer')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-tunaitransfer"
                        data-title="Laporan Tunai Transfer"
                        data-desc="Laporan rekapitulasi penerimaan penjualan melalui rekening transfer bank.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Tunai Transfer</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penerimaan via transfer</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 17. Effective Call -->
                @can('mkt.effectivecall')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-effectivecall"
                        data-title="Laporan Effective Call (EC)"
                        data-desc="Laporan kunjungan sales, efektivitas call, dan persentase order per kunjungan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="phone-call" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Effective Call</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Efektivitas call salesman</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 18. LHP -->
                @can('mkt.lhp')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-lhp"
                        data-title="Laporan Hasil Penagihan (LHP)"
                        data-desc="Laporan rekapitulasi lembar hasil penagihan salesman dan setoran kas ke kasir.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">LHP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Hasil penagihan salesman</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 19. Harga Net -->
                @can('mkt.harganet')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-harganet"
                        data-title="Laporan Harga Net"
                        data-desc="Daftar harga net produk setelah diskon reguler, diskon khusus, dan penyesuaian promo.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Harga Net</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Daftar harga net produk</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 20. Routing Salesman -->
                @can('mkt.routingsalesman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-routingsalesman"
                        data-title="Laporan Routing Salesman"
                        data-desc="Jadwal pemetaan rute kunjungan salesman harian per wilayah dan hari kerja.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="route" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Routing Salesman</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rute kunjungan harian</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 21. Sales Performance -->
                @can('mkt.salesperfomance')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-salesperfomance"
                        data-title="Laporan Sales Performance"
                        data-desc="Laporan pencapaian target penjualan, omset, dan evaluasi performa kinerja salesman.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="award" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Sales Performance</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pencapaian target salesman</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 22. Persentase SFA -->
                @can('mkt.persentasesfa')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-persentasesfa"
                        data-title="Laporan Persentase SFA"
                        data-desc="Laporan utilisasi dan tingkat kepatuhan penggunaan aplikasi Sales Force Automation (SFA).">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Persentase SFA</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Utilisasi sistem SFA</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 23. Persentase Data Pelanggan -->
                @can('mkt.persentasesfa')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-persentasedatapelanggan"
                        data-title="Laporan Data Pelanggan SFA"
                        data-desc="Persentase kelengkapan data koordinat GPS, foto toko, dan profil outlet pelanggan SFA.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="database" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Data Pelanggan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Kelengkapan data profil</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 24. Komisi Salesman -->
                @can('mkt.komisisalesman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-komisisalesman"
                        data-title="Laporan Komisi Salesman"
                        data-desc="Perhitungan komisi penjualan salesman, reward poin, dan rekapitulasi pencapaian target.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="badge-dollar-sign" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Komisi Salesman</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Perhitungan komisi & reward</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="flex-1 p-4 sm:p-6 flex flex-col justify-start min-w-0">
                
                <!-- HEADER STRIP ACTIVE TAB INFO (BERSIH, TANPA TITIK BIRU & TANPA BADGE FORMAT) -->
                <div class="pb-3 mb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h4 id="activeTabTitle" class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                        Laporan Penjualan
                    </h4>
                    <p id="activeTabDesc" class="text-[11px] text-slate-400 mt-0.5">
                        Laporan rincian transaksi penjualan barang, faktur standar, PO, dan format satu baris.
                    </p>
                </div>

                <!-- DYNAMIC TAB PANES -->
                <div class="laporan-panes-wrapper">

                    @can('mkt.penjualan')
                        <div id="pane-penjualan" class="laporan-pane">
                            @include('v2.marketing.laporan.penjualan')
                        </div>
                    @endcan

                    @can('mkt.rekappenjualan')
                        <div id="pane-rekappenjualan" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.rekappenjualan')
                        </div>
                    @endcan

                    @can('mkt.kasbesar')
                        <div id="pane-kasbesar" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.kasbesar')
                        </div>
                    @endcan

                    @can('mkt.retur')
                        <div id="pane-retur" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.retur')
                        </div>
                    @endcan

                    @can('mkt.tunaikredit')
                        <div id="pane-tunaikredit" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.tunaikredit')
                        </div>
                    @endcan

                    @can('mkt.kartupiutang')
                        <div id="pane-kartupiutang" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.kartupiutang')
                        </div>
                    @endcan

                    @can('mkt.aup')
                        <div id="pane-aup" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.aup')
                        </div>
                    @endcan

                    @can('mkt.lebihsatufaktur')
                        <div id="pane-lebihsatufaktur" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.lebihsatufaktur')
                        </div>
                    @endcan

                    @can('mkt.dpp')
                        <div id="pane-dpp" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.dpp')
                        </div>
                    @endcan

                    @can('mkt.dppp')
                        <div id="pane-dppp" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.dppp')
                        </div>
                    @endcan

                    @can('mkt.omsetpelanggan')
                        <div id="pane-omsetpelanggan" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.omsetpelanggan')
                        </div>
                    @endcan

                    @can('mkt.rekappelanggan')
                        <div id="pane-rekappelanggan" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.rekappelanggan')
                        </div>
                    @endcan

                    @can('mkt.rekapkendaraan')
                        <div id="pane-rekapkendaraan" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.rekapkendaraan')
                        </div>
                    @endcan

                    @can('mkt.rekapwilayah')
                        <div id="pane-rekapwilayah" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.rekapwilayah')
                        </div>
                    @endcan

                    @can('mkt.analisatransaksi')
                        <div id="pane-analisatransaksi" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.analisatransaksi')
                        </div>
                    @endcan

                    @can('mkt.tunaitransfer')
                        <div id="pane-tunaitransfer" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.tunaitransfer')
                        </div>
                    @endcan

                    @can('mkt.effectivecall')
                        <div id="pane-effectivecall" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.effectivecall')
                        </div>
                    @endcan

                    @can('mkt.lhp')
                        <div id="pane-lhp" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.lhp')
                        </div>
                    @endcan

                    @can('mkt.harganet')
                        <div id="pane-harganet" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.harganet')
                        </div>
                    @endcan

                    @can('mkt.routingsalesman')
                        <div id="pane-routingsalesman" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.routingsalesman')
                        </div>
                    @endcan

                    @can('mkt.salesperfomance')
                        <div id="pane-salesperfomance" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.salesperfomance')
                        </div>
                    @endcan

                    @can('mkt.persentasesfa')
                        <div id="pane-persentasesfa" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.persentasesfa')
                        </div>
                    @endcan

                    @can('mkt.persentasesfa')
                        <div id="pane-persentasedatapelanggan" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.persentasedatapelanggan')
                        </div>
                    @endcan

                    @can('mkt.komisisalesman')
                        <div id="pane-komisisalesman" class="laporan-pane hidden">
                            @include('v2.marketing.laporan.komisisalesman')
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

    // Initialize Flatpickr Dates for all report forms
    $(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d"
    });

    // Initialize Select2 across all marketing forms with standard V2 configuration
    function initLaporanSelect2() {
        const select2Configs = [
            { selector: '.select2Kodecabangpenjualan', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanpenjualan', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelangganpenjualan', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangrekappenjualan', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanrekappenjualan', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Tahunrekappenjualan', placeholder: 'Pilih Tahun', allowClear: false },

            { selector: '.select2Kodecabangkasbesar', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmankasbesar', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelanggankasbesar', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangretur', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanretur', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelangganretur', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangtunaikredit', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmantunaikredit', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangkartupiutang', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmankartupiutang', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelanggankartupiutang', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangaup', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanaup', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelangganaup', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabanglebihsatufaktur', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanlebihsatufaktur', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangdpp', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmandpp', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelanggandpp', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangdppp', placeholder: 'Pilih Cabang', allowClear: true },

            { selector: '.select2Kodecabangomsetpelanggan', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanomsetpelanggan', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangrekappelanggan', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanrekappelanggan', placeholder: 'Semua Salesman', allowClear: true },
            { selector: '.select2Kodepelangganrekappelanggan', placeholder: 'Semua Pelanggan', allowClear: true },

            { selector: '.select2Kodecabangrekapkendaraan', placeholder: 'Pilih Cabang', allowClear: true },
            { selector: '.select2Kodekendaraan', placeholder: 'Semua Kendaraan', allowClear: true },

            { selector: '.select2Kodecabangrekapwilayah', placeholder: 'Pilih Cabang', allowClear: true },

            { selector: '.select2Kodecabanganalisatransaksi', placeholder: 'Pilih Cabang', allowClear: true },
            { selector: '.select2Kodesalesmananalisatransaksi', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangtunaitransfer', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalemanntunaitransfer', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangeffectivecall', placeholder: 'Pilih Cabang', allowClear: true },

            { selector: '.select2Kodecabanglhp', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalemanlhp', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangroutingsalesman', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmanroutingsalesman', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangsalseperfomance', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodesalesmansalesperfomance', placeholder: 'Semua Salesman', allowClear: true },

            { selector: '.select2Kodecabangsfa', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2KodecabangDatapelanggan', placeholder: 'Semua Cabang', allowClear: true },
            { selector: '.select2Kodecabangkomisisalesman', placeholder: 'Pilih Cabang', allowClear: true }
        ];

        select2Configs.forEach(function(cfg) {
            if ($(cfg.selector).length) {
                $(cfg.selector).select2({
                    placeholder: cfg.placeholder,
                    allowClear: cfg.allowClear,
                    width: '100%'
                });
            }
        });
    }

    initLaporanSelect2();

    // Helper for Cabang -> Salesman -> Pelanggan AJAX Chaining
    function bindChainedDropdown(formId, cabangSelector, salesmanSelector, pelangganSelector) {
        const $form = $(formId);
        if (!$form.length) return;

        function loadSalesman() {
            if (!salesmanSelector) return;
            const $cabang = $form.find(cabangSelector);
            const kode_cabang = $cabang.length ? $cabang.val() : '';
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(html) {
                    $form.find(salesmanSelector).html(html);
                    if (pelangganSelector) {
                        loadPelanggan();
                    }
                }
            });
        }

        function loadPelanggan() {
            if (!pelangganSelector) return;
            const $cabang = $form.find(cabangSelector);
            const $salesman = $form.find(salesmanSelector);
            const kode_cabang = $cabang.length ? $cabang.val() : '';
            const kode_salesman = $salesman.length ? $salesman.val() : '';
            $.ajax({
                type: 'POST',
                url: '/pelanggan/getpelangganbysalesman',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_salesman: kode_salesman,
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(html) {
                    $form.find(pelangganSelector).html(html);
                }
            });
        }

        if (cabangSelector) {
            $form.find(cabangSelector).on('change', function() {
                loadSalesman();
            });
        }

        if (salesmanSelector) {
            $form.find(salesmanSelector).on('change', function() {
                loadPelanggan();
            });
        }

        // Initial trigger
        loadSalesman();
    }

    // Helper for Cabang -> Kendaraan AJAX Chaining
    function bindCabangKendaraan(formId, cabangSelector, kendaraanSelector) {
        const $form = $(formId);
        if (!$form.length) return;

        function loadKendaraan() {
            const kode_cabang = $form.find(cabangSelector).val();
            $.ajax({
                type: 'POST',
                url: '/kendaraan/getkendaraanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(html) {
                    $form.find(kendaraanSelector).html(html);
                }
            });
        }

        $form.find(cabangSelector).on('change', function() {
            loadKendaraan();
        });
        loadKendaraan();
    }

    // Bind AJAX chaining to forms
    bindChainedDropdown('#formPenjualan', '#kode_cabang_penjualan', '#kode_salesman_penjualan', '#kode_pelanggan_penjualan');
    bindChainedDropdown('#formKasbesar', '#kode_cabang_kasbesar', '#kode_salesman_kasbesar', '#kode_pelanggan_kasbesar');
    bindChainedDropdown('#formRetur', '#kode_cabang_retur', '#kode_salesman_retur', '#kode_pelanggan_retur');
    bindChainedDropdown('#formTunaikredit', '#kode_cabang_tunaikredit', '#kode_salesman_tunaikredit', null);
    bindChainedDropdown('#formKartupiutang', '#kode_cabang_kartupiutang', '#kode_salesman_kartupiutang', '#kode_pelanggan_kartupiutang');
    bindChainedDropdown('#formAup', '#kode_cabang_aup', '#kode_salesman_aup', '#kode_pelanggan_aup');
    bindChainedDropdown('#formLebihsatufaktur', '#kode_cabang_lebihsatufaktur', '#kode_salesman_lebihsatufaktur', null);
    bindChainedDropdown('#formDpp', '#kode_cabang_dpp', '#kode_salesman_dpp', '#kode_pelanggan_dpp');
    bindChainedDropdown('#formomsetpelanggan', '#kode_cabang_omsetpelanggan', '#kode_salesman_omsetpelanggan', null);
    bindChainedDropdown('#formrekappelanggan', '#kode_cabang_rekappelanggan', '#kode_salesman_rekappelanggan', '#kode_pelanggan_rekappelanggan');
    bindChainedDropdown('#formanalisatransaksi', '#kode_cabang_analisatransaksi', '#kode_salesman_analisatransaksi', null);
    bindChainedDropdown('#formtunaitransfer', '#kode_cabang_tunaitransfer', '#kode_salesman_tunaitransfer', null);
    bindChainedDropdown('#formlhp', '#kode_cabang_lhp', '#kode_salesman_lhp', null);
    bindChainedDropdown('#formRoutingsalesman', '#kode_cabang_routingsalesman', '#kode_salesman_routingsalesman', null);
    bindChainedDropdown('#formSalesperfomance', '#kode_cabang_salesperfomance', '#kode_salesman_salesperfomance', null);
    bindChainedDropdown('#formRekappenjualan', '#kode_cabang_rekappenjualan', '#kode_salesman_rekappenjualan', null);
    bindCabangKendaraan('#formrekapkendaraan', '#kode_cabang_rekapkendaraan', '#kode_kendaraan');

    // Rekap Penjualan Dynamic Field Visibility Handler
    function handleRekappenjualanFields() {
        const $form = $('#formRekappenjualan');
        if (!$form.length) return;
        const jenis_laporan = $form.find('#jenis_laporan_rekappenjualan').val();

        if (jenis_laporan == "5") {
            // Collect AUP: Single date
            $form.find("#tanggalaup").show();
            $form.find("#tanggalpenjualan").hide();
            $form.find("#tahunmultitahun").hide();
            $form.find("#divKodecabangRekappenjualan").show();
            $form.find("#divKodesalesmanRekappenjualan").show();
        } else if (["6", "9", "12", "15"].includes(jenis_laporan)) {
            // Multi Tahun per Salesman
            $form.find("#tanggalaup").hide();
            $form.find("#tanggalpenjualan").hide();
            $form.find("#tahunmultitahun").show();
            $form.find("#divKodecabangRekappenjualan").show();
            $form.find("#divKodesalesmanRekappenjualan").show();
        } else if (["7", "10", "13", "16"].includes(jenis_laporan)) {
            // Multi Tahun per Cabang
            $form.find("#tanggalaup").hide();
            $form.find("#tanggalpenjualan").hide();
            $form.find("#tahunmultitahun").show();
            $form.find("#divKodecabangRekappenjualan").hide();
            $form.find("#divKodesalesmanRekappenjualan").hide();
        } else if (["8", "11", "14", "17"].includes(jenis_laporan)) {
            // Multi Tahun per Regional
            $form.find("#tanggalaup").hide();
            $form.find("#tanggalpenjualan").hide();
            $form.find("#tahunmultitahun").show();
            $form.find("#divKodecabangRekappenjualan").hide();
            $form.find("#divKodesalesmanRekappenjualan").hide();
        } else {
            // Default: Date range
            $form.find("#tanggalaup").hide();
            $form.find("#tanggalpenjualan").show();
            $form.find("#tahunmultitahun").hide();
            $form.find("#divKodecabangRekappenjualan").show();
            $form.find("#divKodesalesmanRekappenjualan").show();
        }
    }

    handleRekappenjualanFields();
    $('#formRekappenjualan').find('#jenis_laporan_rekappenjualan').on('change', function() {
        handleRekappenjualanFields();
    });

    // VERTICAL TAB SWITCHING LOGIC
    $('.laporan-tab-btn').on('click', function(e) {
        e.preventDefault();
        const $this = $(this);
        const targetPane = $this.data('target');
        const title = $this.data('title');
        const desc = $this.data('desc');

        // Reset all buttons to inactive
        $('.laporan-tab-btn').each(function() {
            const $btn = $(this);
            $btn.removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $btn.find('.tab-icon').removeClass('bg-white/20 text-white')
                .addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
            $btn.find('.tab-title').removeClass('text-white')
                .addClass('group-hover:text-[#284c9a] dark:group-hover:text-white');
            $btn.find('.tab-desc').removeClass('text-white/75')
                .addClass('text-slate-400 dark:text-slate-500');
            $btn.find('.tab-chevron').removeClass('text-white/80')
                .addClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');
        });

        // Set clicked button to active
        $this.addClass('active bg-[#284c9a] text-white shadow-xs')
            .removeClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
        $this.find('.tab-icon').addClass('bg-white/20 text-white')
            .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
        $this.find('.tab-title').addClass('text-white')
            .removeClass('group-hover:text-[#284c9a] dark:group-hover:text-white');
        $this.find('.tab-desc').addClass('text-white/75')
            .removeClass('text-slate-400 dark:text-slate-500');
        $this.find('.tab-chevron').addClass('text-white/80')
            .removeClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');

        // Update Header Titles
        $('#activeTabTitle').text(title);
        $('#activeTabDesc').text(desc);

        // Switch Panes
        $('.laporan-pane').addClass('hidden');
        $(targetPane).removeClass('hidden');

        // Refresh icons and select2 in active pane
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // STANDAR VALIDASI INTERAKTIF V2 (ANTI SWAL ALERT) - SESUAI STANDAR LAPORAN PRODUKSI
    function setFieldError($field, message) {
        clearFieldError($field);
        const $group = $field.closest('.field-group');
        $group.addClass('has-error');
        if ($field.hasClass('select2-hidden-accessible') || $field.next().hasClass('select2')) {
            $field.addClass('is-invalid');
            const $s2Container = $field.next('.select2');
            $s2Container.addClass('is-invalid');
            $s2Container.find('.select2-selection').addClass('is-invalid');
            $group.find('.select2-selection').addClass('is-invalid');
        } else {
            $field.addClass('is-invalid');
        }
        $group.find('.input-prefix-icon').addClass('text-rose-500');
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $group.removeClass('has-error');
        if ($field.hasClass('select2-hidden-accessible') || $field.next().hasClass('select2')) {
            $field.removeClass('is-invalid');
            const $s2Container = $field.next('.select2');
            $s2Container.removeClass('is-invalid');
            $s2Container.find('.select2-selection').removeClass('is-invalid');
            $group.find('.select2-selection').removeClass('is-invalid');
        } else {
            $field.removeClass('is-invalid');
        }
        $group.find('.input-prefix-icon').removeClass('text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    // Live Cleanup on any input/select change
    $('.laporan-panes-wrapper form').each(function() {
        const $form = $(this);
        $form.find('input, select').on('input change select2:select select2:clear', function() {
            clearFieldError($(this));
        });
    });

    // Helper Submit Handler Standar V2 dengan Proteksi Button Name ($_POST['exportButton'] / $_POST['submitButton'])
    function handleReportSubmit($form, validateFn) {
        let hasError = false;
        let $firstErrorField = null;

        function markError($field, msg) {
            setFieldError($field, msg);
            hasError = true;
            if (!$firstErrorField) {
                $firstErrorField = $field;
            }
        }

        validateFn(markError);

        if (hasError) {
            if ($firstErrorField) {
                if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                    $firstErrorField.select2('open');
                } else {
                    $firstErrorField.focus();
                }
            }
            return false;
        }

        // Anti double-submit with temporary spinner (target="_blank" friendly)
        const $activeBtn = $(document.activeElement).is('button[type="submit"]') 
            ? $(document.activeElement) 
            : $form.find('button[type="submit"]:hover, button[type="submit"]:focus').first();
        const $btn = $activeBtn.length ? $activeBtn : $form.find('button[type="submit"]').first();
        const origHtml = $btn.html();
        const btnName = $btn.attr('name');

        // Pastikan parameter tombol tetap terkirim di POST walau tombol nanti di-disable
        if (btnName && !$form.find(`input[type="hidden"][name="${btnName}"]`).length) {
            $form.append(`<input type="hidden" name="${btnName}" value="1" class="temp-submit-param">`);
        }

        setTimeout(function() {
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);
        }, 10);

        setTimeout(function() {
            $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(origHtml);
            $form.find('.temp-submit-param').remove();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 2500);

        return true;
    }

    // 1. Penjualan
    $('#formPenjualan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_penjualan');
            const $sampai = $form.find('#sampai_penjualan');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 2. Rekap Penjualan
    $('#formRekappenjualan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $jenis = $form.find('#jenis_laporan_rekappenjualan');
            if (!$jenis.val()) {
                markError($jenis, 'Pilih jenis laporan terlebih dahulu');
                return;
            }
            const jenis = $jenis.val();
            if (jenis === '5') {
                const $tgl = $form.find('#tanggal_rekappenjualan');
                if (!$tgl.val()) markError($tgl, 'Tanggal harus diisi');
            } else if (['6','7','8','9','10','11','12','13','14','15','16','17'].includes(jenis)) {
                const $thn = $form.find('#tahun_rekappenjualan');
                if (!$thn.val() || !$thn.val().length) markError($thn, 'Pilih minimal satu tahun');
            } else {
                const $dari = $form.find('#dari_rekappenjualan');
                const $sampai = $form.find('#sampai_rekappenjualan');
                if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
                if (!$sampai.val()) {
                    markError($sampai, 'Periode sampai tanggal harus diisi');
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
                }
            }
        });
    });

    // 3. Kas Besar
    $('#formKasbesar').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_kasbesar');
            const $sampai = $form.find('#sampai_kasbesar');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 4. Retur
    $('#formRetur').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_retur');
            const $sampai = $form.find('#sampai_retur');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 5. Tunai Kredit
    $('#formTunaikredit').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_tunaikredit');
            const $sampai = $form.find('#sampai_tunaikredit');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 6. Kartu Piutang
    $('#formKartupiutang').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_kartupiutang');
            const $sampai = $form.find('#sampai_kartupiutang');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 7. AUP
    $('#formAup').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $tgl = $form.find('#tanggal_aup');
            if (!$tgl.val()) markError($tgl, 'Tanggal harus diisi');
        });
    });

    // 8. Lebih Satu Faktur
    $('#formLebihsatufaktur').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $tgl = $form.find('#tanggal_lebihsatufaktur');
            if (!$tgl.val()) markError($tgl, 'Tanggal harus diisi');
        });
    });

    // 9. DPP
    $('#formDpp').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_dpp');
            const $sampai = $form.find('#sampai_dpp');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 10. DPPP
    $('#formDppp').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_dppp');
            const $bln = $form.find('#bulan_dppp');
            const $thn = $form.find('#tahun_dppp');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$bln.val()) markError($bln, 'Pilih bulan terlebih dahulu');
            if (!$thn.val()) markError($thn, 'Pilih tahun terlebih dahulu');
        });
    });

    // 11. Omset Pelanggan
    $('#formomsetpelanggan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_omsetpelanggan');
            const $sampai = $form.find('#sampai_omsetpelanggan');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 12. Rekap Pelanggan
    $('#formrekappelanggan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_rekappelanggan');
            const $sampai = $form.find('#sampai_rekappelanggan');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 13. Rekap Kendaraan
    $('#formrekapkendaraan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_rekapkendaraan');
            const $dari = $form.find('#dari_rekapkendaraan');
            const $sampai = $form.find('#sampai_rekapkendaraan');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 14. Rekap Wilayah
    $('#formRekapwilayah').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_rekapwilayah');
            const $thn = $form.find('#tahun_rekapwilayah');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$thn.val()) markError($thn, 'Pilih tahun terlebih dahulu');
        });
    });

    // 15. Analisa Transaksi
    $('#formanalisatransaksi').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_analisatransaksi');
            const $bln = $form.find('#bulan_analisatransaksi');
            const $thn = $form.find('#tahun_analisatransaksi');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$bln.val()) markError($bln, 'Pilih bulan terlebih dahulu');
            if (!$thn.val()) markError($thn, 'Pilih tahun terlebih dahulu');
        });
    });

    // 16. Tunai Transfer
    $('#formtunaitransfer').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_tunaitransfer');
            const $sampai = $form.find('#sampai_tunaitransfer');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 17. Effective Call
    $('#formeffectivecall').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_effectivecall');
            const $fmt = $form.find('#formatlaporan_effectivecall');
            const $dari = $form.find('#dari_effectivecall');
            const $sampai = $form.find('#sampai_effectivecall');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$fmt.val()) markError($fmt, 'Pilih format laporan terlebih dahulu');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 18. LHP
    $('#formlhp').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $tgl = $form.find('#tanggal_lhp');
            if (!$tgl.val()) markError($tgl, 'Tanggal harus diisi');
        });
    });

    // 19. Harga Net
    $('#formharganet').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $bln = $form.find('#bulan_harganet');
            const $thn = $form.find('#tahun_harganet');
            if (!$bln.val()) markError($bln, 'Pilih bulan terlebih dahulu');
            if (!$thn.val()) markError($thn, 'Pilih tahun terlebih dahulu');
        });
    });

    // 20. Routing Salesman
    $('#formRoutingsalesman').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_routingsalesman');
            const $sampai = $form.find('#sampai_routingsalesman');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 21. Sales Performance
    $('#formSalesperfomance').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_salesperfomance');
            const $sampai = $form.find('#sampai_salesperfomance');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 22. Persentase SFA
    $('#formSFA').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_sfa');
            const $sampai = $form.find('#sampai_sfa');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 23. Persentase Data Pelanggan
    $('#formDatapelanggan').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $dari = $form.find('#dari_datapelanggan');
            const $sampai = $form.find('#sampai_datapelanggan');
            if (!$dari.val()) markError($dari, 'Periode dari tanggal harus diisi');
            if (!$sampai.val()) {
                markError($sampai, 'Periode sampai tanggal harus diisi');
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
            }
        });
    });

    // 24. Komisi Salesman
    $('#formKomisisalesman').on('submit', function() {
        const $form = $(this);
        return handleReportSubmit($form, function(markError) {
            const $cbg = $form.find('#kode_cabang_komisisalesman');
            const $bln = $form.find('#bulan_komisisalesman');
            const $thn = $form.find('#tahun_komisisalesman');
            if ($cbg.length && !$cbg.val()) markError($cbg, 'Pilih cabang terlebih dahulu');
            if (!$bln.val()) markError($bln, 'Pilih bulan terlebih dahulu');
            if (!$thn.val()) markError($thn, 'Pilih tahun terlebih dahulu');
        });
    });
});
</script>
@endpush
