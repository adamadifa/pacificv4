<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Kas Besar Cabang {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN KAS BESAR PENJUALAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>CABANG</th>
                        <th>Cash IN</th>
                        <th>Voucher</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cash_in = 0;
                        $total_voucher = 0;
                        $grandtotal = 0;
                    @endphp
                    @foreach ($rekap as $d)
                        @php
                            $total_cash_in += $d->cash_in;
                            $total_voucher += $d->voucher;
                            $grandtotal += $d->total;
                        @endphp
                        <tr>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="right">{{ formatAngka($d->cash_in) }}</td>
                            <td class="right">{{ formatAngka($d->voucher) }}</td>
                            <td class="right">{{ formatAngka($d->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="right">{{ formatAngka($total_cash_in) }}</th>
                        <th class="right">{{ formatAngka($total_voucher) }}</th>
                        <th class="right">{{ formatAngka($grandtotal) }}</th>
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
