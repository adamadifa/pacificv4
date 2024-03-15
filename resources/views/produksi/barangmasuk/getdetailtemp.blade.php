@foreach ($detailtemp as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->kode_barang_produksi }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->keterangan }}</td>
        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
        <td>
            <a href="#" id="{{ $d->id }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
        </td>
    </tr>
@endforeach
