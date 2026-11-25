<div class="space-y-3.5">
    <!-- 1. DOKUMEN RINGKASAN TERPADU (EXECUTIVE SUMMARY STRIP V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Rincian Laporan Hasil Penjualan (LHP)</h5>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                {{ DateToIndo($tanggal) }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Salesman</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">
                    {{ textUpperCase($salesman->nama_salesman) }}
                </span>
                <span class="text-[10px] text-slate-400 font-mono-num block">{{ $salesman->kode_salesman }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal LHP</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block font-mono-num">{{ DateToIndo($tanggal) }}</span>
            </div>
        </div>
    </div>

    <!-- 2. TABEL KAS BESAR PENJUALAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Kas Besar Penjualan (Tunai & Tagihan)</h6>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20">
                {{ count($lhp) }} Transaksi
            </span>
        </div>

        <div class="overflow-x-auto max-h-56 overflow-y-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Bukti</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Tunai</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Tagihan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Giro ke Cash</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $total_lhp_tunai = 0;
                        $total_lhp_tagihan = 0;
                        $total_gtct = 0;
                    @endphp
                    @forelse ($lhp as $d)
                        @php
                            $total_lhp_tunai += $d->lhp_tunai;
                            $total_lhp_tagihan += $d->lhp_tagihan;
                            $total_gtct += $d->giro_to_cash_transfer;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 font-mono-num">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="py-1.5 px-3 font-mono-num">{{ $d->no_bukti }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-brand-600 dark:text-brand-400">{{ $d->no_faktur }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngka($d->lhp_tunai) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngka($d->lhp_tagihan) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-amber-600 dark:text-amber-400">{{ formatAngka($d->giro_to_cash_transfer) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-slate-400">Tidak ada transaksi tunai/tagihan</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700 sticky bottom-0">
                    <tr>
                        <td colspan="4" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">Total LHP</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ formatAngka($total_lhp_tunai) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ formatAngka($total_lhp_tagihan) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-amber-600 dark:text-amber-400">{{ formatAngka($total_gtct) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 3. TABEL LIST GIRO -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="file-check-2" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Giro Diterima</h6>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20">
                {{ count($giro) }} Giro
            </span>
        </div>

        <div class="overflow-x-auto max-h-48 overflow-y-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Giro</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $total_lhp_giro = 0;
                    @endphp
                    @forelse ($giro as $d)
                        @php
                            $total_lhp_giro += $d->lhp_giro;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition {{ $d->status == 1 ? 'bg-emerald-50/50 dark:bg-emerald-950/20' : '' }}">
                            <td class="py-1.5 px-3 font-mono-num">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->no_giro }}</td>
                            <td class="py-1.5 px-3 font-mono-num">{{ $d->no_faktur }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td class="py-1.5 px-3 font-mono-num">{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngka($d->lhp_giro) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-400">Tidak ada giro pada periode ini</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700 sticky bottom-0">
                    <tr>
                        <td colspan="5" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">Total Giro</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ formatAngka($total_lhp_giro) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 4. TABEL LIST TRANSFER -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Transfer Diterima</h6>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20">
                {{ count($transfer) }} Transfer
            </span>
        </div>

        <div class="overflow-x-auto max-h-48 overflow-y-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Kode Transfer</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Giro ke Transfer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $total_lhp_transfer = 0;
                        $total_giro_to_transfer = 0;
                    @endphp
                    @forelse ($transfer as $d)
                        @php
                            $total_lhp_transfer += $d->lhp_transfer;
                            $total_giro_to_transfer += $d->giro_to_transfer;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition {{ $d->status == 1 ? 'bg-emerald-50/50 dark:bg-emerald-950/20' : '' }}">
                            <td class="py-1.5 px-3 font-mono-num">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_transfer }}</td>
                            <td class="py-1.5 px-3 font-mono-num">{{ $d->no_faktur }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngka($d->lhp_transfer) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-amber-600 dark:text-amber-400">{{ formatAngka($d->giro_to_transfer) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-400">Tidak ada transfer pada periode ini</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700 sticky bottom-0">
                    <tr>
                        <td colspan="4" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">Total Transfer</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ formatAngka($total_lhp_transfer) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-amber-600 dark:text-amber-400">{{ formatAngka($total_giro_to_transfer) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
