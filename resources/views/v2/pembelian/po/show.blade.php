<div class="space-y-4">

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            
            <!-- No. Bukti PO -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">No. Bukti PO</span>
                <span class="font-bold font-mono-num text-sm text-[#284c9a] dark:text-blue-400">{{ $po->no_bukti }}</span>
            </div>

            <!-- Tanggal PO -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($po->tanggal) }}</span>
            </div>

            <!-- Supplier -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Supplier</span>
                <span class="font-bold text-slate-900 dark:text-slate-100 uppercase">{{ $po->nama_supplier }}</span>
            </div>

            <!-- Kategori / Jenis PO -->
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Jenis PO</span>
                @if ($po->kategori_perusahaan == 'MP')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                        MP
                    </span>
                @elseif ($po->kategori_perusahaan == 'CVMP')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                        CV MP
                    </span>
                @else
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $po->kategori_perusahaan ?: '-' }}</span>
                @endif
            </div>

        </div>
    </div>

    <!-- DETAIL BARANG TABLE (~36PX COMPACT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Judul Tabel (bg-[#284c9a]) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Rincian Barang Pemesanan</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    {{ count($detail) }} Item Barang
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Baris 2: Header Kolom (bg-[#1e3a78]) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA BARANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[150px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">QTY</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">HARGA</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @php
                        $total_pembelian = 0;
                    @endphp
                    @forelse ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total_pembelian += $subtotal;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->kode_barang }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ textCamelCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->keterangan ? textCamelCase($d->keterangan) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngkaDesimal($d->harga) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ formatAngkaDesimal($subtotal) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 text-xs">
                                Tidak ada data rincian barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                        <td colspan="5" class="py-2.5 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                            TOTAL PRE-ORDER:
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-sm font-black text-[#284c9a] dark:text-blue-400">
                            {{ formatAngkaDesimal($total_pembelian) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
