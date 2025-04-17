<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive">
            <table class="table  table-hover table-bordered">
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
                    <tr class="bg-success text-white">
                        <td>GUDANG</td>
                        @foreach ($products as $product)
                            <td class="text-center">
                                {{ formatAngka($rekapgudang->{"saldoakhir_$product->kode_produk"}) }}</td>
                        @endforeach
                    </tr>
                </tbody>
                <tbody id="report">
                    @foreach ($rekappersediaancabang as $data)
                        <tr class="master">
                            <td>{{ textUpperCase($data->nama_cabang) }}</td>
                            @foreach ($products as $product)
                                @php
                                    ${"saldo_akhir_$product->kode_produk"} =
                                        ($data->{"saldo_$product->kode_produk"} +
                                            $data->{"mutasi_$product->kode_produk"} -
                                            $data->{"ambil_$product->kode_produk"} +
                                            $data->{"kembali_$product->kode_produk"}) /
                                        $product->isi_pcs_dus;

                                    ${"saldo_akhir_$product->kode_produk"} =
                                        ${"saldo_akhir_$product->kode_produk"} < 0
                                            ? 0
                                            : ${"saldo_akhir_$product->kode_produk"};

                                    //Jika Saldo Akhir <= Buffer Stok
                                    if (
                                        ${"saldo_akhir_$product->kode_produk"} <=
                                        $data->{"buffer_$product->kode_produk"}
                                    ) {
                                        $color = 'bg-danger text-white opacity-60';
                                    } elseif (
                                        ${"saldo_akhir_$product->kode_produk"} >= $data->{"max_$product->kode_produk"}
                                    ) {
                                        $color = 'bg-info text-white';
                                    } else {
                                        $color = '';
                                    }
                                @endphp
                                <td class="text-center {{ $color }}">
                                    {{ formatAngka(floor(${"saldo_akhir_$product->kode_produk"})) }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-warning text-white">
                            <td>BUFFER STOK</td>
                            @foreach ($products as $product)
                                <td class="text-center">{{ formatAngka($data->{"buffer_$product->kode_produk"}) }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-warning text-white">
                            <td>MAX STOK</td>
                            @foreach ($products as $product)
                                <td class="text-center">{{ formatAngka($data->{"max_$product->kode_produk"}) }}</td>
                            @endforeach
                        </tr>

                        <tr class="bg-warning text-white">
                            <td>SELL OUT</td>
                            @foreach ($products as $product)
                                <td class="text-center">
                                    {{ formatAngka(floor($data->{"penjualan_$product->kode_produk"} / $product->isi_pcs_dus)) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<script>
    $("#report tr:not(.master)").hide();
    $("#report tr:first-child").show();
    $("#report tr.master").click(function() {
        $(this).next("tr").toggle();
        $(this).next("tr").next("tr").toggle();
        $(this).next("tr").next("tr").next("tr").toggle();

    });
</script>
