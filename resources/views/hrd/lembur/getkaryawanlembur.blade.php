@foreach ($detail as $d)
    <tr>
        <td>{{ $d->nik }}</td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->kode_dept }}</td>
        <td>{{ $d->nama_group }}</td>
        <td>
            @if ($d->status === '0')
                <a href="#" class="delete" nik="{{ $d->nik }}">
                    <i class="ti ti-circle-minus text-danger"></i>
                </a>
            @endif


        </td>
    </tr>
@endforeach
