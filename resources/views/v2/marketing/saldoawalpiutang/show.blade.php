<div class="space-y-4">
    <!-- EXECUTIVE SUMMARY STRIP (INFORMASI SALDO AWAL) -->
    <div class="rounded-xl bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 p-3 sm:p-3.5">
        <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="database-import" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Saldo Awal Piutang</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Kode Saldo Awal</span>
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $saldo_awal->kode_saldo_awal }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Tanggal Efektif</span>
                <span class="font-mono-num font-medium text-slate-700 dark:text-slate-200">{{ date('d-m-Y', strtotime($saldo_awal->tanggal)) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $nama_bulan[$saldo_awal->bulan] }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Tahun</span>
                <span class="font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ $saldo_awal->tahun }}</span>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN PIUTANG (KOMPAK ~36px) -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-2xs">
        <div class="overflow-x-auto max-h-[50vh] overflow-y-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Faktur Piutang</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ count($detail) }} Faktur
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">NO. FAKTUR</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA PELANGGAN</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php
                        $total = 0;
                    @endphp
                    @forelse ($detail as $d)
                        @php
                            $total += $d->jumlah;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_faktur }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_pelanggan }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ number_format($d->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">
                                Tidak ada data rincian piutang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TOTAL FOOTER BANNER (PACIFIC NAVY STRIP) -->
        <div class="px-4 py-3 bg-[#284c9a] text-white flex items-center justify-between border-t border-blue-900">
            <div class="flex items-center gap-2">
                <i data-lucide="calculator" class="w-4 h-4 text-blue-200"></i>
                <span class="text-xs font-bold uppercase tracking-wider text-white">TOTAL PIUTANG</span>
            </div>
            <span class="text-base sm:text-lg font-black font-mono-num text-white">
                Rp {{ number_format($total, 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>
