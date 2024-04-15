<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Repack</th>
                <td>{{ $repack->no_mutasi }}</td>
            </tr>

            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($repack->tanggal) }}</td>
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
