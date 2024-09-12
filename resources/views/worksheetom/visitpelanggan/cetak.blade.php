<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visit Pelanggan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>Visit Pelanggan</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Faktur</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tgl Faktur</th>
                    <th>Hasil Konfirmasi</th>
                    <th>Note</th>
                    <th>Saran / Keluhan Produk</th>
                    <th>Act OM</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($visit as $d)
                    <tr>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td>{{ $d->alamat_pelanggan }}</td>
                        <td>{{ formatIndo($d->tanggal_faktur) }}</td>
                        <td>{{ $d->hasil_konfirmasi }}</td>
                        <td>{{ $d->note }}</td>
                        <td>{{ $d->saran }}</td>
                        <td>{{ $d->act_om }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
