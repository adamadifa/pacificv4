<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian {{ date('Y-m-d H:i:s') }}</title>
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
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP PEMBELILAN SUPPLIER<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE SUPPLIER</th>
                        <th>NAMA SUPPLIER</th>
                        <th>DEBET</th>
                        <th>KREDIT</t>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                    @endphp
                    @foreach ($rekapsupplier as $d)
                        @php
                            $grandtotal += $d->total;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_supplier }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->total) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 10,
        'shadow': true,
    });
</script> --}}
