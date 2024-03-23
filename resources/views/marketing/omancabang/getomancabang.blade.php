@foreach ($detail as $d)
    <tr>

        <td class="text-center">
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            {{ $d->kode_produk }}
        </td>
        <td>{{ $d->nama_produk }}</td>
        <td>
            <input type="text" id="jmlm1" name="jmlm1[]" class="jmlm1 text-end form-oman number-separator"
                placeholder="0" autocomplete="false" aria-autocomplete="list" value="{{ formatAngka($d->minggu_1) }}">
        </td>
        <td>
            <input type="text" id="jmlm2" name="jmlm2[]" class="jmlm2 text-end form-oman number-separator"
                placeholder="0" autocomplete="false" aria-autocomplete="list" value="{{ formatAngka($d->minggu_2) }}" />
        </td>
        <td>
            <input type="text" id="jmlm3" name="jmlm3[]" class="jmlm3 text-end form-oman number-separator"
                placeholder="0" autocomplete="false" aria-autocomplete="list" value="{{ formatAngka($d->minggu_3) }}" />
        </td>
        <td>
            <input type="text" id="jmlm4" name="jmlm4[]" class="jmlm4 text-end form-oman number-separator"
                placeholder="0" autocomplete="false" aria-autocomplete="list" value="{{ formatAngka($d->minggu_4) }}" />
        </td>
        <td>
            <input type="text" id="subtotal" name="subtotal[]" class="subtotal text-end form-oman" placeholder="0"
                value="{{ formatAngka($d->total) }}" readonly />
        </td>
    </tr>
@endforeach
