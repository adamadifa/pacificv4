@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
    $grandtotaltarif = 0;
    foreach ($detail as $d) {
        $grandtotaltarif += (float)($d->tarif ?? 0) + (float)($d->tepung ?? 0) + (float)($d->bs ?? 0);
    }
    $totalSJ = count($detail);
@endphp

<div class="space-y-4">
    <!-- STRIP INFORMASI KONTRABON -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Kontrabon</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm">
                    {{ $kontrabon->no_kontrabon }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Kontrabon</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">
                    {{ DateToIndo($kontrabon->tanggal) }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Armada Angkutan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ textUpperCase($kontrabon->nama_angkutan) }}
                </span>
            </div>
        </div>
    </div>

    <!-- METRIC STRIP PACIFIC NAVY -->
    <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white p-3.5 flex flex-wrap items-center justify-around gap-4">
        <div class="text-center">
            <span class="block text-[11px] font-semibold text-white/75 uppercase tracking-wider">Total Surat Jalan</span>
            <span class="text-lg font-black font-mono-num">{{ $totalSJ }} Dokumen</span>
        </div>
        <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
        <div class="text-center">
            <span class="block text-[11px] font-semibold text-white/75 uppercase tracking-wider">Grand Total Tagihan</span>
            <span class="text-lg font-black font-mono-num">Rp {{ $numFormat($grandtotaltarif) }}</span>
        </div>
    </div>

    <!-- TABEL RINCIAN DOKUMEN SURAT JALAN DUAL THEAD -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="py-2 px-3.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                    <i data-lucide="list" class="w-4 h-4 text-white/80"></i>
                                    <span>Rincian Surat Jalan Terlampir</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ $totalSJ }} SJ</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-1.5 px-3">No. Dokumen</th>
                        <th class="py-1.5 px-3">Tanggal</th>
                        <th class="py-1.5 px-3">No. Polisi</th>
                        <th class="py-1.5 px-3">Tujuan</th>
                        <th class="py-1.5 px-3 text-right">Tarif</th>
                        <th class="py-1.5 px-3 text-right">Tepung</th>
                        <th class="py-1.5 px-3 text-right">BS</th>
                        <th class="py-1.5 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @forelse ($detail as $d)
                        @php
                            $totaltarif = (float)($d->tarif ?? 0) + (float)($d->tepung ?? 0) + (float)($d->bs ?? 0);
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_dok }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                {{ formatIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                {{ $d->no_polisi ?? '-' }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->tujuan }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">
                                {{ $numFormat($d->tarif) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-500">
                                {{ $numFormat($d->tepung) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-500">
                                {{ $numFormat($d->bs) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($totaltarif) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">
                                Tidak ada data rincian surat jalan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 text-xs font-bold">
                    <tr>
                        <td colspan="7" class="py-2 px-3 text-slate-700 dark:text-slate-300 uppercase tracking-wider">TOTAL TAGIHAN</td>
                        <td class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-white text-sm">{{ $numFormat($grandtotaltarif) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
