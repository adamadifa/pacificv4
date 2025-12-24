<div class="row mb-3">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Bukti</th>
                <td class="text-end">{{ $po->no_bukti }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($po->tanggal) }}</td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td class="text-end">{{ $po->nama_supplier }}</td>
            </tr>
            <tr>
                <th>Asal Ajuan</th>
                <td class="text-end">{{ $po->kategori_perusahaan }}</td>
        </table>

    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered  table-hover">
            <thead class="table-dark">
                <tr>
                    <th colspan="6">Data Pembelian</th>
                </tr>
                <tr>
                    <th style="width: 10%">Kode</th>
                    <th style="width: 25%">Nama Barang</th>
                    <th style="width: 20%">Keterangan</th>
                    <th style="width: 10%">Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_pembelian = 0;
                @endphp
                @foreach ($detail as $d)
                    @php
                        $subtotal = $d->jumlah * $d->harga;
                        $total = $subtotal;
                        $total_pembelian += $total;
                        $bg = '';
                    @endphp
                    <tr class="{{ $bg }}">
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textCamelCase($d->nama_barang) }}</td>
                        <td>{{ textCamelCase($d->keterangan) }}</td>
                        <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-end">{{ formatAngkaDesimal($total_pembelian) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
