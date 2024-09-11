<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelanggan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>DATA PELANGGAN</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4>
        {{-- <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4> --}}
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                    <th>Kode Pelanggan</th>
                    <th>Tanggal Register</th>
                    <th>NIK</th>
                    <th>No. KK</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Pelanggan</th>
                    <th>Alamat Toko</th>
                    <th>No. HP Pelanggan</th>
                    <th>Kode Wilayah</th>
                    <th>Hari</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status Lokasi</th>
                    <th>LJT</th>
                    <th>Status Outlet</th>
                    <th>Type Outlet</th>
                    <th>Cara Pembayaran</th>
                    <th>Kepemilikan</th>
                    <th>Lama Berjualan</th>
                    <th>Jaminan</th>
                    <th>Omset Toko</th>
                    <th>Foto</th>
                    <th>Limit Pelanggan</th>
                    <th>Kode Salesman</th>
                    <th>Kode Cabang</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>

</html>
