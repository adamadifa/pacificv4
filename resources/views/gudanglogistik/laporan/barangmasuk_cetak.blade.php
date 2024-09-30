<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Barang Masuk Gudang Logistik {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG MASUK GUDANG LOGISTIK<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>{{ $kategori != null ? 'KATEGORI :' . textUpperCase($kategori->nama_kategori) : '' }}</h4>
        @if ($barang != null)
            <h4>KODE BARANG : {{ $barang->kode_barang }}</h4>
            <h4>NAMA BARANG : {{ textUpperCase($barang->nama_barang) }}</h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">TANGGAL</th>
                    <th rowspan="2">SUPPLIER</th>
                    <th rowspan="2">BUKTI</th>
                    @can('pembelian.harga')
                        <th colspan="9">BARANG MASUK</th>
                    @else
                        <th colspan="6">BARANG MASUK</th>
                    @endcan
                </tr>
                <tr>
                    <th>KODE</th>
                    <th style="width: 15%">NAMA BARANG</th>
                    <th>SATUAN</th>
                    <th style="width: 20%">KETERANGAN</th>
                    <th>AKUN</th>
                    <th>QTY</th>
                    @can('pembelian.harga')
                        <th>HARGA</th>
                        <th>PENY</th>
                        <th>SUBTOTAL</th>
                    @endcan

                </tr>
            </thead>
            <tbody>
                @php
                    $total_qty = 0;
                    $grandtotal = 0;
                @endphp
                @foreach ($barangmasuk as $d)
                    @php
                        $subtotal = $d->jumlah * $d->harga + $d->penyesuaian;
                        $total_qty += $d->jumlah;
                        $grandtotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->nama_supplier }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textUpperCase($d->nama_barang) }}</td>
                        <td>{{ textUpperCase($d->satuan) }}</td>
                        <td>{{ textCamelCase($d->keterangan) }}</td>
                        <td>{{ $d->kode_akun . '-' . textCamelCase($d->nama_akun) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        @can('pembelian.harga')
                            <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($subtotal) }}</td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @can('pembelian.harga')
                    <th colspan="10" bgcolor="#024a75">TOTAL</th>
                @else
                    <th colspan="9" bgcolor="#024a75">TOTAL</th>
                @endcan
                <th class="right">{{ formatAngkaDesimal($total_qty) }}</td>
                    @can('pembelian.harga')
                    <th></th>
                    <th class="right">{{ formatAngkaDesimal($grandtotal) }}</td>
                    @endcan
            </tfoot>
        </table>
    </div>
</body>
