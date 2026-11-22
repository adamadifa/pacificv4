<div class="space-y-3.5">
    <!-- INFO SUMMARY STRIP -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800">
        <div>
            <span class="block text-[11px] font-medium text-slate-400">No. Bukti</span>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $barangkeluar->no_bukti }}</span>
        </div>
        <div>
            <span class="block text-[11px] font-medium text-slate-400">Tanggal</span>
            <span class="text-xs font-semibold text-slate-800 dark:text-slate-100 font-mono-num">{{ DateToIndo($barangkeluar->tanggal) }}</span>
        </div>
        <div>
            <span class="block text-[11px] font-medium text-slate-400">Jenis Pengeluaran</span>
            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-[#284c9a] dark:text-blue-300 border border-blue-100 dark:border-blue-900">
                {{ $jenis_pengeluaran[$barangkeluar->kode_jenis_pengeluaran] ?? $barangkeluar->kode_jenis_pengeluaran }}
            </span>
        </div>
        <div>
            <span class="block text-[11px] font-medium text-slate-400">Tujuan / Keterangan</span>
            <span class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                @if ($barangkeluar->kode_jenis_pengeluaran == 'CBG')
                    <span class="font-bold text-[#284c9a] dark:text-blue-400">{{ textUpperCase($barangkeluar->nama_cabang) }}</span>
                @elseif ($barangkeluar->kode_jenis_pengeluaran == 'PRD')
                    <span>Unit {{ $barangkeluar->keterangan }}</span>
                @else
                    <span>{{ $barangkeluar->keterangan ?: '-' }}</span>
                @endif
            </span>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Keluar</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">QTY UNIT</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">QTY BERAT</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">QTY LEBIH</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a]">
                                {{ $d->kode_barang }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ textUpperCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                {{ formatAngkaDesimal($d->qty_unit) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                {{ formatAngkaDesimal($d->qty_berat) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                {{ formatAngkaDesimal($d->qty_lebih) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->keterangan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">
                                Tidak ada data rincian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
