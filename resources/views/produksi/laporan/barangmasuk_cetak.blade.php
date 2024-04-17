<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Barang Masuk Produksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG MASUK PRODUKSI<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>NO. BUKTI</th>
                    <th>ASAL BARANG</th>
                    <th>NAMA BARANG</th>
                    <th>SATUAN</th>
                    <th>KETERANGAN</th>
                    <th>QTY</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($barangmasuk as $d)
                    @php
                        $total += $d->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $asal_barang_produksi[$d->kode_asal_barang] }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
