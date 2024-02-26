<table class="table">
    <tr>
        <th>No. Mutasi</th>
        <td>{{ $bpbj->no_mutasi }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ DateToIndo($bpbj->tanggal_mutasi) }}</td>
    </tr>
</table>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Shift</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $d)
            <tr>
                <td>{{ $d->kode_produk }}</td>
                <td>{{ $d->nama_produk }}</td>
                <td>{{ $d->shift }}</td>
                <td class="text-end">{{ formatRupiah($d->jumlah) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
