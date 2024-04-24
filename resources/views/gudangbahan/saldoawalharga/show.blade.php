<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode</th>
                <td>{{ $saldo_awal->kode_saldo_awal }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $nama_bulan[$saldo_awal->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $saldo_awal->tahun }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($saldo_awal->tanggal) }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->nama_kategori }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
