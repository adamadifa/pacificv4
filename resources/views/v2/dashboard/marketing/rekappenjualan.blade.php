<div class="space-y-4">
    <!-- Modern Tabs Navigation -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <ul class="flex space-x-2 -mb-px nav-tabs" role="tablist">
            <li role="presentation">
                <button type="button" class="nav-link active inline-flex items-center gap-2 py-2.5 px-4 font-bold text-xs border-b-2 border-brand-600 text-brand-600 dark:text-brand-400 bg-transparent transition-colors" data-bs-toggle="tab" data-bs-target="#rekappenjualan" aria-selected="true">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    <span>Rekap Penjualan</span>
                </button>
            </li>
            <li role="presentation">
                <button type="button" class="nav-link inline-flex items-center gap-2 py-2.5 px-4 font-bold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 bg-transparent transition-colors" data-bs-toggle="tab" data-bs-target="#rekapkabesar" aria-selected="false">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    <span>Rekap Kas Besar</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content w-full">
        <!-- 1. TAB: REKAP PENJUALAN -->
        <div class="tab-pane active" id="rekappenjualan" role="tabpanel">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
                <div class="overflow-x-auto max-h-[520px] scrollbar-thin">
                    <table class="w-full text-left border-collapse text-xs font-sans">
                        <thead>
                            <tr class="bg-[#1e3a8a] text-white">
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Cabang</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Bruto</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Retur</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Potongan</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Istimewa</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Penyesuaian</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">DPP</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">PPN</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">NETTO</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                            @php
                                $total_bruto = 0;
                                $total_retur = 0;
                                $total_potongan = 0;
                                $total_potongan_istimewa = 0;
                                $total_penyesuaian = 0;
                                $total_dpp = 0;
                                $total_netto = 0;
                                $total_ppn = 0;
                            @endphp
                            @foreach ($rekappenjualan as $d)
                                @php
                                    $total_bruto += $d->total_bruto;
                                    $total_retur += $d->total_retur;
                                    $total_potongan += $d->total_potongan;
                                    $total_potongan_istimewa += $d->total_potongan_istimewa;
                                    $total_penyesuaian += $d->total_penyesuaian;
                                    $dpp = $d->total_bruto - $d->total_retur - $d->total_potongan - $d->total_potongan_istimewa - $d->total_penyesuaian;
                                    $total_dpp += $dpp;
                                    $total_ppn += $d->total_ppn;
                                    $netto = $dpp + $d->total_ppn;
                                    $total_netto += $netto;
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2 px-3 font-bold text-slate-800 dark:text-slate-100 uppercase tracking-tight">{{ textUpperCase($d->nama_cabang) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d->total_bruto) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-rose-600 dark:text-rose-400">{{ formatAngka($d->total_retur) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-amber-600 dark:text-amber-400">{{ formatAngka($d->total_potongan) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">{{ formatAngka($d->total_potongan_istimewa) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">{{ formatAngka($d->total_penyesuaian) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-200">{{ formatAngka($dpp) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">{{ formatAngka($d->total_ppn) }}</td>
                                    <td class="py-2 px-3 text-right font-mono-num font-bold text-brand-700 dark:text-brand-300 bg-blue-50/40 dark:bg-blue-950/20">{{ formatAngka($netto) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900 text-white font-bold sticky bottom-0 z-10 border-t-2 border-blue-600">
                            <tr>
                                <td class="py-2.5 px-3 uppercase tracking-wider text-[11px]">Total</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px]">{{ formatAngka($total_bruto) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px] text-rose-300">{{ formatAngka($total_retur) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px] text-amber-300">{{ formatAngka($total_potongan) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px]">{{ formatAngka($total_potongan_istimewa) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px]">{{ formatAngka($total_penyesuaian) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px]">{{ formatAngka($total_dpp) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px]">{{ formatAngka($total_ppn) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono-num text-[11px] text-emerald-300 bg-blue-950/80">{{ formatAngka($total_netto) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. TAB: REKAP KAS BESAR -->
        <div class="tab-pane hidden" id="rekapkabesar" role="tabpanel">
            <div class="max-w-xl rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
                <div class="overflow-x-auto max-h-[520px] scrollbar-thin">
                    <table class="w-full text-left border-collapse text-xs font-sans">
                        <thead>
                            <tr class="bg-[#1e3a8a] text-white">
                                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80">Cabang</th>
                                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80">Voucher</th>
                                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80">Cash IN</th>
                                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                            @php
                                $total_voucher = 0;
                                $total_cashin = 0;
                                $grandtotal = 0;
                            @endphp
                            @foreach ($rekapkasbesar as $d)
                                @php
                                    $total = $d->total_voucher + $d->total_cashin;
                                    $total_voucher += $d->total_voucher;
                                    $total_cashin += $d->total_cashin;
                                    $grandtotal += $total;
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2 px-3.5 font-bold text-slate-800 dark:text-slate-100 uppercase tracking-tight">{{ textUpperCase($d->nama_cabang) }}</td>
                                    <td class="py-2 px-3.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d->total_voucher) }}</td>
                                    <td class="py-2 px-3.5 text-right font-mono-num text-emerald-600 dark:text-emerald-400 font-semibold">{{ formatAngka($d->total_cashin) }}</td>
                                    <td class="py-2 px-3.5 text-right font-mono-num font-bold text-brand-700 dark:text-brand-300 bg-blue-50/40 dark:bg-blue-950/20">{{ formatAngka($total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900 text-white font-bold sticky bottom-0 z-10 border-t-2 border-blue-600">
                            <tr>
                                <td class="py-2.5 px-3.5 uppercase tracking-wider text-[11px]">Total</td>
                                <td class="py-2.5 px-3.5 text-right font-mono-num text-[11px]">{{ formatAngka($total_voucher) }}</td>
                                <td class="py-2.5 px-3.5 text-right font-mono-num text-[11px] text-emerald-300">{{ formatAngka($total_cashin) }}</td>
                                <td class="py-2.5 px-3.5 text-right font-mono-num text-[11px] text-brand-300 bg-blue-950/80">{{ formatAngka($grandtotal) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
