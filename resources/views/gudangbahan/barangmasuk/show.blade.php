<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Bukti</th>
                <td>{{ $barangmasuk->no_bukti }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($barangmasuk->tanggal) }}</td>
            </tr>
            <tr>
                <th>Asal Barang</th>
                <td>{{ $asal_barang[$barangmasuk->kode_asal_barang] }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Qty Unit</th>
                    <th>Qty Berat</th>
                    <th>Qty Lebih</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textUpperCase($d->nama_barang) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->qty_unit) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->qty_berat) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->qty_lebih) }}</td>
                        <td>{{ $d->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
