<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penerimaan Uang (LPU) {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

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
            LAPORAN PENERIMAAN UANG <br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th colspan="{{ count($salesman) + 2 }}">PENERIMAAN LHP</th>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        @foreach ($salesman as $d)
                            <th>{{ $d->nama_salesman }}</th>
                        @endforeach
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesman as $d)
                        @php
                            $total_["lhp_$d->kode_salesman"] = 0;
                        @endphp
                    @endforeach
                    @foreach ($lpu as $d)
                        <tr>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            @php
                                $total_lhp_pertanggal = 0;
                            @endphp
                            @foreach ($salesman as $s)
                                @php
                                    $total_lhp_pertanggal += $d->{"lhp_$s->kode_salesman"};
                                    $total_["lhp_$s->kode_salesman"] += $d->{"lhp_$s->kode_salesman"};
                                @endphp
                                <td class="right">{{ formatAngka($d->{"lhp_$s->kode_salesman"}) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngka($total_lhp_pertanggal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        @php
                            $grandtotal_lhp = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_lhp += $total_["lhp_$d->kode_salesman"];
                            @endphp
                            <th class="right">{{ formatAngka($total_["lhp_$d->kode_salesman"]) }}</td>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_lhp) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
