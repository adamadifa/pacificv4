@foreach ($detailtemp as $d)
    <tr>
        <td>{{ $d->kode_produk }}</td>
        <td>{{ $d->nama_produk }}</td>
        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
        <td>
            <a href="#" id="{{ $d->id }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
        </td>
    </tr>
@endforeach
