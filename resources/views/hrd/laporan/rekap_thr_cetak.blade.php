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
            REKAP THR KARYAWAN TAHUN {{ date('Y', strtotime($start_date)) }}<br>
        </h4>
    </div>
    <div class="content">
        <div class="freeze-table">

            @php
                $total_jam_satubulan = 173;

                //Gaji Pokok
                $total_gajipokok = 0;
                $total_gajipokok_administrasi = 0;
                $total_gajipokok_penjualan = 0;
                $total_gajipokok_tkl = 0;
                $total_gajipokok_tktl = 0;
                $total_gajipokok_mp = 0;
                $total_gajipokok_pcf = 0;

                //Tunjangan Jabatan
                $total_tunjangan_jabatan = 0;
                $total_t_jabatan_administrasi = 0;
                $total_t_jabatan_penjualan = 0;
                $total_t_jabatan_tkl = 0;
                $total_t_jabatan_tktl = 0;
                $total_t_jabatan_mp = 0;
                $total_t_jabatan_pcf = 0;

                //Tunjangan Masa Kerja
                $total_tunjangan_masakerja = 0;
                $total_t_masakerja_administrasi = 0;
                $total_t_masakerja_penjualan = 0;
                $total_t_masakerja_tkl = 0;
                $total_t_masakerja_tktl = 0;
                $total_t_masakerja_mp = 0;
                $total_t_masakerja_pcf = 0;

                //Tunjangan Tanggung Jawab
                $total_tunjangan_tanggungjawab = 0;
                $total_t_tanggungjawab_administrasi = 0;
                $total_t_tanggungjawab_penjualan = 0;
                $total_t_tanggungjawab_tkl = 0;
                $total_t_tanggungjawab_tktl = 0;
                $total_t_tanggungjawab_mp = 0;
                $total_t_tanggungjawab_pcf = 0;

                //Tunjangan Makan
                $total_tunjangan_makan = 0;
                $total_t_makan_administrasi = 0;
                $total_t_makan_penjualan = 0;
                $total_t_makan_tkl = 0;
                $total_t_makan_tktl = 0;
                $total_t_makan_mp = 0;
                $total_t_makan_pcf = 0;

                //TUnjangan Istri
                $total_tunjangan_istri = 0;
                $total_t_istri_administrasi = 0;
                $total_t_istri_penjualan = 0;
                $total_t_istri_tkl = 0;
                $total_t_istri_tktl = 0;
                $total_t_istri_mp = 0;
                $total_t_istri_pcf = 0;

                //Tunjangan Skill Khusus
                $total_tunjangan_skill = 0;
                $total_t_skill_administrasi = 0;
                $total_t_skill_penjualan = 0;
                $total_t_skill_tkl = 0;
                $total_t_skill_tktl = 0;
                $total_t_skill_mp = 0;
                $total_t_skill_pcf = 0;

                //Insentif umum Masa Kjra
                $total_insentif_masakerja = 0;
                $total_i_masakerja_administrasi = 0;
                $total_i_masakerja_penjualan = 0;
                $total_i_masakerja_tkl = 0;
                $total_i_masakerja_tktl = 0;
                $total_i_masakerja_mp = 0;
                $total_i_masakerja_pcf = 0;

                //Insentif Lembur
                $total_insentif_lembur = 0;
                $total_i_lembur_administrasi = 0;
                $total_i_lembur_penjualan = 0;
                $total_i_lembur_tkl = 0;
                $total_i_lembur_tktl = 0;
                $total_i_lembur_mp = 0;
                $total_i_lembur_pcf = 0;

                $total_insentif_penempatan = 0;
                $total_i_penempatan_administrasi = 0;
                $total_i_penempatan_penjualan = 0;
                $total_i_penempatan_tkl = 0;
                $total_i_penempatan_tktl = 0;
                $total_i_penempatan_mp = 0;
                $total_i_penempatan_pcf = 0;

                //Insentif KPI
                $total_insentif_kpi = 0;
                $total_i_kpi_administrasi = 0;
                $total_i_kpi_penjualan = 0;
                $total_i_kpi_tkl = 0;
                $total_i_kpi_tktl = 0;
                $total_i_kpi_mp = 0;
                $total_i_kpi_pcf = 0;

                //Insentif Ruang Lingkup Manager
                $total_im_ruanglingkup = 0;
                $total_im_ruanglingkup_administrasi = 0;
                $total_im_ruanglingkup_penjualan = 0;
                $total_im_ruanglingkup_tkl = 0;
                $total_im_ruanglingkup_tktl = 0;
                $total_im_ruanglingkup_mp = 0;
                $total_im_ruanglingkup_pcf = 0;

                $total_im_penempatan = 0;
                $total_im_penempatan_administrasi = 0;
                $total_im_penempatan_penjualan = 0;
                $total_im_penempatan_tkl = 0;
                $total_im_penempatan_tktl = 0;
                $total_im_penempatan_mp = 0;
                $total_im_penempatan_pcf = 0;

                $total_im_kinerja = 0;
                $total_im_kinerja_administrasi = 0;
                $total_im_kinerja_penjualan = 0;
                $total_im_kinerja_tkl = 0;
                $total_im_kinerja_tktl = 0;
                $total_im_kinerja_mp = 0;
                $total_im_kinerja_pcf = 0;

                $total_im_kendaraan = 0;
                $total_im_kendaraan_administrasi = 0;
                $total_im_kendaraan_penjualan = 0;
                $total_im_kendaraan_tkl = 0;
                $total_im_kendaraan_tktl = 0;
                $total_im_kendaraan_mp = 0;
                $total_im_kendaraan_pcf = 0;

                //Upah
                $total_upah = 0;
                $grandtotal_thr = 0;
                $grandtotal_thr_seperempat = 0;
                $grandtotal_thr_setengah = 0;
                $grandtotal_all_thr = 0;

                $total_upah_administrasi = 0;
                $total_upah_penjualan = 0;
                $total_upah_tkl = 0;
                $total_upah_tktl = 0;
                $total_upah_mp = 0;
                $total_upah_pcf = 0;

                //INSENTIF
                $total_insentif = 0;
                $total_insentif_administrasi = 0;
                $total_insentif_penjualan = 0;
                $total_insentif_tkl = 0;
                $total_insentif_tktl = 0;
                $total_insentif_mp = 0;
                $total_insentif_pcf = 0;

                $total_all_jamkerja = 0;
                $total_jamkerja_administrasi = 0;
                $total_jamkerja_penjualan = 0;
                $total_jamkerja_tkl = 0;
                $total_jamkerja_tktl = 0;
                $total_jamkerja_mp = 0;
                $total_jamkerja_pcf = 0;

                $total_all_upahperjam = 0;
                $total_upahperjam_administrasi = 0;
                $total_upahperjam_penjualan = 0;
                $total_upahperjam_tkl = 0;
                $total_upahperjam_tktl = 0;
                $total_upahperjam_mp = 0;
                $total_upahperjam_pcf = 0;

                $total_all_overtime_1 = 0;
                $total_overtime_1_administrasi = 0;
                $total_overtime_1_penjualan = 0;
                $total_overtime_1_tkl = 0;
                $total_overtime_1_tktl = 0;
                $total_overtime_1_mp = 0;
                $total_overtime_1_pcf = 0;

                $total_all_upah_ot_1 = 0;
                $total_upah_ot_1_administrasi = 0;
                $total_upah_ot_1_penjualan = 0;
                $total_upah_ot_1_tkl = 0;
                $total_upah_ot_1_tktl = 0;
                $total_upah_ot_1_mp = 0;
                $total_upah_ot_1_pcf = 0;

                //OVERTIME 2
                $total_all_overtime_2 = 0;
                $total_overtime_2_administrasi = 0;
                $total_overtime_2_penjualan = 0;
                $total_overtime_2_tkl = 0;
                $total_overtime_2_tktl = 0;
                $total_overtime_2_mp = 0;
                $total_overtime_2_pcf = 0;

                $total_all_upah_ot_2 = 0;
                $total_upah_ot_2_administrasi = 0;
                $total_upah_ot_2_penjualan = 0;
                $total_upah_ot_2_tkl = 0;
                $total_upah_ot_2_tktl = 0;
                $total_upah_ot_2_mp = 0;
                $total_upah_ot_2_pcf = 0;

                $total_all_overtime_libur = 0;
                $total_overtime_libur_administrasi = 0;
                $total_overtime_libur_penjualan = 0;
                $total_overtime_libur_tkl = 0;
                $total_overtime_libur_tktl = 0;
                $total_overtime_libur_mp = 0;
                $total_overtime_libur_pcf = 0;

                $total_all_upah_overtime_libur = 0;
                $total_upah_overtime_libur_administrasi = 0;
                $total_upah_overtime_libur_penjualan = 0;
                $total_upah_overtime_libur_tkl = 0;
                $total_upah_overtime_libur_tktl = 0;
                $total_upah_overtime_libur_mp = 0;
                $total_upah_overtime_libur_pcf = 0;

                $total_all_upah_overtime = 0;
                $total_upah_ot_administrasi = 0;
                $total_upah_ot_penjualan = 0;
                $total_upah_ot_tkl = 0;
                $total_upah_ot_tktl = 0;
                $total_upah_ot_mp = 0;
                $total_upah_ot_pcf = 0;

                $total_all_premi_shift2 = 0;
                $total_premi_shift2_administrasi = 0;
                $total_premi_shift2_penjualan = 0;
                $total_premi_shift2_tkl = 0;
                $total_premi_shift2_tktl = 0;
                $total_premi_shift2_mp = 0;
                $total_premi_shift2_pcf = 0;

                $total_all_upah_premi_shift2 = 0;
                $total_upah_premi_shift2_administrasi = 0;
                $total_upah_premi_shift2_penjualan = 0;
                $total_upah_premi_shift2_tkl = 0;
                $total_upah_premi_shift2_tktl = 0;
                $total_upah_premi_shift2_mp = 0;
                $total_upah_premi_shift2_pcf = 0;

                $total_all_premi_shift3 = 0;
                $total_premi_shift3_administrasi = 0;
                $total_premi_shift3_penjualan = 0;
                $total_premi_shift3_tkl = 0;
                $total_premi_shift3_tktl = 0;
                $total_premi_shift3_mp = 0;
                $total_premi_shift3_pcf = 0;

                $total_all_upah_premi_shift3 = 0;
                $total_upah_premi_shift3_administrasi = 0;
                $total_upah_premi_shift3_penjualan = 0;
                $total_upah_premi_shift3_tkl = 0;
                $total_upah_premi_shift3_tktl = 0;
                $total_upah_premi_shift3_mp = 0;
                $total_upah_premi_shift3_pcf = 0;

                $total_all_bruto = 0;
                $total_bruto_administrasi = 0;
                $total_bruto_penjualan = 0;
                $total_bruto_tkl = 0;
                $total_bruto_tktl = 0;
                $total_bruto_mp = 0;
                $total_bruto_pcf = 0;

                $total_all_potongan_jam = 0;
                $total_potongan_jam_administrasi = 0;
                $total_potongan_jam_penjualan = 0;
                $total_potongan_jam_tkl = 0;
                $total_potongan_jam_tktl = 0;
                $total_potongan_jam_mp = 0;
                $total_potongan_jam_pcf = 0;

                $total_all_bpjskesehatan = 0;
                $total_bpjskesehatan_administrasi = 0;
                $total_bpjskesehatan_penjualan = 0;
                $total_bpjskesehatan_tkl = 0;
                $total_bpjskesehatan_tktl = 0;
                $total_bpjskesehatan_mp = 0;
                $total_bpjskesehatan_pcf = 0;

                $total_all_bpjstk = 0;
                $total_bpjstk_administrasi = 0;
                $total_bpjstk_penjualan = 0;
                $total_bpjstk_tkl = 0;
                $total_bpjstk_tktl = 0;
                $total_bpjstk_mp = 0;
                $total_bpjstk_pcf = 0;

                $total_all_denda = 0;
                $total_denda_administrasi = 0;
                $total_denda_penjualan = 0;
                $total_denda_tkl = 0;
                $total_denda_tktl = 0;
                $total_denda_mp = 0;
                $total_denda_pcf = 0;

                $total_all_pjp = 0;
                $total_pjp_administrasi = 0;
                $total_pjp_penjualan = 0;
                $total_pjp_tkl = 0;
                $total_pjp_tktl = 0;
                $total_pjp_mp = 0;
                $total_pjp_pcf = 0;

                $total_all_kasbon = 0;
                $total_kasbon_administrasi = 0;
                $total_kasbon_penjualan = 0;
                $total_kasbon_tkl = 0;
                $total_kasbon_tktl = 0;
                $total_kasbon_mp = 0;
                $total_kasbon_pcf = 0;

                $total_all_nonpjp = 0;
                $total_nonpjp_administrasi = 0;
                $total_nonpjp_penjualan = 0;
                $total_nonpjp_tkl = 0;
                $total_nonpjp_tktl = 0;
                $total_nonpjp_mp = 0;
                $total_nonpjp_pcf = 0;

                $total_all_spip = 0;
                $total_spip_administrasi = 0;
                $total_spip_penjualan = 0;
                $total_spip_tkl = 0;
                $total_spip_tktl = 0;
                $total_spip_mp = 0;
                $total_spip_pcf = 0;

                $total_all_pengurang = 0;
                $total_pengurang_administrasi = 0;
                $total_pengurang_penjualan = 0;
                $total_pengurang_tkl = 0;
                $total_pengurang_tktl = 0;
                $total_pengurang_mp = 0;
                $total_pengurang_pcf = 0;

                $total_all_penambah = 0;
                $total_penambah_administrasi = 0;
                $total_penambah_penjualan = 0;
                $total_penambah_tkl = 0;
                $total_penambah_tktl = 0;
                $total_penambah_mp = 0;
                $total_penambah_pcf = 0;

                $total_all_potongan = 0;
                $total_potongan_administrasi = 0;
                $total_potongan_penjualan = 0;
                $total_potongan_tkl = 0;
                $total_potongan_tktl = 0;
                $total_potongan_mp = 0;
                $total_potongan_pcf = 0;

                $total_all_jmlbersih = 0;
                $total_jmlbersih_administrasi = 0;
                $total_jmlbersih_penjualan = 0;
                $total_jmlbersih_tkl = 0;
                $total_jmlbersih_tktl = 0;
                $total_jmlbersih_mp = 0;
                $total_jmlbersih_pcf = 0;

                $total_thr_administrasi = 0;
                $total_thr_penjualan = 0;
                $total_thr_tkl = 0;
                $total_thr_tktl = 0;
                $total_thr_mp = 0;
                $total_thr_pcf = 0;

                $total_thr_seperempat_administrasi = 0;
                $total_thr_seperempat_penjualan = 0;
                $total_thr_seperempat_tkl = 0;
                $total_thr_seperempat_tktl = 0;
                $total_thr_seperempat_mp = 0;
                $total_thr_seperempat_pcf = 0;

                $total_thr_setengah_administrasi = 0;
                $total_thr_setengah_penjualan = 0;
                $total_thr_setengah_tkl = 0;
                $total_thr_setengah_tktl = 0;
                $total_thr_setengah_mp = 0;
                $total_thr_setengah_pcf = 0;

                $total_thr_all_administrasi = 0;
                $total_thr_all_penjualan = 0;
                $total_thr_all_tkl = 0;
                $total_thr_all_tktl = 0;
                $total_thr_all_mp = 0;
                $total_thr_all_pcf = 0;

            @endphp
            @foreach ($presensi as $d)
                @php
                    $upah =
                        $d['gaji_pokok'] +
                        $d['t_jabatan'] +
                        $d['t_masakerja'] +
                        $d['t_tanggungjawab'] +
                        $d['t_makan'] +
                        $d['t_istri'] +
                        $d['t_skill'];
                    $insentif = $d['iu_masakerja'] + $d['iu_lembur'] + $d['iu_penempatan'] + $d['iu_kpi'];
                    $insentif_manager = $d['im_ruanglingkup'] + $d['im_penempatan'] + $d['im_kinerja'] + $d['im_kendaraan'];
                    $jumlah_insentif = $insentif + $insentif_manager;
                    $startdate = date('Y-m', strtotime($end_date)) . '-01';
                    $enddate = date('Y-m-t', strtotime($end_date));
                    $masakerja = hitungMasakerja($d['tanggal_masuk'], $enddate);
                    $tahunkerja = $masakerja['tahun'];
                    $bulankerja = $masakerja['bulan'];
                @endphp
                @if ($tahunkerja >= 1)
                    @php
                        $thr = $upah;
                    @endphp
                @else
                    @php
                        $thr = ($bulankerja / 12) * $upah;
                    @endphp
                @endif
                @if ($tahunkerja >= 10 && $tahunkerja < 15 && $d['nama_jabatan'] != 'DIREKTUR')
                    @php
                        $thr_seperempat = 0.25 * $d['gaji_pokok'];
                    @endphp
                @else
                    @php
                        $thr_seperempat = 0;
                    @endphp
                @endif
                @if ($tahunkerja >= 15 && $d['nama_jabatan'] != 'DIREKTUR')
                    @php
                        $thr_setengah = 0.5 * $d['gaji_pokok'];
                    @endphp
                @else
                    @php
                        $thr_setengah = 0;
                    @endphp
                @endif
                @php
                    $total_all_thr = $thr + $thr_seperempat + $thr_setengah;
                @endphp
                <tr>


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
                        $total_overtime_libur_reguler = 0;
                        $total_overtime_libur_nasional = 0;
                        $total_premi_shift2_lembur = 0;
                        $total_premi_shift3_lembur = 0;
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
                                //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal atau Hari Minggu Absen
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
                                        $d[$tanggal_presensi]['izin_keluar_direktur'] == '1' || $izin_keluar['desimal'] <= 1
                                            ? 0
                                            : $izin_keluar['desimal'];
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

                        $premi_shift2 = $total_premi_shift2 + $total_premi_shift2_lembur;
                        $premi_shift3 = $total_premi_shift3 + $total_premi_shift3_lembur;

                        $upah_premi_shift2 = 5000 * $premi_shift2;
                        $upah_premi_shift3 = 6000 * $premi_shift3;

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

                </tr>

                @php
                    $total_gajipokok += $d['gaji_pokok'];
                    $total_tunjangan_jabatan += $d['t_jabatan'];
                    $total_tunjangan_masakerja += $d['t_masakerja'];
                    $total_tunjangan_tanggungjawab += $d['t_tanggungjawab'];
                    $total_tunjangan_makan += $d['t_makan'];
                    $total_tunjangan_istri += $d['t_istri'];
                    $total_tunjangan_skill += $d['t_skill'];

                    //INSENTIF

                    $total_insentif_masakerja += $d['iu_masakerja'];
                    $total_insentif_lembur += $d['iu_lembur'];
                    $total_insentif_penempatan += $d['iu_penempatan'];
                    $total_insentif_kpi += $d['iu_kpi'];

                    //INSENTIF MANAGER

                    $total_im_ruanglingkup += $d['im_ruanglingkup'];
                    $total_im_penempatan += $d['im_penempatan'];
                    $total_im_kinerja += $d['im_kinerja'];
                    $total_im_kendaraan += $d['im_kendaraan'];

                    //UPAH
                    $total_upah += $upah;

                    //THR
                    $grandtotal_thr += $thr;
                    $grandtotal_thr_seperempat += $thr_seperempat;
                    $grandtotal_thr_setengah += $thr_setengah;
                    $grandtotal_all_thr += $total_all_thr;

                    //INSENTIF
                    $total_insentif += $jumlah_insentif;

                    //Jam Kerja
                    $total_all_jamkerja += $total_jam_kerja;
                    $total_all_upahperjam += $upah_perjam;

                    //Overtime
                    $total_all_overtime_1 += $total_overtime_1;
                    $total_all_overtime_2 += $total_overtime_2;
                    $total_all_overtime_libur += $total_overtime_libur;

                    $total_all_upah_ot_1 += $upah_overtime_1;
                    $total_all_upah_ot_2 += $upah_overtime_2;
                    $total_all_upah_overtime_libur += $upah_overtime_libur;

                    $total_all_upah_overtime += $total_upah_overtime;

                    $total_all_premi_shift2 += $premi_shift2;
                    $total_all_premi_shift3 += $premi_shift3;

                    $total_all_upah_premi_shift2 += $upah_premi_shift2;
                    $total_all_upah_premi_shift3 += $upah_premi_shift3;

                    //Bruto
                    $total_all_bruto += $bruto;
                    $total_all_potongan_jam += $grand_total_potongan_jam;

                    //BPJS

                    $total_all_bpjskesehatan += $iuran_bpjs_kesehatan;
                    $total_all_bpjstk += $iuran_bpjs_tenagakerja;

                    //Denda
                    $total_all_denda += $total_denda;

                    //Pinjaman
                    $total_all_pjp += $cicilan_pjp;
                    $total_all_kasbon += $cicilan_kasbon;
                    $total_all_nonpjp += $cicilan_piutang;

                    //SPIP
                    $total_all_spip += $spip;

                    //PENGURANG
                    $total_all_pengurang += $jml_pengurang;
                    $total_all_potongan += $jml_potongan_upah;

                    //Penambah
                    $total_all_penambah += $jml_penambah;

                    //Jumlah Bersih
                    $total_all_jmlbersih += $jmlbersih;

                    //ADMINISTRASI
                    if ($d['kode_klasifikasi'] == 'K03') {
                        $total_gajipokok_administrasi += $d['gaji_pokok'];
                        $total_t_jabatan_administrasi += $d['t_jabatan'];
                        $total_t_masakerja_administrasi += $d['t_masakerja'];
                        $total_t_tanggungjawab_administrasi += $d['t_tanggungjawab'];
                        $total_t_makan_administrasi += $d['t_makan'];
                        $total_t_istri_administrasi += $d['t_istri'];
                        $total_t_skill_administrasi += $d['t_skill'];

                        $total_i_masakerja_administrasi += $d['iu_masakerja'];
                        $total_i_lembur_administrasi += $d['iu_lembur'];
                        $total_i_penempatan_administrasi += $d['iu_penempatan'];
                        $total_i_kpi_administrasi += $d['iu_kpi'];

                        $total_im_ruanglingkup_administrasi += $d['im_ruanglingkup'];
                        $total_im_penempatan_administrasi += $d['im_penempatan'];
                        $total_im_kinerja_administrasi += $d['im_kinerja'];
                        $total_im_kendaraan_administrasi += $d['im_kendaraan'];

                        $total_upah_administrasi += $upah;
                        $total_thr_administrasi += $thr;
                        $total_thr_seperempat_administrasi += $thr_seperempat;
                        $total_thr_setengah_administrasi += $thr_setengah;
                        $total_thr_all_administrasi += $total_all_thr;

                        $total_insentif_administrasi += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_administrasi += $total_jam_kerja;
                        $total_upahperjam_administrasi += $upah_perjam;

                        //Overtime
                        $total_overtime_1_administrasi += $total_overtime_1;
                        $total_overtime_2_administrasi += $total_overtime_2;
                        $total_overtime_libur_administrasi += $total_overtime_libur;

                        $total_upah_ot_1_administrasi += $upah_overtime_1;
                        $total_upah_ot_2_administrasi += $upah_overtime_2;
                        $total_upah_overtime_libur_administrasi += $upah_overtime_libur;

                        $total_upah_ot_administrasi += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_administrasi += $premi_shift2;
                        $total_premi_shift3_administrasi += $premi_shift3;

                        $total_upah_premi_shift2_administrasi += $upah_premi_shift2;
                        $total_upah_premi_shift3_administrasi += $upah_premi_shift3;

                        $total_bruto_administrasi += $bruto;

                        //Potongan
                        $total_potongan_jam_administrasi += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_administrasi += $iuran_bpjs_kesehatan;
                        $total_bpjstk_administrasi += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_administrasi += $total_denda;

                        //Pinjaman
                        $total_pjp_administrasi += $cicilan_pjp;
                        $total_kasbon_administrasi += $cicilan_kasbon;
                        $total_nonpjp_administrasi += $cicilan_piutang;

                        //SPIP
                        $total_spip_administrasi += $spip;

                        //Pengurang
                        $total_pengurang_administrasi += $jml_pengurang;
                        $total_potongan_administrasi += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_administrasi += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_administrasi += $jmlbersih;
                    }

                    //PENJUALAN
                    if ($d['kode_klasifikasi'] == 'K04') {
                        $total_gajipokok_penjualan += $d['gaji_pokok'];
                        $total_t_jabatan_penjualan += $d['t_jabatan'];
                        $total_t_masakerja_penjualan += $d['t_masakerja'];
                        $total_t_tanggungjawab_penjualan += $d['t_tanggungjawab'];
                        $total_t_makan_penjualan += $d['t_makan'];
                        $total_t_istri_penjualan += $d['t_istri'];
                        $total_t_skill_penjualan += $d['t_skill'];

                        $total_i_masakerja_penjualan += $d['iu_masakerja'];
                        $total_i_lembur_penjualan += $d['iu_lembur'];
                        $total_i_penempatan_penjualan += $d['iu_penempatan'];
                        $total_i_kpi_penjualan += $d['iu_kpi'];

                        $total_im_ruanglingkup_penjualan += $d['im_ruanglingkup'];
                        $total_im_penempatan_penjualan += $d['im_penempatan'];
                        $total_im_kinerja_penjualan += $d['im_kinerja'];
                        $total_im_kendaraan_penjualan += $d['im_kendaraan'];

                        $total_upah_penjualan += $upah;

                        $total_thr_penjualan += $thr;
                        $total_thr_seperempat_penjualan += $thr_seperempat;
                        $total_thr_setengah_penjualan += $thr_setengah;
                        $total_thr_all_penjualan += $total_all_thr;

                        $total_insentif_penjualan += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_penjualan += $total_jam_kerja;
                        $total_upahperjam_penjualan += $upah_perjam;

                        //Overtime

                        $total_overtime_1_penjualan += $total_overtime_1;
                        $total_overtime_2_penjualan += $total_overtime_2;
                        $total_overtime_libur_penjualan += $total_overtime_libur;

                        $total_upah_ot_1_penjualan += $upah_overtime_1;
                        $total_upah_ot_2_penjualan += $upah_overtime_2;
                        $total_upah_overtime_libur_penjualan += $upah_overtime_libur;

                        $total_upah_ot_penjualan += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_penjualan += $premi_shift2;
                        $total_premi_shift3_penjualan += $premi_shift3;

                        $total_upah_premi_shift2_penjualan += $upah_premi_shift2;
                        $total_upah_premi_shift3_penjualan += $upah_premi_shift3;

                        $total_bruto_penjualan += $bruto;

                        //Potongan
                        $total_potongan_jam_penjualan += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_penjualan += $iuran_bpjs_kesehatan;
                        $total_bpjstk_penjualan += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_penjualan += $total_denda;

                        //Pinjaman
                        $total_pjp_penjualan += $cicilan_pjp;
                        $total_kasbon_penjualan += $cicilan_kasbon;
                        $total_nonpjp_penjualan += $cicilan_piutang;

                        //SPIP
                        $total_spip_penjualan += $spip;

                        //Pengurang
                        $total_pengurang_penjualan += $jml_pengurang;
                        $total_potongan_penjualan += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_penjualan += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_penjualan += $jmlbersih;
                    }

                    //TKL
                    if ($d['kode_klasifikasi'] == 'K01') {
                        $total_gajipokok_tkl += $d['gaji_pokok'];
                        $total_t_jabatan_tkl += $d['t_jabatan'];
                        $total_t_masakerja_tkl += $d['t_masakerja'];
                        $total_t_tanggungjawab_tkl += $d['t_tanggungjawab'];
                        $total_t_makan_tkl += $d['t_makan'];
                        $total_t_istri_tkl += $d['t_istri'];
                        $total_t_skill_tkl += $d['t_skill'];

                        $total_i_masakerja_tkl += $d['iu_masakerja'];
                        $total_i_lembur_tkl += $d['iu_lembur'];
                        $total_i_penempatan_tkl += $d['iu_penempatan'];
                        $total_i_kpi_tkl += $d['iu_kpi'];

                        $total_im_ruanglingkup_tkl += $d['im_ruanglingkup'];
                        $total_im_penempatan_tkl += $d['im_penempatan'];
                        $total_im_kinerja_tkl += $d['im_kinerja'];
                        $total_im_kendaraan_tkl += $d['im_kendaraan'];

                        $total_upah_tkl += $upah;

                        $total_thr_tkl += $thr;
                        $total_thr_seperempat_tkl += $thr_seperempat;
                        $total_thr_setengah_tkl += $thr_setengah;
                        $total_thr_all_tkl += $total_all_thr;

                        $total_insentif_tkl += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_tkl += $total_jam_kerja;
                        $total_upahperjam_tkl += $upah_perjam;

                        //Overtime

                        $total_overtime_1_tkl += $total_overtime_1;
                        $total_overtime_2_tkl += $total_overtime_2;
                        $total_overtime_libur_tkl += $total_overtime_libur;

                        $total_upah_ot_1_tkl += $upah_overtime_1;
                        $total_upah_ot_2_tkl += $upah_overtime_2;
                        $total_upah_overtime_libur_tkl += $upah_overtime_libur;

                        $total_upah_ot_tkl += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_tkl += $premi_shift2;
                        $total_premi_shift3_tkl += $premi_shift3;

                        $total_upah_premi_shift2_tkl += $upah_premi_shift2;
                        $total_upah_premi_shift3_tkl += $upah_premi_shift3;

                        $total_bruto_tkl += $bruto;

                        //Potongan
                        $total_potongan_jam_tkl += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_tkl += $iuran_bpjs_kesehatan;
                        $total_bpjstk_tkl += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_tkl += $total_denda;

                        //Pinjaman
                        $total_pjp_tkl += $cicilan_pjp;
                        $total_kasbon_tkl += $cicilan_kasbon;
                        $total_nonpjp_tkl += $cicilan_piutang;

                        //SPIP
                        $total_spip_tkl += $spip;

                        //Pengurang
                        $total_pengurang_tkl += $jml_pengurang;
                        $total_potongan_tkl += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_tkl += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_tkl += $jmlbersih;
                    }

                    //TKTL
                    if ($d['kode_klasifikasi'] == 'K02') {
                        $total_gajipokok_tktl += $d['gaji_pokok'];
                        $total_t_jabatan_tktl += $d['t_jabatan'];
                        $total_t_masakerja_tktl += $d['t_masakerja'];
                        $total_t_tanggungjawab_tktl += $d['t_tanggungjawab'];
                        $total_t_makan_tktl += $d['t_makan'];
                        $total_t_istri_tktl += $d['t_istri'];
                        $total_t_skill_tktl += $d['t_skill'];

                        $total_i_masakerja_tktl += $d['iu_masakerja'];
                        $total_i_lembur_tktl += $d['iu_lembur'];
                        $total_i_penempatan_tktl += $d['iu_penempatan'];
                        $total_i_kpi_tktl += $d['iu_kpi'];

                        $total_im_ruanglingkup_tktl += $d['im_ruanglingkup'];
                        $total_im_penempatan_tktl += $d['im_penempatan'];
                        $total_im_kinerja_tktl += $d['im_kinerja'];
                        $total_im_kendaraan_tktl += $d['im_kendaraan'];

                        $total_upah_tktl += $upah;

                        $total_thr_tkl += $thr;
                        $total_thr_seperempat_tkl += $thr_seperempat;
                        $total_thr_setengah_tkl += $thr_setengah;
                        $total_thr_all_tkl += $total_all_thr;

                        $total_insentif_tktl += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_tktl += $total_jam_kerja;
                        $total_upahperjam_tktl += $upah_perjam;

                        //Overtime

                        $total_overtime_1_tktl += $total_overtime_1;
                        $total_overtime_2_tktl += $total_overtime_2;
                        $total_overtime_libur_tktl += $total_overtime_libur;

                        $total_upah_ot_1_tktl += $upah_overtime_1;
                        $total_upah_ot_2_tktl += $upah_overtime_2;
                        $total_upah_overtime_libur_tktl += $upah_overtime_libur;

                        $total_upah_ot_tktl += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_tktl += $premi_shift2;
                        $total_premi_shift3_tktl += $premi_shift3;

                        $total_upah_premi_shift2_tktl += $upah_premi_shift2;
                        $total_upah_premi_shift3_tktl += $upah_premi_shift3;

                        $total_bruto_tktl += $bruto;

                        //Potongan
                        $total_potongan_jam_tktl += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_tktl += $iuran_bpjs_kesehatan;
                        $total_bpjstk_tktl += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_tktl += $total_denda;

                        //Pinjaman
                        $total_pjp_tktl += $cicilan_pjp;
                        $total_kasbon_tktl += $cicilan_kasbon;
                        $total_nonpjp_tktl += $cicilan_piutang;

                        //SPIP
                        $total_spip_tktl += $spip;

                        //Pengurang
                        $total_pengurang_tktl += $jml_pengurang;
                        $total_potongan_tktl += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_tktl += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_tktl += $jmlbersih;
                    }

                    if ($d['kode_perusahaan'] == 'MP') {
                        $total_gajipokok_mp += $d['gaji_pokok'];
                        $total_t_jabatan_mp += $d['t_jabatan'];
                        $total_t_masakerja_mp += $d['t_masakerja'];
                        $total_t_tanggungjawab_mp += $d['t_tanggungjawab'];
                        $total_t_makan_mp += $d['t_makan'];
                        $total_t_istri_mp += $d['t_istri'];
                        $total_t_skill_mp += $d['t_skill'];

                        $total_i_masakerja_mp += $d['iu_masakerja'];
                        $total_i_lembur_mp += $d['iu_lembur'];
                        $total_i_penempatan_mp += $d['iu_penempatan'];
                        $total_i_kpi_mp += $d['iu_kpi'];

                        $total_im_ruanglingkup_mp += $d['im_ruanglingkup'];
                        $total_im_penempatan_mp += $d['im_penempatan'];
                        $total_im_kinerja_mp += $d['im_kinerja'];
                        $total_im_kendaraan_mp += $d['im_kendaraan'];

                        $total_upah_mp += $upah;
                        $total_thr_mp += $thr;
                        $total_thr_seperempat_mp += $thr_seperempat;
                        $total_thr_setengah_mp += $thr_setengah;
                        $total_thr_all_mp += $total_all_thr;

                        $total_insentif_mp += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_mp += $total_jam_kerja;
                        $total_upahperjam_mp += $upah_perjam;

                        //Overtime

                        $total_overtime_1_mp += $total_overtime_1;
                        $total_overtime_2_mp += $total_overtime_2;
                        $total_overtime_libur_mp += $total_overtime_libur;

                        $total_upah_ot_1_mp += $upah_overtime_1;
                        $total_upah_ot_2_mp += $upah_overtime_2;
                        $total_upah_overtime_libur_mp += $upah_overtime_libur;

                        $total_upah_ot_mp += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_mp += $premi_shift2;
                        $total_premi_shift3_mp += $premi_shift3;

                        $total_upah_premi_shift2_mp += $upah_premi_shift2;
                        $total_upah_premi_shift3_mp += $upah_premi_shift3;

                        $total_bruto_mp += $bruto;

                        //Potongan
                        $total_potongan_jam_mp += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_mp += $iuran_bpjs_kesehatan;
                        $total_bpjstk_mp += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_mp += $total_denda;

                        //Pinjaman
                        $total_pjp_mp += $cicilan_pjp;
                        $total_kasbon_mp += $cicilan_kasbon;
                        $total_nonpjp_mp += $cicilan_piutang;

                        //SPIP
                        $total_spip_mp += $spip;

                        //Pengurang
                        $total_pengurang_mp += $jml_pengurang;
                        $total_potongan_mp += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_mp += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_mp += $jmlbersih;
                    }

                    if ($d['kode_perusahaan'] == 'PC') {
                        $total_gajipokok_pcf += $d['gaji_pokok'];
                        $total_t_jabatan_pcf += $d['t_jabatan'];
                        $total_t_masakerja_pcf += $d['t_masakerja'];
                        $total_t_tanggungjawab_pcf += $d['t_tanggungjawab'];
                        $total_t_makan_pcf += $d['t_makan'];
                        $total_t_istri_pcf += $d['t_istri'];
                        $total_t_skill_pcf += $d['t_skill'];

                        $total_i_masakerja_pcf += $d['iu_masakerja'];
                        $total_i_lembur_pcf += $d['iu_lembur'];
                        $total_i_penempatan_pcf += $d['iu_penempatan'];
                        $total_i_kpi_pcf += $d['iu_kpi'];

                        $total_im_ruanglingkup_pcf += $d['im_ruanglingkup'];
                        $total_im_penempatan_pcf += $d['im_penempatan'];
                        $total_im_kinerja_pcf += $d['im_kinerja'];
                        $total_im_kendaraan_pcf += $d['im_kendaraan'];

                        $total_upah_pcf += $upah;

                        $total_thr_pcf += $thr;
                        $total_thr_seperempat_pcf += $thr_seperempat;
                        $total_thr_setengah_pcf += $thr_setengah;
                        $total_thr_all_pcf += $total_all_thr;

                        $total_insentif_pcf += $jumlah_insentif;

                        //Jam Kerja
                        $total_jamkerja_pcf += $total_jam_kerja;
                        $total_upahperjam_pcf += $upah_perjam;

                        //Overtime

                        $total_overtime_1_pcf += $total_overtime_1;
                        $total_overtime_2_pcf += $total_overtime_2;
                        $total_overtime_libur_pcf += $total_overtime_libur;

                        $total_upah_ot_1_pcf += $upah_overtime_1;
                        $total_upah_ot_2_pcf += $upah_overtime_2;
                        $total_upah_overtime_libur_pcf += $upah_overtime_libur;

                        $total_upah_ot_pcf += $total_upah_overtime;

                        //Premi
                        $total_premi_shift2_pcf += $premi_shift2;
                        $total_premi_shift3_pcf += $premi_shift3;

                        $total_upah_premi_shift2_pcf += $upah_premi_shift2;
                        $total_upah_premi_shift3_pcf += $upah_premi_shift3;

                        $total_bruto_pcf += $bruto;

                        //Potongan
                        $total_potongan_jam_pcf += $grand_total_potongan_jam;

                        //BPJS
                        $total_bpjskesehatan_pcf += $iuran_bpjs_kesehatan;
                        $total_bpjstk_pcf += $iuran_bpjs_tenagakerja;

                        //Denda
                        $total_denda_pcf += $total_denda;

                        //Pinjaman
                        $total_pjp_pcf += $cicilan_pjp;
                        $total_kasbon_pcf += $cicilan_kasbon;
                        $total_nonpjp_pcf += $cicilan_piutang;

                        //SPIP
                        $total_spip_pcf += $spip;

                        //Pengurang
                        $total_pengurang_pcf += $jml_pengurang;
                        $total_potongan_pcf += $jml_potongan_upah;
                        //Penambah
                        $total_penambah_pcf += $jml_penambah;

                        //Jumlah Bersih
                        $total_jmlbersih_pcf += $jmlbersih;
                    }

                    // //Pengurang
                    // $grandtotal_all_pengurang += $jml_pengurang;

                    // //Potongan Upah
                    // $grandtotal_all_total_potongan += $jml_potongan_upah;

                    // //penambah
                    // $grandtotal_all_penambah += $jml_penambah;

                    // //Jumlah Bersih
                    // $grandtotal_all_jmlbersih += $jmlbersih;

                @endphp
            @endforeach
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">KLASIFIKASI</th>
                        <th rowspan="2">Gaji Pokok</th>
                        <th colspan="6">Tunjangan</th>
                        <th rowspan="2">Upah</th>
                        <th rowspan="2">THR</th>
                        <th rowspan="2">1/4 Upah</th>
                        <th rowspan="2">1/2 Upah</th>
                        <th rowspan="2">TOTAL</th>

                    </tr>
                    <tr>
                        <!-- TUNJANGAN -->
                        <th>JABATAN</th>
                        <th>MASA KERJA</th>
                        <th>T. JAWAB</th>
                        <th>MAKAN</th>
                        <th>ISTRI</th>
                        <th>SKILL</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ADMINISTRASI</td>
                        <td style="text-align: right">{{ formatAngka($total_gajipokok_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_jabatan_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_masakerja_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_makan_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_istri_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_skill_administrasi) }}</td>



                        <td style="text-align: right">{{ formatAngka($total_upah_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_thr_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_seperempat_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_setengah_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_all_administrasi) }}</td>





                    </tr>
                    <tr>
                        <td>PENJUALAN</td>
                        <td style="text-align: right">{{ formatAngka($total_gajipokok_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_jabatan_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_masakerja_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_makan_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_istri_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_skill_penjualan) }}</td>



                        <td style="text-align: right">{{ formatAngka($total_upah_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_thr_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_seperempat_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_setengah_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_all_penjualan) }}</td>



                    </tr>
                    <tr>
                        <td>TKL</td>
                        <td style="text-align: right">{{ formatAngka($total_gajipokok_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_jabatan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_masakerja_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_makan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_istri_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_skill_tkl) }}</td>



                        <td style="text-align: right">{{ formatAngka($total_upah_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_thr_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_seperempat_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_setengah_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_all_tkl) }}</td>


                    </tr>
                    <tr>
                        <td>TKTL</td>
                        <td style="text-align: right">{{ formatAngka($total_gajipokok_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_jabatan_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_masakerja_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_makan_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_istri_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_skill_tktl) }}</td>



                        <td style="text-align: right">{{ formatAngka($total_upah_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_seperempat_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_setengah_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_thr_all_tktl) }}</td>


                    </tr>
                </tbody>

                <tr>
                    <th>TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_gajipokok) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_jabatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_masakerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_tanggungjawab) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_makan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_istri) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_skill) }}</th>



                    <th style="text-align: right">{{ formatAngka($total_upah) }}</th>

                    <th style="text-align: right">{{ formatAngka($grandtotal_thr) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_thr_seperempat) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_thr_setengah) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_all_thr) }}</th>
                </tr>


                <tr>
                    <th rowspan="2">KLASIFIKASI</th>
                    <th rowspan="2">Gaji Pokok</th>
                    <th colspan="6">Tunjangan</th>
                    <th rowspan="2">Upah</th>
                    <th rowspan="2">THR</th>
                    <th rowspan="2">1/4 Upah</th>
                    <th rowspan="2">1/2 Upah</th>
                    <th rowspan="2">TOTAL</th>


                </tr>
                <tr>
                    <!-- TUNJANGAN -->
                    <th>JABATAN</th>
                    <th>MASA KERJA</th>
                    <th>T. JAWAB</th>
                    <th>MAKAN</th>
                    <th>ISTRI</th>
                    <th>SKILL</th>


                </tr>
                <tr>
                    <td>MP</td>
                    <td style="text-align: right">{{ formatAngka($total_gajipokok_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_jabatan_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_masakerja_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_makan_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_istri_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_skill_mp) }}</td>



                    <td style="text-align: right">{{ formatAngka($total_upah_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_thr_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_seperempat_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_setengah_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_all_mp) }}</td>


                </tr>
                <tr>
                    <td>PCF</td>
                    <td style="text-align: right">{{ formatAngka($total_gajipokok_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_jabatan_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_masakerja_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_makan_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_istri_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_t_skill_pcf) }}</td>



                    <td style="text-align: right">{{ formatAngka($total_upah_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_thr_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_seperempat_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_setengah_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_thr_all_pcf) }}</td>


                </tr>
                <tr>
                    <th>TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_gajipokok) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_jabatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_masakerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_tanggungjawab) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_makan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_istri) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_tunjangan_skill) }}</th>


                    <th style="text-align: right">{{ formatAngka($total_upah) }}</th>



                    <th style="text-align: right">{{ formatAngka($grandtotal_thr) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_thr_seperempat) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_thr_setengah) }}</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal_all_thr) }}</th>


                </tr>
            </table>
        </div>

    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 7,
        'shadow': true,
    });
</script> --}}
