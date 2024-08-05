<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ledger {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            SALDO KAS BESAR<br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 160%">
                <thead>
                    <tr>
                        <th colspan="6" class="green">PENERIMAAN LHP</th>
                        <th class="green">TOTAL</th>
                        <th colspan="4" class="red">SETORAN KE BANK</th>
                        <th colspan="2" class="red">TOTAL</th>
                        <th>SALDO</th>
                        <th style="border: none !important; background-color: white"></th>
                        <th colspan="8">RINCIAN UANG PADA KAS BESAR</th>
                    </tr>
                    <tr>
                        <th>TGL</th>
                        <th>UANG KERTAS</th>
                        <th>LOGAM</th>
                        <th>GIRO</th>
                        <th>TRANSFER</th>
                        <th>LAINNYA</th>
                        <th>PENERIMAAN</th>
                        <th>UANG KERTAS</th>
                        <th>LOGAM</th>
                        <th>GIRO</th>
                        <th>TRANSFER</th>
                        <th>SETORAN KE BANK</th>
                        <th>LAINNYA</th>
                        <th>KAS BESAR</th>
                        <th style="border: none !important; background-color: white"></th>
                        <th>UANG KERTAS</th>
                        <th>LOGAM</th>
                        <th>GIRO</th>
                        <th>TRANSFER</th>
                        <th>TOTAL UANG FISIK</th>
                        <th>PENUKARAN LOGAM JADI KERTAS</th>
                        <th>PENUKARAN GIRO JADI KERTAS</th>
                        <th>PENUKARAN GIRO JADI TRANSFER</th>
                    </tr>
                    <tr style=" background-color:white; color:black; font-size:12;">
                        <th colspan="13" style="background-color:white; color:black;">SALDO AWAL</th>
                        <th style="background-color:white; color:black;">
                            @if ($saldo_awal != null)
                                @php
                                    $saldoawalkasbesar =
                                        $saldo_awal->uang_kertas + $saldo_awal->uang_logam + $saldo_awal->giro + $saldo_awal->transfer;
                                @endphp
                            @else
                                @php
                                    $saldoawalkasbesar = 0;
                                @endphp
                            @endif

                            {{ formatAngka($saldoawalkasbesar) }}
                        </th>
                        <th style="background-color:white; color:black; border:none !important"></th>
                        <th style="background-color:orange; color:white;">
                            @if ($saldo_awal != null)
                                {{ formatAngka($saldo_awal->uang_kertas) }}
                            @endif
                        </th>
                        <th style="background-color:orange; color:white;">
                            @if ($saldo_awal != null)
                                {{ formatAngka($saldo_awal->uang_logam) }}
                            @endif
                        </th>
                        <th style="background-color:orange; color:white;">
                            @if ($saldo_awal != null)
                                {{ formatAngka($saldo_awal->giro) }}
                            @endif
                        <th style="background-color:orange; color:white;">
                            @if ($saldo_awal != null)
                                {{ formatAngka($saldo_awal->transfer) }}
                            @endif
                        </th>


                        <th style="background-color:orange; color:black;"></th>
                        <th style="background-color:orange; color:black;"></th>
                        <th style="background-color:orange; color:black;"></th>
                        <th style="background-color:orange; color:black;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = $saldoawalkasbesar;
                        $saldo_kertas = $saldo_awal != null ? $saldo_awal->uang_kertas : 0;
                        $saldo_logam = $saldo_awal != null ? $saldo_awal->uang_logam : 0;
                        $saldo_giro = $saldo_awal != null ? $saldo_awal->giro : 0;
                        $saldo_transfer = $saldo_awal != null ? $saldo_awal->transfer : 0;

                        $grandtotal_lhpkertas = 0;
                        $grandtotal_lhplogam = 0;
                        $grandtotal_lhpgiro = 0;
                        $grandtotal_lhptransfer = 0;
                        $grandtotal_lhplainnya = 0;
                        $grandtotal_lhp = 0;

                        $grandtotal_setoran_kertas = 0;
                        $grandtotal_setoran_logam = 0;
                        $grandtotal_setoran_giro = 0;
                        $grandtotal_setoran_transfer = 0;
                        $grandtotal_setoran_lainnya = 0;
                        $grandtotal_setoran = 0;
                    @endphp
                    @foreach ($saldokasbesar as $d)
                        @php
                            $lhp_kertas = $d['lhp_kertas'] + $d['kurang_kertas'] - $d['lebih_kertas'];
                            $lhp_logam = $d['lhp_logam'] + $d['kurang_logam'] - $d['lebih_logam'];
                            $total_lhp = $lhp_kertas + $lhp_logam + $d['lhp_giro'] + $d['lhp_transfer'] + $d['lhp_lainnya'];
                            $total_setoran = $d['setoran_kertas'] + $d['setoran_logam'] + $d['setoran_transfer'] + $d['setoran_giro'];
                            $saldo += $total_lhp - $total_setoran - $d['setoran_lainnya'];

                            //Rincian Saldo
                            $rincian_kertas =
                                $lhp_kertas -
                                $d['setoran_kertas'] -
                                $d['setoran_lainnya'] +
                                $d['logamtokertas'] +
                                $d['lhp_giro_to_cash'] +
                                $d['lhp_lainnya'];

                            $saldo_kertas += $rincian_kertas;

                            $rincian_logam = $lhp_logam - $d['setoran_logam'] - $d['logamtokertas'];
                            $saldo_logam += $rincian_logam;

                            $rincian_giro = $d['lhp_giro'] - $d['setoran_giro'] - $d['lhp_giro_to_cash'] - $d['lhp_giro_to_transfer'];
                            $saldo_giro += $rincian_giro;

                            $rincian_transfer = $d['lhp_transfer'] - $d['setoran_transfer'] + $d['lhp_giro_to_transfer'];
                            $saldo_transfer += $rincian_transfer;
                            $total_uang = $saldo_kertas + $saldo_logam + $saldo_giro + $saldo_transfer;

                            $grandtotal_lhpkertas += $lhp_kertas;
                            $grandtotal_lhplogam += $lhp_logam;
                            $grandtotal_lhpgiro += $d['lhp_giro'];
                            $grandtotal_lhptransfer += $d['lhp_transfer'];
                            $grandtotal_lhplainnya += $d['lhp_lainnya'];
                            $grandtotal_lhp += $total_lhp;

                            $grandtotal_setoran_kertas += $d['setoran_kertas'];
                            $grandtotal_setoran_logam += $d['setoran_logam'];
                            $grandtotal_setoran_giro += $d['setoran_giro'];
                            $grandtotal_setoran_transfer += $d['setoran_transfer'];
                            $grandtotal_setoran_lainnya += $d['setoran_lainnya'];
                            $grandtotal_setoran += $total_setoran;
                        @endphp
                        <tr>
                            <td class="red center">{{ formatIndo($d['tanggal']) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($lhp_kertas) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($lhp_logam) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($d['lhp_giro']) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($d['lhp_transfer']) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($d['lhp_lainnya']) }}</td>
                            <td class="right" style="color:green">{{ formatAngka($total_lhp) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($d['setoran_kertas']) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($d['setoran_logam']) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($d['setoran_giro']) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($d['setoran_transfer']) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($total_setoran) }}</td>
                            <td class="right" style="color:red">{{ formatAngka($d['setoran_lainnya']) }}</td>
                            <td class="right" style="color:blue">{{ formatAngka($saldo) }}</td>
                            <td style="border:none !important"></td>
                            <td class="right">{{ formatAngka($saldo_kertas) }}</td>
                            <td class="right">{{ formatAngka($saldo_logam) }}</td>
                            <td class="right">{{ formatAngka($saldo_giro) }}</td>
                            <td class="right">{{ formatAngka($saldo_transfer) }}</td>
                            <td class="right">{{ formatAngka($total_uang) }}</td>
                            <td class="right">{{ formatAngka($d['logamtokertas']) }}</td>
                            <td class="right">{{ formatAngka($d['lhp_giro_to_cash']) }}</td>
                            <td class="right">{{ formatAngka($d['lhp_giro_to_transfer']) }}</td>


                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <th class="right">{{ formatAngka($grandtotal_lhpkertas) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_lhplogam) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_lhpgiro) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_lhptransfer) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_lhplainnya) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_lhp) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran_kertas) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran_logam) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran_giro) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran_transfer) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_setoran_lainnya) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
