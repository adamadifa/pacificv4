<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bahan dan Kemasan {{ date('Y-m-d H:i:s') }}</title>
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
            BAHAN DAN KEMASAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE</th>
                        <th>NAMA BAHAN</th>
                        <th>JENIS</th>
                        <th>SATUAN</th>
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>QTY(gram)</th>
                        <th>HARGA(gram)</th>
                        <th>Jurnal Koreksi</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $grandtotal = 0;
                        $subtotal_per_jenis = 0;
                    @endphp
                    @foreach ($bahankemasan as $key => $d)
                        @php
                            $kode_jenis_barang = @$bahankemasan[$key + 1]->kode_jenis_barang;
                            $harga = $d->totalharga / $d->totalqty;
                            $totalharga = $d->totalharga - $d->jml_jk;
                            $grandtotal += $totalharga;
                            $subtotal_per_jenis += $totalharga;

                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $jenis_barang[$d->kode_jenis_barang] }}</td>
                            <td>{{ $d->satuan }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->totalqty) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga) }}</td>
                            @if ($d->satuan == 'KG')
                                @php
                                    $totalqtygram = $d->totalqty * 1000;
                                    $hargapergram = $d->totalharga / $totalqtygram;
                                @endphp
                            @else
                                @php
                                    $totalqtygram = $d->totalqty;
                                    $hargapergram = $d->totalharga / $d->totalqty;
                                @endphp
                            @endif
                            <td class="right">{{ formatAngkaDesimal($totalqtygram) }}</td>
                            <td class="right">{{ formatAngkaDesimal($hargapergram) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jml_jk) }}</td>
                            <td class="right">{{ formatAngkaDesimal($totalharga) }}</td>
                        </tr>
                        @if ($kode_jenis_barang != $d->kode_jenis_barang)
                            <tr>
                                <th colspan="10">TOTAL {{ $jenis_barang[$d->kode_jenis_barang] }}</th>
                                <th class="right">{{ formatAngkaDesimal($subtotal_per_jenis) }}</th>
                            </tr>
                            @php
                                $subtotal_per_jenis = 0;
                            @endphp
                        @endif
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="10">TOTAL</th>
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
