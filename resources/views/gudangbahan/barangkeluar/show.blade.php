<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Bukti</th>
                <td>{{ $barangkeluar->no_bukti }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($barangkeluar->tanggal) }}</td>
            </tr>
            <tr>
                <th>Jenis Pengeluaran</th>
                <td>{{ $jenis_pengeluaran[$barangkeluar->kode_jenis_pengeluaran] }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>
                    @if ($barangkeluar->kode_jenis_pengeluaran == 'CBG')
                        {{ textUpperCase($barangkeluar->nama_cabang) }}
                    @elseif ($barangkeluar->kode_jenis_pengeluaran == 'PRD')
                        Unit {{ $barangkeluar->keterangan }}
                    @else
                        {{ $barangkeluar->keterangan }}
                    @endif
                </td>
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
                    <th style="width: 30%">Keterangan</th>
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
