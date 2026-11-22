@foreach ($barang as $d)
    @php
        $saldo_akhir = $d->saldo_awal_jumlah + $d->bm_jumlah - $d->bk_jumlah;
    @endphp
    @if (!empty($saldo_akhir))
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a]">
                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                {{ $d->kode_barang }}
            </td>
            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                {{ $d->nama_barang }}
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                <input type="text" name="jumlah[]" value="{{ formatAngkaDesimal($saldo_akhir) }}"
                    class="money number-separator w-36 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-hidden">
            </td>
        </tr>
    @endif
@endforeach
<script>
    if (typeof easyNumberSeparator !== 'undefined') {
        easyNumberSeparator({
            selector: '.money, .number-separator',
            separator: '.',
            decimalSeparator: ',',
        });
    }
</script>
