@if (empty($pp))
    @php
        $bIndex = (int) ($bulan ?? request('bulan', date('m')));
        $namaBulan = config('global.nama_bulan')[$bIndex] ?? 'Bulan ' . $bIndex;
        $tahunPilih = $tahun ?? request('tahun', date('Y'));
    @endphp
    <div class="h-full min-h-[320px] flex flex-col items-center justify-center p-6 text-center rounded-xl bg-slate-50/70 dark:bg-slate-950/40 border border-dashed border-slate-200 dark:border-slate-800 transition-colors">
        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-400/10 text-amber-500 dark:text-amber-400 flex items-center justify-center mb-3.5 ring-8 ring-amber-500/5">
            <i data-lucide="clipboard-x" class="w-7 h-7"></i>
        </div>
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60 mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            Data Belum Tersedia
        </span>
        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100 mb-1">
            Permintaan Produksi Belum Ditemukan
        </h5>
        <p class="text-[11px] text-slate-500 dark:text-slate-400 max-w-[260px] leading-relaxed">
            Tidak ada dokumen OMAN atau Permintaan Produksi aktif untuk periode <span class="font-bold text-slate-700 dark:text-slate-200">{{ $namaBulan }} {{ $tahunPilih }}</span>.
        </p>
    </div>
@else
    @php
        $bulanName = config('global.nama_bulan')[(int)$pp->bulan] ?? ($namabulan[(int)$pp->bulan] ?? 'Bulan ' . $pp->bulan);
    @endphp

    <!-- Compact Mini Info 2x2 Grid -->
    <div class="grid grid-cols-2 gap-2 mb-3">
        <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">No. PP</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 font-mono-num block truncate mt-0.5" title="{{ $pp->no_permintaan }}">
                {{ $pp->no_permintaan }}
            </span>
        </div>
        <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Periode</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block truncate mt-0.5">
                {{ $bulanName }} {{ $pp->tahun }}
            </span>
        </div>
        <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Satuan</span>
            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 font-mono-num">DUS</span>
        </div>
        <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Status</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/60 leading-none">
                Selesai
            </span>
        </div>
    </div>

    <!-- Table Realisasi vs Permintaan -->
    <div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800 max-h-[385px] overflow-y-auto scrollbar-thin">
        <table class="w-full text-xs text-left border-collapse">
            <thead class="sticky top-0 bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-b border-slate-200/80 dark:border-slate-800 z-10 text-[10px]">
                <tr>
                    <th class="px-2.5 py-2 font-bold uppercase tracking-wider">Produk</th>
                    <th class="px-2 py-2 font-bold uppercase tracking-wider text-right">Target</th>
                    <th class="px-2 py-2 font-bold uppercase tracking-wider text-right">Realisasi</th>
                    <th class="px-2 py-2 font-bold uppercase tracking-wider text-center w-24">Ratio</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                @forelse ($detail as $d)
                    @php
                        $permintaan = $d->oman_marketing - $d->stok_gudang + $d->buffer_stok;
                        $realisasi = $d->jml_realisasi;
                        $ratio = $permintaan != 0 ? ($realisasi / $permintaan) * 100 : 0;

                        if ($ratio < 50) {
                            $barBg = 'bg-rose-500';
                            $textColor = 'text-rose-600 dark:text-rose-400';
                        } elseif ($ratio < 70) {
                            $barBg = 'bg-amber-500';
                            $textColor = 'text-amber-600 dark:text-amber-400';
                        } elseif ($ratio < 90) {
                            $barBg = 'bg-sky-500';
                            $textColor = 'text-sky-600 dark:text-sky-400';
                        } else {
                            $barBg = 'bg-emerald-500';
                            $textColor = 'text-emerald-600 dark:text-emerald-400';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100 text-[11px]">
                        <!-- Produk -->
                        <td class="px-2.5 py-2 font-bold text-slate-900 dark:text-white">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 shrink-0"></span>
                                <span class="truncate font-mono-num">{{ $d->kode_produk }}</span>
                            </div>
                        </td>

                        <!-- Target / Permintaan -->
                        <td class="px-2 py-2 text-right font-mono-num font-semibold text-slate-600 dark:text-slate-400">
                            {{ formatAngka($permintaan) }}
                        </td>

                        <!-- Realisasi -->
                        <td class="px-2 py-2 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
                            {{ formatAngka($realisasi) }}
                        </td>

                        <!-- Ratio & Progress Bar -->
                        <td class="px-2 py-2 text-center">
                            <div class="flex items-center gap-1.5 justify-center">
                                <div class="w-10 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0">
                                    <div class="h-full rounded-full {{ $barBg }}" style="width: {{ min(100, round($ratio)) }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold font-mono-num {{ $textColor }} shrink-0">
                                    {{ round($ratio) }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center py-4">
                                <i data-lucide="package-open" class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2"></i>
                                <span class="text-xs font-medium">Belum ada item detail produk pada permintaan ini.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif
