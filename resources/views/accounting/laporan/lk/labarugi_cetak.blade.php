<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laba Rugi {{ date('Y-m-d H:i:s') }}</title>
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
            LABA RUGI<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable9">
                <thead>
                    <tr>
                        <th style="font-size:12; text-align:left !important">NAMA AKUN</th>
                        <th style="font-size:12;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labarugi as $d)
                        @php
                            $kode_akun_minus = ['4-1201'];
                            // Hitung indentasi berdasarkan level (misal: 20px per level)
                            $indent = ($d->level ?? 0) * 20;
                            if (in_array($d->kode_akun, $kode_akun_minus)) {
                                $saldo_akhir = $d->saldo_akhir * -1;
                                $test = 'minus';
                            } else {
                                $saldo_akhir = $d->saldo_akhir;
                                $test = 'plus';
                            }
                        @endphp
                        <tr>
                            {{-- <td>
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ $d->kode_akun }}</b>
                                @else
                                    {{ $d->kode_akun }}
                                @endif
                            </td> --}}
                            <td style="padding-left: {{ $indent }}px;">
                                @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                                    <b>{{ $d->kode_akun }} {{ $d->nama_akun }}</b>
                                @else
                                    {{ $d->kode_akun }} {{ $d->nama_akun }}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                {{ $test }}
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($saldo_akhir) }}</b>
                                @else
                                    {{ formatAngka($saldo_akhir) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
