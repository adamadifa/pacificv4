<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan Omset per Regional Multi Tahun {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PENJUALAN OMSET PER REGIONAL MULTI TAHUN <br>
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
                    <th rowspan="2" style="vertical-align: middle;">Regional</th>
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
                @foreach ($regional_list as $reg)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ textUpperCase($reg->nama_regional) }}</td>
                        @foreach ($years as $year)
                            @php
                                $total_regional_year = 0;
                            @endphp
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $netto = $netto_map[$reg->kode_regional][$year][$m] ?? 0;
                                    $grand_totals[$year][$m] += $netto;
                                    $total_regional_year += $netto;
                                @endphp
                                <td align="right">{{ $netto != 0 ? formatAngka($netto) : '' }}</td>
                            @endfor
                            @php
                                $grand_totals[$year]['total'] += $total_regional_year;
                            @endphp
                            <td align="right" style="font-weight: bold; background-color: #f5f5f5;">{{ $total_regional_year != 0 ? formatAngka($total_regional_year) : '' }}</td>
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
