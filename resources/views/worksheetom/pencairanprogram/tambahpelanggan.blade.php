<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Kode Pelanggan</th>
            <th>Nama Pelanggan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $d)
            <tr>
                <td>{{ $d->kode_pelanggan }}</td>
                <td>{{ $d->nama_pelanggan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
