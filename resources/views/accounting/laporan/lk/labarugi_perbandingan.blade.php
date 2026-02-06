<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laba Rugi Perbandingan {{ $tahun }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        .text-right {
            text-align: right !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LABA RUGI PERBANDINGAN<br>
        </h4>
        <h4>TAHUN {{ $tahun }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">KODE AKUN</th>
                        <th rowspan="2">NAMA AKUN</th>
                        <th colspan="1" class="text-center">JANUARI</th>
                        <th colspan="3" class="text-center">PEBRUARI</th>
                        <th colspan="3" class="text-center">MARET</th>
                        <th colspan="3" class="text-center">APRIL</th>
                        <th colspan="3" class="text-center">MEI</th>
                        <th colspan="3" class="text-center">JUNI</th>
                        <th colspan="3" class="text-center">JULI</th>
                        <th colspan="3" class="text-center">AGUSTUS</th>
                        <th colspan="3" class="text-center">SEPTEMBER</th>
                        <th colspan="3" class="text-center">OKTOBER</th>
                        <th colspan="3" class="text-center">NOVEMBER</th>
                        <th colspan="3" class="text-center">DESEMBER</th>
                    </tr>
                    <tr>
                        <!-- Jan -->
                        <th>Nominal</th>
                        
                        <!-- Feb - Dec loops -->
                        @foreach(['PEB','MAR','APR','MEI','JUN','JUL','AGU','SEP','OKT','NOP','DES'] as $bulan)
                            <th>Nominal</th>
                            <th>+/-</th>
                            <th>%</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal_level_0 = array_fill(1, 12, 0);
                        $level_0_name = '';
                        $subtotal_level_1 = array_fill(1, 12, 0);
                        $level_1_name = '';
                        $subtotal_level_2 = array_fill(1, 12, 0);
                        $level_2_name = '';

                        $kode_akun_pendapatan = 4;
                        $kode_akun_pokok_penjualan = 5;
                        $kode_akun_pendapatanlain = 8;
                        $kode_akun_biayalain = 9;
                        $kode_akun_biaya_penjualan = '6-1';
                        $kode_akun_biaya_adm = '6-2';

                        $subtotal_akun_pendapatan = array_fill(1, 12, 0);
                        $subtotal_akun_pokok_penjualan = array_fill(1, 12, 0);
                        $subtotal_akun_pendapatanlain = array_fill(1, 12, 0);
                        $subtotal_akun_biayalain = array_fill(1, 12, 0);
                        $subtotal_akun_biaya_penjualan = array_fill(1, 12, 0);
                        $subtotal_akun_biaya_adm = array_fill(1, 12, 0);
                    @endphp

                    @foreach ($data_perbandingan as $index => $d)
                        @php
                            $kode_akun_minus = [
                                '4-2101', '4-2201', '4-2202', '5-1202', 
                                '5-3200', '5-3400', '5-3800', '5-1203',
                            ];

                            $raw_vals = [];
                            $raw_vals[1] = $d->januari;
                            $raw_vals[2] = $d->februari;
                            $raw_vals[3] = $d->maret;
                            $raw_vals[4] = $d->april;
                            $raw_vals[5] = $d->mei;
                            $raw_vals[6] = $d->juni;
                            $raw_vals[7] = $d->juli;
                            $raw_vals[8] = $d->agustus;
                            $raw_vals[9] = $d->september;
                            $raw_vals[10] = $d->oktober;
                            $raw_vals[11] = $d->november;
                            $raw_vals[12] = $d->desember;

                            $vals = [];
                            if (in_array($d->kode_akun, $kode_akun_minus)) {
                                for($i=1; $i<=12; $i++) $vals[$i] = $raw_vals[$i] * -1;
                            } else {
                                $vals = $raw_vals;
                            }

                            $next_level = $data_perbandingan[$index + 1]->level ?? null;
                            $next_kode_akun = $data_perbandingan[$index + 1]->kode_akun ?? null;

                            // Level 0
                            if ($d->level == 0) $level_0_name = $d->nama_akun;
                            for($i=1; $i<=12; $i++) $subtotal_level_0[$i] += $vals[$i];

                            // Level 1
                            if ($d->level == 1) $level_1_name = $d->nama_akun;
                            for($i=1; $i<=12; $i++) $subtotal_level_1[$i] += $vals[$i];

                            // Level 2
                            if ($d->level == 2) $level_2_name = $d->nama_akun;
                            for($i=1; $i<=12; $i++) $subtotal_level_2[$i] += $vals[$i];

                            // Groups
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatan) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_pendapatan[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_pokok_penjualan) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_pokok_penjualan[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatanlain) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_pendapatanlain[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_biayalain) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_biayalain[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_penjualan) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_biaya_penjualan[$i] += $vals[$i];
                            }
                             if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_adm) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_biaya_adm[$i] += $vals[$i];
                            }

                        @endphp
                        <tr>
                            <td>
                                @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                                    <b>{{ $d->kode_akun }}</b>
                                @else
                                    {{ $d->kode_akun }}
                                @endif
                            </td>
                            <td>
                                @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                                    <b>{{ $d->nama_akun }}</b>
                                @else
                                    {{ $d->nama_akun }}
                                @endif
                            </td>
                            <!-- Jan -->
                            <td class="text-right">
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ formatAngka($vals[1]) }}</b>
                                @else
                                    {{ formatAngka($vals[1]) }}
                                @endif
                            </td>

                            <!-- Feb - Dec -->
                            @for ($i = 2; $i <= 12; $i++)
                                @php
                                    $curr = $vals[$i];
                                    $prev = $vals[$i-1];
                                    $diff = $curr - $prev;
                                    $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                    $colorDiff = $diff < 0 ? 'red' : 'black';
                                    $colorPct = $pct < 0 ? 'red' : 'black';
                                @endphp
                                <td class="text-right">
                                    @if ($d->level == 0 || $d->level == 1)
                                        <b>{{ formatAngka($curr) }}</b>
                                    @else
                                        {{ formatAngka($curr) }}
                                    @endif
                                </td>
                                <td class="text-right" style="color: {{ $colorDiff }}">
                                    @if ($d->level == 0 || $d->level == 1)
                                        <b>{{ formatAngka($diff) }}</b>
                                    @else
                                        {{ formatAngka($diff) }}
                                    @endif
                                </td>
                                <td class="text-right" style="color: {{ $colorPct }}">
                                    @if ($d->level == 0 || $d->level == 1)
                                        <b>{{ formatAngkaDesimal($pct) }}%</b>
                                    @else
                                        {{ formatAngkaDesimal($pct) }}%
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        {{-- Subtotal Level 2 --}}
                         @if (
                            (array_sum($subtotal_level_2) != 0 && $next_level == 2 && $d->level == 2) ||
                            (array_sum($subtotal_level_2) != 0 && $next_level == 2 && $d->level == 3) ||
                            (array_sum($subtotal_level_2) != 0 && $next_level == 1 && $d->level == 3) ||
                            (array_sum($subtotal_level_2) != 0 && $next_level == 1 && $d->level == 2) ||
                            (array_sum($subtotal_level_2) != 0 && $next_level == 0 && $d->level == 3))
                            <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">SUBTOTAL {{ strtoupper($level_2_name) }}</td>
                                <td class="text-right">{{ formatAngka($subtotal_level_2[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $subtotal_level_2[$i];
                                        $prev = $subtotal_level_2[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                            @php
                                $subtotal_level_2 = array_fill(1, 12, 0);
                                $level_2_name = '';
                            @endphp
                        @endif

                        {{-- Subtotal Level 1 --}}
                        @if (
                            (array_sum($subtotal_level_1) != 0 && $next_level == 1 && $d->level == 3) ||
                            (array_sum($subtotal_level_1) != 0 && $next_level == 0 && $d->level == 3) ||
                            (array_sum($subtotal_level_1) != 0 && $next_level == 1 && $d->level == 2) ||
                            (array_sum($subtotal_level_1) != 0 && $next_level == 1 && $d->level == 1) ||
                            (array_sum($subtotal_level_1) != 0 && $next_level == 0 && $d->level == 1) ||
                            (array_sum($subtotal_level_1) != 0 && $next_level == 0 && $d->level == 2))
                            <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">SUBTOTAL {{ strtoupper($level_1_name) }}</td>
                                <td class="text-right">{{ formatAngka($subtotal_level_1[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $subtotal_level_1[$i];
                                        $prev = $subtotal_level_1[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                            @php
                                $subtotal_level_1 = array_fill(1, 12, 0);
                                $level_1_name = '';
                            @endphp
                        @endif

                        {{-- Subtotal Level 0 --}}
                        @if ($next_level == 0)
                            <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">SUBTOTAL {{ strtoupper($level_0_name) }}</td>
                                <td class="text-right">{{ formatAngka($subtotal_level_0[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $subtotal_level_0[$i];
                                        $prev = $subtotal_level_0[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                            @php
                                $subtotal_level_0 = array_fill(1, 12, 0);
                                $level_0_name = '';
                            @endphp
                        @endif

                        {{-- GROSS PROFIT --}}
                        @if (substr($next_kode_akun, 0, 1) == 6 && substr($d->kode_akun, 0, 1) == 5)
                            <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">GROSS PROFIT</td>
                                @php
                                    $gross_profit = [];
                                    for($i=1; $i<=12; $i++) $gross_profit[$i] = $subtotal_akun_pendapatan[$i] - $subtotal_akun_pokok_penjualan[$i];
                                @endphp
                                <td class="text-right">{{ formatAngka($gross_profit[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $gross_profit[$i];
                                        $prev = $gross_profit[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                        @endif

                         {{-- TOTAL BIAYA PENJUALAN --}}
                         @if (substr($next_kode_akun, 0, 3) == '6-2' && substr($d->kode_akun, 0, 3) == '6-1')
                            <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">TOTAL BIAYA PENJUALAN</td>
                                <td class="text-right">{{ formatAngka($subtotal_akun_biaya_penjualan[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $subtotal_akun_biaya_penjualan[$i];
                                        $prev = $subtotal_akun_biaya_penjualan[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                         @endif

                         {{-- OPERATING PROFIT --}}
                         @if (substr($next_kode_akun, 0, 3) != '6-2' && substr($d->kode_akun, 0, 3) == '6-2')
                             <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">OPERATING PROFIT</td>
                                @php
                                    $operating_profit = [];
                                    // Make sure gross_profit is available (it should be calculated above)
                                    // Re-calc if needed or use stored var? Blade loop scope persists.
                                    // Recalc safe:
                                    for($i=1; $i<=12; $i++) {
                                        $gp = $subtotal_akun_pendapatan[$i] - $subtotal_akun_pokok_penjualan[$i];
                                        $bo = $subtotal_akun_biaya_adm[$i] + $subtotal_akun_biaya_penjualan[$i];
                                        $operating_profit[$i] = $gp - $bo;
                                    }
                                @endphp
                                <td class="text-right">{{ formatAngka($operating_profit[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $operating_profit[$i];
                                        $prev = $operating_profit[$i-1];
                                        $diff = $curr - $prev;
                                        $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                        $colorDiff = $diff < 0 ? 'red' : 'black';
                                        $colorPct = $pct < 0 ? 'red' : 'black';
                                    @endphp
                                    <td class="text-right">{{ formatAngka($curr) }}</td>
                                    <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                    <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                                @endfor
                            </tr>
                         @endif

                    @endforeach

                     {{-- NET PROFIT / LOSS --}}
                    <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2">NET PROFIT / LOSS</td>
                         @php
                             $net_profit_loss = [];
                              for($i=1; $i<=12; $i++) {
                                     $gp = $subtotal_akun_pendapatan[$i] - $subtotal_akun_pokok_penjualan[$i];
                                     $bo = $subtotal_akun_biaya_adm[$i] + $subtotal_akun_biaya_penjualan[$i];
                                     $op = $gp - $bo;
                                     $net_profit_loss[$i] = $op + $subtotal_akun_pendapatanlain[$i] - $subtotal_akun_biayalain[$i];
                              }
                         @endphp
                         <td class="text-right">{{ formatAngka($net_profit_loss[1]) }}</td>
                         @for ($i = 2; $i <= 12; $i++)
                            @php
                                $curr = $net_profit_loss[$i];
                                $prev = $net_profit_loss[$i-1];
                                $diff = $curr - $prev;
                                $pct = $prev != 0 ? ($diff / $prev) * 100 : 0;
                                $colorDiff = $diff < 0 ? 'red' : 'black';
                                $colorPct = $pct < 0 ? 'red' : 'black';
                            @endphp
                            <td class="text-right">{{ formatAngka($curr) }}</td>
                            <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                            <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
