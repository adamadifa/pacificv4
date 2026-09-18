@forelse ($karyawan as $d)
    <tr>
        <td class="text-center text-muted">{{ $loop->iteration }}</td>
        <td><span class="fw-semibold">{{ $d->nik }}</span></td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->nama_group }}</td>
        <td class="text-center">
            <a href="#" nik="{{ $d->nik }}" class="updateLibur" title="{{ empty($d->ceklibur) ? 'Tambahkan' : 'Batalkan' }}">
                @if (empty($d->ceklibur))
                    <i class="ti ti-plus text-primary fs-5"></i>
                @else
                    <i class="ti ti-circle-check-filled text-success fs-5"></i>
                @endif
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-3 text-muted">
            Tidak ada data karyawan yang ditemukan.
        </td>
    </tr>
@endforelse

