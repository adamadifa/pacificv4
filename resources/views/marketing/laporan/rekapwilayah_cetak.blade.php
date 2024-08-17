<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Wilayah {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}

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
            REKAP WILAYAH <br>
        </h4>
        <h4>TAHUN :{{ $tahun }}</h4>
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
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Kode Wilayah</th>
                        <th rowspan="2">Nama Wilayah</th>
                        <th colspan="12">Bulan</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= 12; $i++)
                            <th>{{ $namabulan[$i] }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapwilayah as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_wilayah }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            @for ($i = 1; $i <= 12; $i++)
                                <td class="right">{{ formatAngka($d->{"bulan_$i"}) }}</td>
                            @endfor
                            <td class="right">{{ formatAngka($d->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}
