@extends('v2.layouts.app')
@section('titlepage', 'Laporan Keuangan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan Keuangan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Keuangan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Laporan kas kecil, ledger, mutasi keuangan, kas besar, dan pinjaman karyawan.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">

        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[500px]">

            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5 overflow-y-auto max-h-[660px]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                @can('keu.kaskecil')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kaskecil"
                        data-title="Laporan Kas Kecil"
                        data-desc="Laporan rincian atau rekap transaksi kas kecil cabang per periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kas Kecil</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Detail & rekap kas kecil</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.ledger')
                    @php $ledgerTitle = auth()->user()->kode_cabang == 'PST' ? 'Ledger' : 'Mutasi Bank'; @endphp
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-ledger"
                        data-title="Laporan Ledger / Mutasi Bank"
                        data-desc="Laporan mutasi rekening bank dan pembukuan ledger per akun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">{{ $ledgerTitle }}</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Mutasi rekening bank</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.mutasikeuangan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-mutasikeuangan"
                        data-title="Laporan Mutasi Keuangan"
                        data-desc="Laporan mutasi keluar masuk keuangan per bank dan kategori.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Mutasi Keuangan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Detail & rekap kategori</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapledger"
                        data-title="Rekap Ledger"
                        data-desc="Rekapitulasi saldo dan mutasi seluruh rekening ledger.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap Ledger</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Ringkasan seluruh bank</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endhasanyrole

                @can('keu.saldokasbesar')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-saldokasbesar"
                        data-title="Laporan Saldo Kas Besar"
                        data-desc="Laporan posisi saldo kas besar cabang per bulan dan tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Saldo Kas Besar</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Posisi kas besar bulanan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.lpu')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-lpu"
                        data-title="Laporan LPU"
                        data-desc="Laporan penerimaan uang (LHP setoran penjualan dan setoran pusat).">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">LPU</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Setoran penjualan & pusat</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.penjualan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-penjualan"
                        data-title="Laporan Penjualan (Keuangan)"
                        data-desc="Laporan realisasi penjualan dan penerimaan cabang per periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Penjualan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Realisasi penjualan cabang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.uanglogam')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-uanglogam"
                        data-title="Laporan Uang Logam"
                        data-desc="Laporan mutasi uang logam dan penukaran ke uang kertas.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="coins" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Uang Logam</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Mutasi logam ke kertas</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.rekapbg')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapbg"
                        data-title="Rekap Bilyet Giro (BG)"
                        data-desc="Rekapitulasi penerimaan dan pencairan bilyet giro per cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="receipt" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap BG</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekapitulasi giro cabang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.pinjaman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-pinjaman"
                        data-title="Laporan PJP"
                        data-desc="Laporan pengajuan pinjaman jangka panjang karyawan per periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">PJP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pinjaman jangka panjang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.kasbon')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kasbon"
                        data-title="Laporan Kasbon"
                        data-desc="Laporan pengajuan kasbon karyawan per cabang dan departemen.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="coins" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kasbon</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Data pengajuan kasbon</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.piutangkaryawan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-piutangkaryawan"
                        data-title="Laporan Piutang Karyawan"
                        data-desc="Laporan daftar piutang karyawan aktif per periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="user-minus" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Piutang Karyawan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Karyawan aktif</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>

                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-piutangekskaryawan"
                        data-title="Laporan Piutang Eks Karyawan"
                        data-desc="Laporan daftar piutang eks karyawan per periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Piutang Eks Karyawan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Eks karyawan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.kartupinjaman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartupinjaman"
                        data-title="Kartu PJP"
                        data-desc="Kartu monitoring saldo & angsuran PJP karyawan per bulan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="contact" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kartu PJP</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Monitoring saldo PJP</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.kartukasbon')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartukasbon"
                        data-title="Kartu Kasbon"
                        data-desc="Kartu monitoring saldo & potongan kasbon karyawan per bulan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="contact" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kartu Kasbon</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Monitoring saldo kasbon</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('keu.kartupiutangkaryawan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartupiutangkaryawan"
                        data-title="Kartu Piutang Karyawan"
                        data-desc="Kartu monitoring saldo piutang karyawan aktif per bulan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="contact" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kartu Piutang Karyawan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Saldo piutang aktif</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>

                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartupiutangekskaryawan"
                        data-title="Kartu Piutang Eks Karyawan"
                        data-desc="Kartu monitoring saldo piutang eks karyawan per bulan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="contact" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kartu Piutang Eks Karyawan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Saldo eks karyawan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-kartupiutangkeuangan"
                        data-title="Kartu Piutang Khusus Keuangan"
                        data-desc="Kartu piutang dengan status akses khusus keuangan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Kartu Piutang Keuangan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Khusus keuangan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endhasanyrole

                @can('keu.rekapkartupiutang')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapkartupiutang"
                        data-title="Rekap Kartu Pinjaman"
                        data-desc="Rekapitulasi seluruh kartu pinjaman PJP, kasbon, dan piutang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="files" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap Kartu Pinjaman</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Detail & rekap pinjaman</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="flex-1 p-5 sm:p-6 bg-white dark:bg-slate-900 flex flex-col justify-start min-w-0">

                <!-- Active Report Header Info (Clean: No Blue Dot Bullet, No Format Badge) -->
                <div class="flex items-start justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 id="activeReportTitle" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Laporan Keuangan</h4>
                        <p id="activeReportDesc" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pilih kategori laporan di sebelah kiri.</p>
                    </div>
                </div>

                <!-- Form Panes -->
                <div class="laporan-panes-wrapper">
                    @can('keu.kaskecil')
                        <div class="laporan-panel hidden" id="pane-kaskecil">
                            @include('v2.keuangan.laporan.kaskecil')
                        </div>
                    @endcan

                    @can('keu.ledger')
                        <div class="laporan-panel hidden" id="pane-ledger">
                            @include('v2.keuangan.laporan.ledger')
                        </div>
                    @endcan

                    @can('keu.mutasikeuangan')
                        <div class="laporan-panel hidden" id="pane-mutasikeuangan">
                            @include('v2.keuangan.laporan.mutasikeuangan')
                        </div>
                    @endcan

                    @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                        <div class="laporan-panel hidden" id="pane-rekapledger">
                            @include('v2.keuangan.laporan.rekapledger')
                        </div>
                    @endhasanyrole

                    @can('keu.saldokasbesar')
                        <div class="laporan-panel hidden" id="pane-saldokasbesar">
                            @include('v2.keuangan.laporan.saldokasbesar')
                        </div>
                    @endcan

                    @can('keu.lpu')
                        <div class="laporan-panel hidden" id="pane-lpu">
                            @include('v2.keuangan.laporan.lpu')
                        </div>
                    @endcan

                    @can('keu.penjualan')
                        <div class="laporan-panel hidden" id="pane-penjualan">
                            @include('v2.keuangan.laporan.penjualan')
                        </div>
                    @endcan

                    @can('keu.uanglogam')
                        <div class="laporan-panel hidden" id="pane-uanglogam">
                            @include('v2.keuangan.laporan.uanglogam')
                        </div>
                    @endcan

                    @can('keu.rekapbg')
                        <div class="laporan-panel hidden" id="pane-rekapbg">
                            @include('v2.keuangan.laporan.rekapbg')
                        </div>
                    @endcan

                    @can('keu.pinjaman')
                        <div class="laporan-panel hidden" id="pane-pinjaman">
                            @include('v2.keuangan.laporan.pinjaman')
                        </div>
                    @endcan

                    @can('keu.kasbon')
                        <div class="laporan-panel hidden" id="pane-kasbon">
                            @include('v2.keuangan.laporan.kasbon')
                        </div>
                    @endcan

                    @can('keu.piutangkaryawan')
                        <div class="laporan-panel hidden" id="pane-piutangkaryawan">
                            @include('v2.keuangan.laporan.piutangkaryawan')
                        </div>
                        <div class="laporan-panel hidden" id="pane-piutangekskaryawan">
                            @include('v2.keuangan.laporan.piutangekskaryawan')
                        </div>
                    @endcan

                    @can('keu.kartupinjaman')
                        <div class="laporan-panel hidden" id="pane-kartupinjaman">
                            @include('v2.keuangan.laporan.kartupjp')
                        </div>
                    @endcan

                    @can('keu.kartukasbon')
                        <div class="laporan-panel hidden" id="pane-kartukasbon">
                            @include('v2.keuangan.laporan.kartukasbon')
                        </div>
                    @endcan

                    @can('keu.kartupiutangkaryawan')
                        <div class="laporan-panel hidden" id="pane-kartupiutangkaryawan">
                            @include('v2.keuangan.laporan.kartupiutangkaryawan')
                        </div>
                        <div class="laporan-panel hidden" id="pane-kartupiutangekskaryawan">
                            @include('v2.keuangan.laporan.kartupiutangekskaryawan')
                        </div>
                    @endcan

                    @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                        <div class="laporan-panel hidden" id="pane-kartupiutangkeuangan">
                            @include('v2.keuangan.laporan.kartupiutangkeuangan')
                        </div>
                    @endhasanyrole

                    @can('keu.rekapkartupiutang')
                        <div class="laporan-panel hidden" id="pane-rekapkartupiutang">
                            @include('v2.keuangan.laporan.rekapkartupiutang')
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
    // Shared V2 inline validation & print spinner helper for Laporan Keuangan
    window.lapKeuHelper = {
        showError: function($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>${message}</span>
                </p>
            `);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        },
        clearError: function($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        },
        bindLiveCleanup: function($form) {
            const self = this;
            $form.on('input change', 'input, select', function() {
                self.clearError($(this));
            });
            $form.find('select').on('select2:select select2:clear', function() {
                self.clearError($(this));
            });
        },
        triggerPrintSpinner: function($form) {
            const $btnSubmit = $form.find('button[name="submitButton"], button[type="submit"]:not([name="exportButton"])').first();
            const $btnExport = $form.find('button[name="exportButton"]');
            const origSubmit = $btnSubmit.html();
            const origExport = $btnExport.html();

            $btnSubmit.prop('disabled', true).addClass('opacity-75 pointer-events-none');
            $btnExport.prop('disabled', true).addClass('opacity-75 pointer-events-none');

            $btnSubmit.html(`
                <svg class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(function() {
                $btnSubmit.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origSubmit);
                $btnExport.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origExport);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 2500);
        }
    };

    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $firstTab = $('.laporan-tab-btn').first();
        if ($firstTab.length) {
            activateTab($firstTab);
        }

        function activateTab($tab) {
            const target = $tab.data('target');
            const title = $tab.data('title');
            const desc = $tab.data('desc');

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

        $('.laporan-tab-btn').on('click', function() {
            activateTab($(this));
        });
    });
</script>
@endpush
