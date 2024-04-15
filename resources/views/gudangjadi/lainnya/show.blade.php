<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Mutasi</th>
                <td>{{ $lainnya->no_mutasi }}</td>
            </tr>

            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($lainnya->tanggal) }}</td>
            </tr>
            <tr>
                <th>IN / OUT</th>
                <td>
                    @if ($lainnya->in_out == 'I')
                        <span class="badge bg-success">IN</span>
                    @else
                        <span class="badge bg-danger">OUT</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $lainnya->keterangan }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th style="width:50%">Nama Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
