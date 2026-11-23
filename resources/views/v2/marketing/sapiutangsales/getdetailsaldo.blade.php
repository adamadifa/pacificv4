@forelse ($rekappenjualan as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
            <input type="hidden" name="kode_salesman[]" class="kode_salesman" value="{{ $d['kode_salesman'] }}">
            {{ $d['kode_salesman'] }}
        </td>
        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
            {{ $d['nama_salesman'] }}
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
            <input type="hidden" name="saldo_akhir[]" class="saldo_akhir" value="{{ $d['saldo_akhir'] }}">
            {{ number_format($d['saldo_akhir'], 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="py-6 px-4 text-center text-rose-500 font-semibold text-xs">
            <div class="flex items-center justify-center gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Tidak ada data piutang salesman untuk cabang dan periode ini.</span>
            </div>
        </td>
    </tr>
@endforelse
