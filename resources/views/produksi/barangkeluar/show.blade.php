<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>No. Bukti</th>
                <td>{{ $barangkeluarproduksi->no_bukti }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($barangkeluarproduksi->tanggal) }}</td>
            </tr>
            <tr>
                <th>Jenis Pengeluaran</th>
                <td>{{ $jenis_pengeluaran[$barangkeluarproduksi->kode_jenis_pengeluaran] }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Berat</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td style="width:1%">{{ $loop->iteration }}</td>
                        <td style="width:13%">{{ $d->kode_barang_produksi }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->jumlah_berat) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
