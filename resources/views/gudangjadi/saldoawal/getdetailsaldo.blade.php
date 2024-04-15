@foreach ($produk as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            {{ $d->kode_produk }}
        </td>
        <td>{{ $d->nama_produk }}</td>
        <td class="text-end">
            @if ($readonly)
                <input type="hidden" name="jumlah[]" value="{{ empty($d->saldo_akhir) ? 0 : $d->saldo_akhir }}"
                    style="text-align: right" class="noborder-form">
                {{ !empty($d->saldo_akhir) ? formatAngka($d->saldo_akhir) : '' }}
            @else
                <input type="text" name="jumlah[]" value="{{ $d->jumlah }}" style="text-align: right"
                    class="noborder-form money">
            @endif
        </td>
    </tr>
@endforeach

<script>
    $(".money").maskMoney();
</script>
