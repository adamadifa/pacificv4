<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No.Bukti</th>
                <td>{{ $barangkeluar->no_bukti }}</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($barangkeluar->tanggal) }}</td>
            </tr>
            <tr>
                <th>Jenis Pengeluaran</th>
                <td>{{ $jenis_pengeluaran[$barangkeluar->kode_jenis_pengeluaran] }}</td>
            </tr>
            @if ($barangkeluar->kode_jenis_pengeluaran == 'CBG')
                <tr>
                    <th>Cabang</th>
                    <td> {{ textUpperCase($barangkeluar->nama_cabang) }}</td>
                </tr>
            @endif
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
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Cabang</th>
                    <th style="width: 20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textCamelCase($d->nama_barang) }}</td>
                        <td>{{ textUpperCase($d->satuan) }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        <td>{{ textUpperCase($d->nama_cabang) }}</td>
                        <td>{{ $d->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
