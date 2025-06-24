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
            LEDGER<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th style="font-size:12; width: 10%;">TGL</th>
                        <th style="font-size:12; width: 15%;">NO BUKTI</th>
                        <th style="font-size:12; width: 15%;">SUMBER</th>
                        <th style="font-size:12; width: 30%;">KETERANGAN</th>
                        <th style="font-size:12; width: 10%;">DEBET</th>
                        <th style="font-size:12; width: 10%;">KREDIT</th>
                        <th style="font-size:12; width: 10%;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $kode_akun = '';
                        $total_debet = 0;
                        $total_kredit = 0;
                    @endphp
                    @foreach ($bukubesar as $key => $d)
                        @php
                            // $saldo_awal = $saldoawalCollection->firstWhere('kode_akun', $d->kode_akun)['saldo'] ?? null;
                            $saldo_awal = 0;
                            $akun = @$bukubesar[$key + 1]->kode_akun;
                        @endphp
                        @if ($kode_akun != $d->kode_akun)
                            @php
                                $saldo = 0;
                            @endphp
                            <tr style="background-color:rgba(116, 170, 227, 0.465);">
                                <th style="text-align: left" colspan="7">Akun : {{ $d->kode_akun }} -
                                    {{ $d->nama_akun }}
                                </th>
                            </tr>
                            <tr style="background-color:rgba(116, 170, 227, 0.465);">
                                <th style="text-align: left" colspan="6">SALDO AWAL</th>
                                <th style="text-align: right">{{ formatAngkaDesimal($saldo_awal) }}</th>
                            </tr>
                            @php
                                $saldo = $saldo_awal;
                            @endphp
                        @endif
                        @php
                            $saldo += $d->jml_debet - $d->jml_kredit;
                            $total_debet = $total_debet + $d->jml_debet;
                            $total_kredit = $total_kredit + $d->jml_kredit;
                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ textUpperCase($d->sumber) }}</td>
                            <td>{{ textCamelCase($d->keterangan) }}</td>
                            <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_debet) }}</td>
                            <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_kredit) }}</td>
                            <td style="text-align: right;">{{ formatAngkaDesimal($saldo) }}</td>
                        </tr>
                        @if ($akun != $d->kode_akun)
                            <tr class="thead-dark">
                                <th colspan="4">TOTAL</th>
                                <th style="text-align: right;">{{ formatAngkaDesimal($total_debet) }}</th>
                                <th style="text-align: right;">{{ formatAngkaDesimal($total_kredit) }}</th>
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
