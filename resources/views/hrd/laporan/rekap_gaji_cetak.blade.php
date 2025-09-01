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
            PRESENSI KARYAWAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($start_date) }} s/d {{ DateToIndo($end_date) }}</h4>
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

                foreach ($cabang as $cbg) {
                    ${'total_gajipokok_' . $cbg->kode_cabang} = 0;

                    ${'total_tunjangan_jabatan_' . $cbg->kode_cabang} = 0;
                    ${'total_tunjangan_masakerja_' . $cbg->kode_cabang} = 0;
                    ${'total_tunjangan_tanggungjawab_' . $cbg->kode_cabang} = 0;
                    ${'total_tunjangan_makan_' . $cbg->kode_cabang} = 0;
                    ${'total_tunjangan_istri_' . $cbg->kode_cabang} = 0;
                    ${'total_tunjangan_skill_' . $cbg->kode_cabang} = 0;

                    ${'total_insentif_masakerja_' . $cbg->kode_cabang} = 0;
                    ${'total_insentif_lembur_' . $cbg->kode_cabang} = 0;
                    ${'total_insentif_penempatan_' . $cbg->kode_cabang} = 0;
                    ${'total_insentif_kpi_' . $cbg->kode_cabang} = 0;

                    ${'total_im_ruanglingkup_' . $cbg->kode_cabang} = 0;
                    ${'total_im_penempatan_' . $cbg->kode_cabang} = 0;
                    ${'total_im_kinerja_' . $cbg->kode_cabang} = 0;
                    ${'total_im_kendaraan_' . $cbg->kode_cabang} = 0;

                    ${'total_upah_' . $cbg->kode_cabang} = 0;
                    ${'total_insentif_' . $cbg->kode_cabang} = 0;
                    ${'total_all_jamkerja_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upahperjam_' . $cbg->kode_cabang} = 0;
                    ${'total_all_overtime_1_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_ot_1_' . $cbg->kode_cabang} = 0;
                    ${'total_all_overtime_2_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_ot_2_' . $cbg->kode_cabang} = 0;
                    ${'total_all_overtime_libur_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_overtime_libur_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_overtime_' . $cbg->kode_cabang} = 0;
                    ${'total_all_premi_shift2_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_premi_shift2_' . $cbg->kode_cabang} = 0;
                    ${'total_all_premi_shift3_' . $cbg->kode_cabang} = 0;
                    ${'total_all_upah_premi_shift3_' . $cbg->kode_cabang} = 0;
                    ${'total_all_bruto_' . $cbg->kode_cabang} = 0;
                    ${'total_all_potongan_jam_' . $cbg->kode_cabang} = 0;
                    ${'total_all_bpjskesehatan_' . $cbg->kode_cabang} = 0;
                    ${'total_all_bpjstk_' . $cbg->kode_cabang} = 0;
                    ${'total_all_denda_' . $cbg->kode_cabang} = 0;
                    ${'total_all_pjp_' . $cbg->kode_cabang} = 0;
                    ${'total_all_kasbon_' . $cbg->kode_cabang} = 0;
                    ${'total_all_nonpjp_' . $cbg->kode_cabang} = 0;
                    ${'total_all_spip_' . $cbg->kode_cabang} = 0;
                    ${'total_all_pengurang_' . $cbg->kode_cabang} = 0;
                    ${'total_all_penambah_' . $cbg->kode_cabang} = 0;
                    ${'total_all_potongan_' . $cbg->kode_cabang} = 0;
                    ${'total_all_jmlbersih_' . $cbg->kode_cabang} = 0;
                }

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
                    $insentif_manager =
                        $d['im_ruanglingkup'] + $d['im_penempatan'] + $d['im_kinerja'] + $d['im_kendaraan'];
                    $jumlah_insentif = $insentif + $insentif_manager;
                    $masakerja = hitungMasakerja($d['tanggal_masuk'], $end_date);
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
                                if ($d['kode_jabatan'] == 'J20') {
                                    $overtime_libur = $lembur_libur['overtime_libur'] * 2;
                                } else {
                                    $overtime_libur = $lembur_libur['overtime_libur'];
                                }
                                $total_overtime_libur_nasional += $overtime_libur;
                                $total_overtime_libur_reguler += 0;
                            } else {
                                $overtime_libur = $lembur_libur['overtime_libur'];
                                $total_overtime_libur_nasional += 0;
                                $total_overtime_libur_reguler += $overtime_libur;
                            }

                            $total_overtime_libur += $overtime_libur;
                            $total_premi_shift2_lembur +=
                                $lembur['jmlharilembur_shift_2'] + $lembur_libur['jmlharilembur_shift_2'];
                            $total_premi_shift3_lembur +=
                                $lembur['jmlharilembur_shift_3'] + $lembur_libur['jmlharilembur_shift_3'];
                        @endphp
                        @if (isset($d[$tanggal_presensi]))
                            @php
                                $lintashari = $d[$tanggal_presensi]['lintashari'];
                                $tanggal_selesai =
                                    $lintashari == '1'
                                        ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)))
                                        : $tanggal_presensi;
                                $total_jam_jadwal = $d[$tanggal_presensi]['total_jam'];
                                //Jadwal Jam Kerja
                                $j_mulai = date(
                                    'Y-m-d H:i',
                                    strtotime($tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_mulai']),
                                );
                                $j_selesai = date(
                                    'Y-m-d H:i',
                                    strtotime($tanggal_selesai . ' ' . $d[$tanggal_presensi]['jam_selesai']),
                                );

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
                                                strtotime(
                                                    $tanggal_presensi .
                                                        ' ' .
                                                        $d[$tanggal_presensi]['jam_awal_istirahat'],
                                                ),
                                            );
                                            $jam_akhir_istirahat = date(
                                                'Y-m-d H:i',
                                                strtotime(
                                                    $tanggal_presensi .
                                                        ' ' .
                                                        $d[$tanggal_presensi]['jam_akhir_istirahat'],
                                                ),
                                            );
                                        } else {
                                            $jam_awal_istirahat = date(
                                                'Y-m-d H:i',
                                                strtotime(
                                                    $tanggal_selesai .
                                                        ' ' .
                                                        $d[$tanggal_presensi]['jam_awal_istirahat'],
                                                ),
                                            );
                                            $jam_akhir_istirahat = date(
                                                'Y-m-d H:i',
                                                strtotime(
                                                    $tanggal_selesai .
                                                        ' ' .
                                                        $d[$tanggal_presensi]['jam_akhir_istirahat'],
                                                ),
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
                                        empty($d[$tanggal_presensi]['jam_in']) ||
                                        empty($d[$tanggal_presensi]['jam_out'])
                                            ? $total_jam_jadwal
                                            : 0;
                                    $potongan_jam_izin = 0;
                                    $potongan_jam_pulangcepat =
                                        $d[$tanggal_presensi]['izin_pulang_direktur'] == '1'
                                            ? 0
                                            : $pulangcepat['desimal'];
                                    $potongan_jam_izinkeluar =
                                        $d[$tanggal_presensi]['izin_keluar_direktur'] == '1' ||
                                        $izin_keluar['desimal'] <= 1
                                            ? 0
                                            : $izin_keluar['desimal'];
                                    $potongan_jam_terlambat =
                                        $d[$tanggal_presensi]['izin_terlambat_direktur'] == '1'
                                            ? 0
                                            : $terlambat['desimal'];

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
                                        !empty($d[$tanggal_presensi]['jam_in']) &&
                                        !empty($d[$tanggal_presensi]['jam_out'])
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
                                        $keterangan = '';
                                    @endphp
                                @endif
                                @if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21' && $tanggal_presensi < '2025-04-21')
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
                                    if (
                                        in_array($d['nik'], $privillage_karyawan) &&
                                        $tanggal_presensi >= '2024-11-21'
                                    ) {
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
                                        $potongan_jam_izin = !empty($cekdirumahkan)
                                            ? $total_jam_jadwal / 2
                                            : $total_jam_jadwal;
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

                                    if (
                                        in_array($d['nik'], $privillage_karyawan) &&
                                        $tanggal_presensi >= '2024-11-21'
                                    ) {
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
                                    $keterangan =
                                        'Libur Pengganti Hari Minggu <br>(' .
                                        formatIndo($cekliburpengganti[0]['tanggal_diganti']) .
                                        ')';
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

                        $bruto =
                            $upah_perjam * $total_jam_kerja +
                            $total_upah_overtime +
                            $upah_premi_shift2 +
                            $upah_premi_shift3;

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

                    foreach ($cabang as $cbg) {
                        if ($d['kode_cabang'] == $cbg->kode_cabang && $d['kode_klasifikasi'] == 'K04') {
                            ${'total_gajipokok_' . $cbg->kode_cabang} += $d['gaji_pokok'];

                            ${'total_tunjangan_jabatan_' . $cbg->kode_cabang} += $d['t_jabatan'];
                            ${'total_tunjangan_masakerja_' . $cbg->kode_cabang} += $d['t_masakerja'];
                            ${'total_tunjangan_tanggungjawab_' . $cbg->kode_cabang} += $d['t_tanggungjawab'];
                            ${'total_tunjangan_makan_' . $cbg->kode_cabang} += $d['t_makan'];
                            ${'total_tunjangan_istri_' . $cbg->kode_cabang} += $d['t_istri'];
                            ${'total_tunjangan_skill_' . $cbg->kode_cabang} += $d['t_skill'];

                            //Insentif
                            ${'total_insentif_masakerja_' . $cbg->kode_cabang} += $d['iu_masakerja'];
                            ${'total_insentif_lembur_' . $cbg->kode_cabang} += $d['iu_lembur'];
                            ${'total_insentif_penempatan_' . $cbg->kode_cabang} += $d['iu_penempatan'];
                            ${'total_insentif_kpi_' . $cbg->kode_cabang} += $d['iu_kpi'];

                            //IM
                            ${'total_im_ruanglingkup_' . $cbg->kode_cabang} += $d['im_ruanglingkup'];
                            ${'total_im_penempatan_' . $cbg->kode_cabang} += $d['im_penempatan'];
                            ${'total_im_kinerja_' . $cbg->kode_cabang} += $d['im_kinerja'];
                            ${'total_im_kendaraan_' . $cbg->kode_cabang} += $d['im_kendaraan'];

                            //Upah
                            ${'total_upah_' . $cbg->kode_cabang} += $upah;
                            ${'total_insentif_' . $cbg->kode_cabang} += $jumlah_insentif;

                            //Jam Kerja
                            ${'total_all_jamkerja_' . $cbg->kode_cabang} += $total_jam_kerja;
                            ${'total_all_upahperjam_' . $cbg->kode_cabang} += $upah_perjam;
                            ${'total_all_overtime_1_' . $cbg->kode_cabang} += $total_overtime_1;
                            ${'total_all_upah_ot_1_' . $cbg->kode_cabang} += $upah_overtime_1;
                            ${'total_all_overtime_2_' . $cbg->kode_cabang} += $total_overtime_2;
                            ${'total_all_upah_ot_2_' . $cbg->kode_cabang} += $upah_overtime_2;
                            ${'total_all_overtime_libur_' . $cbg->kode_cabang} += $total_overtime_libur;
                            ${'total_all_upah_overtime_libur_' . $cbg->kode_cabang} += $upah_overtime_libur;
                            ${'total_all_upah_overtime_' . $cbg->kode_cabang} += $total_upah_overtime;

                            //Premi
                            ${'total_all_premi_shift2_' . $cbg->kode_cabang} += $premi_shift2;
                            ${'total_all_upah_premi_shift2_' . $cbg->kode_cabang} += $upah_premi_shift2;
                            ${'total_all_premi_shift3_' . $cbg->kode_cabang} += $premi_shift3;
                            ${'total_all_upah_premi_shift3_' . $cbg->kode_cabang} += $upah_premi_shift3;

                            //Bruto
                            ${'total_all_bruto_' . $cbg->kode_cabang} += $bruto;
                            ${'total_all_potongan_jam_' . $cbg->kode_cabang} += $grand_total_potongan_jam;

                            //BPJS
                            ${'total_all_bpjskesehatan_' . $cbg->kode_cabang} += $iuran_bpjs_kesehatan;
                            ${'total_all_bpjstk_' . $cbg->kode_cabang} += $iuran_bpjs_tenagakerja;
                            ${'total_all_denda_' . $cbg->kode_cabang} += $total_denda;

                            //Pinjaman
                            ${'total_all_pjp_' . $cbg->kode_cabang} += $cicilan_pjp;
                            ${'total_all_kasbon_' . $cbg->kode_cabang} += $cicilan_kasbon;
                            ${'total_all_nonpjp_' . $cbg->kode_cabang} += $cicilan_piutang;
                            ${'total_all_spip_' . $cbg->kode_cabang} += $spip;

                            //Pengurang
                            ${'total_all_pengurang_' . $cbg->kode_cabang} += $jml_pengurang;
                            ${'total_all_penambah_' . $cbg->kode_cabang} += $jml_penambah;
                            ${'total_all_potongan_' . $cbg->kode_cabang} += $jml_potongan_upah;
                            ${'total_all_jmlbersih_' . $cbg->kode_cabang} += $jmlbersih;
                        }
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
            <table class="datatable3" style="width: 320%">
                <thead>
                    <tr>
                        <th rowspan="2">KLASIFIKASI</th>
                        <th rowspan="2">Gaji Pokok</th>
                        <th colspan="6">Tunjangan</th>
                        <th colspan="4">Insentif Umum</th>
                        <th colspan="4">Insentif Manager</th>
                        <th rowspan="2">Upah</th>
                        <th rowspan="2">Insentif</th>
                        <th rowspan="2">Σ Jam</th>
                        <th rowspan="2">Upah / Jam</th>
                        <th colspan="2">Overtime 1</th>
                        <th colspan="2">Overtime 2</th>
                        <th colspan="2">Overtime Libur</th>
                        <th rowspan="2">Total OT</th>
                        <th colspan="2">Premi Shift 2</th>
                        <th colspan="2">Premi Shift 3</th>
                        <th rowspan="2" style="background-color: #df9d1b; color:white;">Bruto</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">Pot. Jam</th>
                        <th colspan="3" style="background-color: #df1b38; color:white;">BPJS</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">Denda</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">PJP</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">Kasbon</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">Pinjaman</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">SPIP</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">PENGURANG</th>
                        <th rowspan="2" style="background-color: #df1b38; color:white;">TOTAL<br>POTONGAN</th>
                        <th rowspan="2" style="background-color: #007b21; color:white;">PENAMBAH</th>
                        <th rowspan="2">JML BERSIH</th>

                    </tr>
                    <tr>
                        <!-- TUNJANGAN -->
                        <th>JABATAN</th>
                        <th>MASA KERJA</th>
                        <th>T. JAWAB</th>
                        <th>MAKAN</th>
                        <th>ISTRI</th>
                        <th>SKILL</th>

                        <!-- INSENTIF UMUM -->
                        <th>MASA KERJA</th>
                        <th>LEMBUR</th>
                        <th>PENEMPATAN</th>
                        <th>KPI</th>

                        <!-- INSENTIF MANAGER -->
                        <th>RUANG LINGKUP</th>
                        <th>PENEMPATAN</th>
                        <th>KINERJA</th>
                        <th>KENDARAAN</th>

                        <!-- OVERTIME -->
                        <th>JAM</th>
                        <th>JUMLAH</th>

                        <th>JAM</th>
                        <th>JUMLAH</th>

                        <th>JAM</th>
                        <th>JUMLAH</th>

                        <!-- PREMI SHIFT -->
                        <th>HARI</th>
                        <th>JUMLAH</th>

                        <th>HARI</th>
                        <th>JUMLAH</th>

                        <!-- BPJS -->
                        <th style="background-color: #df1b38; color:white;">KES</th>
                        <th style="background-color: #df1b38; color:white;">PERUSAHAAN</th>
                        <th style="background-color: #df1b38; color:white;">TK</th>
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

                        <td style="text-align: right">{{ formatAngka($total_i_masakerja_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_lembur_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_penempatan_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_kpi_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_penempatan_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kinerja_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kendaraan_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_insentif_administrasi) }}</td>

                        <!--Jam Kerja -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upahperjam_administrasi) }}</td>

                        <!--Overtime-->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_administrasi) }}
                        </td>

                        <td style="text-align: right">{{ formatAngka($total_upah_ot_administrasi) }}</td>

                        <!-- Premi -->
                        <td style="text-align: right">{{ formatAngka($total_premi_shift2_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_administrasi) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_premi_shift3_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_administrasi) }}</td>

                        <!-- Bruto-->
                        <td style="text-align: right">{{ formatAngka($total_bruto_administrasi) }}</td>

                        <!-- Potongan -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_administrasi) }}</td>

                        <!-- BPJS -->
                        <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_administrasi) }}</td>
                        <td></td>
                        <td style="text-align: right">{{ formatAngka($total_bpjstk_administrasi) }}</td>

                        <!-- Denda-->
                        <td style="text-align: right">{{ formatAngka($total_denda_administrasi) }}</td>

                        <!-- Pinjaman-->
                        <td style="text-align: right">{{ formatAngka($total_pjp_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_kasbon_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_nonpjp_administrasi) }}</td>

                        <!--SPIP -->
                        <td style="text-align: right">{{ formatAngka($total_spip_administrasi) }}</td>

                        <!-- PENGURANG-->
                        <td style="text-align: right">{{ formatAngka($total_pengurang_administrasi) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_potongan_administrasi) }}</td>

                        <!-- penambah -->
                        <td style="text-align: right">{{ formatAngka($total_penambah_administrasi) }}</td>

                        <!-- JUMLAH BERSIH -->
                        <td style="text-align: right">{{ formatAngka($total_jmlbersih_administrasi) }}</td>





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

                        <td style="text-align: right">{{ formatAngka($total_i_masakerja_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_lembur_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_penempatan_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_kpi_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_penempatan_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kinerja_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kendaraan_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_insentif_penjualan) }}</td>

                        <!--Jam Kerja -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upahperjam_penjualan) }}</td>

                        <!--Overtime-->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_penjualan) }}
                        </td>

                        <td style="text-align: right">{{ formatAngka($total_upah_ot_penjualan) }}</td>

                        <!-- Premi -->
                        <td style="text-align: right">{{ formatAngka($total_premi_shift2_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_penjualan) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_premi_shift3_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_penjualan) }}</td>

                        <!-- Bruto-->
                        <td style="text-align: right">{{ formatAngka($total_bruto_penjualan) }}</td>

                        <!-- Potongan -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_penjualan) }}</td>

                        <!-- BPJS -->
                        <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_penjualan) }}</td>
                        <td></td>
                        <td style="text-align: right">{{ formatAngka($total_bpjstk_penjualan) }}</td>

                        <!-- Denda-->
                        <td style="text-align: right">{{ formatAngka($total_denda_penjualan) }}</td>

                        <!--Pinjaman-->
                        <td style="text-align: right">{{ formatAngka($total_pjp_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_kasbon_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_nonpjp_penjualan) }}</td>

                        <!-- SPIP-->
                        <td style="text-align: right">{{ formatAngka($total_spip_penjualan) }}</td>

                        <!-- PENGURANG-->
                        <td style="text-align: right">{{ formatAngka($total_pengurang_penjualan) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_potongan_penjualan) }}</td>

                        <!-- penambah -->
                        <td style="text-align: right">{{ formatAngka($total_penambah_penjualan) }}</td>

                        <!-- JUMLAH BERSIH -->
                        <td style="text-align: right">{{ formatAngka($total_jmlbersih_penjualan) }}</td>
                    </tr>
                    @foreach ($cabang as $cbg)
                        <tr>
                            <td>{{ $cbg->nama_cabang }}</td>
                            <td style="text-align: right">{{ formatAngka(${'total_gajipokok_' . $cbg->kode_cabang}) }}
                            </td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_jabatan_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_masakerja_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_tanggungjawab_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_makan_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_istri_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_tunjangan_skill_' . $cbg->kode_cabang}) }}</td>

                            <td style="text-align: right">
                                {{ formatAngka(${'total_insentif_masakerja_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_insentif_lembur_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_insentif_penempatan_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_insentif_kpi_' . $cbg->kode_cabang}) }}</td>

                            <td style="text-align: right">
                                {{ formatAngka(${'total_im_ruanglingkup_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_im_penempatan_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_im_kinerja_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_im_kendaraan_' . $cbg->kode_cabang}) }}</td>

                            <td style="text-align: right">{{ formatAngka(${'total_upah_' . $cbg->kode_cabang}) }}
                            </td>
                            <td style="text-align: right">{{ formatAngka(${'total_insentif_' . $cbg->kode_cabang}) }}
                            </td>

                            <!--Jam Kerja -->
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_jamkerja_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_upahperjam_' . $cbg->kode_cabang}) }}</td>

                            <!--Overtime-->
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_overtime_1_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_upah_ot_1_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_overtime_2_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_upah_ot_2_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_overtime_libur_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_upah_overtime_libur_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_upah_overtime_' . $cbg->kode_cabang}) }}</td>

                            <!-- Premi -->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_premi_shift2_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_upah_premi_shift2_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_premi_shift3_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_upah_premi_shift3_' . $cbg->kode_cabang}) }}</td>

                            <!-- Bruto-->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_bruto_' . $cbg->kode_cabang}) }}</td>

                            <!-- Potongan -->
                            <td style="text-align: right">
                                {{ formatAngkaDesimal(${'total_all_potongan_jam_' . $cbg->kode_cabang}) }}</td>

                            <!-- BPJS -->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_bpjskesehatan_' . $cbg->kode_cabang}) }}</td>
                            <td></td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_bpjstk_' . $cbg->kode_cabang}) }}</td>

                            <!-- Denda-->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_denda_' . $cbg->kode_cabang}) }}</td>

                            <!--Pinjaman-->
                            <td style="text-align: right">{{ formatAngka(${'total_all_pjp_' . $cbg->kode_cabang}) }}
                            </td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_kasbon_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_nonpjp_' . $cbg->kode_cabang}) }}</td>

                            <!-- SPIP-->
                            <td style="text-align: right">{{ formatAngka(${'total_all_spip_' . $cbg->kode_cabang}) }}
                            </td>

                            <!-- PENGURANG-->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_pengurang_' . $cbg->kode_cabang}) }}</td>
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_potongan_' . $cbg->kode_cabang}) }}</td>

                            <!-- penambah -->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_penambah_' . $cbg->kode_cabang}) }}</td>

                            <!-- JUMLAH BERSIH -->
                            <td style="text-align: right">
                                {{ formatAngka(${'total_all_jmlbersih_' . $cbg->kode_cabang}) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TKL</td>
                        <td style="text-align: right">{{ formatAngka($total_gajipokok_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_jabatan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_masakerja_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_tanggungjawab_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_makan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_istri_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_t_skill_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_i_masakerja_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_lembur_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_penempatan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_kpi_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_penempatan_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kinerja_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kendaraan_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_insentif_tkl) }}</td>

                        <!--Jam Kerja -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upahperjam_tkl) }}</td>

                        <!--Overtime-->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_ot_tkl) }}</td>

                        <!-- Premi -->
                        <td style="text-align: right">{{ formatAngka($total_premi_shift2_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_tkl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_premi_shift3_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_tkl) }}</td>

                        <!-- Bruto-->
                        <td style="text-align: right">{{ formatAngka($total_bruto_tkl) }}</td>

                        <!-- Potongan -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_tkl) }}</td>

                        <!-- BPJS -->
                        <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_tkl) }}</td>
                        <td></td>
                        <td style="text-align: right">{{ formatAngka($total_bpjstk_tkl) }}</td>

                        <!-- Denda-->
                        <td style="text-align: right">{{ formatAngka($total_denda_tkl) }}</td>

                        <!--Pinjaman-->
                        <td style="text-align: right">{{ formatAngka($total_pjp_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_kasbon_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_nonpjp_tkl) }}</td>

                        <!-- SPIP -->
                        <td style="text-align: right">{{ formatAngka($total_spip_tkl) }}</td>

                        <!-- PENGURANG-->
                        <td style="text-align: right">{{ formatAngka($total_pengurang_tkl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_potongan_tkl) }}</td>

                        <!-- penambah -->
                        <td style="text-align: right">{{ formatAngka($total_penambah_tkl) }}</td>

                        <!-- JUMLAH BERSIH -->
                        <td style="text-align: right">{{ formatAngka($total_jmlbersih_tkl) }}</td>
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

                        <td style="text-align: right">{{ formatAngka($total_i_masakerja_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_lembur_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_penempatan_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_i_kpi_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_penempatan_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kinerja_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_im_kendaraan_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_insentif_tktl) }}</td>

                        <!--Jam Kerja -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upahperjam_tktl) }}</td>

                        <!--Overtime-->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_upah_ot_tktl) }}</td>

                        <!-- Premi -->
                        <td style="text-align: right">{{ formatAngka($total_premi_shift2_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_tktl) }}</td>

                        <td style="text-align: right">{{ formatAngka($total_premi_shift3_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_tktl) }}</td>

                        <!-- Bruto -->
                        <td style="text-align: right">{{ formatAngka($total_bruto_tktl) }}</td>

                        <!-- Potongan -->
                        <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_tktl) }}</td>

                        <!-- BPJS -->
                        <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_tktl) }}</td>
                        <td></td>
                        <td style="text-align: right">{{ formatAngka($total_bpjstk_tktl) }}</td>

                        <!-- Denda-->
                        <td style="text-align: right">{{ formatAngka($total_denda_tktl) }}</td>

                        <!-- Pinjaman-->
                        <td style="text-align: right">{{ formatAngka($total_pjp_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_kasbon_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_nonpjp_tktl) }}</td>

                        <!--SPIP -->
                        <td style="text-align: right">{{ formatAngka($total_spip_tktl) }}</td>

                        <!-- PENGURANG-->
                        <td style="text-align: right">{{ formatAngka($total_pengurang_tktl) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_potongan_tktl) }}</td>

                        <!-- penambah -->
                        <td style="text-align: right">{{ formatAngka($total_penambah_tktl) }}</td>

                        <!-- JUMLAH BERSIH -->
                        <td style="text-align: right">{{ formatAngka($total_jmlbersih_tktl) }}</td>
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

                    <!--INSENTIF UMUM -->
                    <th style="text-align: right">{{ formatAngka($total_insentif_masakerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_lembur) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_penempatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_kpi) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_im_ruanglingkup) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_penempatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_kinerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_kendaraan) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_upah) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_insentif) }}</th>

                    <!--Jam Kerja -->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_jamkerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upahperjam) }}</th>

                    <!--Overtime-->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_1) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_ot_1) }}</th>

                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_2) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_ot_2) }}</th>

                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_libur) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_overtime_libur) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_all_upah_overtime) }}</th>

                    <!-- Premi -->
                    <th style="text-align: right">{{ formatAngka($total_all_premi_shift2) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upah_premi_shift2) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_all_premi_shift3) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upah_premi_shift3) }}</th>

                    <!-- Bruto -->
                    <th style="text-align: right">{{ formatAngka($total_all_bruto) }}</th>

                    <!-- Potongan -->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_potongan_jam) }}</th>

                    <!-- BPJS -->
                    <th style="text-align: right">{{ formatAngka($total_all_bpjskesehatan) }}</th>
                    <th></th>
                    <th style="text-align: right">{{ formatAngka($total_all_bpjstk) }}</th>

                    <!-- Denda-->
                    <th style="text-align: right">{{ formatAngka($total_all_denda) }}</th>

                    <!-- Pinjaman-->
                    <th style="text-align: right">{{ formatAngka($total_all_pjp) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_kasbon) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_nonpjp) }}</th>

                    <!-- SPIP -->
                    <th style="text-align: right">{{ formatAngka($total_all_spip) }}</th>

                    <!-- PENGURANG-->
                    <th style="text-align: right">{{ formatAngka($total_all_pengurang) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_potongan) }}</th>

                    <!-- PENAMBAH-->
                    <th style="text-align: right">{{ formatAngka($total_all_penambah) }}</th>

                    <!-- JUMLAH BERSIH -->
                    <th style="text-align: right">{{ formatAngka($total_all_jmlbersih) }}</th>
                </tr>


                <tr>
                    <th rowspan="2">KLASIFIKASI</th>
                    <th rowspan="2">Gaji Pokok</th>
                    <th colspan="6">Tunjangan</th>
                    <th colspan="4">Insentif Umum</th>
                    <th colspan="4">Insentif Manager</th>
                    <th rowspan="2">Upah</th>
                    <th rowspan="2">Insentif</th>
                    <th rowspan="2">Σ Jam</th>
                    <th rowspan="2">Upah / Jam</th>
                    <th colspan="2">Overtime 1</th>
                    <th colspan="2">Overtime 2</th>
                    <th colspan="2">Overtime Libur</th>
                    <th rowspan="2">Total OT</th>
                    <th colspan="2">Premi Shift 2</th>
                    <th colspan="2">Premi Shift 3</th>
                    <th rowspan="2" style="background-color: #df9d1b; color:white;">Bruto</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">Pot. Jam</th>
                    <th colspan="3" style="background-color: #df1b38; color:white;">BPJS</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">Denda</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">PJP</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">Kasbon</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">Pinjaman</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">SPIP</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">PENGURANG</th>
                    <th rowspan="2" style="background-color: #df1b38; color:white;">TOTAL<br>POTONGAN</th>
                    <th rowspan="2" style="background-color: #007b21; color:white;">PENAMBAH</th>
                    <th rowspan="2">JML BERSIH</th>

                </tr>
                <tr>
                    <!-- TUNJANGAN -->
                    <th>JABATAN</th>
                    <th>MASA KERJA</th>
                    <th>T. JAWAB</th>
                    <th>MAKAN</th>
                    <th>ISTRI</th>
                    <th>SKILL</th>

                    <!-- INSENTIF UMUM -->
                    <th>MASA KERJA</th>
                    <th>LEMBUR</th>
                    <th>PENEMPATAN</th>
                    <th>KPI</th>

                    <!-- INSENTIF MANAGER -->
                    <th>RUANG LINGKUP</th>
                    <th>PENEMPATAN</th>
                    <th>KINERJA</th>
                    <th>KENDARAAN</th>

                    <!-- OVERTIME -->
                    <th>JAM</th>
                    <th>JUMLAH</th>

                    <th>JAM</th>
                    <th>JUMLAH</th>

                    <th>JAM</th>
                    <th>JUMLAH</th>

                    <!-- PREMI SHIFT -->
                    <th>HARI</th>
                    <th>JUMLAH</th>

                    <th>HARI</th>
                    <th>JUMLAH</th>

                    <!-- BPJS -->
                    <th style="background-color: #df1b38; color:white;">KES</th>
                    <th style="background-color: #df1b38; color:white;">PERUSAHAAN</th>
                    <th style="background-color: #df1b38; color:white;">TK</th>
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

                    <td style="text-align: right">{{ formatAngka($total_i_masakerja_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_lembur_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_penempatan_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_kpi_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_penempatan_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_kinerja_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_kendaraan_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_upah_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_insentif_mp) }}</td>

                    <!--Jam Kerja -->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upahperjam_mp) }}</td>

                    <!--Overtime-->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_mp) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_mp) }}</td>

                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_mp) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_mp) }}</td>

                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_mp) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_upah_ot_mp) }}</td>

                    <!-- Premi -->
                    <td style="text-align: right">{{ formatAngka($total_premi_shift2_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_mp) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_premi_shift3_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_mp) }}</td>

                    <!-- Bruto -->
                    <td style="text-align: right">{{ formatAngka($total_bruto_mp) }}</td>

                    <!-- Potongan -->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_mp) }}</td>

                    <!-- BPJS -->
                    <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_mp) }}</td>
                    <td></td>
                    <td style="text-align: right">{{ formatAngka($total_bpjstk_mp) }}</td>

                    <!-- Denda -->
                    <td style="text-align: right">{{ formatAngka($total_denda_mp) }}</td>

                    <!-- Pinjaman -->
                    <td style="text-align: right">{{ formatAngka($total_pjp_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_kasbon_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_nonpjp_mp) }}</td>

                    <!-- SPIP -->
                    <td style="text-align: right">{{ formatAngka($total_spip_mp) }}</td>

                    <!-- PENGURANG -->
                    <td style="text-align: right">{{ formatAngka($total_pengurang_mp) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_potongan_mp) }}</td>

                    <!-- penambah -->
                    <td style="text-align: right">{{ formatAngka($total_penambah_mp) }}</td>

                    <!-- JUMLAH BERSIH -->
                    <td style="text-align: right">{{ formatAngka($total_jmlbersih_mp) }}</td>
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

                    <td style="text-align: right">{{ formatAngka($total_i_masakerja_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_lembur_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_penempatan_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_i_kpi_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_im_ruanglingkup_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_penempatan_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_kinerja_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_im_kendaraan_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_upah_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_insentif_pcf) }}</td>

                    <!--Jam Kerja -->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_jamkerja_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upahperjam_pcf) }}</td>

                    <!--Overtime-->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_1_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_1_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_2_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_ot_2_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngkaDesimal($total_overtime_libur_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($total_upah_overtime_libur_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_upah_ot_pcf) }}</td>

                    <!-- Premi -->
                    <td style="text-align: right">{{ formatAngka($total_premi_shift2_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upah_premi_shift2_pcf) }}</td>

                    <td style="text-align: right">{{ formatAngka($total_premi_shift3_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_upah_premi_shift3_pcf) }}</td>

                    <!-- Bruto -->
                    <td style="text-align: right">{{ formatAngka($total_bruto_pcf) }}</td>

                    <!-- Potongan -->
                    <td style="text-align: right">{{ formatAngkaDesimal($total_potongan_jam_pcf) }}</td>

                    <!-- BPJS -->
                    <td style="text-align: right">{{ formatAngka($total_bpjskesehatan_pcf) }}</td>
                    <td></td>
                    <td style="text-align: right">{{ formatAngka($total_bpjstk_pcf) }}</td>

                    <!-- Denda -->
                    <td style="text-align: right">{{ formatAngka($total_denda_pcf) }}</td>

                    <!-- Pinjaman -->
                    <td style="text-align: right">{{ formatAngka($total_pjp_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_kasbon_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_nonpjp_pcf) }}</td>

                    <!-- SPIP -->
                    <td style="text-align: right">{{ formatAngka($total_spip_pcf) }}</td>

                    <!-- PENGURANG -->
                    <td style="text-align: right">{{ formatAngka($total_pengurang_pcf) }}</td>
                    <td style="text-align: right">{{ formatAngka($total_potongan_pcf) }}</td>

                    <!-- penambah -->
                    <td style="text-align: right">{{ formatAngka($total_penambah_pcf) }}</td>

                    <!-- JUMLAH BERSIH -->
                    <td style="text-align: right">{{ formatAngka($total_jmlbersih_pcf) }}</td>
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

                    <!--INSENTIF UMUM -->
                    <th style="text-align: right">{{ formatAngka($total_insentif_masakerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_lembur) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_penempatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_insentif_kpi) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_im_ruanglingkup) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_penempatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_kinerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_im_kendaraan) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_upah) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_insentif) }}</th>

                    <!--Jam Kerja -->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_jamkerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upahperjam) }}</th>

                    <!--Overtime-->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_1) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_ot_1) }}</th>

                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_2) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_ot_2) }}</th>

                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_overtime_libur) }}</th>
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_upah_overtime_libur) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_all_upah_overtime) }}</th>

                    <!-- Premi -->
                    <th style="text-align: right">{{ formatAngka($total_all_premi_shift2) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upah_premi_shift2) }}</th>

                    <th style="text-align: right">{{ formatAngka($total_all_premi_shift3) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upah_premi_shift3) }}</th>

                    <!-- Bruto -->
                    <th style="text-align: right">{{ formatAngka($total_all_bruto) }}</th>

                    <!-- Potongan -->
                    <th style="text-align: right">{{ formatAngkaDesimal($total_all_potongan_jam) }}</th>

                    <!-- BPJS -->
                    <th style="text-align: right">{{ formatAngka($total_all_bpjskesehatan) }}</th>
                    <th></th>
                    <th style="text-align: right">{{ formatAngka($total_all_bpjstk) }}</th>

                    <!-- Denda-->
                    <th style="text-align: right">{{ formatAngka($total_all_denda) }}</th>

                    <!-- Pinjaman-->
                    <th style="text-align: right">{{ formatAngka($total_all_pjp) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_kasbon) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_nonpjp) }}</th>

                    <!-- SPIP -->
                    <th style="text-align: right">{{ formatAngka($total_all_spip) }}</th>

                    <!-- PENGURANG-->
                    <th style="text-align: right">{{ formatAngka($total_all_pengurang) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_potongan) }}</th>

                    <!-- PENAMBAH-->
                    <th style="text-align: right">{{ formatAngka($total_all_penambah) }}</th>

                    <!-- JUMLAH BERSIH -->
                    <th style="text-align: right">{{ formatAngka($total_all_jmlbersih) }}</th>
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
