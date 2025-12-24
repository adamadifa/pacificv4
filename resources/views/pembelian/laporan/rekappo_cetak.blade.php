<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Rekap PO {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PO<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NO PO</th>
                        <th>SUPPLIER</th>
                        <th>TANGGAL</th>
                        <th>KODE BARANG</th>
                        <th>NAMA BARANG</th>
                        <th class="right">QTY</th>
                        <th class="right">HARGA</th>
                        <th class="right">SUBTOTAL</th>
                        <th class="right">SUDAH DATANG</th>
                        <th class="right">SISA</th>
                        <th class="right">STATUS</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $grandtotal = 0;
                        $no = 1;
                    @endphp

                    @foreach ($rekappo as $kode_supplier => $items)
                        @foreach ($items as $d)
                            @php
                                $grandtotal += $d->qty_po * $d->harga;
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $d->no_bukti }}</td>
                                <td>{{ $d->nama_supplier }}</td>
                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                <td>{{ $d->kode_barang }}</td>
                                <td>{{ $d->nama_barang }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->qty_po) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->qty_po * $d->harga) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->qty_beli) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->sisa_po) }}</td>
                                <td>
                                    @if ($d->status_po == 'CLOSE')
                                        <span style="color:green;font-weight:bold">CLOSE</span>
                                    @else
                                        <span style="color:red;font-weight:bold">OPEN</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="8">GRAND TOTAL</th>
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
