<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pelanggaran {{ $tahun }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&display=swap');

        body {
            font-family: 'Poppins';
        }

        .datatable3 {
            border: 2px solid #D6DDE6;
            border-collapse: collapse;
            font-size: 11px;
        }

        .datatable3 td {
            border: 1px solid #000000;
            padding: 6px;
        }

        .datatable3 th {
            border: 2px solid #828282;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <b style="font-size:14pt">REKAP LAPORAN PELANGGARAN<br>
        TAHUN {{ $tahun }}
    </b>
    <br>
    <table class="datatable3" style="width:100%">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama Karyawan</th>
                <th rowspan="2">Departemen</th>
                <th rowspan="2">Jabatan</th>
                <th colspan="4">Jenis SP</th>
                <th rowspan="2">Jumlah</th>
            </tr>
            <tr>
                <th>STT</th>
                <th>SP1</th>
                <th>SP2</th>
                <th>SP3</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggaran as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->nik }}</td>
                    <td>{{ textUpperCase($d->nama_karyawan) }}</td>
                    <td>{{ $d->nama_dept }}</td>
                    <td>{{ textUpperCase($d->nama_jabatan) }}</td>
                    <td style="text-align: center">{{ $d->stt }}</td>
                    <td style="text-align: center">{{ $d->sp1 }}</td>
                    <td style="text-align: center">{{ $d->sp2 }}</td>
                    <td style="text-align: center">{{ $d->sp3 }}</td>
                    <td style="text-align: center">{{ $d->jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
