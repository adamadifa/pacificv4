@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="space-y-4">
    <!-- INFO SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Mutasi Reject</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $reject->no_mutasi }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Reject</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($reject->tanggal) }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (Dua Baris Thead V2 Standard) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[440px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Reject</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $grandTotal = 0; @endphp
                    @forelse ($detail as $d)
                        @php $grandTotal += (float)($d->jumlah ?? 0); @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Tidak ada detail produk</td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($detail) > 0)
                    <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/70 font-bold text-xs">
                        <tr>
                            <td colspan="3" class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                                Total Kuantitas Reject:
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-[#284c9a] dark:text-blue-400 font-black">
                                {{ $numFormat($grandTotal) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
