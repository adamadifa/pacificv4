@if ($detail->isEmpty())
    <tr>
        <td colspan="7">
            Data Belum Tersedia
        </td>
    </tr>
@else
    @foreach ($detail as $d)
        <tr>

            <td class="text-center">
                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                {{ $d->kode_produk }}
            </td>
            <td>{{ $d->nama_produk }}</td>
            <td class="text-center">
                <a href="#" class="editOmancabang" kode_produk="{{ $d->kode_produk }}" minggu_ke="1"
                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">{{ formatAngka($d->minggu_1) }}</a>
                <input type="hidden" id="jmlm1" name="jmlm1[]" class="jmlm1 text-end form-oman number-separator"
                    placeholder="0" autocomplete="false" aria-autocomplete="list"
                    value="{{ formatAngka($d->minggu_1) }}">
            </td>
            <td class="text-center">
                <a href="#" class="editOmancabang" kode_produk="{{ $d->kode_produk }}" minggu_ke="2"
                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">{{ formatAngka($d->minggu_2) }}</a>
                <input type="hidden" id="jmlm2" name="jmlm2[]" class="jmlm2 text-end form-oman number-separator"
                    placeholder="0" autocomplete="false" aria-autocomplete="list"
                    value="{{ formatAngka($d->minggu_2) }}" />
            </td class="text-center">
            <td class="text-center">
                <a href="#" class="editOmancabang" kode_produk="{{ $d->kode_produk }}" minggu_ke="3"
                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">{{ formatAngka($d->minggu_3) }}</a>
                <input type="hidden" id="jmlm3" name="jmlm3[]" class="jmlm3 text-end form-oman number-separator"
                    placeholder="0" autocomplete="false" aria-autocomplete="list"
                    value="{{ formatAngka($d->minggu_3) }}" />
            </td>
            <td class="text-center">
                <a href="#" class="editOmancabang" kode_produk="{{ $d->kode_produk }}" minggu_ke="4"
                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">{{ formatAngka($d->minggu_4) }}</a>
                <input type="hidden" id="jmlm4" name="jmlm4[]" class="jmlm4 text-end form-oman number-separator"
                    placeholder="0" autocomplete="false" aria-autocomplete="list"
                    value="{{ formatAngka($d->minggu_4) }}" />
            </td>
            <td class="text-center">
                {{ formatAngka($d->total) }}
                <input type="hidden" id="subtotal" name="subtotal[]" class="subtotal text-end form-oman"
                    placeholder="0" value="{{ formatAngka($d->total) }}" readonly />
            </td>
        </tr>
    @endforeach
@endif
