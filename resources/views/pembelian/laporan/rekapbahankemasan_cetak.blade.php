<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Bahan Kemasan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP BAHAN KEMASAN<br>
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
                <thead>
                    <th>NO</th>
                    <th>NO BUKTI</th>
                    <th>TANGGAL</th>
                    <th>KODE SUPPLIER</th>
                    <th>NAMA SUPPLIER</th>
                    <th>NAMA BARANG</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>SUBTOTAL</th>
                    <th>PENYESUAIAN</th>
                    <th>JURNAL KOREKSI</th>
                    <th>TOTAL</th>
                </thead>
                <tbody>
                    @php
                        $qtypersupplier = 0;
                        $subtotalpersupplier = 0;
                        $totalpersupplier = 0;
                        $grandtotal = 0;
                        $grandtotalqty = 0;
                        $grandtotalsubtotal = 0;
                    @endphp
                    @foreach ($rekapbahankemasan as $key => $d)
                        @php
                            $subtotal = ROUND($d->jumlah * $d->harga, 2);
                            $total = $subtotal + $d->penyesuaian - $d->jml_jk;
                            $kode_supplier = @$rekapbahankemasan[$key + 1]->kode_supplier;

                            $qtypersupplier += $d->jumlah;
                            $subtotalpersupplier += $subtotal;
                            $totalpersupplier += $total;

                            $grandtotal += $total;
                            $grandtotalqty += $d->jumlah;
                            $grandtotalsubtotal += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_supplier }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($subtotal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jml_jk) }}</td>
                            <td class="right">{{ formatAngkaDesimal($total) }}</td>
                        </tr>
                        @if ($kode_supplier != $d->kode_supplier)
                            <tr>
                                <th colspan="6">TOTAL {{ $d->nama_supplier }}</th>
                                <th class="right">{{ formatAngkaDesimal($qtypersupplier) }}</th>
                                <th class="right"></th>
                                <th class="right">{{ formatAngkaDesimal($subtotalpersupplier) }}</th>
                                <th class="right"></th>
                                <th class="right"></th>
                                <th class="right">{{ formatAngkaDesimal($totalpersupplier) }}</th>
                            </tr>
                            @php
                                $qtypersupplier = 0;
                                $subtotalpersupplier = 0;
                                $totalpersupplier = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotalqty) }}</th>
                        <th class="right"></th>
                        <th class="right">{{ formatAngkaDesimal($grandtotalsubtotal) }}</th>
                        <th class="right"></th>
                        <th class="right"></th>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                    </tr>
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
