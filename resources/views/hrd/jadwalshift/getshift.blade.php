@foreach ($shift as $d)
    <tr>
        <td>{{ $d->nik }}</td>
        <td>{{ formatName($d->nama_karyawan) }}</td>
        <td>{{ $d->nama_group }}</td>
    </tr>
@endforeach
