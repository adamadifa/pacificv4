<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KASBON {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            KASBON <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($departemen != null)
            <h4>
                {{ textUpperCase($departemen->nama_dept) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Kasbon</th>
                        <th>Tanggal</th>
                        <th>Nik</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                    @endphp
                    @foreach ($kasbon as $d)
                        @php
                            $grandtotal += $d->jumlah;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_kasbon }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ textCamelCase($d->nama_karyawan) }}</td>
                            <td>{{ $d->nama_jabatan }}</td>
                            <td>{{ $d->nama_dept }}</td>
                            <td class="right" style="font-weight:bold">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">TOTAL</th>
                        <th class="right" style="font-weight:bold">{{ number_format($grandtotal, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
