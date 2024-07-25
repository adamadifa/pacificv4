<table class="table table-striped table-hover table-bordered">
    <thead class="table-dark">
        <tr>
            <th rowspan="2" class="align-middle">Cabang</th>
            <th colspan="{{ count($products) }}" class="text-center">Produk</th>
        </tr>
        <tr>
            @foreach ($products as $product)
                <th class="text-center">{{ $product->kode_produk }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rekappersediaancabang as $data)
            <tr>
                <td>{{ textUpperCase($data->nama_cabang) }}</td>
                @foreach ($products as $product)
                    <td class="text-center">{{ formatAngka(floor($data->{"saldo_$product->kode_produk"} / $product->isi_pcs_dus)) }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
