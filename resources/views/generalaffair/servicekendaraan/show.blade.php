<div class="row mb-3">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Invoice</th>
                <td class="text-end">{{ $servicekendaraan->no_invoice }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($servicekendaraan->tanggal) }}</td>
            </tr>
            <tr>
                <th>No. Polisi</th>
                <td class="text-end">{{ $servicekendaraan->no_polisi }}</td>
            </tr>
            <tr>
                <th>Kendaraan</th>
                <td class="text-end">{{ $servicekendaraan->merek }} {{ $servicekendaraan->tipe }} {{ $servicekendaraan->tipe_kendaraan }}</td>
            </tr>
            <tr>
                <th>Bengkel</th>
                <td class="text-end">{{ $servicekendaraan->nama_bengkel }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td class="text-end">{{ textupperCase($servicekendaraan->nama_cabang) }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandtotal = 0;
                @endphp
                @foreach ($detail as $d)
                    @php
                        $total = $d->jumlah * $d->harga;
                        $grandtotal += $total;
                    @endphp
                    <tr>
                        <td>{{ $d->kode_item }}</td>
                        <td>{{ $d->nama_item }}</td>
                        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        <td class="text-end">{{ formatRupiah($d->harga) }}</td>
                        <td class="text-end">{{ formatRupiah($total) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="4" class="text-end">Total</td>
                    <td class="text-end">{{ formatRupiah($grandtotal) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
