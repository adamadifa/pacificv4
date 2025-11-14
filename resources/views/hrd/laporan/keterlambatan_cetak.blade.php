<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Keterlambatan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/js/freeze-table.js') }}"></script>
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
            REKAP KETERLAMBATAN <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari_tanggal) }} s/d {{ DateToIndo($sampai_tanggal) }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nik</th>
                    <th>Nama Karyawan</th>
                    <th>Cabang</th>
                    <th>Departemen</th>
                    <th>Grup</th>
                    <th>Jabatan</th>
                    <th>Jam Masuk</th>
                    <th>Jam In</th>
                    <th>Terlambat</th>
                    <th>Status Izin Terlambat</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keterlambatan as $d)
                    @php
                        $jam_in = !empty($d->jam_in) ? date('Y-m-d H:i', strtotime($d->jam_in)) : 'Belum Absen';
                        $jam_masuk = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_masuk));
                        $terlambat = presensiHitungJamTerlambat($jam_in, $jam_masuk);
                        if ($terlambat['status']) {
                            $jterlambat =
                                $terlambat['jamterlambat'] <= 9
                                    ? '0' . $terlambat['jamterlambat']
                                    : $terlambat['jamterlambat'];
                            $mterlambat =
                                $terlambat['menitterlambat'] <= 9
                                    ? '0' . $terlambat['menitterlambat']
                                    : $terlambat['menitterlambat'];
                            $format_terlambat = $jterlambat . ':' . $mterlambat;

                            // Hitung Denda
                            $denda = presensiHitungDenda(
                                $terlambat['jamterlambat'],
                                $terlambat['menitterlambat'],
                                $d->kode_izin_terlambat,
                                $d->kode_dept,
                                $d->kode_jabatan,
                            );
                        } else {
                            $format_terlambat = '-';
                            $denda = ['denda' => 0, 'keterangan' => ''];
                        }
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->nama_karyawan }}</td>
                        <td>{{ $d->nama_cabang }}</td>
                        <td>{{ $d->nama_dept }}</td>
                        <td>{{ $d->nama_group }}</td>
                        <td>{{ $d->nama_jabatan }}</td>
                        <td>{{ $d->jam_masuk }}</td>
                        <td>{{ $d->jam_in }}</td>
                        <td style="font-weight: bold; {{ $terlambat['status'] ? 'color: red;' : 'color: green;' }}">
                            {{ $format_terlambat }}
                        </td>
                        <td style="text-align: center;">
                            {{ $d->kode_izin_terlambat }}
                            {{ !empty($d->kode_izin_terlambat) ? 'Sudah Izin' : '-' }}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {{ !empty($denda['denda']) ? formatAngka($denda['denda']) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
