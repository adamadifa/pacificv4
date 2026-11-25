<div class="space-y-3.5">
    <!-- Header Rincian & Export Excel Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2.5 border-b border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-3 text-xs">
            <div>
                <span class="text-slate-400">Bulan:</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 ml-1">{{ $namabulan[$potonganpjp->bulan] }}</span>
            </div>
            <div class="h-3 w-px bg-slate-200 dark:bg-slate-700"></div>
            <div>
                <span class="text-slate-400">Tahun:</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100 ml-1">{{ $potonganpjp->tahun }}</span>
            </div>
        </div>
        <div>
            <a href="{{ route('pembayaranpjp.show', [Crypt::encrypt($potonganpjp->kode_potongan), 'true']) }}" target="_blank"
                class="inline-flex items-center gap-1.5 h-[32px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Tabel Rincian Pemotongan Gaji -->
    <div class="overflow-x-auto max-h-[500px] rounded-xl border border-slate-200/80 dark:border-slate-800">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-[#1e3a78] text-white sticky top-0 z-10">
                <tr>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-10">NO</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. PINJAMAN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NIK</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">NAMA KARYAWAN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">DEPT</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KANTOR</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">CICILAN KE</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                @php
                    $total = 0;
                @endphp
                @foreach ($historibayar as $d)
                    @php
                        $total += $d->jumlah;
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->no_pinjaman }}</td>
                        <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">{{ $d->nik }}</td>
                        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ textUpperCase($d->nama_karyawan) }}</td>
                        <td class="py-1.5 px-3 text-center font-semibold text-slate-600 dark:text-slate-300">{{ textUpperCase($d->kode_dept) }}</td>
                        <td class="py-1.5 px-3 text-center font-semibold text-slate-600 dark:text-slate-300">{{ textUpperCase($d->kode_cabang) }}</td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($d->jumlah) }}</td>
                        <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-700 dark:text-slate-300">{{ $d->cicilan_ke }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-[#1e3a78] text-white font-bold sticky bottom-0 z-10">
                <tr>
                    <td colspan="6" class="py-2 px-3 text-center uppercase tracking-wider text-xs">TOTAL PEMBAYARAN</td>
                    <td class="py-2 px-3 text-right font-mono-num text-xs text-emerald-300 font-black">{{ formatAngka($total) }}</td>
                    <td class="py-2 px-3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
