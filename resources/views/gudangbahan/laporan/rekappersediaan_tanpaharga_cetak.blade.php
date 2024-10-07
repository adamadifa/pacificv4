<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Persediaan Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}"> --}}
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }

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
            REKAPITULASI PERSEDIAAN GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>KATEGORI {{ $kategori->nama_kategori }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3 table" style="width: 200%">
                <thead>
                    <tr>
                        <th rowspan="3" style="width: 1%">NO</th>
                        <th rowspan="3" style="width:1%">KODE</th>
                        <th rowspan="3" style="width:10%">NAMA BARANG</th>
                        <th rowspan="3" style="width: 1%">SATUAN</th>
                    </tr>
                    <tr>
                        <th rowspan="2">SALDO AWAL</th>
                        <th colspan="3" class="green">PEMASUKAN</th>
                        <th colspan="6" class="red">PENGELUARAN</th>
                        <th rowspan="2">SALDO AKHIR
                        </th>
                        <th rowspan="2">OPNAME STOK
                        </th>
                        <th rowspan="2">SELISIH</th>
                    </tr>
                    <tr bgcolor="red">
                        <th class="green">PEMBELIAN</th>
                        <th class="green">LAINNYA</th>
                        <th class="green">RETUR PENGGANTI</th>
                        <th class="red">PRODUKSI</th>
                        <th class="red">SEASONING</th>
                        <th class="red">PDQC</th>
                        <th class="red">SUSUT</th>
                        <th class="red">CABANG</th>
                        <th class="red">LAINNYA</th>
                    </tr>

                </thead>
                <tbody>
                    @php
                        $subtotal_qty_saldo_awal = 0;
                        $subtotal_jumlah_saldo_awal = 0;

                        $subtotal_qty_pembelian = 0;
                        $subtotal_jumlah_pembelian = 0;

                        $subtotal_qty_lainnya = 0;
                        $subtotal_jumlah_lainnya = 0;

                        $subtotal_qty_returpengganti = 0;
                        $subtotal_jumlah_returpengganti = 0;

                        $subtotal_qty_produksi = 0;
                        $subtotal_jumlah_produksi = 0;

                        $subtotal_qty_seasoning = 0;
                        $subtotal_jumlah_seasoning = 0;

                        $subtotal_qty_pdqc = 0;
                        $subtotal_jumlah_pdqc = 0;

                        $subtotal_qty_susut = 0;
                        $subtotal_jumlah_susut = 0;

                        $subtotal_qty_cabang = 0;
                        $subtotal_jumlah_cabang = 0;

                        $subtotal_qty_lainnya_keluar = 0;
                        $subtotal_jumlah_lainnya_keluar = 0;

                        $subtotal_qty_saldo_akhir = 0;
                        $subtotal_jumlah_saldo_akhir = 0;

                        $subtotal_qty_opname = 0;
                        $subtotal_jumlah_opname = 0;

                        $subtotal_qty_selisih = 0;
                        $subtotal_jumlah_selisih = 0;

                        //Grand Total
                        $grandtotal_qty_saldo_awal = 0;
                        $grandtotal_jumlah_saldo_awal = 0;

                        $grandtotal_qty_pembelian = 0;
                        $grandtotal_jumlah_pembelian = 0;

                        $grandtotal_qty_lainnya = 0;
                        $grandtotal_jumlah_lainnya = 0;

                        $grandtotal_qty_returpengganti = 0;
                        $grandtotal_jumlah_returpengganti = 0;

                        $grandtotal_qty_produksi = 0;
                        $grandtotal_jumlah_produksi = 0;

                        $grandtotal_qty_seasoning = 0;
                        $grandtotal_jumlah_seasoning = 0;

                        $grandtotal_qty_pdqc = 0;
                        $grandtotal_jumlah_pdqc = 0;

                        $grandtotal_qty_susut = 0;
                        $grandtotal_jumlah_susut = 0;

                        $grandtotal_qty_cabang = 0;
                        $grandtotal_jumlah_cabang = 0;

                        $grandtotal_qty_lainnya_keluar = 0;
                        $grandtotal_jumlah_lainnya_keluar = 0;

                        $grandtotal_qty_saldo_akhir = 0;
                        $grandtotal_jumlah_saldo_akhir = 0;

                        $grandtotal_qty_opname = 0;
                        $grandtotal_jumlah_opname = 0;

                        $grandtotal_qty_selisih = 0;
                        $grandtotal_jumlah_selisih = 0;
                    @endphp
                    @foreach ($rekappersediaan as $key => $d)
                        @php
                            $kode_jenis_barang = @$rekappersediaan[$key + 1]->kode_jenis_barang;
                            $berat_liter = getBeratliter($dari);

                            if ($d->satuan == 'LITER') {
                                $qty_saldo_awal = $d->saldo_awal_qty_berat * 1000 * $berat_liter;
                                $qty_pembelian = $d->bm_qty_berat_pembelian * 1000 * $berat_liter;
                                $qty_lainnya = $d->bm_qty_berat_lainnya * 1000 * $berat_liter;
                                $qty_returpengganti = $d->bm_qty_berat_returpengganti * 1000 * $berat_liter;

                                $qty_produksi = $d->bk_qty_berat_produksi * 1000 * $berat_liter;
                                $qty_seasoning = $d->bk_qty_berat_seasoning * 1000 * $berat_liter;
                                $qty_pdqc = $d->bk_qty_berat_pdqc * 1000 * $berat_liter;
                                $qty_susut = $d->bk_qty_berat_susut * 1000 * $berat_liter;
                                $qty_cabang = $d->bk_qty_berat_cabang * 1000 * $berat_liter;
                                $qty_lainnya_keluar = $d->bk_qty_berat_lainnya * 1000 * $berat_liter;

                                $qty_opname = $d->opname_qty_berat * 1000 * $berat_liter;
                            } elseif ($d->satuan == 'KG') {
                                $qty_saldo_awal = $d->saldo_awal_qty_berat * 1000;
                                $qty_pembelian = $d->bm_qty_berat_pembelian * 1000;
                                $qty_lainnya = $d->bm_qty_berat_lainnya * 1000;
                                $qty_returpengganti = $d->bm_qty_berat_returpengganti * 1000;

                                $qty_produksi = $d->bk_qty_berat_produksi * 1000;
                                $qty_seasoning = $d->bk_qty_berat_seasoning * 1000;
                                $qty_pdqc = $d->bk_qty_berat_pdqc * 1000;
                                $qty_susut = $d->bk_qty_berat_susut * 1000;
                                $qty_cabang = $d->bk_qty_berat_cabang * 1000;
                                $qty_lainnya_keluar = $d->bk_qty_berat_lainnya * 1000;

                                $qty_opname = $d->opname_qty_berat * 1000;
                            } else {
                                $qty_saldo_awal = $d->saldo_awal_qty_unit;
                                $qty_pembelian = $d->bm_qty_unit_pembelian;
                                $qty_lainnya = $d->bm_qty_unit_lainnya;
                                $qty_returpengganti = $d->bm_qty_unit_returpengganti;

                                $qty_produksi = $d->bk_qty_unit_produksi;
                                $qty_seasoning = $d->bk_qty_unit_seasoning;
                                $qty_pdqc = $d->bk_qty_unit_pdqc;
                                $qty_susut = $d->bk_qty_unit_susut;
                                $qty_cabang = $d->bk_qty_unit_cabang;
                                $qty_lainnya_keluar = $d->bk_qty_unit_lainnya;

                                $qty_opname = $d->opname_qty_unit;
                            }

                            //Saldo Awal

                            if (!empty($qty_saldo_awal)) {
                                $harga_saldo_awal = $d->saldo_awal_harga / $qty_saldo_awal;
                            } else {
                                $harga_saldo_awal = 0;
                            }
                            $jumlah_saldo_awal = $d->saldo_awal_harga;

                            //Pembelian
                            if (!empty($qty_pembelian)) {
                                $harga_pembelian = $d->total_harga / $qty_pembelian;
                            } else {
                                $harga_pembelian = $harga_saldo_awal;
                            }
                            $jumlah_pembelian = $d->total_harga;

                            //Lainnya
                            if (!empty($qty_lainnya)) {
                                if ($d->kode_barang == 'BK-45' and date('m', strtotime($dari)) == '9' and date('Y', strtotime($dari)) == '2021') {
                                    $harga_lainnya = 9078.43;
                                } elseif (
                                    $d->kode_barang == 'BK-44' and
                                    date('m', strtotime($dari)) == '9' and
                                    date('Y', strtotime($dari)) == '2021'
                                ) {
                                    $harga_lainnya = 14612.79;
                                } else {
                                    $harga_lainnya = $harga_pembelian;
                                }
                            } else {
                                $harga_lainnya = 0;
                            }
                            $jumlah_lainnya = $qty_lainnya * $harga_lainnya;

                            //Retur Pengganti

                            if (!empty($qty_returpengganti)) {
                                $harga_returpengganti = $harga_pembelian;
                            } else {
                                $harga_returpengganti = 0;
                            }
                            $jumlah_returpengganti = $qty_returpengganti * $harga_returpengganti;

                            //Produksi
                            $qty_masuk = $qty_saldo_awal + $qty_pembelian + $qty_lainnya + $qty_returpengganti;
                            $jumlah_masuk = $jumlah_saldo_awal + $jumlah_pembelian + $jumlah_lainnya + $jumlah_returpengganti;

                            if (!empty($qty_produksi)) {
                                $harga_produksi = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_produksi = 0;
                            }
                            $jumlah_produksi = $qty_produksi * $harga_produksi;

                            //Seasoning

                            if (!empty($qty_seasoning)) {
                                $harga_seasoning = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_seasoning = 0;
                            }
                            $jumlah_seasoning = $qty_seasoning * $harga_seasoning;

                            //PDQC

                            if (!empty($qty_pdqc)) {
                                $harga_pdqc = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_pdqc = 0;
                            }
                            $jumlah_pdqc = $qty_pdqc * $harga_pdqc;

                            //SUSUT

                            if (!empty($qty_susut)) {
                                $harga_susut = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_susut = 0;
                            }
                            $jumlah_susut = $qty_susut * $harga_susut;

                            //Cabang

                            if (!empty($qty_cabang)) {
                                $harga_cabang = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_cabang = 0;
                            }
                            $jumlah_cabang = $qty_cabang * $harga_cabang;

                            //Lainnya

                            if (!empty($qty_lainnya_keluar)) {
                                $harga_lainnya_keluar = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_lainnya_keluar = 0;
                            }
                            $jumlah_lainnya_keluar = $qty_lainnya_keluar * $harga_lainnya_keluar;

                            $qty_keluar = $qty_produksi + $qty_seasoning + $qty_pdqc + $qty_susut + $qty_cabang + $qty_lainnya_keluar;

                            //Saldo Akhir
                            $qty_saldo_akhir = $qty_masuk - $qty_keluar;
                            if (!empty($qty_saldo_akhir)) {
                                $harga_saldo_akhir = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_saldo_akhir = 0;
                            }
                            $jumlah_saldo_akhir = $qty_saldo_akhir * $harga_saldo_akhir;

                            if (!empty($qty_opname)) {
                                $harga_opname = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                            } else {
                                $harga_opname = 0;
                            }
                            $jumlah_opname = $qty_opname * $harga_opname;

                            $qty_selisih = ROUND($qty_saldo_akhir, 2) - ROUND($qty_opname, 2);
                            $jumlah_selisih = ROUND($jumlah_saldo_akhir, 2) - ROUND($jumlah_opname, 2);

                            //Subtotal
                            $subtotal_qty_saldo_awal += $qty_saldo_awal;
                            $subtotal_jumlah_saldo_awal += $jumlah_saldo_awal;

                            $subtotal_qty_pembelian += $qty_pembelian;
                            $subtotal_jumlah_pembelian += $jumlah_pembelian;

                            $subtotal_qty_lainnya += $qty_lainnya;
                            $subtotal_jumlah_lainnya += $jumlah_lainnya;

                            $subtotal_qty_returpengganti += $qty_returpengganti;
                            $subtotal_jumlah_returpengganti += $jumlah_returpengganti;

                            $subtotal_qty_produksi += $qty_produksi;
                            $subtotal_jumlah_produksi += $jumlah_produksi;

                            $subtotal_qty_seasoning += $qty_seasoning;
                            $subtotal_jumlah_seasoning += $jumlah_seasoning;

                            $subtotal_qty_pdqc += $qty_pdqc;
                            $subtotal_jumlah_pdqc += $jumlah_pdqc;

                            $subtotal_qty_susut += $qty_susut;
                            $subtotal_jumlah_susut += $jumlah_susut;

                            $subtotal_qty_cabang += $qty_cabang;
                            $subtotal_jumlah_cabang += $jumlah_cabang;

                            $subtotal_qty_lainnya_keluar += $qty_lainnya_keluar;
                            $subtotal_jumlah_lainnya_keluar += $jumlah_lainnya_keluar;

                            $subtotal_qty_saldo_akhir += $qty_saldo_akhir;
                            $subtotal_jumlah_saldo_akhir += $jumlah_saldo_akhir;

                            $subtotal_qty_opname += $qty_opname;
                            $subtotal_jumlah_opname += $jumlah_opname;

                            $subtotal_qty_selisih += $qty_selisih;
                            $subtotal_jumlah_selisih += $jumlah_selisih;

                            //Grand Total

                            //grandtotal
                            $grandtotal_qty_saldo_awal += $qty_saldo_awal;
                            $grandtotal_jumlah_saldo_awal += $jumlah_saldo_awal;

                            $grandtotal_qty_pembelian += $qty_pembelian;
                            $grandtotal_jumlah_pembelian += $jumlah_pembelian;

                            $grandtotal_qty_lainnya += $qty_lainnya;
                            $grandtotal_jumlah_lainnya += $jumlah_lainnya;

                            $grandtotal_qty_returpengganti += $qty_returpengganti;
                            $grandtotal_jumlah_returpengganti += $jumlah_returpengganti;

                            $grandtotal_qty_produksi += $qty_produksi;
                            $grandtotal_jumlah_produksi += $jumlah_produksi;

                            $grandtotal_qty_seasoning += $qty_seasoning;
                            $grandtotal_jumlah_seasoning += $jumlah_seasoning;

                            $grandtotal_qty_pdqc += $qty_pdqc;
                            $grandtotal_jumlah_pdqc += $jumlah_pdqc;

                            $grandtotal_qty_susut += $qty_susut;
                            $grandtotal_jumlah_susut += $jumlah_susut;

                            $grandtotal_qty_cabang += $qty_cabang;
                            $grandtotal_jumlah_cabang += $jumlah_cabang;

                            $grandtotal_qty_lainnya_keluar += $qty_lainnya_keluar;
                            $grandtotal_jumlah_lainnya_keluar += $jumlah_lainnya_keluar;

                            $grandtotal_qty_saldo_akhir += $qty_saldo_akhir;
                            $grandtotal_jumlah_saldo_akhir += $jumlah_saldo_akhir;

                            $grandtotal_qty_opname += $qty_opname;
                            $grandtotal_jumlah_opname += $jumlah_opname;

                            $grandtotal_qty_selisih += $qty_selisih;
                            $grandtotal_jumlah_selisih += $jumlah_selisih;
                        @endphp
                        <tr>
                            <td class="center">{{ $loop->iteration }}</td>
                            <td class="center">{{ $d->kode_barang }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td class="center">{{ $d->satuan }}</td>
                            <td class="right">{{ formatAngkaDesimal($qty_saldo_awal) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_pembelian) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_lainnya) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_returpengganti) }}</td>


                            <!-- BARANG KELUAR -->
                            <td class="right">{{ formatAngkaDesimal($qty_produksi) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_seasoning) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_pdqc) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_susut) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_cabang) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_lainnya_keluar) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_saldo_akhir) }}</td>


                            <td class="right">{{ formatAngkaDesimal($qty_opname) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_selisih) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_selisih) }}</td>
                        </tr>
                        @if ($kode_jenis_barang != $d->kode_jenis_barang)
                            <tr class="subtotal">
                                <td colspan="4">SUBTOTAL
                                    {{ $jenis_barang[$d->kode_jenis_barang] }}</td>
                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_saldo_awal) }}</td>

                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_pembelian) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_lainnya) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_returpengganti) }}</td>

                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_produksi) }}</td>

                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_seasoning) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_pdqc) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_susut) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_cabang) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_lainnya_keluar) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_saldo_akhir) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_opname) }}</td>


                                <td class="right">{{ formatAngkaDesimal($subtotal_qty_selisih) }}</td>

                            </tr>
                            @php
                                $subtotal_qty_saldo_awal = 0;
                                $subtotal_jumlah_saldo_awal = 0;

                                $subtotal_qty_pembelian = 0;
                                $subtotal_jumlah_pembelian = 0;

                                $subtotal_qty_lainnya = 0;
                                $subtotal_jumlah_lainnya = 0;

                                $subtotal_qty_returpengganti = 0;
                                $subtotal_jumlah_returpengganti = 0;

                                $subtotal_qty_produksi = 0;
                                $subtotal_jumlah_produksi = 0;

                                $subtotal_qty_seasoning = 0;
                                $subtotal_jumlah_seasoning = 0;

                                $subtotal_qty_pdqc = 0;
                                $subtotal_jumlah_pdqc = 0;

                                $subtotal_qty_susut = 0;
                                $subtotal_jumlah_susut = 0;

                                $subtotal_qty_cabang = 0;
                                $subtotal_jumlah_cabang = 0;

                                $subtotal_qty_lainnya_keluar = 0;
                                $subtotal_jumlah_lainnya_keluar = 0;

                                $subtotal_qty_saldo_akhir = 0;
                                $subtotal_jumlah_saldo_akhir = 0;

                                $subtotal_qty_opname = 0;
                                $subtotal_jumlah_opname = 0;

                                $subtotal_qty_selisih = 0;
                                $subtotal_jumlah_selisih = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="subtotal">
                        <td colspan="4">GRAND TOTAL</td>
                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_saldo_awal) }}</td>

                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_pembelian) }}</td>

                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_lainnya) }}</td>

                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_returpengganti) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_produksi) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_seasoning) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_pdqc) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_susut) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_cabang) }}</td>

                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_lainnya_keluar) }}</td>

                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_saldo_akhir) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_opname) }}</td>


                        <td class="right">{{ formatAngkaDesimal($grandtotal_qty_selisih) }}</td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>



</body>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 3,
        'shadow': true,
    });
</script>

</html>
