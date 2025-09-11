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
                        $lastLevel2 = null;
                        $subtotalAmount = 0;
                        $level2Items = [];
                        $currentLevel2Name = '';
                    @endphp

                    @foreach ($neraca as $index => $d)
                        @php
                            $indent = ($d->level ?? 0) * 20;
                            $nextItem = isset($neraca[$index + 1]) ? $neraca[$index + 1] : null;
                            $isLastItem = $index == count($neraca) - 1;
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
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($d->saldo_akhir) }}</b>
                                @else
                                    {{ formatAngka($d->saldo_akhir) }}
                                @endif
                            </td>
                        </tr>

                        @php
                            // Jika level 2, simpan sebagai header grup
                            if ($d->level == 2) {
                                // Jika ada grup sebelumnya, tampilkan subtotalnya
                                if ($lastLevel2 !== null && !empty($level2Items)) {
                                    echo '<tr class="subtotal-row">';
                                    echo '<td style="padding-left: ' .
                                        ($lastLevel2->level ?? 0) * 20 .
                                        'px;"><b>SUBTOTAL ' .
                                        strtoupper($currentLevel2Name) .
                                        '</b></td>';
                                    echo '<td style="text-align: right;"><b>' . formatAngka($subtotalAmount) . '</b></td>';
                                    echo '</tr>';
                                }

                                $lastLevel2 = $d;
                                $subtotalAmount = 0;
                                $level2Items = [];
                                $currentLevel2Name = $d->nama_akun;
                            }

                            // Jika level > 2, tambahkan ke subtotal
                            if ($d->level > 2 && $lastLevel2 !== null) {
                                $subtotalAmount += $d->saldo_akhir ?? 0;
                                $level2Items[] = $d;
                            }

                            // Cek apakah perlu menampilkan subtotal
                            $showSubtotal = false;

                            if ($isLastItem && !empty($level2Items)) {
                                $showSubtotal = true;
                            } elseif ($nextItem !== null && $nextItem->level <= 2 && !empty($level2Items)) {
                                $showSubtotal = true;
                            }
                        @endphp

                        <!-- Tampilkan subtotal jika diperlukan -->
                        @if ($showSubtotal && $lastLevel2 !== null)
                            <tr class="subtotal-row">
                                <td style="padding-left: {{ ($lastLevel2->level ?? 0) * 20 }}px;">
                                    <b>SUBTOTAL {{ strtoupper($currentLevel2Name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotalAmount) }}</b>
                                </td>
                            </tr>
                            @php
                                $lastLevel2 = null;
                                $subtotalAmount = 0;
                                $level2Items = [];
                                $currentLevel2Name = '';
                            @endphp
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
