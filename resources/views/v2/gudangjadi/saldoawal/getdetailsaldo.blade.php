@foreach ($produk as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400 w-28">
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            {{ $d->kode_produk }}
        </td>
        <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
            {{ $d->nama_produk }}
        </td>
        <td class="py-1 px-3 text-right w-36">
            @if ($readonly)
                <input type="hidden" name="jumlah[]"
                    value="{{ empty($d->saldo_akhir) ? 0 : formatAngka($d->saldo_akhir) }}"
                    class="noborder-form">
                <span class="font-mono-num font-bold text-slate-900 dark:text-white">
                    {{ !empty($d->saldo_akhir) ? formatAngka($d->saldo_akhir) : '0' }}
                </span>
            @else
                <input type="text" name="jumlah[]" value="{{ formatAngka($d->jumlah) }}"
                    class="money w-full h-[32px] px-2.5 text-right font-mono-num font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            @endif
        </td>
    </tr>
@endforeach

<script>
    if (typeof $ !== 'undefined' && $.fn.maskMoney) {
        $(".money").maskMoney();
    }
</script>
