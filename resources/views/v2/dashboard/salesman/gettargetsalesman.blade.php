@if ($target->isEmpty())
    <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40">
        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2">
            <i data-lucide="target" class="w-5 h-5"></i>
        </div>
        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada data target penjualan</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Silakan pilih bulan atau tahun lain untuk melihat pencapaian target.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach ($target as $d)
            @php
                $realisasi = !empty($d->isi_pcs_dus) ? $d->realisasi / $d->isi_pcs_dus : 0;
                $persentase = !empty($d->jumlah) ? (round($realisasi) / $d->jumlah) * 100 : 0;
                $persen = round($persentase);
                $barWidth = min(max($persen, 0), 100);
                if ($persentase < 50) {
                    $barColor = 'bg-rose-500';
                    $badgeColor = 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60';
                } elseif ($persentase < 90) {
                    $barColor = 'bg-[#284c9a]';
                    $badgeColor = 'bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border-blue-200/80 dark:border-blue-800/60';
                } else {
                    $barColor = 'bg-emerald-500';
                    $badgeColor = 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border-emerald-200/80 dark:border-emerald-800/60';
                }
            @endphp
            <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">{{ $d->nama_produk }}</span>
                    <span class="px-2 py-0.5 rounded-md border text-[10.5px] font-bold font-mono-num shrink-0 {{ $badgeColor }}">
                        {{ $persen }}%
                    </span>
                </div>
                <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                    <div class="h-full rounded-full {{ $barColor }} transition-all duration-300" style="width: {{ $barWidth }}%;"></div>
                </div>
                <div class="flex items-center justify-between mt-1.5 text-[11px] text-slate-500 dark:text-slate-400 font-mono-num">
                    <span>Realisasi: <strong class="text-slate-700 dark:text-slate-200">{{ formatRupiah($realisasi) }}</strong></span>
                    <span>Target: <strong class="text-slate-700 dark:text-slate-200">{{ formatAngka($d->jumlah) }}</strong></span>
                </div>
            </div>
        @endforeach
    </div>
@endif
