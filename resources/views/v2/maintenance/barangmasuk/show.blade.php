@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
@endphp

<div class="space-y-3.5">
    
    <!-- DOCUMENT SUMMARY CARD (CLEAN INTEGRATED STRIP) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3.5 sm:p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <!-- No. Bukti -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">No. Bukti</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm tracking-tight truncate" title="{{ $barangmasuk->no_bukti }}">
                        {{ $barangmasuk->no_bukti }}
                    </span>
                </div>
            </div>

            <!-- Tanggal Diterima -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Tanggal Diterima</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ DateToIndo($barangmasuk->tanggal) }}
                    </span>
                </div>
            </div>

            <!-- Kode Supplier -->
            <div class="flex flex-col justify-center lg:border-l lg:border-slate-200/80 lg:dark:border-slate-800 lg:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Kode Supplier</span>
                <div class="flex items-center gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-200/70 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono-num font-bold text-xs">
                        <i data-lucide="hash" class="w-3 h-3 text-slate-400"></i>
                        {{ $barangmasuk->kode_supplier ?? '-' }}
                    </span>
                </div>
            </div>

            <!-- Nama Supplier -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Nama Supplier</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs truncate" title="{{ $barangmasuk->nama_supplier ?? '-' }}">
                        {{ $barangmasuk->nama_supplier ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang yang Diterima</span>
                                </div>
                                <span class="text-[11px] text-white/80 font-mono-num font-semibold">{{ count($detail) }} item barang</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3.5 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3.5 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2 px-3.5 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">SATUAN</th>
                        <th class="py-2 px-3.5 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2 px-4 text-right w-28 font-bold uppercase tracking-wider text-white/90 border-none">QTY</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $totalQty = 0; @endphp
                    @forelse ($detail as $d)
                        @php $totalQty += (float)$d->jumlah; @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2.5 px-3.5 text-center text-slate-400 font-mono-num text-[11px]">{{ $loop->iteration }}</td>
                            <td class="py-2.5 px-3.5">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_barang }}</span>
                            </td>
                            <td class="py-2.5 px-4 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_barang }}
                            </td>
                            <td class="py-2.5 px-3.5 text-center text-slate-600 dark:text-slate-400 text-xs">
                                {{ $d->satuan ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3.5 text-slate-600 dark:text-slate-400 text-xs">
                                {{ !empty($d->keterangan) ? $d->keterangan : '-' }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 text-xs">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada detail barang</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 font-bold text-xs sticky bottom-0 z-10 backdrop-blur-xs">
                    <tr>
                        <td colspan="5" class="py-2.5 px-4 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Total Kuantitas:
                        </td>
                        <td class="py-2.5 px-4 text-right font-mono-num text-sm font-black text-[#284c9a] dark:text-blue-400">
                            {{ $numFormat($totalQty) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ACTION CLOSE BUTTON -->
    <div class="flex items-center justify-end pt-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
