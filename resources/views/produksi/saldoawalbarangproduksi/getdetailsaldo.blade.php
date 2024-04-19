@foreach ($barangproduksi as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_barang_produksi[]" value="{{ $d->kode_barang_produksi }}">
            {{ $d->kode_barang_produksi }}
        </td>
        <td>{{ $d->nama_barang }}</td>
        <td class="text-end">
            @if ($readonly)
                <input type="hidden" name="jumlah[]"
                    value="{{ empty($d->saldo_akhir) ? 0 : formatAngkaDesimal($d->saldo_akhir) }}"
                    style="text-align: right" class="form-control">
                {{ !empty($d->saldo_akhir) ? formatAngkaDesimal($d->saldo_akhir) : '' }}
            @else
                <input type="text" name="jumlah[]" value="{{ formatAngkaDesimal($d->jumlah) }}"
                    style="text-align: right" class="form-control">
            @endif
        </td>
    </tr>
@endforeach
