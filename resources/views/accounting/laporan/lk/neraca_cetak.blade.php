<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
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
            NERACA<br>
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

                    @foreach ($neraca as $index => $d)
                        @php
                            $indent = ($d->level ?? 0) * 20;
                            $next_level = $neraca[$index + 1]->level ?? null;
                            $next_before_level = $neraca[$index - 1]->level ?? null;

                            //Level 0
                            if ($d->level == 0) {
                                $level_0_name = $d->nama_akun;
                            }

                            $subtotal_level_0 += $d->saldo_akhir;

                            //Level 1

                            if ($d->level == 1) {
                                $level_1_name = $d->nama_akun;
                            }

                            $subtotal_level_1 += $d->saldo_akhir;

                            //Level 2
                            if ($d->level == 2) {
                                $level_2_name = $d->nama_akun;
                            }

                            $subtotal_level_2 += $d->saldo_akhir;

                            //echo $level_0_name;

                        @endphp

                        <!-- Tampilkan item -->
                        <tr>
                            <td style="padding-left: {{ $indent }}px;">
                                @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                                    <b>{{ $d->kode_akun }} {{ $d->nama_akun }}</b>
                                @else
                                    {{ $d->kode_akun }} {{ $d->nama_akun }}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if ($d->kode_akun == '3-2000')
                                    $laba_rugi = $net_profit_loss;
                                @else
                                    $laba_rugi = 0;
                                @endif
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($d->saldo_akhir + $laba_rugi) }}</b>
                                @else
                                    {{ formatAngka($d->saldo_akhir + $laba_rugi) }}
                                @endif
                            </td>
                        </tr>

                        <!-- Jika Next Level 2 dan Next Before Level bukan 1 dan Level bukan 1 atau Next Level 1 -->
                        @if (
                            ($next_level == 2 && $next_before_level != 1 && $d->level != 1) ||
                                ($next_level == 2 && $next_before_level == 1 && $d->level == 2) ||
                                ($next_level == 1 && $next_before_level == 3 && $d->level != 0) ||
                                ($next_level == 1 && $next_before_level == 2 && $d->level != 1) ||
                                ($next_level == 0 && $d->level != 1))
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
</body>

</html>
