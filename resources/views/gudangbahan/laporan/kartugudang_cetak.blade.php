<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kartu Gudang {{ $barang->nama_barang }}{{ date('Y-m-d H:i:s') }}</title>
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
            KARTU GUDANG<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>{{ $barang->kode_barang }} {{ textUpperCase($barang->nama_barang) }}</h4>
    </div>
    <div class="content">
        @php
            $satuan = $barang->satuan;
            $satuan_bahan = ['KG', 'LITER'];

            $saldo_akhir_unit = $saldo_awal != null ? $saldo_awal->qty_unit : 0;
            if (in_array($satuan, $satuan_bahan)) {
                $saldo_akhir_berat = $saldo_awal != null ? $saldo_awal->qty_berat : 0;
            } else {
                $saldo_akhir_berat = $saldo_awal != null ? $saldo_awal->qty_unit : 0;
            }
        @endphp
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">TANGGAL</th>
                    <th colspan="2">UNIT</th>
                    <th>SALDO</th>
                    <th colspan="3" class="green">MASUK</th>
                    <th colspan="6" class="red">KELUAR</th>
                    <th>SALDO</th>
                </tr>
                <tr>
                    <th class="green">IN</th>
                    <th class="red">OUT</th>
                    <th class="right">{{ $saldo_awal != null ? formatAngkaDesimal($saldo_akhir_unit) : 0 }}</th>

                    <th class="green">PEMBELIAN</th>
                    <th class="green">RETUR PENGGANTI</th>
                    <th class="green">LAINNYA</th>

                    <th class="red">PRODUKSI</th>
                    <th class="red">SEASONING</th>
                    <th class="red">PDQC</th>
                    <th class="red">SUSUT</th>
                    <th class="red">CABANG</th>
                    <th class="red">LAINNYA</th>
                    <th class="right">{{ $saldo_awal != null ? formatAngkaDesimal($saldo_akhir_berat) : 0 }}</th>
                </tr>
            </thead>
            <tbody>
                @php

                    $total_qty_unit_masuk = 0;
                    $total_qty_unit_keluar = 0;

                    $total_qty_berat_masuk = 0;
                    $total_qty_berat_keluar = 0;

                    $total_qty_pembelian = 0;
                    $total_qty_returpengganti = 0;
                    $total_qty_lainnya = 0;

                    $total_qty_produksi = 0;
                    $total_qty_seasoning = 0;
                    $total_qty_pdqc = 0;
                    $total_qty_susut = 0;
                    $total_qty_lainnya_keluar = 0;
                    $total_qty_cabang = 0;

                @endphp
                @foreach ($kartu_gudang as $d)
                    @php
                        $saldo_akhir_unit += $d['qty_unit_masuk'] - $d['qty_unit_keluar'];

                        if (in_array($satuan, $satuan_bahan)) {
                            $saldo_akhir_berat += $d['qty_berat_masuk'] - $d['qty_berat_keluar'];
                            $qty_pembelian = $d['qty_berat_pembelian'];
                            $qty_lainnya = $d['qty_berat_lainnya'];
                            $qty_returpengganti = $d['qty_berat_returpengganti'];

                            $qty_produksi = $d['qty_berat_produksi'];
                            $qty_seasoning = $d['qty_berat_seasoning'];
                            $qty_pdqc = $d['qty_berat_pdqc'];
                            $qty_susut = $d['qty_berat_susut'];
                            $qty_lainnya_keluar = $d['qty_berat_lainnya_keluar'];
                            $qty_cabang = $d['qty_berat_cabang'];
                        } else {
                            $saldo_akhir_berat += $d['qty_unit_masuk'] - $d['qty_unit_keluar'];
                            $qty_pembelian = $d['qty_unit_pembelian'];
                            $qty_lainnya = $d['qty_unit_lainnya'];
                            $qty_returpengganti = $d['qty_unit_returpengganti'];

                            $qty_produksi = $d['qty_unit_produksi'];
                            $qty_seasoning = $d['qty_unit_seasoning'];
                            $qty_pdqc = $d['qty_unit_pdqc'];
                            $qty_susut = $d['qty_unit_susut'];
                            $qty_lainnya_keluar = $d['qty_unit_lainnya_keluar'];
                            $qty_cabang = $d['qty_unit_cabang'];
                        }

                        $total_qty_unit_masuk += $d['qty_unit_masuk'];
                        $total_qty_unit_keluar += $d['qty_unit_keluar'];

                        $total_qty_pembelian += $qty_pembelian;
                        $total_qty_returpengganti += $qty_returpengganti;
                        $total_qty_lainnya += $qty_lainnya;

                        $total_qty_produksi += $qty_produksi;
                        $total_qty_seasoning += $qty_seasoning;
                        $total_qty_pdqc += $qty_pdqc;
                        $total_qty_susut += $qty_susut;
                        $total_qty_cabang += $qty_cabang;
                        $total_qty_lainnya_keluar += $qty_lainnya_keluar;
                    @endphp
                    <tr>
                        <td>{{ DateToIndo($d['tanggal']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_unit_masuk']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_unit_keluar']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($saldo_akhir_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_pembelian) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_returpengganti) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_lainnya) }}</td>


                        <td class="right">{{ formatAngkaDesimal($qty_produksi) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_seasoning) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_pdqc) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_susut) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_cabang) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_lainnya_keluar) }}</td>
                        <td class="right">{{ formatAngkaDesimal($saldo_akhir_berat) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_unit_masuk) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_unit_keluar) }}</th>
                    <th class="right">{{ formatAngkaDesimal($saldo_akhir_unit) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_pembelian) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_returpengganti) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_lainnya) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_produksi) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_seasoning) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_pdqc) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_susut) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_cabang) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_lainnya_keluar) }}</th>
                    <th class="right">{{ formatAngkaDesimal($saldo_akhir_berat) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
