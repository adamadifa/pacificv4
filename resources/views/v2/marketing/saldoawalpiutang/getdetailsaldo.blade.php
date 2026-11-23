@if ($piutang->isEmpty())
    <tr>
        <td colspan="4" class="py-6 px-4 text-center text-rose-500 font-semibold text-xs">
            <div class="flex items-center justify-center gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Tidak ada data piutang untuk periode ini.</span>
            </div>
        </td>
    </tr>
@else
    @foreach ($piutang as $d)
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                <input type="hidden" class="no_faktur" value="{{ $d->no_faktur }}">
                {{ $d->no_faktur }}
            </td>
            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                {{ date('d-m-Y', strtotime($d->tanggal)) }}
            </td>
            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                {{ $d->nama_pelanggan }}
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                <input type="hidden" class="jumlah-piutang" value="{{ $d->saldo_akhir }}">
                {{ number_format($d->saldo_akhir, 0, ',', '.') }}
            </td>
        </tr>
    @endforeach
@endif
