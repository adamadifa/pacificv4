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

        .datatable3 td {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
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
            <table class="datatable3" style="width: 230%">
                <thead>
                    <tr>
                        <th rowspan="3">No</th>
                        <th rowspan="3">Nik</th>
                        <th rowspan="3">Nama Karyawan</th>
                        <th colspan="{{ $jmlhari }}">Tanggal</th>
                    </tr>
                    <tr>
                        @php
                            $tanggal_presensi = $start_date;
                        @endphp
                        @while (strtotime($tanggal_presensi) <= strtotime($end_date))
                            <th>{{ getNamahari(date('Y-m-d', strtotime($tanggal_presensi))) }}</th>
                            @php
                                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                            @endphp
                        @endwhile
                    </tr>
                    <tr>
                        @php
                            $tanggal_presensi = $start_date;
                        @endphp
                        @while (strtotime($tanggal_presensi) <= strtotime($end_date))
                            <th>{{ date('d', strtotime($tanggal_presensi)) }}</th>
                            @php
                                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
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
                                $tanggal_presensi = $start_date;
                            @endphp
                            @while (strtotime($tanggal_presensi) <= strtotime($end_date))
                                @if (isset($d[$tanggal_presensi]))
                                    @php
                                        $lintashari = $d[$tanggal_presensi]['lintashari'];
                                        $tanggal_selesai =
                                            $lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi;
                                        $total_jam_jadwal = $d[$tanggal_presensi]['total_jam'];
                                        //Jadwal Jam Kerja
                                        $j_mulai = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_mulai']));
                                        $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_selesai']));

                                        //Jadwal SPG
                                        //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                        $jam_mulai = in_array($d['kode_jabatan'], ['J22', 'J23']) ? $jam_in : $j_mulai;
                                        $jam_selesai = in_array($d['kode_jabatan'], ['J22', 'J23']) ? $jam_out : $j_selesai;
                                    @endphp
                                    @if ($d[$tanggal_presensi]['status'] == 'h')
                                        <td style="padding: 10px">
                                            <!-- Jika Status Hadir -->
                                            @php
                                                $istirahat = $d[$tanggal_presensi]['istirahat'];

                                                //Jam Absen Masuk dan Pulang
                                                $jam_in = !empty($d[$tanggal_presensi]['jam_in'])
                                                    ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_in']))
                                                    : 'Belum Absen';
                                                $jam_out = !empty($d[$tanggal_presensi]['jam_out'])
                                                    ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_out']))
                                                    : 'Belum Absen';
                                                $color_in = !empty($d[$tanggal_presensi]['jam_in']) ? '' : 'red';
                                                $color_out = !empty($d[$tanggal_presensi]['jam_out']) ? '' : 'red';

                                                // Jam Keluar
                                                $jam_keluar = !empty($d[$tanggal_presensi]['jam_keluar'])
                                                    ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_keluar']))
                                                    : '';
                                                $jam_kembali = !empty($d[$tanggal_presensi]['jam_kembali'])
                                                    ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_kembali']))
                                                    : '';

                                                //Istirahat
                                                if ($istirahat == '1') {
                                                    if ($lintashari == '0') {
                                                        $jam_awal_istirahat = date(
                                                            'Y-m-d H:i',
                                                            strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_awal_istirahat']),
                                                        );
                                                        $jam_akhir_istirahat = date(
                                                            'Y-m-d H:i',
                                                            strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_akhir_istirahat']),
                                                        );
                                                    } else {
                                                        $jam_awal_istirahat = date(
                                                            'Y-m-d H:i',
                                                            strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_awal_istirahat']),
                                                        );
                                                        $jam_akhir_istirahat = date(
                                                            'Y-m-d H:i',
                                                            strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_akhir_istirahat']),
                                                        );
                                                    }
                                                } else {
                                                    $jam_awal_istirahat = null;
                                                    $jam_akhir_istirahat = null;
                                                }

                                                //Cek Terlambat
                                                $terlambat = presensiHitungJamTerlambat($jam_in, $jam_mulai);

                                                //Hitung Denda
                                                $denda = presensiHitungDenda(
                                                    $terlambat['jamterlambat'],
                                                    $terlambat['menitterlambat'],
                                                    $d[$tanggal_presensi]['kode_izin_terlambat'],
                                                    $d['kode_dept'],
                                                );

                                                //Cek Pulang Cepat
                                                $pulangcepat = presensiHitungPulangCepat($jam_out, $jam_selesai);

                                                //Cek Izin Keluar
                                                $izin_keluar = presensiHitungJamKeluarKantor(
                                                    $jam_keluar,
                                                    $jam_kembali,
                                                    $jam_selesai,
                                                    $jam_out,
                                                    $total_jam_jadwal,
                                                    $istirahat,
                                                    $jam_awal_istirahat,
                                                    $jam_akhir_istirahat,
                                                );

                                                //Potongan Jam
                                                $potongan_jam_sakit = 0;
                                                $potongan_jam_pulangcepat =
                                                    $d[$tanggal_presensi]['izin_pulang_direktur'] == '1' ? 0 : $pulangcepat['desimal'];
                                                $potongan_jam_izinkeluar =
                                                    $d[$tanggal_presensi]['izin_keluar_direktur'] == '1' ? 0 : $izin_keluar['desimal'];
                                                $potongan_jam_terlambat =
                                                    $d[$tanggal_presensi]['izin_terlambat_direktur'] == '1' ? 0 : $terlambat['desimal'];

                                                //Total Potongan
                                                $total_potongan_jam =
                                                    $potongan_jam_sakit +
                                                    $potongan_jam_pulangcepat +
                                                    $potongan_jam_izinkeluar +
                                                    $potongan_jam_terlambat;

                                                //Total Jam Kerja
                                                $total_jam = $total_jam_jadwal - $total_potongan_jam;
                                            @endphp
                                            <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <!-- Jam Masuk dan Pulang -->
                                            <p style="margin:0">
                                                <span style="color: {{ $color_in }}">{{ date('H:i', strtotime($jam_in)) }}</span>
                                                - <span style="color: {{ $color_out }}">{{ date('H:i', strtotime($jam_out)) }}</span>
                                            </p>
                                            <!-- Terlambat -->
                                            <p style="margin:0">
                                                <span style="color: {{ $terlambat['color'] }}"> {{ $terlambat['keterangan'] }}
                                                    <br>
                                                    {{ !empty($denda['denda']) ? '(' . formatAngka($denda['denda']) . ')' : '' }}
                                                </span>
                                            </p>
                                            <!-- Pulang Cepat -->
                                            <p style="margin:0">
                                                <span style="color: {{ $pulangcepat['color'] }}"> {{ $pulangcepat['keterangan'] }}</span>
                                            </p>
                                            <!-- Izin Keluar -->
                                            <p style="margin:0">
                                                <span style="color: {{ $izin_keluar['color'] }}"> {{ $izin_keluar['keterangan'] }}</span>
                                            </p>

                                            <!-- Total Jam Kerja -->
                                            <p style="margin:0">
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p>
                                        </td>
                                    @elseif($d[$tanggal_presensi]['status'] == 's')
                                        @if (!empty($d[$tanggal_presensi]['doc_sid']) || !empty($d[$tanggal_presensi]['izin_sakit_direktur']))
                                            @php
                                                $total_jam = $total_jam_jadwal;
                                                $potongan_sakit = 0;
                                                $keterangan = 'SID';
                                            @endphp
                                        @else
                                            @php
                                                $total_jam = 0;
                                                $potongan_sakit = $total_jam_jadwal;
                                                $keterangan = '';
                                            @endphp
                                        @endif

                                        <td style="padding: 10px; background-color: #f4858e">
                                            <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">SAKIT {{ !empty($keterangan) ? '(' . $keterangan . ')' : '' }}</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p>
                                        </td>
                                    @elseif($d[$tanggal_presensi]['status'] == 'c')
                                        @php
                                            $total_jam = $total_jam_jadwal;
                                        @endphp
                                        <td style="padding: 10px; background-color: #1794e1d3">
                                            <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">CUTI ({{ $d[$tanggal_presensi]['nama_cuti'] }})</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p>
                                        </td>
                                    @elseif($d[$tanggal_presensi]['status'] == 'i')
                                        @php
                                            if ($d[$tanggal_presensi]['izin_absen_direktur'] == '1') {
                                                $total_jam = $total_jam_jadwal;
                                                $potongan_jam_izin = 0;
                                            } else {
                                                $total_jam = 0;
                                                $potongan_jam_izin = $total_jam_jadwal;
                                            }
                                        @endphp
                                        <td style="padding: 10px; background-color: #d74405dc">
                                            <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">IZIN</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @else
                                    @php
                                        $search = [
                                            'nik' => $d['nik'],
                                            'tanggal' => $tanggal_presensi,
                                        ];
                                        $cekdirumahkan = ceklibur($datadirumahkan, $search); // Cek Dirumahkan
                                    @endphp
                                    @if (getNamahari($tanggal_presensi) == 'Minggu')
                                        @php
                                            $color = 'rgba(243, 158, 0, 0.833)';
                                            $keterangan = '';
                                            $total_jam = 0;
                                        @endphp
                                    @elseif(!empty($cekdirumahkan))
                                        @php
                                            $color = 'rgb(69, 2, 140)';
                                            $keterangan = 'Dirumahkan';
                                            if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                $total_jam = 2.5;
                                            } else {
                                                $total_jam = 7;
                                            }
                                        @endphp
                                    @else
                                        @php
                                            $color = '';
                                            $keterangan = '';
                                            $total_jam = 0;
                                        @endphp
                                    @endif
                                    <td style="background-color: {{ $color }}; color:white">
                                        {{ $keterangan }}
                                        <br>
                                        @if (!empty($total_jam))
                                            <span style="font-weight: bold ;color:#fae603">Total Jam :{{ $total_jam }}</span>
                                        @endif
                                    </td>
                                @endif
                                @php
                                    $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
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
