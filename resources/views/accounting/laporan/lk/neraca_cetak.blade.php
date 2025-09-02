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
            <table class="datatable9">
                <thead>
                    <tr>
                        <th style="font-size:12; text-align:left !important">NAMA AKUN</th>
                        <th style="font-size:12;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($neraca as $d)
                        @php
                            // Hitung indentasi berdasarkan level (misal: 20px per level)
                            $indent = ($d->level ?? 0) * 20;
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
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ $d->kode_akun }} {{ $d->nama_akun }}</b>
                                @else
                                    {{ $d->kode_akun }} {{ $d->nama_akun }}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($d->saldo_akhir) }}</b>
                                @else
                                    {{ formatAngka($d->saldo_akhir) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
