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
    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .subtotal-row {
            background-color: #f0f0f0;
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 1px solid #666;
        }

        .subtotal-row td {
            font-weight: bold;
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
                    @php
                        // $lastLevel2 = null;
                        // $subtotalAmount = 0;
                        // $level2Items = [];
                        // $currentLevel2Name = '';

                        $subtotal_level_0 = 0;
                        $level_0_name = '';

                        $subtotal_level_1 = 0;
                        $level_1_name = '';

                        $subtotal_level_2 = 0;
                        $level_2_name = '';
                    @endphp
                    @foreach ($labarugi as $index => $d)
                        @php
                            $kode_akun_minus = ['4-2101', '4-2201', '4-2202', '5-1202', '5-3200', '5-3400', '5-3800'];
                            // Hitung indentasi berdasarkan level (misal: 20px per level)
                            // $indent = ($d->level ?? 0) * 20;
                            if (in_array($d->kode_akun, $kode_akun_minus)) {
                                $saldo_akhir = $d->saldo_akhir * -1;
                                $test = 'minus';
                            } else {
                                $saldo_akhir = $d->saldo_akhir;
                                $test = 'plus';
                            }

                            $indent = ($d->level ?? 0) * 20;
                            $next_level = $labarugi[$index + 1]->level ?? null;
                            $next_before_level = $labarugi[$index - 1]->level ?? null;

                            //Level 0
                            if ($d->level == 0) {
                                $level_0_name = $d->nama_akun;
                            }

                            $subtotal_level_0 += $saldo_akhir;

                            //Level 1

                            if ($d->level == 1) {
                                $level_1_name = $d->nama_akun;
                            }

                            $subtotal_level_1 += $saldo_akhir;

                            //Level 2
                            if ($d->level == 2) {
                                $level_2_name = $d->nama_akun;
                            }

                            $subtotal_level_2 += $d->saldo_akhir;

                            //echo $level_0_name;

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
                                {{-- {{ $d->level }} {{ $next_level }} {{ $next_before_level }} --}}
                            </td>
                            <td style="text-align: right;">
                                {{-- {{ $test }} --}}
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($saldo_akhir) }}</b>
                                @else
                                    {{ formatAngka($saldo_akhir) }}
                                @endif
                            </td>
                        </tr>
                        @if (
                            ($next_level == 0 && $d->level != 1) ||
                                ($next_level == 1 && $next_before_level == 3 && $d->level != 0) ||
                                ($next_level == 1 && $next_before_level == 2 && $d->level != 1) ||
                                ($next_level == 2 && $next_before_level != 1 && $d->level != 1) ||
                                ($next_level == 2 && $next_before_level == 1 && $d->level == 2))
                            <tr class="subtotal-row">
                                <td style="padding-left:40px;">
                                    <b>SUBTOTAL {{ strtoupper($level_2_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_2) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_2 = 0;
                                $level_2_name = '';
                            @endphp
                        @endif

                        <!-- Jika Next Level 1 dan Next Before Level bukan 0 dan Level bukan 0 atau Next Level 0 -->
                        @if (($next_level == 1 && $next_before_level != 0 && $d->level != 0) || $next_level == 0)
                            <tr class="subtotal-row">
                                <td style="padding-left:20px;">
                                    <b>SUBTOTAL {{ strtoupper($level_1_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_1) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_1 = 0;
                                $level_1_name = '';
                            @endphp
                        @endif


                        @if ($next_level == 0)
                            <tr class="subtotal-row">
                                <td>
                                    <b>SUBTOTAL {{ strtoupper($level_0_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_0) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_0 = 0;
                                $level_0_name = '';
                            @endphp
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
