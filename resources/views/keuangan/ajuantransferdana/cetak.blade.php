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
        <h4>AJUAN TRANSFER DANA</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Bank</th>
                    <th>No. Rekening</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Cabang</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($ajuantransfer as $d)
                    @php
                        $total += $d->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $d->no_pengajuan }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->nama_bank }}</td>
                        <td>{{ $d->no_rekening }}</td>
                        <td class="right">{{ formatAngka($d->jumlah) }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td>{{ $d->nama_cabang }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">TOTAL</th>
                    <th class="right">{{ formatAngka($total) }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
