@foreach ($detailharilibur as $d)
    <tr>
        <td>{{ $d->nik }}</td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->kode_dept }}</td>
    </tr>
@endforeach
