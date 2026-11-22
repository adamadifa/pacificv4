@forelse ($produk as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
        <td class="py-1.5 px-4 font-bold text-slate-700 dark:text-slate-300 font-mono-num">
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            {{ $d->kode_produk }}
        </td>
        <td class="py-1.5 px-4 font-semibold text-slate-900 dark:text-white">
            {{ $d->nama_produk }}
        </td>
        <td class="py-1.5 px-4 text-right">
            @if ($readonly)
                <input type="hidden" name="jumlah[]"
                    value="{{ empty($d->saldo_akhir) ? 0 : formatAngka($d->saldo_akhir) }}">
                <span class="font-mono-num font-bold text-brand-600 dark:text-brand-400">
                    {{ !empty($d->saldo_akhir) ? formatAngka($d->saldo_akhir) : '0' }}
                </span>
            @else
                <input type="text" name="jumlah[]" value="{{ formatAngka($d->jumlah) }}"
                    class="noborder-form w-32 h-7 px-2 text-xs font-bold text-right font-mono-num rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500"
                    placeholder="0">
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="py-6 text-center text-slate-400">
            <span class="text-xs font-semibold">Tidak ada produk yang ditemukan</span>
        </td>
    </tr>
@endforelse
