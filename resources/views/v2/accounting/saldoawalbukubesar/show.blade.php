<div class="space-y-3.5">
    <!-- INFO HEADER STRIP -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kode</p>
            <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $saldoawalbukubesar->kode_saldo_awal }}</p>
        </div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Bulan</p>
            <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $nama_bulan[$saldoawalbukubesar->bulan] ?? $saldoawalbukubesar->bulan }}</p>
        </div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tahun</p>
            <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $saldoawalbukubesar->tahun }}</p>
        </div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</p>
            <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ date('d-m-Y', strtotime($saldoawalbukubesar->tanggal)) }}</p>
        </div>
    </div>

    <!-- TABEL RINCIAN SALDO -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">KODE AKUN</th>
                        <th class="py-2 px-3">NAMA AKUN</th>
                        <th class="py-2 px-3 text-right">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($detailsaldoawalbukubesar as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ $d->kode_akun }}</td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ $d->nama_akun }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ formatAngka($d->jumlah) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 px-4 text-center text-xs text-slate-400 font-semibold">
                                Tidak ada rincian akun pada saldo awal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
