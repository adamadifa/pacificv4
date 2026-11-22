<div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-left border-collapse font-sans text-xs">
            <thead>
                <tr class="bg-[#284c9a] text-white">
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] border-b border-white/20">Produk</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-white/20">Buffer</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-white/20">Max</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-white/20">Good Stok</th>
                    <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-white/20">Bad Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @foreach ($rekappersediaan as $d)
                    @php
                        $saldo_akhir = ($d->saldo_awal + $d->sisa_mutasi - $d->dpb_ambil + $d->dpb_kembali) / $d->isi_pcs_dus;
                        if ($saldo_akhir <= $d->buffer_stok) {
                            $color = 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-black';
                        } elseif ($saldo_akhir >= $d->max_stok) {
                            $color = 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-400 font-bold';
                        } else {
                            $color = 'text-slate-800 dark:text-slate-100 font-semibold';
                        }

                        $saldo_akhir_bs = ($d->saldo_awal_bs + $d->sisa_mutasi_bs) / $d->isi_pcs_dus;
                        if ($saldo_akhir_bs > 0) {
                            $color_bs = 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-bold';
                        } else {
                            $color_bs = 'text-emerald-600 dark:text-emerald-400 font-semibold';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-2 px-3.5 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $d->nama_produk }}
                        </td>
                        <td class="py-2 px-3.5 text-right font-mono-num text-slate-500 dark:text-slate-400">
                            {{ formatAngka($d->buffer_stok) }}
                        </td>
                        <td class="py-2 px-3.5 text-right font-mono-num text-slate-500 dark:text-slate-400">
                            {{ formatAngka($d->max_stok) }}
                        </td>
                        <td class="py-2 px-3.5 text-right font-mono-num {{ $color }}">
                            {{ formatAngkaDesimal($saldo_akhir) }}
                        </td>
                        <td class="py-2 px-3.5 text-right font-mono-num {{ $color_bs }}">
                            {{ formatAngkaDesimal($saldo_akhir_bs) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
