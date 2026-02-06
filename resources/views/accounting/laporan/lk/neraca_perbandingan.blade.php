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
                    @foreach ($data_perbandingan as $d)
                        @php
                            // Data array for easy iteration
                            $vals = [
                                1 => $d->januari,
                                2 => $d->februari,
                                3 => $d->maret,
                                4 => $d->april,
                                5 => $d->mei,
                                6 => $d->juni,
                                7 => $d->juli,
                                8 => $d->agustus,
                                9 => $d->september,
                                10 => $d->oktober,
                                11 => $d->november,
                                12 => $d->desember
                            ];
                        @endphp
                        <tr>
                            <td>{{ $d->kode_akun }}</td>
                            <td>
                                @if ($d->level == 0 || $d->level == 1)
                                    <b>{{ $d->nama_akun }}</b>
                                @else
                                    {{ $d->nama_akun }}
                                @endif
                            </td>
                            
                            <!-- Jan -->
                            <td class="text-right">{{ formatAngka($vals[1]) }}</td>

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
                                <td class="text-right">{{ formatAngka($curr) }}</td>
                                <td class="text-right" style="color: {{ $colorDiff }}">{{ formatAngka($diff) }}</td>
                                <td class="text-right" style="color: {{ $colorPct }}">{{ formatAngkaDesimal($pct) }}%</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
