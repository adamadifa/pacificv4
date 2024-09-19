<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No.Bukti</th>
                <td>{{ $barangmasuk->no_bukti }}</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($barangmasuk->tanggal) }}</td>
            </tr>
            <tr>
                <th>Kode Supplier</th>
                <td>{{ $barangmasuk->kode_supplier }}</td>
            </tr>
            <tr>
                <th>Nama Supplier</th>
                <td>{{ $barangmasuk->nama_supplier }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th style="width: 20%">Keterangan</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>PENY</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    @php
                        $subtotal = $d->jumlah * $d->harga;
                        $total = $subtotal + $d->penyesuaian;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textCamelCase($d->nama_barang) }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
