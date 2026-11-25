<div class="space-y-4">

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
            
            <!-- No. Invoice -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">No. Invoice</span>
                <span class="font-bold font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ $servicekendaraan->no_invoice }}</span>
            </div>

            <!-- Tanggal -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($servicekendaraan->tanggal) }}</span>
            </div>

            <!-- No. Polisi -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">No. Polisi</span>
                <span class="font-mono-num font-bold text-slate-900 dark:text-slate-100 uppercase">{{ $servicekendaraan->no_polisi }}</span>
            </div>

            <!-- Kendaraan -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kendaraan</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $servicekendaraan->merek }} {{ $servicekendaraan->tipe }} {{ $servicekendaraan->tipe_kendaraan }}</span>
            </div>

            <!-- Bengkel -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Bengkel</span>
                <span class="font-bold text-slate-900 dark:text-slate-100">{{ $servicekendaraan->nama_bengkel }}</span>
            </div>

            <!-- Cabang -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Cabang</span>
                <span class="font-semibold text-slate-700 dark:text-slate-300 uppercase">{{ textupperCase($servicekendaraan->nama_cabang) }}</span>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEM SERVICE TABLE (~36PX COMPACT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Rincian Item Service & Suku Cadang</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    {{ count($detail) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">ITEM SERVICE</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">JUMLAH</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">HARGA</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @php
                        $grandtotal = 0;
                    @endphp
                    @forelse ($detail as $d)
                        @php
                            $total = $d->jumlah * $d->harga;
                            $grandtotal += $total;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->kode_item }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $d->nama_item }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatRupiah($d->harga) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ formatRupiah($total) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-xs">
                                Tidak ada data rincian item service.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                        <td colspan="4" class="py-2.5 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                            TOTAL SERVICE:
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-sm font-black text-[#284c9a] dark:text-blue-400">
                            {{ formatRupiah($grandtotal) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
