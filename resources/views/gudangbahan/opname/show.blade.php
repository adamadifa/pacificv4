<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode</th>
                <td>{{ $opname->kode_opname }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $nama_bulan[$opname->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $opname->tahun }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($opname->tanggal) }}</td>
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
                    <th>Qty Unit</th>
                    <th>Qty Berat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->nama_kategori }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->qty_unit) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->qty_berat) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
