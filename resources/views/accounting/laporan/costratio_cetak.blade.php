<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cost Ratio {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            COST RATIO <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        @foreach ($cabang as $c)
                            <th>{{ textUppercase($c->nama_cabang) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cabang as $c)
                        @php
                            ${"total_biaya_$c->kode_cabang"} = 0;
                        @endphp
                    @endforeach
                    @foreach ($costratio as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            @foreach ($cabang as $c)
                                @php
                                    ${"total_biaya_$c->kode_cabang"} += $d->{"jmlbiaya_$c->kode_cabang"};
                                @endphp
                                <td align="right">{{ formatAngka($d->{"jmlbiaya_$c->kode_cabang"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Logistik</td>
                        @foreach ($cabang as $c)
                            <td align="right">{{ formatAngka($logistik->{"logistik_$c->kode_cabang"}) }}</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TOTAL</th>
                        @foreach ($cabang as $c)
                            <th class="right">{{ formatAngka(${"total_biaya_$c->kode_cabang"}) }}</th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>
