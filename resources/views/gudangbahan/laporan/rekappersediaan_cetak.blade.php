<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAPITULASI PERSEDIAAN GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>KATEGORI {{ $kategori->nama_kategori }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="4">NO</th>
                    <th rowspan="4">KODE BARANG</th>
                    <th rowspan="4">NAMA BARANG</th>
                    <th rowspan="4">SATUAN</th>
                </tr>
                <tr>
                    <th colspan="3" rowspan="2">SALDO AWAL
                    </th>
                    <th colspan="9">PEMASUKAN</th>
                    <th colspan="18">PENGELUARAN</th>
                    <th colspan="3" rowspan="2">SALDO AKHIR
                    </th>
                    <th colspan="3" rowspan="2">OPNAME STOK
                    </th>
                    <th colspan="2" rowspan="2">SELISIH</th>
                </tr>
                <tr bgcolor="red">
                    <th colspan="3">PEMBELIAN</th>
                    <th colspan="3">LAINNYA</th>
                    <th colspan="3">RETUR PENGGANTI</th>
                    <th colspan="3">PRODUKSI</th>
                    <th colspan="3">SEASONING</th>
                    <th colspan="3">PDQC</th>
                    <th colspan="3">SUSUT</th>
                    <th colspan="3">CABANG</th>
                    <th colspan="3">LAINNYA</th>
                </tr>
                <tr bgcolor="red">
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>QTY</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>
