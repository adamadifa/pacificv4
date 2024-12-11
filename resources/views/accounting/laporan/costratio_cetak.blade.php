<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cost Ratio {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
    {{-- <style>
        .datatable3 th {
            font-size: 11px !important;
        }

    </style> --}}
</head>

<body>
    <div class="header">
        <h4 class="title">
            COST RATIO <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        @foreach ($cabang as $c)
                            <th>{{ textUppercase($c->nama_cabang) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cabang as $c)
                        @php
                            ${"total_biaya_$c->kode_cabang"} = 0;
                        @endphp
                    @endforeach
                    @foreach ($costratio as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            @foreach ($cabang as $c)
                                @php
                                    ${"total_biaya_$c->kode_cabang"} += $d->{"jmlbiaya_$c->kode_cabang"};
                                @endphp
                                <td align="right">{{ formatAngka($d->{"jmlbiaya_$c->kode_cabang"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Logistik</td>
                        @foreach ($cabang as $c)
                            <td align="right">{{ formatAngka($logistik->{"logistik_$c->kode_cabang"}) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Penggunaan Bahan Kemasan</td>
                        @foreach ($cabang as $c)
                            <td align="right">{{ formatAngka($bahan->{"bahan_$c->kode_cabang"}) }}</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TOTAL</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                            @endphp
                            <th class="right">{{ formatAngka($total_biaya) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th rowspan="4" colspan="2">PENJUALAN</th>
                        <th>SWAN</th>
                        @foreach ($cabang as $c)
                            @php
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};
                            @endphp
                            <th class="right">{{ formatAngka($netto_swan) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>COST RATIO (%)</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};

                                $costratio_swan = !empty($netto_swan) ? ($total_biaya / $netto_swan) * 100 : 0;
                            @endphp
                            <th class="center">{{ formatAngka($costratio_swan) }} %</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="red">AIDA</th>
                        @foreach ($cabang as $c)
                            @php
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};
                            @endphp
                            <th class="right red">{{ formatAngka($netto_aida) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="red">COST RATIO (%)</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};

                                $costratio_aida = !empty($netto_aida) ? ($total_biaya / $netto_aida) * 100 : 0;
                            @endphp
                            <th class="center red">{{ formatAngka($costratio_aida) }} %</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3">TOTAL PPN</th>
                        @foreach ($cabang as $c)
                            <th class="right">{{ formatAngka($penjualanpotongan->{"ppn_$c->kode_cabang"}) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3">TOTAL PENJUALAN + PPN</th>
                        @foreach ($cabang as $c)
                            @php
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};

                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};

                                $ppn = $penjualanpotongan->{"ppn_$c->kode_cabang"};

                                $totalpenjualan = $netto_swan + $netto_aida + $ppn;
                            @endphp
                            <th class="right">{{ formatAngka($totalpenjualan) }}</th>
                        @endforeach
                    </tr>

                    <tr>
                        <th colspan="3"> COST RATIO (%)</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};

                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};

                                $ppn = $penjualanpotongan->{"ppn_$c->kode_cabang"};

                                $totalpenjualan = $netto_swan + $netto_aida + $ppn;
                                $costratio_penjualan = !empty($totalpenjualan) ? ($total_biaya / $totalpenjualan) * 100 : 0;
                            @endphp
                            <th class="center">{{ formatAngka($costratio_penjualan) }} %</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 109, 8); color:white">PIUTANG > 1 BULAN</th>
                        @foreach ($cabang as $c)
                            @php
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};
                            @endphp
                            <th class="right" style="background-color:rgb(187, 109, 8); color:white">{{ formatAngka($piutang) }} </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 109, 8); color:white">COST RATIO SWAN</th>
                        @foreach ($cabang as $c)
                            @php
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};

                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};

                                $costratio_piutang_swan = !empty($netto_swan) ? ($piutang / $netto_swan) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 109, 8); color:white"> {{ formatAngka($costratio_piutang_swan) }} %
                            </th>
                        @endforeach
                    </tr>

                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 109, 8); color:white">COST RATIO AIDA</th>
                        @foreach ($cabang as $c)
                            @php
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};

                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};

                                // $costratio_piutang_aida = !empty($netto_swan) ? ($piutang / $netto_aida) * 100 : 0;
                                $costratio_piutang_aida = !empty($netto_aida) ? ($piutang / $netto_aida) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 109, 8); color:white"> {{ formatAngka($costratio_piutang_aida) }} %
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 109, 8); color:white">COST RATIO SWAN + AIDA</th>
                        @foreach ($cabang as $c)
                            @php

                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};
                                $netto_swan_aida = $netto_swan + $netto_aida;
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};

                                $costratio_swan_aida = !empty($netto_swan_aida) ? ($piutang / $netto_swan_aida) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 109, 8); color:white"> {{ formatAngka($costratio_swan_aida) }} %
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 8, 106); color:white">BIAYA + PIUTANG</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};
                                $biaya_piutang = $total_biaya + $piutang;

                            @endphp
                            <th class="center" style="background-color:rgb(187, 8, 106); color:white"> {{ formatAngka($biaya_piutang) }}
                            </th>
                        @endforeach
                    </tr>

                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 8, 106); color:white">COST RATIO SWAN</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};
                                $biaya_piutang = $total_biaya + $piutang;
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};
                                $cr_biaya_piutang_swan = !empty($netto_swan) ? ($biaya_piutang / $netto_swan) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 8, 106); color:white"> {{ formatAngka($cr_biaya_piutang_swan) }} %
                            </th>
                        @endforeach
                    </tr>


                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 8, 106); color:white">COST RATIO AIDA</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};
                                $biaya_piutang = $total_biaya + $piutang;
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};
                                $cr_biaya_piutang_aida = !empty($netto_aida) ? ($biaya_piutang / $netto_aida) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 8, 106); color:white"> {{ formatAngka($cr_biaya_piutang_aida) }} %
                            </th>
                        @endforeach
                    </tr>


                    <tr>
                        <th colspan="3" style="background-color:rgb(187, 8, 106); color:white">COST RATIO SWAN + AIDA</th>
                        @foreach ($cabang as $c)
                            @php
                                $total_biaya =
                                    ${"total_biaya_$c->kode_cabang"} + $logistik->{"logistik_$c->kode_cabang"} + $bahan->{"bahan_$c->kode_cabang"};
                                $piutang = $saldoawalpiutang->{"piutang_$c->kode_cabang"} + $penjualan->{"penjualan_$c->kode_cabang"};
                                $biaya_piutang = $total_biaya + $piutang;
                                $netto_swan =
                                    $penjualanbruto->{"bruto_swan_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_swan_$c->kode_cabang"} -
                                    $retur->{"retur_swan_$c->kode_cabang"};
                                $netto_aida =
                                    $penjualanbruto->{"bruto_aida_$c->kode_cabang"} -
                                    $penjualanpotongan->{"potongan_aida_$c->kode_cabang"} -
                                    $retur->{"retur_aida_$c->kode_cabang"};
                                $netto_swan_aida = $netto_swan + $netto_aida;

                                $cr_biaya_piutang_swan_aida = !empty($netto_swan_aida) ? ($biaya_piutang / $netto_swan_aida) * 100 : 0;
                            @endphp
                            <th class="center" style="background-color:rgb(187, 8, 106); color:white"> {{ formatAngka($cr_biaya_piutang_swan_aida) }}
                                %
                            </th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>
