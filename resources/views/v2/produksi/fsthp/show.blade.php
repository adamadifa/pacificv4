<div class="space-y-3.5">
    <!-- INFO CARDS STRIP -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">No. FSTHP</span>
            <span class="text-xs font-black text-brand-600 dark:text-brand-400 font-mono-num block mt-0.5">
                {{ $fsthp->no_mutasi }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tanggal Serah Terima</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ DateToIndo($fsthp->tanggal_mutasi) }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Unit Produksi</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                Unit {{ $fsthp->unit }}
            </span>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap font-sans">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang yang Diserah-Terimakan</span>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ count($detail) }} Item</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 text-center w-10 font-bold uppercase tracking-wider text-white/90">NO</th>
                        <th class="py-2 px-3 w-28 font-bold uppercase tracking-wider text-white/90">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90">SHIFT</th>
                        <th class="py-2 px-3 text-right w-32 font-bold uppercase tracking-wider text-white/90">JUMLAH (DUS)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $total_jumlah = 0; @endphp
                    @forelse ($detail as $d)
                        @php $total_jumlah += $d->jumlah; @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <td class="py-1.5 px-3 text-center text-slate-500 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $d->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $d->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    Shift {{ $d->shift }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
                                {{ formatRupiah($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada rincian data</td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($detail) > 0)
                    <tfoot>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-700">
                            <td colspan="4" class="py-2 px-3 text-right uppercase text-[11px] text-slate-600 dark:text-slate-300">Total FSTHP:</td>
                            <td class="py-2 px-3 text-right font-mono-num text-brand-600 dark:text-brand-400 text-xs">{{ formatRupiah($total_jumlah) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
