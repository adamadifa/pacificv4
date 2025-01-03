<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Omset Pelanggan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP OMSET PELANGGAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE PELANGGAN</th>
                        <th>NAMA PELANGGAN</th>
                        <th>PASAR</th>
                        <th>KLASIFIKASI</th>
                        <th>TOTAL OMSET</th>
                        <th>SWAN</th>
                        <th>AIDA</th>
                        <th>SALESMAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_omset = 0;
                    @endphp
                    @foreach ($omsetpelanggan as $d)
                        @php
                            $total_omset += $d->total_netto;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td>{{ $d->klasifikasi }}</td>
                            <td class="right">{{ formatAngka($d->total_netto) }}</td>
                            <td class="right">{{ formatAngka($d->total_netto_swan) }}</td>
                            <td class="right">{{ formatAngka($d->total_netto_aida) }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">TOTAL</th>
                        <th class="right">{{ formatAngka($total_omset) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
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
