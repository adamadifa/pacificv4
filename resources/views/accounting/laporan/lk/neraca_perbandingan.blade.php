<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca Perbandingan {{ $tahun }}</title>
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
            NERACA PERBANDINGAN<br>
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
                        // Initialize all subtotal arrays and names here, once.
                        $subtotal_level_0 = array_fill(1, 12, 0);
                        $level_0_name = '';

                        $subtotal_level_1 = array_fill(1, 12, 0);
                        $level_1_name = '';

                        $subtotal_level_2 = array_fill(1, 12, 0);
                        $level_2_name = '';

                        $kode_akun_kas_bank = ['1-11', '1-12'];
                        $subtotal_akun_kas_bank = array_fill(1, 12, 0);

                        $kode_akun_hutang = 2;
                        $subtotal_akun_hutang = array_fill(1, 12, 0);

                        $kode_akun_modal = 3;
                        $subtotal_akun_modal = array_fill(1, 12, 0);
                    @endphp

                    @foreach ($data_perbandingan as $index => $d)
                        @php
                            $vals = [];
                            // Load values for 12 months
                            $vals[1] = $d->januari;
                            $vals[2] = $d->februari;
                            $vals[3] = $d->maret;
                            $vals[4] = $d->april;
                            $vals[5] = $d->mei;
                            $vals[6] = $d->juni;
                            $vals[7] = $d->juli;
                            $vals[8] = $d->agustus;
                            $vals[9] = $d->september;
                            $vals[10] = $d->oktober;
                            $vals[11] = $d->november;
                            $vals[12] = $d->desember;

                            $next_level = $data_perbandingan[$index + 1]->level ?? null;
                            $next_kode_akun = $data_perbandingan[$index + 1]->kode_akun ?? null;

                            // Accumulate for Level 0
                            if ($d->level == 0) {
                                $level_0_name = $d->nama_akun;
                            }
                            for($i=1; $i<=12; $i++) $subtotal_level_0[$i] += $vals[$i];

                            // Accumulate for Level 1
                            if ($d->level == 1) {
                                $level_1_name = $d->nama_akun;
                            }
                            for($i=1; $i<=12; $i++) $subtotal_level_1[$i] += $vals[$i];

                            // Accumulate for Level 2
                            if ($d->level == 2) {
                                $level_2_name = $d->nama_akun;
                            }
                            for($i=1; $i<=12; $i++) $subtotal_level_2[$i] += $vals[$i];

                            // Accumulate for Specific Groups
                            if (in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank)) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_kas_bank[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_hutang) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_hutang[$i] += $vals[$i];
                            }
                            if (substr($d->kode_akun, 0, 1) == $kode_akun_modal) {
                                for($i=1; $i<=12; $i++) $subtotal_akun_modal[$i] += $vals[$i];
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
                            (array_sum($subtotal_level_2) != 0 && $next_level == 0 && $d->level == 3) ||
                            (array_sum($subtotal_level_2) != 0 && $index == count($data_perbandingan) - 1 && $d->level == 2) ||
                            (array_sum($subtotal_level_2) != 0 && $index == count($data_perbandingan) - 1 && $d->level == 3))
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
                            (array_sum($subtotal_level_1) != 0 && $next_level == 0 && $d->level == 2) ||
                            (array_sum($subtotal_level_1) != 0 && $index == count($data_perbandingan) - 1 && $d->level == 1) ||
                            (array_sum($subtotal_level_1) != 0 && $index == count($data_perbandingan) - 1 && $d->level == 2) ||
                            (array_sum($subtotal_level_1) != 0 && $index == count($data_perbandingan) - 1 && $d->level == 3))
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
                        @if ($next_level == 0 || ($index == count($data_perbandingan) - 1 && $d->level == 0) || ($index == count($data_perbandingan) - 1 && $d->level == 1) || ($index == count($data_perbandingan) - 1 && $d->level == 2) || ($index == count($data_perbandingan) - 1 && $d->level == 3))
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

                         {{-- Subtotal Kas Bank --}}
                        @if (!in_array(substr($next_kode_akun, 0, 4), $kode_akun_kas_bank) && in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank))
                             <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2">SUBTOTAL KAS BANK</td>
                                <td class="text-right">{{ formatAngka($subtotal_akun_kas_bank[1]) }}</td>
                                @for ($i = 2; $i <= 12; $i++)
                                    @php
                                        $curr = $subtotal_akun_kas_bank[$i];
                                        $prev = $subtotal_akun_kas_bank[$i-1];
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

                    {{-- Total Pasiva --}}
                     <tr class="subtotal-row" style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2">TOTAL PASIVA</td>
                         @php
                            // Total Pasiva = Hutang + Modal
                             $total_pasiva = array_fill(1, 12, 0);
                             for($i=1; $i<=12; $i++) $total_pasiva[$i] = $subtotal_akun_hutang[$i] + $subtotal_akun_modal[$i];
                         @endphp
                        <td class="text-right">{{ formatAngka($total_pasiva[1]) }}</td>
                        @for ($i = 2; $i <= 12; $i++)
                            @php
                                $curr = $total_pasiva[$i];
                                $prev = $total_pasiva[$i-1];
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
