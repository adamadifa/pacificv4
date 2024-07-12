<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No.Bukti</th>
                <td class="text-end">{{ $barangmasuk->no_bukti }}</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($barangmasuk->tanggal) }}</td>
            </tr>
            <tr>
                <th>Kode Supplier</th>
                <td class="text-end">{{ $barangmasuk->kode_supplier }}</td>
            </tr>
            <tr>
                <th>Nama Supplier</th>
                <td class="text-end">{{ $barangmasuk->nama_supplier }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td>{{ formatAngkaDesimal($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
