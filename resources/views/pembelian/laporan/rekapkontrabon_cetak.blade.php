<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Kontrabon {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP KONTRABON<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 60%">
                <thead>
                    <th>NO</th>
                    <th>NO KONTRABON</th>
                    <th>NAMA SUPPLIER</th>
                    <th>KETERANGAN</th>
                    <th>TOTAL</th>
                    <th>NO REKENING</th>
                </thead>
                <tbody>
                    @php
                        $totalpf = 0;
                    @endphp
                    @foreach ($pf as $p)
                        @php
                            $totalpf += $p->jumlah;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->no_dokumen }}</td>
                            <td>{{ $p->nama_supplier }}</td>
                            <td>FP</td>
                            <td class="right">{{ formatAngkaDesimal($p->jumlah) }}</td>
                            <td class="center">{{ $p->no_rekening_supplier }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($totalpf) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <div style="margin-top:50px"></div>
            <table class="datatable3" style="width: 60%">
                <thead>
                    <th>NO</th>
                    <th>NO KONTRABON</th>
                    <th>NAMA SUPPLIER</th>
                    <th>KETERANGAN</th>
                    <th>TOTAL</th>
                    <th>NO REKENING</th>
                </thead>
                <tbody>
                    @php
                        $totalkb = 0;
                    @endphp
                    @foreach ($kb as $k)
                        @php
                            $totalkb += $k->jumlah;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->no_dokumen }}</td>
                            <td>{{ $k->nama_supplier }}</td>
                            <td>KB</td>
                            <td class="right">{{ formatAngkaDesimal($k->jumlah) }}</td>
                            <td class="center">{{ $k->no_rekening_supplier }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($totalkb) }}</th>
                        <th></th>
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
