@forelse ($detailharilibur as $d)
    <tr class="row-karyawan">
        <td class="text-center text-muted">{{ $loop->iteration }}</td>
        <td><span class="fw-semibold">{{ $d->nik }}</span></td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->kode_dept }}</td>
        <td>{{ $d->nama_group }}</td>
        <td class="text-center">
            @can('harilibur.setharilibur')
                <a href="#" class="text-danger delete" nik="{{ $d->nik }}" nama="{{ formatName2($d->nama_karyawan) }}" title="Hapus">
                    <i class="ti ti-trash"></i>
                </a>
            @endcan
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-3 text-muted">
            Belum ada karyawan yang ditambahkan ke hari libur ini.
        </td>
    </tr>
@endforelse


