<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LHP Penjualan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

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
            LHP PENJUALAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
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
                        <th rowspan="2">Tanggal</th>
                        @foreach ($salesman as $d)
                            <th colspan="3">{{ $d->nama_salesman }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($salesman as $d)
                            <th>TUNAI</th>
                            <th>TAGIHAN</th>
                            <th>TOTAL</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($setoranpenjualan as $d)
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            @foreach ($salesman as $s)
                                <td class="right">{{ formatAngka($d->{"lhptunai_$s->kode_salesman"}) }}</td>
                                <td class="right">{{ formatAngka($d->{"lhptagihan_$s->kode_salesman"}) }}</td>
                                <td class="right">{{ formatAngka($d->{"lhptotal_$s->kode_salesman"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
