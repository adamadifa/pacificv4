<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN PEMBELIAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($supplier != null)
            <h4>
                {{ $supplier->kode_supplier }} - {{ $supplier->nama_supplier }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3" style="width: 130%">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TGL</th>
                    <th>NO BUKTI</th>
                    <th>SUPPLIER</th>
                    <th>NAMA BARANG</th>
                    <th>KETERANGAN</th>
                    <th>JENIS TRANSAKSI</th>
                    <th>PCF/MP</th>
                    <th>AKUN</th>
                    <th>JURNAL</th>
                    <th>PPN</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>SUBTOTAL</th>
                    <th>PENYESUAIAN</th>
                    <th>TOTAL</th>
                    <th>DEBET</th>
                    <th>KREDIT</th>
                    <th>KATEGORI</th>
                    <th>TANGGAL INPUT</th>
                    <th>TANGGAL UPDATE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelian as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $d->nama_supplier }}</td>
                        <td>{{ $d->nama_barang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
