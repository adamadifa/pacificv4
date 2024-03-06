@foreach ($produk as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            {{ $d->kode_produk }}
        </td>
        <td>{{ $d->nama_produk }}</td>
        <td class="text-end">
            @if ($readonly)
                <input type="hidden" name="jumlah[]" value="{{ empty($d->jumlah) ? 0 : $d->jumlah }}"
                    style="text-align: right" class="form-control">
                {{ !empty($d->jumlah) ? formatAngka($d->jumlah) : '' }}
            @else
                <input type="text" name="jumlah[]" value="{{ $d->jumlah }}" style="text-align: right"
                    class="form-control">
            @endif
        </td>
    </tr>
@endforeach
