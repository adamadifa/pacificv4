<div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800 scrollbar-thin">
    <table class="w-full text-xs text-left border-collapse font-sans">
        <thead>
            <tr class="bg-[#284c9a] text-white text-[11px]">
                <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider border-r border-white/10">No</th>
                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider border-r border-white/10">Cabang</th>
                <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider border-r border-white/10">Keterangan</th>
                <th class="py-2.5 px-3.5 text-right font-bold uppercase tracking-wider">Jumlah Saldo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
            @php $total = 0; @endphp
            @forelse ($details as $d)
                @php $total += $d->jumlah; @endphp
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                    <td class="py-2 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                    <td class="py-2 px-3.5 font-bold text-slate-900 dark:text-white uppercase">{{ $d->nama_cabang }}</td>
                    <td class="py-2 px-3.5 text-slate-600 dark:text-slate-300">{{ $d->keterangan }}</td>
                    <td class="py-2 px-3.5 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                        {{ formatAngka($d->jumlah) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-slate-400">
                        <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                        <span>Data saldo awal kas besar tidak ditemukan</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="bg-slate-50 dark:bg-slate-800/80 border-t-2 border-slate-200 dark:border-slate-700">
            <tr>
                <th colspan="3" class="py-2.5 px-3.5 text-center font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    TOTAL KESELURUHAN
                </th>
                <th class="py-2.5 px-3.5 text-right font-mono-num font-black text-brand-600 dark:text-brand-400 text-xs">
                    {{ formatAngka($total) }}
                </th>
            </tr>
        </tfoot>
    </table>
</div>
