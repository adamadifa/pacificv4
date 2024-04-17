<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Mutasi Produksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">REKAP MUTASI PRODUKSI<br></h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Produk</th>
                    <th rowspan="2">Produk</th>
                    <th rowspan="2">Saldo Awal</th>
                    <th colspan="2" class="green">IN</th>
                    <th colspan="2" class="red">OUT</th>
                    <th rowspan="2" rowspan="2">SALDO AKHIR
                    </th>
                </tr>
                <tr>
                    <th class="green">BARANG HASIL PRODUKSI</th>
                    <th class="green">LAINNYA</th>
                    <th class="red">GUDANG</th>
                    <th class="red">LAINNYA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $d)
                    @php
                        $saldo_akhir = $d->jml_saldo_awal + $d->jml_bpbj - $d->jml_fsthp;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="right">{{ formatAngka($d->jml_saldo_awal) }}</td>
                        <td class="right">{{ formatAngka($d->jml_bpbj) }}</td>
                        <td></td>
                        <td class="right">{{ formatAngka($d->jml_fsthp) }}</td>
                        <td></td>
                        <td class="right">{{ formatAngka($saldo_akhir) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
