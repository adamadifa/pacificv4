<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Barang Keluar Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}"> --}}
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
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG KELUAR GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if (!empty($barang))
            <h4>KODE BARANG : {{ $barang->kode_barang }}</h4>
            <h4>NAMA BARANG : {{ $barang->nama_barang }}</h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>

                    <th rowspan="2">NO</th>
                    <th rowspan="2">TANGGAL</th>
                    <th rowspan="2">NO. BUKTI</th>
                    <th rowspan="2">KODE</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th rowspan="2">KETERANGAN</th>
                    <th rowspan="2">ASAL BARANG</th>
                    <th colspan="3" class="red">QTY</th>
                </tr>
                <tr>
                    <th class="red">UNIT</th>
                    <th class="red">BERAT</th>
                    <th class="red">LEBIH</th>
                </tr>
            </thead>
            <tbody>


                @php
                    $total_qty_unit = 0;
                    $total_qty_berat = 0;
                    $total_qty_lebih = 0;
                @endphp
                @foreach ($barangkeluar as $d)
                    @php
                        $total_qty_unit += $d->qty_unit;
                        $total_qty_berat += $d->qty_berat;
                        $total_qty_lebih += $d->qty_lebih;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td>{{ $jenis_pengeluaran[$d->kode_jenis_pengeluaran] }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_berat) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_lebih) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="8">TOTAL</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_unit) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_berat) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_lebih) }}</th>
            </tfoot>
        </table>
    </div>
</body>
