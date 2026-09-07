<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Piutang per Cabang Multi Tahun {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP PIUTANG PER CABANG MULTI TAHUN <br>
        </h4>
        <h4>TAHUN : {{ implode(', ', $years) }}</h4>
        @if ($cabang != null)
            <h4>
                CABANG: {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Cabang</th>
                    @foreach ($years as $year)
                        <th colspan="13" style="text-align: center;">{{ $year }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @for ($m = 1; $m <= 12; $m++)
                            <th style="font-size: 9px; padding: 4px;">{{ $m }}</th>
                        @endfor
                        <th style="font-size: 9px; padding: 4px; background-color: #e0e0e0;">TOTAL</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_totals = [];
                    foreach ($years as $year) {
                        for ($m = 1; $m <= 12; $m++) {
                            $grand_totals[$year][$m] = 0;
                        }
                        $grand_totals[$year]['total'] = 0;
                    }
                @endphp
                @foreach ($cabang_list as $cb)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ textUpperCase($cb->nama_cabang) }}</td>
                        @foreach ($years as $year)
                            @php
                                $total_cabang_year = 0;
                            @endphp
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $piutang = $piutang_map[$cb->kode_cabang][$year][$m] ?? 0;
                                    $grand_totals[$year][$m] += $piutang;
                                    $total_cabang_year += $piutang;
                                @endphp
                                <td align="right">{{ $piutang != 0 ? formatAngka($piutang) : '' }}</td>
                            @endfor
                            @php
                                $grand_totals[$year]['total'] += $total_cabang_year;
                            @endphp
                            <td align="right" style="font-weight: bold; background-color: #f5f5f5;">{{ $total_cabang_year != 0 ? formatAngka($total_cabang_year) : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #e2e2e2;">
                    <td colspan="2" align="center">TOTAL</td>
                    @foreach ($years as $year)
                        @for ($m = 1; $m <= 12; $m++)
                            <td align="right">{{ formatAngka($grand_totals[$year][$m]) }}</td>
                        @endfor
                        <td align="right">{{ formatAngka($grand_totals[$year]['total']) }}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
