<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi {{ date('Y-m-d H:i:s') }}</title>
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
            PRESENSI KARYAWAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($start_date) }} s/d {{ DateToIndo($end_date) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 200%">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nik</th>
                        <th rowspan="2">Nama Karyawan</th>
                        <th colspan="{{ $jmlhari }}">Tanggal</th>
                    </tr>
                    <tr>
                        @php
                            $tanggal_mulai = $start_date;
                        @endphp
                        @while (strtotime($tanggal_mulai) <= strtotime($end_date))
                            <th>{{ date('d', strtotime($tanggal_mulai)) }}</th>
                            @php
                                $tanggal_mulai = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_mulai)));
                            @endphp
                        @endwhile
                    </tr>
                </thead>
                <tbody>
                    @foreach ($presensi as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d['nik'] }}</td>
                            <td>{{ $d['nama_karyawan'] }}</td>
                            @php
                                $tanggal_mulai = $start_date;
                            @endphp
                            @while (strtotime($tanggal_mulai) <= strtotime($end_date))
                                <td>
                                    <!-- Jika Status Hadir -->
                                    @if (isset($d[$tanggal_mulai]))
                                        @if ($d[$tanggal_mulai]['status'] == 'h')
                                            @php
                                                $jam_in = !empty($d[$tanggal_mulai]['jam_in'])
                                                    ? date('H:i', strtotime($d[$tanggal_mulai]['jam_in']))
                                                    : 'Belum Absen';
                                                $jam_out = !empty($d[$tanggal_mulai]['jam_out'])
                                                    ? date('H:i', strtotime($d[$tanggal_mulai]['jam_out']))
                                                    : 'Belum Absen';
                                            @endphp
                                            {{ $jam_in }} - {{ $jam_out }}
                                        @endif
                                    @endif
                                </td>
                                @php
                                    $tanggal_mulai = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_mulai)));
                                @endphp
                            @endwhile
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
