<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Permintaan</th>
                <td>{{ $pk->no_permintaan }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($pk->tanggal) }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ textUpperCase($pk->nama_cabang) }}</td>
            </tr>
            @if (!empty($pk->kode_salesman))
                <tr>
                    <th>Salesman</th>
                    <td>{{ $pk->nama_salesman }}</td>
                </tr>
            @endif
            <tr>
                <th>Keterangan</th>
                <td>{{ $pk->keterangan }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
