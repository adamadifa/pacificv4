<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Barang Masuk Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG MASUK GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if (!empty($barang))
            <h4>KODE BARANG : {{ $barang->kode_barang }}</h4>
            <h4>NAMA BARANG : {{ $barang->nama_barang }}</h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>

                    <th rowspan="2">NO</th>
                    <th rowspan="2">TANGGAL</th>
                    <th rowspan="2">NO. BUKTI</th>
                    <th rowspan="2">KODE</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th rowspan="2">KETERANGAN</th>
                    <th rowspan="2">ASAL BARANG</th>
                    <th colspan="3" class="green">QTY</th>
                </tr>
                <tr>
                    <th class="green">UNIT</th>
                    <th class="green">BERAT</th>
                    <th class="green">LEBIH</th>
                </tr>
            </thead>
            <tbody>


                @php
                    $total_qty_unit = 0;
                    $total_qty_berat = 0;
                    $total_qty_lebih = 0;
                @endphp
                @foreach ($barangmasuk as $d)
                    @php
                        $total_qty_unit += $d->qty_unit;
                        $total_qty_berat += $d->qty_berat;
                        $total_qty_lebih += $d->qty_lebih;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td>{{ $asal_barang[$d->kode_asal_barang] }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_unit) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_berat) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_lebih) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="8">TOTAL</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_unit) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_berat) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_qty_lebih) }}</th>
            </tfoot>
        </table>
    </div>
</body>
