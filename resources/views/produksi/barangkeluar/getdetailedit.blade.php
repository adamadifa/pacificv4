@foreach ($detailtemp as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->kode_barang_produksi }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->satuan }}</td>
        <td>{{ $d->keterangan }}</td>
        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
        <td class="text-end">{{ formatAngkaDesimal($d->jumlah_berat) }}</td>
        <td>
            <div class="d-flex">
                <a href="#" id="{{ Crypt::encrypt($d->id) }}" class="edit"><i
                        class="ti ti-edit text-success me-1"></i></a>
                <a href="#" id="{{ $d->id }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
            </div>
        </td>
    </tr>
@endforeach
