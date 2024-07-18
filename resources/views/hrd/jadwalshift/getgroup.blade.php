@foreach ($karyawan as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_karyawan }}</td>
        <td class="statusjadwal">
            @if (empty($d->kode_jadwal))
                <i class="ti ti-hourglass-empty text-warning"></i>
            @else
                <span class="badge bg-success">{{ $d->nama_jadwal }}</span>
            @endif
        </td>
        <td>
            <a href="#" nik="{{ $d->nik }}" class=" updateJadwal">
                @if (empty($d->kode_jadwal))
                    <i class="ti ti-plus"></i>
                @else
                    <i class="ti ti-refresh text-warning"></i>
                @endif
            </a>
        </td>
    </tr>
@endforeach
