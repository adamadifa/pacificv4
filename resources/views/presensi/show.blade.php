<div class="row">
    <div class="col-4 text-center">
        @if (!empty($presensi->foto_in))
        @else
            <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
        @endif
    </div>
    <div class="col-8">
        <table class="table">
            <tr>
                <th>NIK</th>
                <td>{{ $presensi->nik }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $presensi->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($presensi->tanggal) }}</td>
            </tr>
            <tr>
                <th>Jam Masuk</th>
                <td>{{ date('H:i', strtotime($presensi->jam_in)) }}</td>
            </tr>
        </table>

    </div>
</div>
