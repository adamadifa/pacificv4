<div class="space-y-3.5">
    @php
        $numFormat = function($val) {
            return number_format((float)($val ?? 0), 2, ',', '.');
        };
    @endphp
    <!-- INFO STRIP / METRICS -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Bukti</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $barangmasuk->no_bukti }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Diterima</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($barangmasuk->tanggal) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Kode Supplier</span>
                <span class="font-bold font-mono-num text-slate-700 dark:text-slate-200">{{ $barangmasuk->kode_supplier ?: '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Nama Supplier</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $barangmasuk->nama_supplier ?: '-' }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Title Strip (colspan=9) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="9" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Masuk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">QTY</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">HARGA</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-32">SUBTOTAL</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PENYESUAIAN</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-32">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $grandTotal = 0; @endphp
                    @forelse ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + ($d->penyesuaian ?? 0);
                            $grandTotal += $total;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a]">{{ $d->kode_barang }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">{{ textCamelCase($d->nama_barang) }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ $d->keterangan ?: '-' }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ $numFormat($d->jumlah) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ $numFormat($d->harga) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ $numFormat($subtotal) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ $numFormat($d->penyesuaian ?? 0) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ $numFormat($total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-slate-400">
                                Tidak ada rincian barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($detail) > 0)
                    <tfoot>
                        <tr class="bg-slate-100/80 dark:bg-slate-800/80 font-bold border-t border-slate-200 dark:border-slate-700">
                            <td colspan="8" class="py-2 px-3 text-right uppercase tracking-wider text-xs text-slate-700 dark:text-slate-200">
                                Total Keseluruhan
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-[#284c9a] dark:text-blue-400">
                                {{ $numFormat($grandTotal) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>