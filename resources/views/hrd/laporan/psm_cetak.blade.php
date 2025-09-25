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
            <table class="datatable3" style="width: 250%">
                <thead>
                    <tr>
                        <th rowspan="3">No</th>
                        <th rowspan="3">Nik</th>
                        <th rowspan="3">Nama Karyawan</th>
                        <th rowspan="3">Cabang</th>
                        <th colspan="{{ $jmlhari }}">Tanggal</th>
                        <th rowspan="3">Σ Jam (1 Bulan)</th>
                        <th rowspan="3">Telat</th>
                        <th rowspan="3">Dirumahkan</th>
                        <th rowspan="3">Keluar</th>
                        <th rowspan="3">PC</th>
                        <th rowspan="3">TH</th>
                        <th rowspan="3">Izin</th>
                        <th rowspan="3">Sakit</th>
                        <th rowspan="3">Σ Jam Kerja</th>
                        <th rowspan="3">Denda</th>
                        <th rowspan="3">Premi <br> Shift 2</th>
                        <th rowspan="3">Premi <br> Shift 3</th>
                        <th rowspan="3">OT1</th>
                        <th rowspan="3">OT2</th>
                        <th rowspan="3">OTL</th>
                        <th rowspan="2" colspan="3">Masuk SHIFT</th>
                        <th rowspan="2" colspan="7">Tidak Masuk Karena</th>
                        <th rowspan="3">Terlambat</th>
                        <th rowspan="2" colspan="4">TOTAL</th>
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
                        <th>P</th>
                        <th>S</th>
                        <th>M</th>

                        <th>SID</th>
                        <th>SKT</th>
                        <th>A</th>
                        <th>IK</th>
                        <th>C</th>
                        <th>ITH</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_jam_satubulan = 173;

                    @endphp
                    @foreach ($presensi as $d)
                        <tr>
                            <td style="width:1%">{{ $loop->iteration }}</td>
                            <td style="width:2%">'{{ $d['nik'] }}</td>
                            <td style="width:5%">{{ $d['nama_karyawan'] }}</td>
                            <td>{{ $d['kode_cabang'] }}</td>
                            @php
                                $tanggal_presensi = $start_date;
                                $total_potongan_jam_terlambat = 0;
                                $total_potongan_jam_dirumahkan = 0;
                                $total_potongan_jam_izinkeluar = 0;
                                $total_potongan_jam_pulangcepat = 0;
                                $total_potongan_jam_tidakhadir = 0;
                                $total_potongan_jam_izin = 0;
                                $total_potongan_jam_sakit = 0;
                                $grand_total_potongan_jam = 0;
                                $total_premi_shift2 = 0;
                                $total_premi_shift3 = 0;
                                $total_denda = 0;
                                $total_overtime_1 = 0;
                                $total_overtime_2 = 0;
                                $total_overtime_libur = 0;
                                $total_premi_shift2_lembur = 0;
                                $total_premi_shift3_lembur = 0;

                                $jml_hadir_pagi = 0;
                                $jml_hadir_siang = 0;
                                $jml_hadir_malam = 0;
                            @endphp
                            @while (strtotime($tanggal_presensi) <= strtotime($end_date))
                                @php
                                    $search = [
                                        'nik' => $d['nik'],
                                        'tanggal' => $tanggal_presensi,
                                    ];
                                    $cekdirumahkan = ceklibur($datadirumahkan, $search); // Cek Dirumahkan
                                    $cekliburnasional = ceklibur($dataliburnasional, $search); // Cek Libur Nasional
                                    $cektanggallimajam = ceklibur($datatanggallimajam, $search); // Cek Tanggal Lima Jam
                                    $cekliburpengganti = ceklibur($dataliburpengganti, $search); // Cek Libur Pengganti
                                    $cekminggumasuk = ceklibur($dataminggumasuk, $search); // Cek Minggu Masuk
                                    $ceklembur = ceklembur($datalembur, $search);
                                    $ceklemburharilibur = ceklembur($datalemburharilibur, $search);

                                    $lembur = presensiHitunglembur($ceklembur);
                                    $lembur_libur = presensiHitunglembur($ceklemburharilibur);

                                    $total_overtime_1 += $lembur['overtime_1'];
                                    $total_overtime_2 += $lembur['overtime_2'];

                                    if (!empty($cekliburnasional)) {
                                        if ($d['kode_jabatan'] == 'J20') {
                                            $overtime_libur = $lembur_libur['overtime_libur'] * 2;
                                        } else {
                                            $overtime_libur = $lembur_libur['overtime_libur'];
                                        }
                                    } else {
                                        $overtime_libur = $lembur_libur['overtime_libur'];
                                    }
                                    $total_overtime_libur += $overtime_libur;

                                    $total_premi_shift2_lembur += $lembur['jmlharilembur_shift_2'] + $lembur_libur['jmlharilembur_shift_2'];
                                    $total_premi_shift3_lembur += $lembur['jmlharilembur_shift_3'] + $lembur_libur['jmlharilembur_shift_3'];
                                @endphp
                                @if (isset($d[$tanggal_presensi]))
                                    @php
                                        $lintashari = $d[$tanggal_presensi]['lintashari'];
                                        $tanggal_selesai =
                                            $lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi;
                                        $total_jam_jadwal = $d[$tanggal_presensi]['total_jam'];
                                        //Jadwal Jam Kerja
                                        $j_mulai = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_mulai']));
                                        $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_selesai']));

                                        //Jam Absen Masuk dan Pulang
                                        $jam_in = !empty($d[$tanggal_presensi]['jam_in'])
                                            ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_in']))
                                            : 'Belum Absen';
                                        $jam_out = !empty($d[$tanggal_presensi]['jam_out'])
                                            ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_out']))
                                            : 'Belum Absen';

                                        //Jadwal SPG
                                        //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                        $jam_mulai =
                                            in_array($d['kode_jabatan'], ['J22', 'J23']) ||
                                            (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk))
                                                ? $jam_in
                                                : $j_mulai;
                                        $jam_selesai =
                                            in_array($d['kode_jabatan'], ['J22', 'J23']) ||
                                            (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk))
                                                ? $jam_out
                                                : $j_selesai;
                                    @endphp
                                    @if ($d[$tanggal_presensi]['status'] == 'h')
                                        @if (!empty($cekliburnasional))
                                            @php
                                                $color = 'green';
                                                $textcolor = 'white';
                                            @endphp
                                        @elseif(getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk))
                                            @php
                                                $color = 'rgba(243, 158, 0, 0.833)';
                                                $textcolor = 'white';
                                            @endphp
                                        @else
                                            @php
                                                $color = '';
                                                $textcolor = '';
                                            @endphp
                                        @endif
                                        <td style="padding: 10px; background-color: {{ $color }}; color: {{ $textcolor }} !important">
                                            <!-- Jika Status Hadir -->
                                            @php
                                                $istirahat = $d[$tanggal_presensi]['istirahat'];

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
                                                    $d['kode_jabatan'],
                                                );

                                                //Cek Pulang Cepat
                                                $pulangcepat = presensiHitungPulangCepat(
                                                    $jam_out,
                                                    $jam_selesai,
                                                    $jam_awal_istirahat,
                                                    $jam_akhir_istirahat,
                                                );

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
                                                    $d[$tanggal_presensi]['keperluan'],
                                                );

                                                //Potongan Jam
                                                $potongan_jam_sakit = 0;
                                                $potongan_jam_dirumahkan = 0;
                                                $potongan_jam_tidakhadir =
                                                    empty($d[$tanggal_presensi]['jam_in']) || empty($d[$tanggal_presensi]['jam_out'])
                                                        ? $total_jam_jadwal
                                                        : 0;
                                                $potongan_jam_izin = 0;
                                                $potongan_jam_pulangcepat =
                                                    $d[$tanggal_presensi]['izin_pulang_direktur'] == '1' ? 0 : $pulangcepat['desimal'];
                                                $potongan_jam_izinkeluar =
                                                    $d[$tanggal_presensi]['izin_keluar_direktur'] == '1' || $izin_keluar['desimal'] <= 1
                                                        ? 0
                                                        : $izin_keluar['desimal'];
                                                $potongan_jam_terlambat =
                                                    $d[$tanggal_presensi]['izin_terlambat_direktur'] == '1' ? 0 : $terlambat['desimal'];

                                                //Total Potongan
                                                $total_potongan_jam =
                                                    $potongan_jam_sakit +
                                                    $potongan_jam_pulangcepat +
                                                    $potongan_jam_izinkeluar +
                                                    $potongan_jam_terlambat +
                                                    $potongan_jam_dirumahkan +
                                                    $potongan_jam_tidakhadir +
                                                    $potongan_jam_izin;

                                                //Total Jam Kerja
                                                $total_jam =
                                                    !empty($d[$tanggal_presensi]['jam_in']) && !empty($d[$tanggal_presensi]['jam_out'])
                                                        ? $total_jam_jadwal - $total_potongan_jam
                                                        : 0;

                                                //Denda
                                                $jumlah_denda = $denda['denda'];
                                                $kode_shift = 'P';
                                                $jml_hadir_pagi += 1;

                                                //PREMI
                                                if ($d[$tanggal_presensi]['kode_jadwal'] == 'JD003') {
                                                    if ($total_jam >= 5 && empty($cekliburnasional) && getNamahari($tanggal_presensi) != 'Minggu') {
                                                        $total_premi_shift2 += 1;
                                                    }
                                                    $kode_shift = 'S';
                                                    $jml_hadir_siang += 1;
                                                }

                                                if ($d[$tanggal_presensi]['kode_jadwal'] == 'JD004') {
                                                    if ($total_jam >= 5 && empty($cekliburnasional) && getNamahari($tanggal_presensi) != 'Minggu') {
                                                        $total_premi_shift3 += 1;
                                                    }
                                                    $kode_shift = 'M';
                                                    $jml_hadir_malam += 1;
                                                }

                                            @endphp
                                            @if (!empty($ceklemburharilibur))
                                                @php
                                                    $kode_shift = $lembur_libur['kode_shift'];
                                                @endphp
                                            @endif
                                            {{ $kode_shift }}{{ $total_jam < $total_jam_jadwal ? $total_jam : '' }}
                                            @if (!empty($ceklembur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OT1 : {{ $lembur['overtime_1'] }}</span>
                                                    <br>
                                                    <span>OT2 : {{ $lembur['overtime_2'] }}</span>
                                                </p>
                                            @endif
                                            @if (!empty($ceklemburharilibur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OTL : {{ $overtime_libur }}</span>
                                                </p>
                                            @endif
                                            {{-- <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
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
                                            </p> --}}
                                        </td>
                                    @elseif($d[$tanggal_presensi]['status'] == 's')
                                        @php
                                            $potongan_jam_terlambat = 0;
                                            $potongan_jam_dirumahkan = 0;
                                            $potongan_jam_izinkeluar = 0;
                                            $potongan_jam_pulangcepat = 0;
                                            $potongan_jam_tidakhadir = 0;
                                            $potongan_jam_izin = 0;

                                            $jumlah_denda = 0;
                                        @endphp
                                        @if (!empty($d[$tanggal_presensi]['doc_sid']) || $d[$tanggal_presensi]['izin_sakit_direktur'] == '1')
                                            @php
                                                $total_jam = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                                                $potongan_jam_sakit = 0;
                                                if (!empty($cekdirumahkan)) {
                                                    $potongan_jam_dirumahkan = $total_jam_jadwal == 7 ? 1.75 : 1.25;
                                                }
                                                $keterangan = 'SID';
                                            @endphp
                                        @else
                                            @php
                                                $total_jam = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                                                $potongan_jam_sakit = !empty($cekdirumahkan) ? $total_jam : $total_jam;
                                                if (!empty($cekdirumahkan)) {
                                                    $potongan_jam_dirumahkan = $total_jam_jadwal == 7 ? 1.75 : 1.25;
                                                }
                                                $keterangan = 'SKT';
                                            @endphp
                                        @endif
                                        @if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21' && $tanggal_presensi < '2025-04-21')
                                            @php
                                                $potongan_jam_sakit = 0;
                                            @endphp
                                        @endif

                                        <td style="padding: 10px; background-color: #f4858e">
                                            {{-- <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">SAKIT {{ !empty($keterangan) ? '(' . $keterangan . ')' : '' }}</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p> --}}
                                            {{ $keterangan }}
                                            @if (!empty($ceklembur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OT1 : {{ $lembur['overtime_1'] }}</span>
                                                    <br>
                                                    <span>OT2 : {{ $lembur['overtime_2'] }}</span>
                                                </p>
                                            @endif
                                            @if (!empty($ceklemburharilibur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OTL : {{ $overtime_libur }}</span>
                                                </p>
                                            @endif
                                        </td>
                                        @php
                                            $total_potongan_jam =
                                                $potongan_jam_sakit +
                                                $potongan_jam_pulangcepat +
                                                $potongan_jam_izinkeluar +
                                                $potongan_jam_terlambat +
                                                $potongan_jam_dirumahkan +
                                                $potongan_jam_tidakhadir +
                                                $potongan_jam_izin;
                                        @endphp
                                    @elseif($d[$tanggal_presensi]['status'] == 'c')
                                        @php
                                            // $total_jam = $total_jam_jadwal;
                                            $potongan_jam_terlambat = 0;
                                            $potongan_jam_dirumahkan = 0;
                                            if ($d[$tanggal_presensi]['kode_cuti'] != 'C01') {
                                                if ($tanggal_presensi >= '2024-11-21') {
                                                    if (!empty($cekdirumahkan)) {
                                                        $total_jam = ROUND($total_jam_jadwal / 1.33, 2);
                                                        // $potongan_jam_dirumahkan = $total_jam_jadwal - $total_jam;
                                                        if (!empty($cekdirumahkan)) {
                                                            $potongan_jam_dirumahkan = $total_jam_jadwal == 7 ? 1.75 : 1.25;
                                                        }
                                                    } else {
                                                        $potongan_jam_dirumahkan = 0;
                                                        $total_jam = $total_jam_jadwal;
                                                    }
                                                } else {
                                                    if (!empty($cekdirumahkan)) {
                                                        // $potongan_jam_dirumahkan = $total_jam_jadwal / 2;
                                                        $total_jam = $total_jam_jadwal / 2;
                                                    } else {
                                                        $potongan_jam_dirumahkan = 0;
                                                        $total_jam = $total_jam_jadwal;
                                                    }
                                                }
                                            } else {
                                                if (!empty($cekdirumahkan)) {
                                                    $potongan_jam_dirumahkan = $total_jam_jadwal == 7 ? 1.75 : 1.25;
                                                }
                                                $total_jam = $total_jam_jadwal;
                                            }

                                            if (in_array($d['nik'], $privillage_karyawan) && $tanggal_presensi >= '2024-11-21') {
                                                $potongan_jam_dirumahkan = 0;
                                            }
                                            $potongan_jam_izinkeluar = 0;
                                            $potongan_jam_pulangcepat = 0;
                                            $potongan_jam_tidakhadir = 0;
                                            $potongan_jam_izin = 0;
                                            $potongan_jam_sakit = 0;
                                            $total_potongan_jam =
                                                $potongan_jam_sakit +
                                                $potongan_jam_pulangcepat +
                                                $potongan_jam_izinkeluar +
                                                $potongan_jam_terlambat +
                                                $potongan_jam_dirumahkan +
                                                $potongan_jam_tidakhadir +
                                                $potongan_jam_izin;

                                            $jumlah_denda = 0;
                                        @endphp
                                        <td style="padding: 10px; background-color: #1794e1d3">
                                            {{-- <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">CUTI ({{ $d[$tanggal_presensi]['nama_cuti'] }})</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p> --}}
                                            @if ($d[$tanggal_presensi]['kode_cuti'] == 'C01')
                                                C
                                            @else
                                                IK
                                            @endif
                                            @if (!empty($ceklembur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OT1 : {{ $lembur['overtime_1'] }}</span>
                                                    <br>
                                                    <span>OT2 : {{ $lembur['overtime_2'] }}</span>
                                                </p>
                                            @endif
                                            @if (!empty($ceklemburharilibur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OTL : {{ $overtime_libur }}</span>
                                                </p>
                                            @endif
                                        </td>
                                    @elseif($d[$tanggal_presensi]['status'] == 'i')
                                        @php
                                            $potongan_jam_terlambat = 0;
                                            $potongan_jam_dirumahkan = 0;
                                            $potongan_jam_izinkeluar = 0;
                                            $potongan_jam_pulangcepat = 0;
                                            $potongan_jam_tidakhadir = 0;
                                            $potongan_jam_sakit = 0;
                                            if ($d[$tanggal_presensi]['izin_absen_direktur'] == '1') {
                                                $total_jam = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                                                $potongan_jam_izin = !empty($cekdirumahkan) ? $total_jam : 0;
                                            } else {
                                                $total_jam = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                                                $potongan_jam_izin = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                                            }
                                            //Jika Jabatan Salesman
                                            if (
                                                $d['kode_jabatan'] == 'J19' &&
                                                $tanggal_presensi >= '2024-10-21' &&
                                                $tanggal_presensi < '2025-04-21'
                                            ) {
                                                $potongan_jam_izin = 0;
                                            }
                                            $total_potongan_jam =
                                                $potongan_jam_sakit +
                                                $potongan_jam_pulangcepat +
                                                $potongan_jam_izinkeluar +
                                                $potongan_jam_terlambat +
                                                $potongan_jam_dirumahkan +
                                                $potongan_jam_tidakhadir +
                                                $potongan_jam_izin;

                                            $jumlah_denda = 0;
                                        @endphp

                                        <td style="padding: 10px; background-color: #d74405dc">
                                            @if (!empty($ceklembur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OT1 : {{ $lembur['overtime_1'] }}</span>
                                                    <br>
                                                    <span>OT2 : {{ $lembur['overtime_2'] }}</span>
                                                </p>
                                            @endif
                                            @if (!empty($ceklemburharilibur))
                                                <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                    <span>OTL : {{ $overtime_libur }}</span>
                                                </p>
                                            @endif
                                            {{-- <h4 style="font-weight: bold; margin-bottom:8px">{{ $d[$tanggal_presensi]['nama_jadwal'] }}</h4>
                                            <p style="color:rgb(38, 86, 197); margin:0; font-weight:bold">
                                                {{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}
                                            </p>
                                            <p style="margin:0">
                                                <span style="color: white">IZIN</span>
                                                <br>
                                                <span style="font-weight: bold ;color:#024a0d">Total Jam :{{ $total_jam }}</span>
                                            </p> --}}
                                            I
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @else
                                    @php
                                        $potongan_jam_terlambat = 0;
                                        $potongan_jam_izinkeluar = 0;
                                        $potongan_jam_pulangcepat = 0;
                                        $potongan_jam_tidakhadir = 0;
                                        $potongan_jam_izin = 0;
                                        $potongan_jam_sakit = 0;
                                        $jumlah_denda = 0;
                                    @endphp
                                    @if (getNamahari($tanggal_presensi) == 'Minggu')
                                        @php
                                            $color = 'rgba(243, 158, 0, 0.833)';
                                            $keterangan = '';
                                            $total_jam = 0;
                                            $potongan_jam_dirumahkan = 0;
                                        @endphp
                                    @elseif(!empty($cekdirumahkan))
                                        @php
                                            $color = 'rgb(69, 2, 140)';

                                            if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                if ($tanggal_presensi == '2024-10-26') {
                                                    $total_jam = 3.5;
                                                } else {
                                                    $total_jam = 2.5;
                                                }
                                            } else {
                                                if (!empty($cektanggallimajam)) {
                                                    $total_jam = 2.5;
                                                } else {
                                                    $total_jam = 3.5;
                                                }
                                            }

                                            //Mulai Berlaku Dari Tanggal 2024-11-21 --> Step 1
                                            if ($tanggal_presensi >= '2024-11-21') {
                                                if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                    $total_jam = 3.75;
                                                    $potongan_jam_dirumahkan = 1.25;
                                                } else {
                                                    if (!empty($cektanggallimajam)) {
                                                        $total_jam = 3.75;
                                                        $potongan_jam_dirumahkan = 1.25;
                                                    } else {
                                                        $total_jam = 5.25;
                                                        $potongan_jam_dirumahkan = 1.75;
                                                    }
                                                }
                                            } else {
                                                $total_jam = $total_jam;
                                                $potongan_jam_dirumahkan = $total_jam;
                                            }

                                            if (in_array($d['nik'], $privillage_karyawan) && $tanggal_presensi >= '2024-11-21') {
                                                $potongan_jam_dirumahkan = 0;
                                            }
                                            $potongan_jam_dirumahkan = $potongan_jam_dirumahkan;
                                            $keterangan = 'P' . $potongan_jam_dirumahkan;
                                        @endphp
                                    @elseif(!empty($cekliburnasional))
                                        @php
                                            $color = 'green';
                                            // $keterangan = 'Libur Nasional <br>(' . $cekliburnasional[0]['keterangan'] . ')';
                                            $keterangan = 'P';
                                            if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                $total_jam = 5;
                                            } else {
                                                $total_jam = 7;
                                            }
                                            $potongan_jam_dirumahkan = 0;
                                        @endphp
                                    @elseif(!empty($cekliburpengganti))
                                        @php
                                            $color = 'rgba(243, 158, 0, 0.833)';
                                            // $keterangan =
                                            //     'Libur Pengganti Hari Minggu <br>(' . formatIndo($cekliburpengganti[0]['tanggal_diganti']) . ')';
                                            $keterangan = '';
                                            $total_jam = 0;
                                            $potongan_jam_dirumahkan = 0;
                                        @endphp
                                    @else
                                        @php
                                            if ($d['tanggal_masuk'] < $tanggal_presensi) {
                                                $color = 'red';
                                                $keterangan = 'A';
                                                if ($d['kode_jabatan'] == 'J01') {
                                                    $color = '';
                                                    $keterangan = '';
                                                }
                                            } else {
                                                $color = '';
                                                $keterangan = '';
                                            }

                                            // $total_jam = 0;
                                            $potongan_jam_dirumahkan = 0;
                                            if (!empty($cekdirumahkan)) {
                                                if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                    $potongan_jam_tidakhadir = 2.5;
                                                    $total_jam = 2.5;
                                                } else {
                                                    $potongan_jam_tidakhadir = 3.5;
                                                    $total_jam = 3.5;
                                                }
                                            } else {
                                                if (getNamahari($tanggal_presensi) == 'Sabtu') {
                                                    $potongan_jam_tidakhadir = 5;
                                                    $total_jam = 5;
                                                } else {
                                                    $potongan_jam_tidakhadir = 7;
                                                    $total_jam = 7;
                                                }
                                            }
                                        @endphp
                                    @endif
                                    @php
                                        $total_potongan_jam =
                                            $potongan_jam_sakit +
                                            $potongan_jam_pulangcepat +
                                            $potongan_jam_izinkeluar +
                                            $potongan_jam_terlambat +
                                            $potongan_jam_dirumahkan +
                                            $potongan_jam_tidakhadir +
                                            $potongan_jam_izin;
                                    @endphp
                                    <td style="background-color: {{ $color }}; color:white;">
                                        {!! $keterangan !!}
                                        @if (!empty($ceklembur))
                                            <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                <span>OT1 : {{ $lembur['overtime_1'] }}</span>
                                                <br>
                                                <span>OT2 : {{ $lembur['overtime_2'] }}</span>
                                            </p>
                                        @endif
                                        @if (!empty($ceklemburharilibur))
                                            <p style="margin:0; color:rgb(0, 42, 255); font-weight:bold">
                                                <span>OTL : {{ $overtime_libur }}</span>
                                            </p>
                                        @endif
                                        {{-- <br>
                                        @if (!empty($total_jam))
                                            <span style="font-weight: bold ;color:#fae603">Total Jam :{{ $total_jam }}</span>
                                        @endif --}}
                                    </td>
                                @endif
                                @php
                                    $total_potongan_jam_terlambat += $potongan_jam_terlambat;
                                    $total_potongan_jam_dirumahkan += $potongan_jam_dirumahkan;
                                    $total_potongan_jam_izinkeluar += $potongan_jam_izinkeluar;
                                    $total_potongan_jam_pulangcepat += $potongan_jam_pulangcepat;
                                    $total_potongan_jam_tidakhadir += $potongan_jam_tidakhadir;
                                    $total_potongan_jam_izin += $potongan_jam_izin;
                                    $total_potongan_jam_sakit += $potongan_jam_sakit;
                                    $grand_total_potongan_jam += $total_potongan_jam;
                                    $total_denda += $jumlah_denda;
                                    $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                                @endphp
                            @endwhile
                            @php
                                $grandtotal_premi_shift2 = $total_premi_shift2 + $total_premi_shift2_lembur;
                                $grandtotal_premi_shift3 = $total_premi_shift3 + $total_premi_shift3_lembur;
                            @endphp
                            <td style="font-weight: bold; color:#024a0d; text-align:center">{{ $total_jam_satubulan }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_terlambat) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_dirumahkan) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_izinkeluar) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_pulangcepat) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_tidakhadir) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_izin) }}
                            </td>
                            <td style="font-weight: bold; color:#f40505; text-align:center">
                                {{ formatAngkaDesimal($total_potongan_jam_sakit) }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                @php
                                    $total_jam_kerja = $total_jam_satubulan - $grand_total_potongan_jam;
                                @endphp
                                {{ formatAngkaDesimal($total_jam_kerja) }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ formatAngka($total_denda) }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ !empty($grandtotal_premi_shift2) ? $grandtotal_premi_shift2 : '' }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ !empty($grandtotal_premi_shift3) ? $grandtotal_premi_shift3 : '' }}
                            </td>

                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ !empty($total_overtime_1) ? $total_overtime_1 : '' }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ !empty($total_overtime_2) ? $total_overtime_2 : '' }}
                            </td>
                            <td style="font-weight: bold; color:#026720; text-align:center">
                                {{ !empty($total_overtime_libur) ? $total_overtime_libur : '' }}
                            </td>
                            <td class="center">
                                {{ $jml_hadir_pagi }}
                            </td>
                            <td class="center">
                                {{ $jml_hadir_siang }}
                            </td>
                            <td class="center">
                                {{ $jml_hadir_malam }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td></td>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
