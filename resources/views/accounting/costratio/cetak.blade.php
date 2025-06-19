<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajuan Transfer Dana {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>COSTRATIO</h4>
        {{-- <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4> --}}
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                <tr>
                    <th style="width: 10%">Kode CR</th>
                    <th style="width: 10%">Tanggal</th>
                    <th style="width: 20%">Akun</th>
                    <th style="width: 25%">Keterangan</th>
                    <th>Jumlah</th>
                    <th>Sumber</th>
                    <th>Cabang</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                @foreach ($costratio as $d)
                    <tr>
                        <td>{{ $d->kode_cr }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->kode_akun }}- {{ $d->nama_akun }}</td>
                        <td>{{ textCamelCase($d->keterangan) }}</td>
                        <td style="text-align:right">{{ formatAngka($d->jumlah) }}</td>
                        <td>{{ $d->sumber }}</td>
                        <td>{{ textUpperCase($d->nama_cabang) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
