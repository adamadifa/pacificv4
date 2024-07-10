@foreach ($detail as $d)
    <tr>
        <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
        <td>{{ $d->kode_produk }}</td>
        <td>{{ $d->nama_produk }}</td>
        <td class="text-end">
            <input type="text" class="noborder-form text-end number-separator" name="harga_awal[]" value="{{ formatAngka($d->harga_awal) }}">
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
