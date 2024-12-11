<table class="table table-bordered" id="tabelajuanprogram">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>No Pengajuan</th>
            <th>No. Dok</th>
            <th>Tanggal</th>
            <th>Program</th>
            <th>Cabang</th>
            <th>Periode</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ajuanprogramikatan as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->no_pengajuan }}</td>
                <td>{{ $d->nomor_dokumen }}</td>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ $d->nama_program }}</td>
                <td>{{ $d->nama_cabang }}</td>
                <td>{{ formatIndo($d->periode_dari) }}/{{ formatIndo($d->periode_sampai) }}</td>
                <td>
                    <a href="#" class="pilihajuan"><i class="ti ti-external-link"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#tabelajuanprogram').DataTable();
    });
</script>
