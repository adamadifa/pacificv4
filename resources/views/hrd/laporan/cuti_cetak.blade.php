<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/js/freeze-table.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}
    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }

        .datatable3 td {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
    </style>
</head>

<body>

    <div class="header">
        <h4 class="title">
            REKAP CUTI TAHUNAN <br>
        </h4>
        <h4>TAHUN {{ $tahun }}</h4>
    </div>
    <div class="content">
        {{-- <div class="freeze-table"> --}}
        <table class="datatable3"">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nik</th>
                    <th rowspan="2">Nama Karyawan</th>
                    <th rowspan="2">Cabang</th>
                    <th rowspan="2">Departemen</th>
                    <th rowspan="2">Grup</th>
                    <th rowspan="2">Jabatan</th>
                    <th colspan="12">Bulan</th>
                    <th rowspan="2">Kuota</th>
                    <th rowspan="2">Digunakan</th>
                    <th rowspan="2">Sisa</th>
                </tr>
                <tr>
                    @for ($i = 1; $i <= 12; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $kuotacuti = 12;
                @endphp
                @foreach ($cuti as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->nama_karyawan }}</td>
                        <td>{{ $d->nama_cabang }}</td>
                        <td>{{ $d->nama_dept }}</td>
                        <td>{{ $d->nama_group }}</td>
                        <td>{{ $d->nama_jabatan }}</td>
                        @php
                            $total_cuti = 0;
                        @endphp
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $total_cuti += $d->{'bulan_' . $i};
                            @endphp
                            @if (!empty($d->{'bulan_' . $i}))
                                <td style="background-color: green; color:white">{{ $d->{'bulan_' . $i} }}</td>
                            @else
                                <td style="background-color: red"></td>
                            @endif
                        @endfor
                        <td>{{ $kuotacuti }}</td>
                        <td>{{ $total_cuti }}</td>
                        <td>{{ $kuotacuti - $total_cuti }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>
