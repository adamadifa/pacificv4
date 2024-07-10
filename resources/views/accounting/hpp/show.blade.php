<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>Kode HPP</th>
                <td class="text-end">{{ $hpp->kode_hpp }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td class="text-end">{{ $namabulan[$hpp->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td class="text-end">{{ $hpp->tahun }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->harga_hpp) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
