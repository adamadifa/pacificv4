<div class="row mb-3">
    <div class="col">
        <table class="table">
            <tr>
                <th>Kode Bad Stok</th>
                <td class="text-end">{{ $badstok->kode_bs }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($badstok->tanggal) }}</td>
            </tr>
            <tr>
                <th>Asal Bad Stok</th>
                <td class="text-end">{{ $badstok->kode_asal_bs }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Produk</th>
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
