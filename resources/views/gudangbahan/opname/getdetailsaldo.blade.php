@foreach ($barang as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
            {{ $d->kode_barang }}
        </td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->nama_kategori }}</td>
        <td class="text-end">
            <input type="text" name="qty_unit[]" value="{{ formatAngkaDesimal($d->saldo_unit) }}"
                    style="text-align: right" class="noborder-form number-separator">
        </td>
        <td class="text-end">
            <input type="text" name="qty_berat[]" value="{{ formatAngkaDesimal($d->saldo_berat) }}"
                    style="text-align: right" class="noborder-form number-separator">
        </td>
    </tr>
@endforeach
<script>
    easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });
</script>
