@foreach ($barang as $d)
    @php
        $saldo_akhir = $d->saldo_awal_jumlah + $d->bm_jumlah - $d->bk_jumlah;
        $jumlah_saldoawal_pemasukan = $d->saldo_awal_jumlah + $d->bm_jumlah;
        if (empty($jumlah_saldoawal_pemasukan)) {
            $jumlah_saldoawal_pemasukan = 1;
        }

        if (empty($d->saldo_awal_harga) and $d->saldo_awal_harga == 0) {
            $saldo_akhir_harga = !empty($d->bm_jumlah) ? $d->bm_totalharga / $d->bm_jumlah : 0;
        } elseif (empty($d->bm_harga) and $d->bm_harga == 0) {
            $saldo_akhir_harga = $d->saldo_awal_harga;
        } else {
            $saldo_akhir_harga = ($d->saldo_awal_totalharga * 1 + $d->bm_totalharga * 1) / $jumlah_saldoawal_pemasukan;
        }
    @endphp
    @if (!empty($saldo_akhir))
        <tr>
            <td>
                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                {{ $d->kode_barang }}
            </td>
            <td>{{ $d->nama_barang }}</td>
            <td class="text-end">
                @if ($readonly)
                    <input type="hidden" name="jumlah[]" value="{{ formatAngkaDesimal($saldo_akhir) }}">
                    {{ formatAngkaDesimal($saldo_akhir) }}
                @else
                    <input type="text" name="jumlah[]" value="" class="noborder-form text-end number-separator">
                @endif
            </td>
            <td class="text-end">
                @if ($readonly)
                    <input type="hidden" name="harga[]" value="{{ formatAngkaDesimal($saldo_akhir_harga) }}">
                    {{ formatAngkaDesimal($saldo_akhir_harga) }}
                @else
                    <input type="text" name="harga[]" value="" class="noborder-form text-end number-separator">
                @endif
            </td>
        </tr>
    @endif
@endforeach
<script>
    easyNumberSeparator({
        selector: '.number-separator',
        separator: '.',
        decimalSeparator: ',',
    });
</script>
