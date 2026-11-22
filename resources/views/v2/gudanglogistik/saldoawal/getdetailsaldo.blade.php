@foreach ($barang as $d)
    @php
        $saldo_akhir = $d->saldo_awal_jumlah + $d->bm_jumlah - $d->bk_jumlah;
        $jumlah_saldoawal_pemasukan = $d->saldo_awal_jumlah + $d->bm_jumlah;
        if (empty($jumlah_saldoawal_pemasukan)) {
            $jumlah_saldoawal_pemasukan = 1;
        }

        if (empty($d->saldo_awal_harga) && $d->saldo_awal_harga == 0) {
            $saldo_akhir_harga = !empty($d->bm_jumlah) ? $d->bm_totalharga / $d->bm_jumlah : 0;
        } elseif (empty($d->bm_harga) && $d->bm_harga == 0) {
            $saldo_akhir_harga = $d->saldo_awal_harga;
        } else {
            $saldo_akhir_harga = ($d->saldo_awal_totalharga * 1 + $d->bm_totalharga * 1) / $jumlah_saldoawal_pemasukan;
        }
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
                @if ($readonly)
                    <input type="hidden" name="jumlah[]" value="{{ formatAngkaDesimal($saldo_akhir) }}">
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ formatAngkaDesimal($saldo_akhir) }}</span>
                @else
                    <input type="text" name="jumlah[]" value=""
                        class="money number-separator w-28 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-hidden">
                @endif
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                @if ($readonly)
                    <input type="hidden" name="harga[]" value="{{ formatAngkaDesimal($saldo_akhir_harga) }}">
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ formatAngkaDesimal($saldo_akhir_harga) }}</span>
                @else
                    <input type="text" name="harga[]" value=""
                        class="money number-separator w-36 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-hidden">
                @endif
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
