<style>
    .table-modal {
        overflow: hidden;
    }
</style>
<div class="table-modal">

    <table class="table table-bordered table-hover table-stripped" style="width: 150%">
        <thead class="table-dark">
            <tr>
                <th rowspan="2">Kode</th>
                <th rowspan="2" style="width: 15%">Nama Produk</th>
                <th colspan="3" class="text-center bg-success">Retur</th>
                <th colspan="3" class="text-center bg-success">Hutang Kirim</th>
                <th colspan="3" class="text-center bg-danger">Penjualan</th>
                <th colspan="3" class="text-center bg-danger">Pelunasan<br> Hutang Kirim</th>
                <th colspan="3" class="text-center bg-danger">Promo</th>
                <th colspan="3" class="text-center bg-danger">Ganti Barang</th>
            </tr>
            <tr class="text-center">
                <th class="bg-success">Dus</th>
                <th class="bg-success">Pack</th>
                <th class="bg-success">Pcs</th>

                <th class="bg-success">Dus</th>
                <th class="bg-success">Pack</th>
                <th class="bg-success">Pcs</th>

                <th class="bg-danger">Dus</th>
                <th class="bg-danger">Pack</th>
                <th class="bg-danger">Pcs</th>

                <th class="bg-danger">Dus</th>
                <th class="bg-danger">Pack</th>
                <th class="bg-danger">Pcs</th>

                <th class="bg-danger">Dus</th>
                <th class="bg-danger">Pack</th>
                <th class="bg-danger">Pcs</th>

                <th class="bg-danger">Dus</th>
                <th class="bg-danger">Pack</th>
                <th class="bg-danger">Pcs</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasi_dpb as $d)
                @php
                    //Retur
                    $retur = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_retur));
                    $retur_dus = $retur[0];
                    $retur_pack = $retur[1];
                    $retur_pcs = $retur[2];

                    //Hutang Kirim
                    $hutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_hutangkirim));
                    $hutangkirim_dus = $hutangkirim[0];
                    $hutangkirim_pack = $hutangkirim[1];
                    $hutangkirim_pcs = $hutangkirim[2];

                    //Penjualan
                    $penjualan = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_penjualan));
                    $penjualan_dus = $penjualan[0];
                    $penjualan_pack = $penjualan[1];
                    $penjualan_pcs = $penjualan[2];

                    //Pelunasan Hutang Kirim
                    $pelunasanhutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_pelunasanhutangkirim));
                    $pelunasanhutangkirim_dus = $pelunasanhutangkirim[0];
                    $pelunasanhutangkirim_pack = $pelunasanhutangkirim[1];
                    $pelunasanhutangkirim_pcs = $pelunasanhutangkirim[2];

                    //Promosi
                    $promosi = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_promosi));
                    $promosi_dus = $promosi[0];
                    $promosi_pack = $promosi[1];
                    $promosi_pcs = $promosi[2];

                    //Ganti Barang
                    $gantibarang = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_gantibarang));
                    $gantibarang_dus = $gantibarang[0];
                    $gantibarang_pack = $gantibarang[1];
                    $gantibarang_pcs = $gantibarang[2];

                @endphp
                <tr class="text-end">
                    <td class="text-start">{{ $d->kode_produk }}</td>
                    <td class="text-start">{{ $d->nama_produk }}</td>
                    <td style="background-color:#28c76f1a">{{ formatAngka($retur_dus) }}</td>
                    <td style="background-color:#28c76f1a">{{ formatAngka($retur_pack) }}</td>
                    <td style="background-color:#28c76f1a">{{ formatAngka($retur_pcs) }}</td>

                    <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_dus) }}</td>
                    <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_pack) }}</td>
                    <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_pcs) }}</td>

                    <td style="background-color: #ea54552e">{{ formatAngka($penjualan_dus) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($penjualan_pack) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($penjualan_pcs) }}</td>

                    <td style="background-color: #ea54552e">
                        {{ formatAngka($pelunasanhutangkirim_dus) }}</td>
                    <td style="background-color: #ea54552e">
                        {{ formatAngka($pelunasanhutangkirim_pack) }}</td>
                    <td style="background-color: #ea54552e">
                        {{ formatAngka($pelunasanhutangkirim_pcs) }}</td>

                    <td style="background-color: #ea54552e">{{ formatAngka($promosi_dus) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($promosi_pack) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($promosi_pcs) }}</td>

                    <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_dus) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_pack) }}</td>
                    <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_pcs) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeHead': false,
            'columnNum': 2,
        });
    })
</script>
