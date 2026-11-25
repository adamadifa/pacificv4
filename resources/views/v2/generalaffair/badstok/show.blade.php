<div class="space-y-4">

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            
            <!-- Kode BS -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kode Bad Stok</span>
                <span class="font-bold font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ $badstok->kode_bs }}</span>
            </div>

            <!-- Tanggal -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($badstok->tanggal) }}</span>
            </div>

            <!-- Asal BS -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Asal Bad Stok</span>
                <span class="font-bold text-slate-900 dark:text-slate-100 uppercase">{{ $badstok->kode_asal_bs }}</span>
            </div>

        </div>
    </div>

    <!-- DETAIL PRODUK BAD STOK TABLE (~36PX COMPACT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="3" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="package-x" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Rincian Produk Bad Stok</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    {{ count($detail) }} Produk
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400 text-xs">
                                Tidak ada data rincian produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
