<div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
    <div class="overflow-x-auto max-h-[520px] scrollbar-thin">
        <table class="w-full text-left border-collapse text-xs font-sans">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th rowspan="2" class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10 align-middle">Cabang</th>
                    <th colspan="7" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Umur Piutang (Hari)</th>
                </tr>
                <tr class="bg-[#1e3a8a] text-blue-100">
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10">0 - 15</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10">16 - 31</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10">32 - 45</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10">46 - 60</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10 text-amber-200">61 - 90</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10 text-orange-200">91 - 180</th>
                    <th class="py-1.5 px-2.5 text-right font-bold text-[11px] border-b border-blue-900/80 sticky top-[33px] bg-[#1e3a8a] z-10 text-rose-300">> 180</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                @php
                    $total_umur_0_15 = 0;
                    $total_umur_16_31 = 0;
                    $total_umur_32_45 = 0;
                    $total_umur_46_60 = 0;
                    $total_umur_61_90 = 0;
                    $total_umur_91_180 = 0;
                    $total_umur_lebih_180 = 0;
                @endphp
                @foreach ($rekapaup as $d)
                    @php
                        $total_umur_0_15 += $d['umur_0_15'];
                        $total_umur_16_31 += $d['umur_16_31'];
                        $total_umur_32_45 += $d['umur_32_45'];
                        $total_umur_46_60 += $d['umur_46_60'];
                        $total_umur_61_90 += $d['umur_61_90'];
                        $total_umur_91_180 += $d['umur_91_180'];
                        $total_umur_lebih_180 += $d['umur_lebih_180'];
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-2 px-3.5 font-bold text-slate-800 dark:text-slate-100 uppercase tracking-tight">{{ textUpperCase($d['nama_cabang']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d['umur_0_15']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d['umur_16_31']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d['umur_32_45']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($d['umur_46_60']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-amber-600 dark:text-amber-400 font-semibold">{{ formatAngka($d['umur_61_90']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-orange-600 dark:text-orange-400 font-semibold">{{ formatAngka($d['umur_91_180']) }}</td>
                        <td class="py-2 px-2.5 text-right font-mono-num text-rose-600 dark:text-rose-400 font-bold bg-rose-50/50 dark:bg-rose-950/20">{{ formatAngka($d['umur_lebih_180']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-900 text-white font-bold sticky bottom-0 z-10 border-t-2 border-blue-600">
                <tr>
                    <td class="py-2.5 px-3.5 uppercase tracking-wider text-[11px]">Total</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px]">{{ formatAngka($total_umur_0_15) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px]">{{ formatAngka($total_umur_16_31) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px]">{{ formatAngka($total_umur_32_45) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px]">{{ formatAngka($total_umur_46_60) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px] text-amber-300">{{ formatAngka($total_umur_61_90) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px] text-orange-300">{{ formatAngka($total_umur_91_180) }}</td>
                    <td class="py-2.5 px-2.5 text-right font-mono-num text-[11px] text-rose-300 bg-blue-950/80">{{ formatAngka($total_umur_lebih_180) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
