@if ($target->isEmpty())
    <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Data target tidak ditemukan</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Coba ubah filter cabang, salesman, bulan, atau tahun.</p>
    </div>
@else
    <div class="space-y-2">
        @foreach ($target as $d)
            @php
                $realisasi = $d->isi_pcs_dus > 0 ? $d->realisasi / $d->isi_pcs_dus : 0;
                $persentase = !empty($d->jumlah_target) ? round((round($realisasi) / $d->jumlah_target) * 100) : 0;
                $barWidth = min(100, max(0, $persentase));

                if ($persentase < 50) {
                    $barColor = 'bg-rose-500';
                    $badgeColor = 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300';
                } elseif ($persentase < 90) {
                    $barColor = 'bg-[#284c9a]';
                    $badgeColor = 'bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-300';
                } else {
                    $barColor = 'bg-emerald-500';
                    $badgeColor = 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300';
                }
            @endphp
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-snug">{{ $d->nama_produk }}</p>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black font-mono-num shrink-0 {{ $badgeColor }}">
                        {{ $persentase }}%
                    </span>
                </div>

                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <div class="h-full rounded-full {{ $barColor }} transition-all duration-300" style="width: {{ $barWidth }}%;"></div>
                </div>

                <div class="flex items-center justify-between text-[11px] font-mono-num text-slate-500 dark:text-slate-400">
                    <span>Realisasi: <strong class="text-slate-700 dark:text-slate-200">{{ formatRupiah($realisasi) }}</strong></span>
                    <span>Target: <strong class="text-slate-700 dark:text-slate-200">{{ formatAngka($d->jumlah_target) }}</strong></span>
                </div>
            </div>
        @endforeach
    </div>
@endif
