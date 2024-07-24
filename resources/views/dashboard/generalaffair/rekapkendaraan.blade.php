<table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>Cabang</th>
            <th class="text-center">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekapkendaraan as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                <td class="text-center">{{ $d->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
