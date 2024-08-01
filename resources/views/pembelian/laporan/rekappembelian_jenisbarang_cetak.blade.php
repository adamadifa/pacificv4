<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Pembelian {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PEMBELILAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NO BUKTI</th>
                        <th>KODE SUPPLIER</th>
                        <th>NAMA SUPPLIER</th>
                        <th>NAMA BARANG</th>
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>SUBTOTAL</th>
                        <th>PENYESUAIAN</th>
                        <th>JURNAL KOREKSI</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                        $subtotal_jenisbarang = 0;
                    @endphp
                    @foreach ($rekappembelian as $key => $d)
                        @php
                            $kode_jenis_barang = @$rekappembelian[$key + 1]->kode_jenis_barang;
                            $subtotal = $d->harga * $d->jumlah;
                            $total = $subtotal + $d->penyesuaian - $d->jml_jk;
                            $grandtotal += $total;
                            if ($d->ppn == '1') {
                                $bgcolor = '#ececc8';
                            } else {
                                $bgcolor = '';
                            }
                            $subtotal_jenisbarang += $total;
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_bukti }}</td>
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
                        @if ($kode_jenis_barang != $d->kode_jenis_barang)
                            <tr>
                                <th colspan="10">TOTAL {{ $jenis_barang[$d->kode_jenis_barang] }}</th>
                                <th class="right">{{ formatAngkaDesimal($subtotal_jenisbarang) }}</th>
                            </tr>
                            @php
                                $subtotal_jenisbarang = 0;
                            @endphp
                        @endif
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
