<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Hasil Produksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>REKAP HASIL PRODUKSI</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="body">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>PRODUK</th>
                    <th style="text-align: center">MINGGU 1 <br> TGL 01 - 07</th>
                    <th style="text-align: center">MINGGU 2 <br> TGL 08 - 14</th>
                    <th style="text-align: center">MINGGU 3 <br> TGL 15 - 21</th>
                    <th style="text-align: center">MINGGU 4 <br> TGL 22 - 31</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ textUpperCase($d->nama_produk) }}</td>
                        <td class="right">{{ formatAngka($d->minggu_1) }}</td>
                        <td class="right">{{ formatAngka($d->minggu_2) }}</td>
                        <td class="right">{{ formatAngka($d->minggu_3) }}</td>
                        <td class="right">{{ formatAngka($d->minggu_4) }}</td>
                        <td class="right">{{ formatAngka($d->total_hasilproduksi) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
