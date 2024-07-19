@foreach ($gantishift as $d)
    <tr>
        <td>{{ formatIndo($d->tanggal) }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_karyawan }}</td>
        <td>{{ $d->nama_jadwal }}</td>
        <td>
            <a href="#" class="deletegs" kode_gs="{{ $d->kode_gs }}">
                <i class="ti ti-trash text-danger"></i>
            </a>
        </td>
    </tr>
@endforeach
