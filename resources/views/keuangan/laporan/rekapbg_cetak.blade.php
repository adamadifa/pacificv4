<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap BG {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP BG <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>TGL PENERIMAAN</th>
                        <th>SALES</th>
                        <th>NO FAKTUR</th>
                        <th>NAMA PELANGGAN</th>
                        <th>NAMA BANK</th>
                        <th>NO CHEQUE</th>
                        <th>TGL JATUH TEMPO</th>
                        <th>JUMLAH PENERIMAAN</th>
                        <th>TGL PENCAIRAN</th>
                        <th>SALDO GIRO BELUM CAIR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_per_giro = 0;
                        $totalbelumcair_per_giro = 0;
                        $grandtotal = 0;
                        $grandtotalbelumcair = 0;
                    @endphp
                    @foreach ($rekapbg as $key => $d)
                        @php
                            $no_giro = @$rekapbg[$key + 1]->no_giro;
                            $giro_belum_cair = empty($d->tanggal_bayar) ? $d->jumlah : 0;
                            $total_per_giro += $d->jumlah;
                            $totalbelumcair_per_giro += $giro_belum_cair;
                            $grandtotal += $d->jumlah;
                            $grandtotalbelumcair += $giro_belum_cair;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->bank_pengirim }}</td>
                            <td>{{ $d->no_giro }}</td>
                            <td>{{ DateToIndo($d->jatuh_tempo) }}</td>
                            <td class="right">{{ formatAngka($d->jumlah) }}</td>
                            <td>{{ !empty($d->tanggal_bayar) ? DateToIndo($d->tanggal_bayar) : '' }}</td>
                            <td class="right">{{ formatAngka($giro_belum_cair) }}</td>
                        </tr>
                        @if ($d->no_giro != $no_giro)
                            <tr>
                                <td colspan="7" style="background-color: #f0f0f0;">>TOTAL</td>
                                <td class="right" style="background-color: #f0f0f0; font-weight:bold">{{ formatAngka($total_per_giro) }}</td>
                                <td style="background-color: #f0f0f0;"></td>
                                <td class="right" style="background-color: #f0f0f0; font-weight:bold">{{ formatAngka($totalbelumcair_per_giro) }}
                                </td>
                            </tr>
                            @php
                                $total_per_giro = 0;
                                $totalbelumcair_per_giro = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">TOTAL</th>
                        <th class="right">{{ formatAngka($grandtotal) }}</th>
                        <th></th>
                        <th class="right">{{ formatAngka($grandtotalbelumcair) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
