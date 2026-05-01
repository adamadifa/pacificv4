@foreach ($penjualan as $d)
    <tr>
        <td>{{ $d->no_faktur }}</td>
        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td>{{ $d->nama_salesman }}</td>
        <td>
            <a href="#" class="pilihFaktur btn btn-primary btn-sm" 
               no_faktur="{{ $d->no_faktur }}" 
               nama_pelanggan="{{ $d->nama_pelanggan }}"
               nama_salesman="{{ $d->nama_salesman }}">
                <i class="ti ti-check"></i> Pilih
            </a>
        </td>
    </tr>
@endforeach
