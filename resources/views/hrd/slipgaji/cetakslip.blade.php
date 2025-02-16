<link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
<style>
    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left !important;
    }
</style>
@php
    $total_jam_satubulan = 173;
    $grandtotal_all_gajipokok = 0;
    $grandtotal_all_t_jabatan = 0;
    $grandtotal_all_t_masakerja = 0;
    $grandtotal_all_t_tanggungjawab = 0;
    $grandtotal_all_t_makan = 0;
    $grandtotal_all_t_istri = 0;
    $grandtotal_all_t_skill = 0;

    //INsentif Umum
    $grandtotal_all_iu_masakerja = 0;
    $grandtotal_all_iu_lembur = 0;
    $grandtotal_all_iu_penempatan = 0;
    $grandtotal_all_iu_kpi = 0;

    //Insentif Manager
    $grandtotal_all_im_ruanglingkup = 0;
    $grandtotal_all_im_penempatan = 0;
    $grandtotal_all_im_kinerja = 0;
    $grandtotal_all_im_kendaraan = 0;

    //Upah
    $grandtotal_all_upah = 0;

    //Insentif
    $grandtotal_all_insentif = 0;

    $grandtotal_all_jamkerja = 0;
    $grandtotal_all_upahperjam = 0;

    //Overtime
    $grandtotal_all_overtime_1 = 0;
    $grandtotal_all_upah_overtime_1 = 0;
    $grandtotal_all_overtime_2 = 0;
    $grandtotal_all_upah_overtime_2 = 0;
    $grandtotal_all_overtime_libur = 0;
    $grandtotal_all_upah_overtime_libur = 0;
    $grandtotal_all_upah_overtime = 0;

    //Premi Shift
    $grandtotal_all_premi_shift2 = 0;
    $grandtotal_all_upah_premi_shift2 = 0;
    $grandtotal_all_premi_shift3 = 0;
    $grandtotal_all_upah_premi_shift3 = 0;

    $grandtotal_all_bruto = 0;

    $grandtotal_all_potongan_jam = 0;
    $grandtotal_all_iuran_bpjs_kesehatan = 0;
    $grandtotal_all_iuran_bpjs_tk = 0;
    $grandtotal_all_denda = 0;
    $grandtotal_all_cicilan_pjp = 0;
    $grandtotal_all_cicilan_kasbon = 0;
    $grandtotal_all_cicilan_piutang = 0;
    $grandtotal_all_spip = 0;
    $grandtotal_all_pengurang = 0;
    $grandtotal_all_total_potongan = 0;
    $grandtotal_all_penambahan = 0;
    $grandtotal_all_jmlbersih = 0;

@endphp
@foreach ($presensi as $d)
    @php
        $upah = $d['gaji_pokok'] + $d['t_jabatan'] + $d['t_masakerja'] + $d['t_tanggungjawab'] + $d['t_makan'] + $d['t_istri'] + $d['t_skill'];
        $insentif = $d['iu_masakerja'] + $d['iu_lembur'] + $d['iu_penempatan'] + $d['iu_kpi'];
        $insentif_manager = $d['im_ruanglingkup'] + $d['im_penempatan'] + $d['im_kinerja'] + $d['im_kendaraan'];
        $jumlah_insentif = $insentif + $insentif_manager;

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
        $total_overtime_libur_reguler = 0;
        $total_overtime_libur_nasional = 0;
        $total_premi_shift2_lembur = 0;
        $total_premi_shift3_lembur = 0;
        $masakerja = hitungMasakerja($d['tanggal_masuk'], $end_date);
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
            $cekminggumasuk = ceklibur($dataminggumasuk, $search);
            $ceklembur = ceklembur($datalembur, $search);
            $ceklemburharilibur = ceklembur($datalemburharilibur, $search);

            $lembur = presensiHitunglembur($ceklembur);
            $lembur_libur = presensiHitunglembur($ceklemburharilibur);
            $total_overtime_1 += $lembur['overtime_1'];
            $total_overtime_2 += $lembur['overtime_2'];

            if (!empty($cekliburnasional)) {
                $overtime_libur = $lembur_libur['overtime_libur'] * 2;
                $total_overtime_libur_nasional += $overtime_libur;
                $total_overtime_libur_reguler += 0;
            } else {
                $overtime_libur = $lembur_libur['overtime_libur'];
                $total_overtime_libur_nasional += 0;
                $total_overtime_libur_reguler += $overtime_libur;
            }

            $total_overtime_libur += $overtime_libur;
            $total_premi_shift2_lembur += $lembur['jmlharilembur_shift_2'] + $lembur_libur['jmlharilembur_shift_2'];
            $total_premi_shift3_lembur += $lembur['jmlharilembur_shift_3'] + $lembur_libur['jmlharilembur_shift_3'];
        @endphp
        @if (isset($d[$tanggal_presensi]))
            @php
                $lintashari = $d[$tanggal_presensi]['lintashari'];
                $tanggal_selesai = $lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi;
                $total_jam_jadwal = $d[$tanggal_presensi]['total_jam'];
                //Jadwal Jam Kerja
                $j_mulai = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_mulai']));
                $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_selesai']));

                //Jam Absen Masuk dan Pulang
                $jam_in = !empty($d[$tanggal_presensi]['jam_in']) ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_in'])) : 'Belum Absen';
                $jam_out = !empty($d[$tanggal_presensi]['jam_out']) ? date('Y-m-d H:i', strtotime($d[$tanggal_presensi]['jam_out'])) : 'Belum Absen';
                //Jadwal SPG
                //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal atau Hari Minggu Absen
                $jam_mulai =
                    in_array($d['kode_jabatan'], ['J22', 'J23']) || (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk))
                        ? $jam_in
                        : $j_mulai;
                $jam_selesai =
                    in_array($d['kode_jabatan'], ['J22', 'J23']) || (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk))
                        ? $jam_out
                        : $j_selesai;
            @endphp
            @if ($d[$tanggal_presensi]['status'] == 'h')
                <!-- Jika Hari Minggu -->


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
                            $jam_awal_istirahat = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_awal_istirahat']));
                            $jam_akhir_istirahat = date(
                                'Y-m-d H:i',
                                strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_akhir_istirahat']),
                            );
                        } else {
                            $jam_awal_istirahat = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_awal_istirahat']));
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
                    $pulangcepat = presensiHitungPulangCepat($jam_out, $jam_selesai, $jam_awal_istirahat, $jam_akhir_istirahat);

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
                        empty($d[$tanggal_presensi]['jam_in']) || empty($d[$tanggal_presensi]['jam_out']) ? $total_jam_jadwal : 0;
                    $potongan_jam_izin = 0;
                    $potongan_jam_pulangcepat = $d[$tanggal_presensi]['izin_pulang_direktur'] == '1' ? 0 : $pulangcepat['desimal'];
                    $potongan_jam_izinkeluar =
                        $d[$tanggal_presensi]['izin_keluar_direktur'] == '1' || $izin_keluar['desimal'] <= 1 ? 0 : $izin_keluar['desimal'];
                    $potongan_jam_terlambat = $d[$tanggal_presensi]['izin_terlambat_direktur'] == '1' ? 0 : $terlambat['desimal'];

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

                    //Premi
                    if (
                        $d[$tanggal_presensi]['kode_jadwal'] == 'JD003' &&
                        $total_jam >= 5 &&
                        empty($cekliburnasional) &&
                        getNamahari($tanggal_presensi) != 'Minggu'
                    ) {
                        $total_premi_shift2 += 1;
                    }

                    if (
                        $d[$tanggal_presensi]['kode_jadwal'] == 'JD004' &&
                        $total_jam >= 5 &&
                        empty($cekliburnasional) &&
                        getNamahari($tanggal_presensi) != 'Minggu'
                    ) {
                        $total_premi_shift3 += 1;
                    }
                @endphp
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
                        $potongan_jam_sakit = !empty($cekdirumahkan) ? $total_jam : 0;
                        $keterangan = 'SID';
                    @endphp
                @else
                    @php
                        $total_jam = !empty($cekdirumahkan) ? $total_jam_jadwal / 2 : $total_jam_jadwal;
                        $potongan_jam_sakit = !empty($cekdirumahkan) ? $total_jam : $total_jam;
                        $keterangan = '';
                    @endphp
                @endif
                @if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21')
                    @php
                        $potongan_jam_sakit = 0;
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
            @elseif($d[$tanggal_presensi]['status'] == 'c')
                @php

                    $potongan_jam_terlambat = 0;
                    if ($d[$tanggal_presensi]['kode_cuti'] != 'C01') {
                        if (!empty($cekdirumahkan)) {
                            $potongan_jam_dirumahkan = $total_jam_jadwal / 2;
                            $total_jam = $total_jam_jadwal / 2;
                        } else {
                            $potongan_jam_dirumahkan = 0;
                            $total_jam = $total_jam_jadwal;
                        }
                    } else {
                        $potongan_jam_dirumahkan = 0;
                        $total_jam = $total_jam_jadwal;
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
                    if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21') {
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
                    $keterangan = 'Dirumahkan';
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
                @endphp
            @elseif(!empty($cekliburnasional))
                @php
                    $color = 'green';
                    $keterangan = 'Libur Nasional <br>(' . $cekliburnasional[0]['keterangan'] . ')';
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
                    $keterangan = 'Libur Pengganti Hari Minggu <br>(' . formatIndo($cekliburpengganti[0]['tanggal_diganti']) . ')';
                    $total_jam = 0;
                    $potongan_jam_dirumahkan = 0;
                @endphp
            @else
                @php
                    $color = 'red';
                    $keterangan = '';
                    // $total_jam = 0;
                    $potongan_jam_dirumahkan = 0;
                    if (!empty($cekdirumahkan)) {
                        if (getNamahari($tanggal_presensi) == 'Sabtu') {
                            if ($tanggal_presensi == '2024-10-26') {
                                $total_jam = 3.5;
                                $potongan_jam_tidakhadir = 3.5;
                            } else {
                                $total_jam = 2.5;
                                $potongan_jam_tidakhadir = 2.5;
                            }
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

        if ($d['kode_jabatan'] == 'J01') {
            $grand_total_potongan_jam = 0;
        }

        $total_jam_kerja = $total_jam_satubulan - $grand_total_potongan_jam;
        $upah_perjam = $upah / $total_jam_satubulan;

        //Upah Overtime
        //Jika Security
        if ($d['kode_jabatan'] == 'J20') {
            $upah_overtime_1 = 1.5 * 6597 * $total_overtime_1;
            $upah_overtime_2 = 1.5 * 6597 * $total_overtime_2;
            $upah_overtime_libur_reguler = 13194 * $total_overtime_libur_reguler;
            $upah_overtime_libur_nasional = 13143 * $total_overtime_libur_nasional;
            $upah_overtime_libur = $upah_overtime_libur_reguler + $upah_overtime_libur_nasional;
        } else {
            $upah_overtime_1 = $upah_perjam * 1.5 * $total_overtime_1;
            $upah_overtime_2 = $upah_perjam * 2 * $total_overtime_2;
            $upah_overtime_libur = floor($upah_perjam * 2 * $total_overtime_libur);
        }
        $total_upah_overtime = $upah_overtime_1 + $upah_overtime_2 + $upah_overtime_libur;

        $premis_shift2 = $total_premi_shift2 + $total_premi_shift2_lembur;
        $premis_shift3 = $total_premi_shift3 + $total_premi_shift3_lembur;

        $upah_premi_shift2 = 5000 * $premis_shift2;
        $upah_premi_shift3 = 6000 * $premis_shift3;

        $bruto = $upah_perjam * $total_jam_kerja + $total_upah_overtime + $upah_premi_shift2 + $upah_premi_shift3;

        $iuran_bpjs_kesehatan = $d['iuran_bpjs_kesehatan'];
        $iuran_bpjs_tenagakerja = $d['iuran_bpjs_tenagakerja'];
        $cicilan_pjp = $d['cicilan_pjp'];
        $cicilan_kasbon = $d['cicilan_kasbon'];
        $cicilan_piutang = $d['cicilan_piutang'];
        $totalbulanmasakerja = $masakerja['tahun'] * 12 + $masakerja['bulan'];

        if (
            ($d['kode_cabang'] == 'PST' && $totalbulanmasakerja >= 3) ||
            ($d['kode_cabang'] == 'TSM' && $totalbulanmasakerja >= 3) ||
            $d['spip'] == 1
        ) {
            $spip = 5000;
        } else {
            $spip = 0;
        }

        $jml_penambah = $d['jml_penambah'];
        $jml_pengurang = $d['jml_pengurang'];

        $jml_potongan_upah =
            $iuran_bpjs_kesehatan +
            $iuran_bpjs_tenagakerja +
            $total_denda +
            $cicilan_pjp +
            $cicilan_kasbon +
            $cicilan_piutang +
            $jml_pengurang +
            $spip;
        $jmlbersih = $bruto - $jml_potongan_upah + $jml_penambah;
    @endphp
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <table class="datatable3" style="widtd: 30%">
                <tr>
                    <td class="text-left">NIK</td>
                    <td>:</td>
                    <td>{{ $d['nik'] }}</td>
                    <td>{{ $d['kode_cabang'] }}</td>
                </tr>
                <tr>
                    <td class="text-left">Nama Karyawan</td>
                    <td>:</td>
                    <td>{{ $d['nama_karyawan'] }}</td>
                    <td>{{ $d['kode_dept'] }}</td>
                </tr>
                <tr>
                    <td class="text-left">Departemen</td>
                    <td>:</td>
                    <td>{{ $d['kode_dept'] }}</td>
                    <td>{{ $d['nama_jabatan'] }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">PENERIMAAN</td>
                    <td>173 Jam</td>
                </tr>
                <tr>
                    <td class="text-left">Gaji Pokok</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['gaji_pokok']) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Tunj. Jabatan</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['t_jabatan']) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Tunj. Masa Kerja</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['t_masakerja']) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Tunj. Tanggung Jawab</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['t_tanggungjawab']) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Tunj. Makan</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['t_makan']) }}</td>
                </tr>
                @if ($d['kategori_jabatan'] == 'MJ')
                    <tr>
                        <td class="text-left">Tunj. Istri</td>
                        <td colspan="2">:</td>
                        <td class="text-right">{{ formatRupiah($d['t_istri']) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-left">Tunj. Skill</td>
                    <td colspan="2">:</td>
                    <td class="text-right">{{ formatRupiah($d['t_skill']) }}</td>
                </tr>
                <tr>
                    <td class="text-left">∑ JAM KERJA BULAN INI</td>
                    <td colspan="3" style="font-weight: bold">{{ formatAngkaDesimal($total_jam_kerja) }} JAM</td>
                </tr>
                <tr>
                    <td class="text-left">UPAH / JAM</td>
                    <td colspan="3" class="text-right">{{ formatAngkaDesimal($upah_perjam) }}</td>
                </tr>
                @php
                    $upah_bulanini = $upah_perjam * $total_jam_kerja;
                @endphp
                <tr>
                    <td class="text-left">UPAH BULAN INI</td>
                    <td colspan="3" style="font-weight: bold; text-align:right">{{ formatRupiah($upah_bulanini) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Overtime 1</td>
                    <td class="text-center">{{ formatAngkaDesimal($total_overtime_1) }}</td>
                    <td>JAM</td>
                    <td class="text-right">{{ formatRupiah($upah_overtime_1) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Overtime 2</td>
                    <td class="text-center">{{ formatAngkaDesimal($total_overtime_2) }}</td>
                    <td>JAM</td>
                    <td class="text-right">{{ formatRupiah($upah_overtime_2) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Lembur Hari Libur</td>
                    <td class="text-center">{{ formatAngkaDesimal($total_overtime_libur) }}
                    </td>
                    <td>JAM</td>
                    <td class="text-right">{{ formatRupiah($upah_overtime_libur) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Premi Shift 2</td>
                    <td class="text-center">{{ $premis_shift2 }}</td>
                    <td>HARI</td>
                    <td class="text-right">{{ formatRupiah($upah_premi_shift2) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Premi Shift 3</td>
                    <td class="text-center">{{ $premis_shift3 }}</td>
                    <td>HARI</td>
                    <td class="text-right">{{ formatRupiah($upah_premi_shift3) }}</td>
                </tr>
                <tr>
                    <td class="text-left">TOTAL PENERIMAAN</td>
                    <td colspan="3" style="font-weight: bold; text-align:right">{{ formatRupiah($bruto) }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="font-size:18px">GAJI BERSIH</td>
                    <td style="font-weight: bold;font-size:18px; text-align:right" colspan="3">{{ formatRupiah($jmlbersih) }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="font-size:18px">INSENTIF</td>
                </tr>
                @if ($d['kategori_jabatan'] == 'MJ')
                    <tr>
                        <td class="text-left">RUANG LINGKUP</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['im_ruanglingkup']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">PENEMPATAN</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['im_penempatan']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">KINERJA</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['im_kinerja']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">KENDARAAN</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['im_kendaraan']) }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-left">MASA KERJA</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['iu_masakerja']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">LEMBUR</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['iu_lembur']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">PENEMPATAN</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['iu_penempatan']) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">KPI</td>
                        <td colspan="3" class="text-right">{{ formatRupiah($d['iu_kpi']) }}</td>
                    </tr>
                @endif
            </table>

        </div>
    </div>
@endforeach
