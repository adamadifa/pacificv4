@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="space-y-4">
    <!-- SUMMARY CARD (CLEAN INTEGRATED STRIP) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3.5 sm:p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- Kode OMAN -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Kode OMAN</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm tracking-tight truncate">
                        {{ $oman_cabang->kode_oman }}
                    </span>
                </div>
            </div>

            <!-- Cabang -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Cabang</span>
                <div class="flex items-center gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-xs border border-blue-200/60 dark:border-blue-900/50">
                        <i data-lucide="building-2" class="w-3 h-3"></i>
                        <span>{{ textUpperCase($oman_cabang->cabang->nama_cabang) }}</span>
                    </span>
                </div>
            </div>

            <!-- Periode (Bulan & Tahun) -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Periode</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ $namabulan[$oman_cabang->bulan] }} {{ $oman_cabang->tahun }}
                    </span>
                </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Status</span>
                <div>
                    @if ($oman_cabang->status_oman_cabang === '1')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                            <i data-lucide="check" class="w-3 h-3"></i>
                            <span>Sudah di Proses</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span>Belum di Proses</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (RINCIAN PERMINTAAN PER MINGGU) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[350px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="layers" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Permintaan OMAN Cabang</span>
                                </div>
                                <span class="text-[11px] text-white/80 font-mono-num font-semibold">{{ count($detail) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-10 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">M1</th>
                        <th class="py-2 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">M2</th>
                        <th class="py-2 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">M3</th>
                        <th class="py-2 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">M4</th>
                        <th class="py-2 px-3 text-right w-28 font-bold uppercase tracking-wider text-white/90 border-none">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php
                        $totM1 = 0; $totM2 = 0; $totM3 = 0; $totM4 = 0; $totGrand = 0;
                    @endphp
                    @forelse ($detail as $d)
                        @php
                            $totM1 += (float)$d->minggu_1;
                            $totM2 += (float)$d->minggu_2;
                            $totM3 += (float)$d->minggu_3;
                            $totM4 += (float)$d->minggu_4;
                            $totGrand += (float)$d->total;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2 px-3 text-center text-slate-400 font-mono-num text-[11px]">{{ $loop->iteration }}</td>
                            <td class="py-2 px-3">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num font-medium text-slate-700 dark:text-slate-300">
                                {{ $numFormat($d->minggu_1) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num font-medium text-slate-700 dark:text-slate-300">
                                {{ $numFormat($d->minggu_2) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num font-medium text-slate-700 dark:text-slate-300">
                                {{ $numFormat($d->minggu_3) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num font-medium text-slate-700 dark:text-slate-300">
                                {{ $numFormat($d->minggu_4) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white bg-slate-50/60 dark:bg-slate-800/40">
                                {{ $numFormat($d->total) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada rincian produk</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 font-bold text-xs sticky bottom-0 z-10 backdrop-blur-xs">
                    <tr>
                        <td colspan="3" class="py-2.5 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Total Permintaan:
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-slate-800 dark:text-slate-100">
                            {{ $numFormat($totM1) }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-slate-800 dark:text-slate-100">
                            {{ $numFormat($totM2) }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-slate-800 dark:text-slate-100">
                            {{ $numFormat($totM3) }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-slate-800 dark:text-slate-100">
                            {{ $numFormat($totM4) }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono-num text-sm font-black text-[#284c9a] dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/30">
                            {{ $numFormat($totGrand) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- FOOTER CLOSE ACTION -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex justify-end">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>
