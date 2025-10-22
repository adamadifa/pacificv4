<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Besar {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}
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
            BUKU BESAR<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                {{-- <thead>
                    
                </thead> --}}
                <tbody>
                    @php
                        $kode_akun = '';
                        $total_debet = 0;
                        $total_kredit = 0;
                        $saldo = 0;
                        $saldo_awal_kredit = 0;
                        $saldo_awal_debet = 0;
                    @endphp
                    @foreach ($bukubesar as $key => $d)
                        @php
                            //$next_akun = @$bukubesar[$key + 1]->kode_akun;
                            //$saldo_awal = $saldoawalCollection->firstWhere('kode_akun', $d->kode_akun)['jumlah'] ?? 0;
                            $mutasi_debet =
                                optional($mutasiakunCollection->firstWhere('kode_akun', $d->kode_akun))->total_debet ??
                                0;
                            $mutasi_kredit =
                                optional($mutasiakunCollection->firstWhere('kode_akun', $d->kode_akun))->total_kredit ??
                                0;
                            // if ($d->jenis_akun == '1') {
                            //     $saldo_awal = $saldo_awal + $mutasi_kredit - $mutasi_debet;
                            // } else {
                            //     $saldo_awal = $saldo_awal + $mutasi_debet - $mutasi_kredit;
                            // }
                            $akun = @$bukubesar[$key + 1]->kode_akun;
                        @endphp
                        @if ($kode_akun != $d->kode_akun)
                            @php
                                $saldo = 0;
                            @endphp
                            <tr>
                                <td colspan="7" style="height: 50px; border:none; background:none;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size:20px; border:none; background:none; font-weight:bold;"
                                    colspan="7">
                                    Akun : {{ $d->kode_akun }} - {{ $d->nama_akun }}
                                </td>
                            </tr>
                            <tr>
                                <th style="font-size:12; width: 10%;">TGL</th>
                                <th style="font-size:12; width: 15%;">NO BUKTI</th>
                                <th style="font-size:12; width: 15%;">SUMBER</th>
                                <th style="font-size:12; width: 30%;">KETERANGAN</th>
                                <th style="font-size:12; width: 10%;">DEBET</th>
                                <th style="font-size:12; width: 10%;">KREDIT</th>
                                <th style="font-size:12; width: 10%;">SALDO</th>
                            </tr>
                            {{-- <tr style="background-color:rgba(116, 170, 227, 0.465);">
                                <th style="text-align: left" colspan="6">SALDO AWAL</th>
                                <th style="text-align: right">{{ formatAngkaDesimal($saldo_awal) }}</th>
                            </tr>
                            @php
                                $saldo = $saldo_awal;
                            @endphp --}}
                        @endif
                        @php

                            if ($d->jenis_akun == '1') {
                                $saldo += $d->jml_kredit - $d->jml_debet;
                            } else {
                                $saldo += $d->jml_debet - $d->jml_kredit;
                            }
                            $total_debet = $total_debet + $d->jml_debet;
                            $total_kredit = $total_kredit + $d->jml_kredit;
                        @endphp
                        @if ($d->sumber == 'SALDO AWAL')
                            @if ($d->jenis_akun == '1')
                                @php
                                    $saldo_awal_kredit = $saldo;
                                    $saldo_awal_debet = 0;
                                @endphp
                            @else
                                @php
                                    $saldo_awal_kredit = 0;
                                    $saldo_awal_debet = $saldo;
                                @endphp
                            @endif
                            <tr class="thead-dark">
                                <th colspan="6">SALDO AWAL</th>
                                <th style="text-align: right;">{{ formatAngkaDesimal($saldo) }}


                                    {{-- {{ 'Saldo Awal Debet' }} {{ $saldo_awal_debet }}
                                    {{ 'Saldo Awal Kredit' }} {{ $saldo_awal_kredit }} --}}
                                </th>
                            </tr>
                        @else
                            <tr>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->no_bukti }}</td>
                                <td>{{ textUpperCase($d->sumber) }}</td>
                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_debet) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_kredit) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($saldo) }}</td>
                            </tr>
                        @endif

                        @if ($akun != $d->kode_akun)
                            <tr class="thead-dark">
                                <th colspan="4">TOTAL {{ $d->kode_akun }} - {{ $d->nama_akun }}</th>
                                <th style="text-align: right;">
                                    {{ formatAngkaDesimal($total_debet - $saldo_awal_debet) }}</th>
                                <th style="text-align: right;">
                                    {{ formatAngkaDesimal($total_kredit - $saldo_awal_kredit) }}</th>
                                <th style="text-align: right;">{{ formatAngkaDesimal($saldo) }}</th>
                            </tr>
                            @php
                                $total_debet = 0;
                                $total_kredit = 0;
                            @endphp
                        @endif
                        @php
                            $kode_akun = $d->kode_akun;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
