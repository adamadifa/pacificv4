<div class="row">
    <div class="col">
        <table class="table table-striped">
            <tr>
                <th class="py-2">Kode HPP</th>
                <td class="text-end py-2"><span class="fw-bold text-primary">{{ $hpp->kode_hpp }}</span></td>
            </tr>
            <tr>
                <th class="py-2">Bulan</th>
                <td class="text-end py-2">{{ $namabulan[$hpp->bulan] }}</td>
            </tr>
            <tr>
                <th class="py-2">Tahun</th>
                <td class="text-end py-2">{{ $hpp->tahun }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr style="background-color: #002e65;">
                        <th class="text-white py-2">KODE PRODUK</th>
                        <th class="text-white py-2">NAMA PRODUK</th>
                        <th class="text-white py-2 text-end">HARGA</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($detail as $d)
                        <tr>
                            <td class="py-2">{{ $d->kode_produk }}</td>
                            <td class="py-2">{{ $d->nama_produk }}</td>
                            <td class="py-2 text-end fw-bold text-primary">{{ formatAngka($d->harga_hpp) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
