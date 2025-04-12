<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}"> --}}
</head>
<style>
    .datatable3 {
        /* border: 1px solid #080909; */
        border-collapse: collapse;

        /*float:left; */
    }

    .datatable3 td {
        border: 1px solid #000000;
        padding: 6px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        border-width: 1px;
    }

    .datatable3 th {
        border: 1px solid #050506;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #024a75;
        color: white;
        text-transform: uppercase;
        border-width: 1px;
    }

    .datatable4 {
        border: 0px solid #161616;
        border-collapse: collapse;
        font-size: 14px;
    }

    .datatable4 td {
        border: 0px solid #000000;
        padding: 5px;
    }


    .datatable5 {
        border: 1px solid #161616;
        border-collapse: collapse;
        font-size: 12px;
        width: 100%
    }

    .datatable5 td {
        border: 1px solid #000000;
        padding: 5px;
        font-size: 11px;
    }

    .datatable5 th {
        border: 1px solid #4d4d4d;
        font-weight: bold;
        text-align: left;
        padding: 4px;
        text-align: center;
        font-size: 12px;
        background-color: #d4d3d3cf
    }

    .datatable6 {
        border: 1px solid #161616;
        border-collapse: collapse;
        font-size: 12px;
        width: 100%
    }

    .datatable6 td {
        border: 1px solid #000000;
        padding: 3px;
    }

    .datatable6 th {
        border: 1px solid #4d4d4d;
        font-weight: bold;
        text-align: left;
        padding: 3px;
        text-align: center;
        font-size: 12px;
        background-color: #d4d3d3cf
    }


    .datatable7 {
        border: 0px solid #161616;
        border-collapse: collapse;
        font-size: 12px;
        width: 100%
    }

    .datatable7 td {
        border: 0px solid #000000;
        padding: 3px;
    }


    .datatable8 {
        border: 1px solid #080909;
        border-collapse: collapse;

        /*float:left; */
    }

    .datatable8 td {
        border: 1px solid #000000;
        padding: 4px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        border-width: 1px;
    }

    .datatable8 th {
        border: 1px solid #050506;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        /* background-color: #024a75; */
        /* color: white; */
        text-transform: uppercase;
        border-width: 1px;
    }

    h4 {
        font-family: Arial, Helvetica, sans-serif;
        line-height: 2px;
    }

    .right {
        text-align: right !important;
    }

    .left {
        text-align: left !important;
    }


    .center {
        text-align: center !important;
    }

    .green {
        background-color: #28a745 !important;
        color: white !important;
    }

    .blue {
        background-color: #1e67c6 !important;
        color: white !important;
    }

    .red {
        background-color: #c7473a !important;
        color: white !important;
    }

    .subtotal {
        border: 1px solid #050506;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #024a75;
        color: white;
        text-transform: uppercase;
    }

    .header {
        margin-top: 30px;
    }
</style>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG PERSEDIAAN GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>KATEGORI {{ $kategori->nama_kategori }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="3">NO</th>
                    <th rowspan="3">KODE</th>
                    <th rowspan="3">NAMA BARANG</th>
                    <th rowspan="3">SATUAN</th>
                </tr>
                <tr>
                    <th colspan="2">SALDO AWAL</th>
                    <th colspan="3" class="green">PEMASUKAN</th>
                    <th colspan="6" class="red">PENGELUARAN</th>
                    <th colspan="2">SALDO AKHIR</th>
                    <th colspan="2">OPNAME</th>
                    <th colspan="2">SELISIH</th>
                </tr>
                <tr>
                    <th>UNIT</th>
                    <th>BERAT</th>
                    <th class="green">PEMBELIAN</th>
                    <th class="green">LAINNYA</th>
                    <th class="green">RETUR PENGGANTI</th>
                    <th class="red">PRODUKSI</th>
                    <th class="red">SEASONING</th>
                    <th class="red">PDQC</th>
                    <th class="red">SUSUT</th>
                    <th class="red">CABANG</th>
                    <th class="red">LAINNYA</th>
                    <th>UNIT</th>
                    <th>BERAT</th>
                    <th>UNIT</th>
                    <th>BERAT</th>
                    <th>UNIT</th>
                    <th>BERAT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_saldo_awal_qty_unit = 0;
                    $total_saldo_awal_qty_berat = 0;
                    $total_bm_qty_pembelian = 0;
                    $total_bm_qty_lainnya = 0;
                    $total_bm_qty_returpengganti = 0;
                    $total_bk_qty_produksi = 0;
                    $total_bk_qty_seasoning = 0;
                    $total_bk_qty_pdqc = 0;
                    $total_bk_qty_susut = 0;
                    $total_bk_qty_cabang = 0;
                    $total_bk_qty_lainnya = 0;
                    $total_saldo_akhir_qty_unit = 0;
                    $total_saldo_akhir_qty_berat = 0;
                    $total_opname_qty_unit = 0;
                    $total_opname_qty_berat = 0;

                @endphp
                @foreach ($persediaan as $d)
                    @php
                        $satuan = $d->kode_barang == 'BB-15' ? 'KG' : $d->satuan;
                        $bm_qty_pembelian = $satuan != 'KG' ? $d->bm_qty_unit_pembelian : $d->bm_qty_berat_pembelian;
                        $bm_qty_lainnya = $satuan != 'KG' ? $d->bm_qty_unit_lainnya : $d->bm_qty_berat_lainnya;
                        $bm_qty_returpengganti =
                            $satuan != 'KG' ? $d->bm_qty_unit_returpengganti : $d->bm_qty_berat_returpengganti;
                        $bk_qty_produksi = $satuan != 'KG' ? $d->bk_qty_unit_produksi : $d->bk_qty_berat_produksi;
                        $bk_qty_seasoning = $satuan != 'KG' ? $d->bk_qty_unit_seasoning : $d->bk_qty_berat_seasoning;
                        $bk_qty_pdqc = $satuan != 'KG' ? $d->bk_qty_unit_pdqc : $d->bk_qty_berat_pdqc;
                        $bk_qty_susut = $satuan != 'KG' ? $d->bk_qty_unit_susut : $d->bk_qty_berat_susut;
                        $bk_qty_cabang = $satuan != 'KG' ? $d->bk_qty_unit_cabang : $d->bk_qty_berat_cabang;
                        $bk_qty_lainnya = $satuan != 'KG' ? $d->bk_qty_unit_lainnya : $d->bk_qty_berat_lainnya;
                        $selisih_unit = ROUND($d->opname_qty_unit, 2) - ROUND($d->saldo_akhir_unit, 2);
                        $selisih_berat = ROUND($d->opname_qty_berat, 2) - ROUND($d->saldo_akhir_berat, 2);

                        $total_saldo_awal_qty_unit += $d->saldo_awal_qty_unit;
                        $total_saldo_awal_qty_berat += $d->saldo_awal_qty_berat;
                        $total_bm_qty_pembelian += $bm_qty_pembelian;
                        $total_bm_qty_lainnya += $bm_qty_lainnya;
                        $total_bm_qty_returpengganti += $bm_qty_returpengganti;
                        $total_bk_qty_produksi += $bk_qty_produksi;
                        $total_bk_qty_seasoning += $bk_qty_seasoning;
                        $total_bk_qty_pdqc += $bk_qty_pdqc;
                        $total_bk_qty_susut += $bk_qty_susut;
                        $total_bk_qty_cabang += $bk_qty_cabang;
                        $total_bk_qty_lainnya += $bk_qty_lainnya;
                        $total_saldo_akhir_qty_unit += $d->saldo_akhir_unit;
                        $total_saldo_akhir_qty_berat += $d->saldo_akhir_berat;
                        $total_opname_qty_unit += $d->opname_qty_unit;
                        $total_opname_qty_berat += $d->opname_qty_berat;

                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->saldo_awal_qty_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->saldo_awal_qty_berat) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bm_qty_pembelian) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bm_qty_lainnya) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bm_qty_returpengganti) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_produksi) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_seasoning) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_pdqc) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_susut) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_cabang) }}</td>
                        <td class="right">{{ formatAngkaDesimal($bk_qty_lainnya) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->saldo_akhir_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->saldo_akhir_berat) }} </td>
                        <td class="right">{{ formatAngkaDesimal($d->opname_qty_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->opname_qty_berat) }}</td>
                        <td class="right">{{ formatAngkaDesimal($selisih_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($selisih_berat) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total_saldo_awal_qty_unit) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_saldo_awal_qty_berat) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bm_qty_pembelian) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bm_qty_lainnya) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bm_qty_returpengganti) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_produksi) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_seasoning) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_pdqc) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_susut) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_cabang) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_bk_qty_lainnya) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_saldo_akhir_qty_unit) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_saldo_akhir_qty_berat) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_opname_qty_unit) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_opname_qty_berat) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
