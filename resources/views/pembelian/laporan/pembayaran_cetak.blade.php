<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembayaran {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN PEMBAYARAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($supplier != null)
            <h4>
                {{ $supplier->kode_supplier }} - {{ $supplier->nama_supplier }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">TGL</th>
                        <th rowspan="2">NO BUKTI</th>
                        <th rowspan="2">SUPPLIER</th>
                        <th rowspan="2">NO KONTRABON</th>
                        <th colspan="{{ count($bank) }}">BANK</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        @foreach ($bank as $d)
                            <th>{{ $d->nama_bank }}</th>
                            @php
                                ${"total_$d->kode_bank"} = 0;
                            @endphp
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                    @endphp
                    @foreach ($pembayaran as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tglbayar) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td>{{ $d->no_kontrabon }}</td>
                            @php
                                $total_bayar = 0;
                            @endphp
                            @foreach ($bank as $b)
                                @php
                                    $total_bayar += $d->{$b->kode_bank};
                                    ${"total_$b->kode_bank"} += $d->{$b->kode_bank};
                                @endphp
                                <td class="right">{{ !empty($d->{$b->kode_bank}) ? formatAngkaDesimal($d->{$b->kode_bank}) : '' }}</td>
                            @endforeach
                            <td class="right" style="font-weight: bold">{{ formatAngka($total_bayar) }}</td>
                        </tr>
                        @php
                            $grandtotal += $total_bayar;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="5">TOTAL</td>
                        @foreach ($bank as $b)
                            <td class="right" style="font-weight: bold">{{ formatAngkaDesimal(${"total_$b->kode_bank"}) }}</td>
                        @endforeach
                        <td class="right" style="font-weight: bold">{{ formatAngkaDesimal($grandtotal) }}</td>

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
