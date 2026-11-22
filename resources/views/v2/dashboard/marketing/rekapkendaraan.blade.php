<div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
    <div class="overflow-x-auto max-h-[520px] scrollbar-thin">
        <table class="w-full text-left border-collapse text-xs font-sans">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">No. Polisi</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Jenis Kendaraan</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Keberangkatan</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Penjualan</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-blue-900/80 sticky top-0 bg-[#1e3a8a] z-10">Rata-Rata</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                @php
                    $total_berangkat = 0;
                    $total_penjualan = 0;
                @endphp
                @foreach ($rekapkendaraan as $d)
                    @php
                        $total_berangkat += $d->jml_berangkat;
                        $total_penjualan += $d->jml_penjualan;
                        $ratarata = $d->jml_berangkat > 0 ? $d->jml_penjualan / $d->jml_berangkat : 0;
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-2 px-3.5 font-mono-num font-bold text-slate-900 dark:text-white uppercase">{{ $d->no_polisi }}</td>
                        <td class="py-2 px-3.5 text-slate-700 dark:text-slate-300">{{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                        <td class="py-2 px-3.5 text-center font-mono-num font-semibold text-slate-800 dark:text-slate-200">{{ formatAngka($d->jml_berangkat) }}</td>
                        <td class="py-2 px-3.5 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngkaDesimal($d->jml_penjualan) }}</td>
                        <td class="py-2 px-3.5 text-right font-mono-num font-bold text-brand-700 dark:text-brand-300 bg-blue-50/40 dark:bg-blue-950/20">{{ formatAngkaDesimal($ratarata) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-900 text-white font-bold sticky bottom-0 z-10 border-t-2 border-blue-600">
                <tr>
                    <td colspan="2" class="py-2.5 px-3.5 uppercase tracking-wider text-[11px]">Total</td>
                    <td class="py-2.5 px-3.5 text-center font-mono-num text-[11px]">{{ formatAngka($total_berangkat) }}</td>
                    <td class="py-2.5 px-3.5 text-right font-mono-num text-[11px]">{{ formatAngkaDesimal($total_penjualan) }}</td>
                    <td class="py-2.5 px-3.5 text-right font-mono-num text-[11px] text-emerald-300 bg-blue-950/80">
                        {{ formatAngkaDesimal($total_berangkat > 0 ? $total_penjualan / $total_berangkat : 0) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
