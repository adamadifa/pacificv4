<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANALISA UMUR HUTANG {{ date('Y-m-d H:i:s') }}</title>
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
            ANALISA UMUR HUTANG<br>
        </h4>
        <h4> PER TANGGAL {{ DateToIndo($tanggal) }} </h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">KODE SUPPLIER</th>
                        <th rowspan="2">NAMA SUPPLIER</th>
                        <th colspan="4">SALDO HUTANG</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr style="text-align:center">
                        <th>BULAN BERJALAN</th>
                        <th>1 BULAN</th>
                        <th>2 BULAN</th>
                        <th>LEBIH 3 BULAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $grandtotalbulanberjalan = 0;
                        $grandtotalsatubulan = 0;
                        $grandtotalduabulan = 0;
                        $grandtotallebihtigabulan = 0;
                        $grandtotal = 0;

                        $bulanberjalan = 0;
                        $satubulan = 0;
                        $duabulan = 0;
                        $lebihtigabulan = 0;
                        $total = 0;
                        $supplier = '';
                    @endphp
                    @foreach ($auh as $key => $d)
                        @php
                            $kode_supplier = @$auh[$key + 1]->kode_supplier;
                            $bulanberjalan += $d->bulanberjalan;
                            $satubulan += $d->satubulan;
                            $duabulan += $d->duabulan;
                            $lebihtigabulan += $d->lebihtigabulan;
                            $total = $bulanberjalan + $satubulan + $duabulan + $lebihtigabulan;
                        @endphp

                        @if ($kode_supplier != $d->kode_supplier)
                            @php
                                $grandtotalbulanberjalan += $bulanberjalan;
                                $grandtotalsatubulan += $satubulan;
                                $grandtotalduabulan += $duabulan;
                                $grandtotallebihtigabulan += $lebihtigabulan;
                                $grandtotal += $total;
                            @endphp
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $d->kode_supplier }}</td>
                                <td>{{ $d->nama_supplier }}</td>
                                <td class="right">{{ formatAngkaDesimal($bulanberjalan) }}</td>
                                <td class="right">{{ formatAngkaDesimal($satubulan) }}</td>
                                <td class="right">{{ formatAngkaDesimal($duabulan) }}</td>
                                <td class="right">{{ formatAngkaDesimal($lebihtigabulan) }}</td>
                                <td class="right">{{ formatAngkaDesimal($total) }}</td>

                            </tr>
                            @php
                                $bulanberjalan = 0;
                                $satubulan = 0;
                                $duabulan = 0;
                                $lebihtigabulan = 0;
                                $total = 0;
                                $no++;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotalbulanberjalan) }}</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotalsatubulan) }}</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotalduabulan) }}</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotallebihtigabulan) }}</th>
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
