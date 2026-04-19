<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SlipgajiResource;
use App\Models\Bpjskesehatan;
use App\Models\Bpjstenagakerja;
use App\Models\Detailpenyesuaianupah;
use App\Models\Historibayarkasbon;
use App\Models\Historibayarpiutangkaryawan;
use App\Models\Historibayarpjp;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Retur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlipgajiController extends Controller
{
    public function getMonths()
    {
        $slips = DB::table('hrd_slipgaji')
            ->where('status', 1)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $months = $slips->map(function ($slip) {
            return [
                'month' => (int)$slip->bulan,
                'month_name' => getMonthName($slip->bulan),
                'year' => (int)$slip->tahun
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List Bulan Slip Gaji',
            'data' => $months
        ]);
    }

    public function show($bulangaji, $tahungaji, $nik)
    {
        $check = DB::table('hrd_slipgaji')
            ->where('bulan', $bulangaji)
            ->where('tahun', $tahungaji)
            ->where('status', 1)
            ->first();

        if (!$check) {
            return response()->json([
                'success' => false,
                'message' => 'Slip Gaji belum dipublish!'
            ], 404);
        }

        $lastbulan = getbulandantahunlalu($bulangaji, $tahungaji, "bulan");
        $lasttahun = getbulandantahunlalu($bulangaji, $tahungaji, "tahun");

        $kode_potongan = "GJ" . $bulangaji . $tahungaji;

        $lastbulan = $lastbulan < 10 ? '0' . $lastbulan : $lastbulan;
        $bulan = $bulangaji < 10 ? '0' . $bulangaji : $bulangaji;
        $dari = $lasttahun . "-" . $lastbulan . "-21";
        $sampai = $tahungaji . "-" . $bulan . "-20";

        $start_date = $dari;
        $end_date = $sampai;

        $daribulangaji = $dari;
        $berlakugaji = $sampai;




        $gajiTerakhir = DB::table('hrd_gaji')
            ->select(
                'nik',
                'gaji_pokok',
                't_jabatan',
                't_masakerja',
                't_tanggungjawab',
                't_makan',
                't_istri',
                't_skill',
                'tanggal_berlaku'
            )
            ->whereIn('kode_gaji', function ($query) use ($berlakugaji) {
                $query->select(DB::raw('MAX(kode_gaji   )'))
                    ->from('hrd_gaji')
                    ->where('tanggal_berlaku', '<=', $berlakugaji)
                    ->groupBy('nik');
            });


        $insentif = DB::table('hrd_insentif')
            ->select(
                'nik',
                'iu_masakerja',
                'iu_lembur',
                'iu_penempatan',
                'iu_kpi',
                'im_ruanglingkup',
                'im_penempatan',
                'im_kinerja',
                'im_kendaraan',
                'tanggal_berlaku'
            )
            ->whereIn('kode_insentif', function ($query) use ($berlakugaji) {
                $query->select(DB::raw('MAX(kode_insentif   )'))
                    ->from('hrd_insentif')
                    ->where('tanggal_berlaku', '<=', $berlakugaji)
                    ->groupBy('nik');
            });


        $pjp = Historibayarpjp::select(
            'nik',
            DB::raw('SUM(jumlah) as cicilan_pjp')
        )
            ->join('keuangan_pjp', 'keuangan_pjp.no_pinjaman', '=', 'keuangan_pjp_historibayar.no_pinjaman')
            ->where('kode_potongan', $kode_potongan)
            ->groupBy('nik');

        $kasbon = Historibayarkasbon::select(
            'nik',
            DB::raw('SUM(keuangan_kasbon_historibayar.jumlah) as cicilan_kasbon')
        )
            ->join('keuangan_kasbon', 'keuangan_kasbon.no_kasbon', '=', 'keuangan_kasbon_historibayar.no_kasbon')
            ->where('kode_potongan', $kode_potongan)
            ->groupBy('nik');

        $piutangkaryawan = Historibayarpiutangkaryawan::select(
            'nik',
            DB::raw('SUM(keuangan_piutangkaryawan_historibayar.jumlah) as cicilan_piutang')
        )
            ->join('keuangan_piutangkaryawan', 'keuangan_piutangkaryawan_historibayar.no_pinjaman', '=', 'keuangan_piutangkaryawan.no_pinjaman')
            ->where('kode_potongan', $kode_potongan)
            ->groupBy('nik');

        $bpjskesehatan = Bpjskesehatan::select('nik', 'iuran')
            ->whereIn('kode_bpjs_kesehatan', function ($query) use ($berlakugaji) {
                $query->select(DB::raw('MAX(kode_bpjs_kesehatan   )'))
                    ->from('hrd_bpjs_kesehatan')
                    ->where('tanggal_berlaku', '<=', $berlakugaji)
                    ->groupBy('nik');
            });


        $bpjstenagakerja = Bpjstenagakerja::select('nik', 'iuran')
            ->whereIn('kode_bpjs_tenagakerja', function ($query) use ($berlakugaji) {
                $query->select(DB::raw('MAX(kode_bpjs_tenagakerja   )'))
                    ->from('hrd_bpjs_tenagakerja')
                    ->where('tanggal_berlaku', '<=', $berlakugaji)
                    ->groupBy('nik');
            });

        $penyesuaianupah = Detailpenyesuaianupah::select(
            'nik',
            DB::raw('SUM(penambah) as jml_penambah'),
            DB::raw('SUM(pengurang) as jml_pengurang')
        )
            ->join('hrd_penyesuaian_upah', 'hrd_penyesuaian_upah_detail.kode_gaji', '=', 'hrd_penyesuaian_upah.kode_gaji')
            ->where('hrd_penyesuaian_upah.kode_gaji', $kode_potongan)
            ->groupBy('nik');
        $qpresensi = Presensi::query();
        $qpresensi->whereBetween('tanggal', [$start_date, $end_date]);

        $query = Karyawan::query();
        $query->select(
            'hrd_presensi.tanggal',
            'hrd_karyawan.nik',
            'nama_karyawan',
            'hrd_karyawan.kode_cabang',
            'hrd_karyawan.kode_jabatan',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_perusahaan',
            'hrd_karyawan.kode_klasifikasi',
            'hrd_karyawan.spip',
            'hrd_klasifikasi.klasifikasi',
            'hrd_karyawan.no_rekening',
            'hrd_karyawan.no_ktp',
            'hrd_karyawan.kode_status_kawin',
            'hrd_group.nama_group',
            'hrd_karyawan.tanggal_masuk',
            'hrd_karyawan.jenis_kelamin',
            'hrd_karyawan.status_karyawan',
            'jam_in',
            'jam_out',
            'hrd_presensi.status',
            'hrd_presensi.kode_jadwal',
            'nama_jadwal',
            'hrd_presensi.kode_jam_kerja',
            'jam_masuk as jam_mulai',
            'hrd_jamkerja.jam_pulang as jam_selesai',
            'lintashari',
            'total_jam',
            'istirahat',
            'jam_awal_istirahat',
            'jam_akhir_istirahat',
            //Izin Keluar
            'hrd_presensi_izinkeluar.kode_izin_keluar',
            'hrd_izinkeluar.jam_keluar',
            'hrd_izinkeluar.jam_kembali',
            'hrd_izinkeluar.keperluan',
            'hrd_izinkeluar.direktur as izin_keluar_direktur',

            //Izin Terlambat
            'hrd_presensi_izinterlambat.kode_izin_terlambat',
            'hrd_izinterlambat.direktur as izin_terlambat_direktur',

            //Izin Sakit
            'hrd_presensi_izinsakit.kode_izin_sakit',
            'hrd_izinsakit.doc_sid',
            'hrd_izinsakit.direktur as izin_sakit_direktur',

            //Izin Pulang
            'hrd_presensi_izinpulang.kode_izin_pulang',
            'hrd_izinpulang.direktur as izin_pulang_direktur',

            //Izin Cuti
            'hrd_presensi_izincuti.kode_izin_cuti',
            'hrd_izincuti.kode_cuti',
            'hrd_izincuti.direktur as izin_cuti_direktur',
            'hrd_jeniscuti.nama_cuti',

            //Izin Absen
            'hrd_presensi_izinabsen.kode_izin',
            'hrd_izinabsen.direktur as izin_absen_direktur',


            //Gaji
            'hrd_gaji.gaji_pokok',
            'hrd_gaji.t_jabatan',
            'hrd_gaji.t_masakerja',
            'hrd_gaji.t_tanggungjawab',
            'hrd_gaji.t_makan',
            'hrd_gaji.t_istri',
            'hrd_gaji.t_skill',

            //Insentif
            'hrd_insentif.iu_masakerja',
            'hrd_insentif.iu_lembur',
            'hrd_insentif.iu_penempatan',
            'hrd_insentif.iu_kpi',
            'hrd_insentif.im_ruanglingkup',
            'hrd_insentif.im_penempatan',
            'hrd_insentif.im_kinerja',
            'hrd_insentif.im_kendaraan',

            'hrd_bpjs_kesehatan.iuran as iuran_bpjs_kesehatan',
            'hrd_bpjs_tenagakerja.iuran as iuran_bpjs_tenagakerja',

            'pjp.cicilan_pjp',
            'kasbon.cicilan_kasbon',
            'piutangkaryawan.cicilan_piutang',
            'penyesuaianupah.jml_penambah',
            'penyesuaianupah.jml_pengurang'
        );
        // $query->join('hrd_karyawan', 'hrd_karyawan.nik', '=', 'hrd_presensi.nik');
        $query->leftJoin('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->leftjoinSub($qpresensi, 'hrd_presensi', 'hrd_karyawan.nik', '=', 'hrd_presensi.nik');
        $query->leftjoinSub($gajiTerakhir, 'hrd_gaji', 'hrd_karyawan.nik', '=', 'hrd_gaji.nik');
        $query->leftjoinSub($insentif, 'hrd_insentif', 'hrd_karyawan.nik', '=', 'hrd_insentif.nik');
        $query->leftjoinSub($bpjskesehatan, 'hrd_bpjs_kesehatan', 'hrd_karyawan.nik', '=', 'hrd_bpjs_kesehatan.nik');
        $query->leftjoinSub($bpjstenagakerja, 'hrd_bpjs_tenagakerja', 'hrd_karyawan.nik', '=', 'hrd_bpjs_tenagakerja.nik');
        $query->leftjoinSub($pjp, 'pjp', 'hrd_karyawan.nik', '=', 'pjp.nik');
        $query->leftjoinSub($kasbon, 'kasbon', 'hrd_karyawan.nik', '=', 'kasbon.nik');
        $query->leftjoinSub($piutangkaryawan, 'piutangkaryawan', 'hrd_karyawan.nik', '=', 'piutangkaryawan.nik');
        $query->leftjoinSub($penyesuaianupah, 'penyesuaianupah', 'hrd_karyawan.nik', '=', 'penyesuaianupah.nik');
        $query->leftJoin('hrd_jadwalkerja', 'hrd_presensi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal');
        $query->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja');

        $query->leftJoin('hrd_presensi_izinterlambat', 'hrd_presensi.id', '=', 'hrd_presensi_izinterlambat.id_presensi');
        $query->leftJoin('hrd_izinterlambat', 'hrd_presensi_izinterlambat.kode_izin_terlambat', '=', 'hrd_izinterlambat.kode_izin_terlambat');

        $query->leftJoin('hrd_presensi_izinkeluar', 'hrd_presensi.id', '=', 'hrd_presensi_izinkeluar.id_presensi');
        $query->leftJoin('hrd_izinkeluar', 'hrd_presensi_izinkeluar.kode_izin_keluar', '=', 'hrd_izinkeluar.kode_izin_keluar');

        $query->leftJoin('hrd_presensi_izinsakit', 'hrd_presensi.id', '=', 'hrd_presensi_izinsakit.id_presensi');
        $query->leftJoin('hrd_izinsakit', 'hrd_presensi_izinsakit.kode_izin_sakit', '=', 'hrd_izinsakit.kode_izin_sakit');

        $query->leftJoin('hrd_presensi_izinpulang', 'hrd_presensi.id', '=', 'hrd_presensi_izinpulang.id_presensi');
        $query->leftJoin('hrd_izinpulang', 'hrd_presensi_izinpulang.kode_izin_pulang', '=', 'hrd_izinpulang.kode_izin_pulang');


        $query->leftJoin('hrd_presensi_izincuti', 'hrd_presensi.id', '=', 'hrd_presensi_izincuti.id_presensi');
        $query->leftJoin('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti');
        $query->leftJoin('hrd_jeniscuti', 'hrd_izincuti.kode_cuti', '=', 'hrd_jeniscuti.kode_cuti');

        $query->leftJoin('hrd_presensi_izinabsen', 'hrd_presensi.id', '=', 'hrd_presensi_izinabsen.id_presensi');
        $query->leftJoin('hrd_izinabsen', 'hrd_presensi_izinabsen.kode_izin', '=', 'hrd_izinabsen.kode_izin');

        $query->where('hrd_karyawan.nik', $nik);
        $presensi = $query->get();

        $datapresensi = $presensi->groupBy('nik')->map(function ($rows) {
            $data = [
                'nik' => $rows->first()->nik,
                'nama_karyawan' => $rows->first()->nama_karyawan,
                'kode_jabatan' => $rows->first()->kode_jabatan,
                'nama_jabatan' => $rows->first()->nama_jabatan,
                'kategori_jabatan' => $rows->first()->kategori_jabatan,
                'kode_dept' => $rows->first()->kode_dept,
                'kode_cabang' => $rows->first()->kode_cabang,
                'kode_perusahaan' => $rows->first()->kode_perusahaan,
                'kode_klasifikasi' => $rows->first()->kode_klasifikasi,
                'klasifikasi' => $rows->first()->klasifikasi,
                'no_rekening' => $rows->first()->no_rekening,
                'no_ktp' => $rows->first()->no_ktp,
                'kode_status_kawin' => $rows->first()->kode_status_kawin,
                'nama_group' => $rows->first()->nama_group,
                'tanggal_masuk' => $rows->first()->tanggal_masuk,
                'jenis_kelamin' => $rows->first()->jenis_kelamin,
                'status_karyawan' => $rows->first()->status_karyawan,
                'gaji_pokok' => $rows->first()->gaji_pokok,
                't_jabatan' => $rows->first()->t_jabatan,
                't_masakerja' => $rows->first()->t_masakerja,
                't_tanggungjawab' => $rows->first()->t_tanggungjawab,
                't_makan' => $rows->first()->t_makan,
                't_istri' => $rows->first()->t_istri,
                't_skill' => $rows->first()->t_skill,
                'iu_masakerja' => $rows->first()->iu_masakerja,
                'iu_lembur' => $rows->first()->iu_lembur,
                'iu_penempatan' => $rows->first()->iu_penempatan,
                'iu_kpi' => $rows->first()->iu_kpi,
                'im_ruanglingkup' => $rows->first()->im_ruanglingkup,
                'im_penempatan' => $rows->first()->im_penempatan,
                'im_kinerja' => $rows->first()->im_kinerja,
                'im_kendaraan' => $rows->first()->im_kendaraan,
                'iuran_bpjs_kesehatan' => $rows->first()->iuran_bpjs_kesehatan,
                'iuran_bpjs_tenagakerja' => $rows->first()->iuran_bpjs_tenagakerja,
                'cicilan_pjp' => $rows->first()->cicilan_pjp,
                'cicilan_kasbon' => $rows->first()->cicilan_kasbon,
                'cicilan_piutang' => $rows->first()->cicilan_piutang,
                'spip' => $rows->first()->spip,
                'jml_penambah' => $rows->first()->jml_penambah,
                'jml_pengurang' => $rows->first()->jml_pengurang,
            ];
            foreach ($rows as $row) {
                $data[$row->tanggal] = [
                    'status' => $row->status,
                    'jam_in' => $row->jam_in,
                    'jam_out' => $row->jam_out,
                    'kode_jadwal' => $row->kode_jadwal,
                    'nama_jadwal' => $row->nama_jadwal,
                    'kode_jam_kerja' => $row->kode_jam_kerja,
                    'jam_mulai' => $row->jam_mulai,
                    'jam_selesai' => $row->jam_selesai,
                    'lintashari' => $row->lintashari,
                    'istirahat' => $row->istirahat,
                    'jam_awal_istirahat' => $row->jam_awal_istirahat,
                    'jam_akhir_istirahat' => $row->jam_akhir_istirahat,
                    'total_jam' => $row->total_jam,
                    'kode_izin_keluar' => $row->kode_izin_keluar,
                    'jam_keluar' => $row->jam_keluar,
                    'jam_kembali' => $row->jam_kembali,
                    'keperluan' => $row->keperluan,
                    'izin_keluar_direktur' => $row->izin_keluar_direktur,

                    'kode_izin_terlambat' => $row->kode_izin_terlambat,
                    'izin_terlambat_direktur' => $row->izin_terlambat_direktur,

                    'kode_izin_sakit' => $row->kode_izin_sakit,
                    'doc_sid' => $row->doc_sid,
                    'izin_sakit_direktur' => $row->izin_sakit_direktur,

                    'kode_izin_pulang' => $row->kode_izin_pulang,
                    'izin_pulang_direktur' => $row->izin_pulang_direktur,

                    'kode_izin_cuti' => $row->kode_izin_cuti,
                    'kode_cuti' => $row->kode_cuti,
                    'izin_cuti_direktur' => $row->izin_cuti_direktur,
                    'nama_cuti' => $row->nama_cuti,

                    'kode_izin' => $row->kode_izin_absen,
                    'izin_absen_direktur' => $row->izin_absen_direktur,
                ];
            }
            return $data;
        });

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $dataliburnasional = getdataliburnasional($start_date, $end_date);
        $datadirumahkan = getdirumahkan($start_date, $end_date);
        $dataliburpengganti = getliburpengganti($start_date, $end_date);
        $dataminggumasuk = getminggumasuk($start_date, $end_date);
        $datatanggallimajam = gettanggallimajam($start_date, $end_date);
        $datalembur = getlembur($start_date, $end_date, 1);
        $datalemburharilibur = getlembur($start_date, $end_date, 2);
        $jmlhari = hitungJumlahHari($start_date, $end_date) + 1;
        $datalemburharilibur = getlembur($start_date, $end_date, 2);
        $jmlhari = hitungJumlahHari($start_date, $end_date) + 1;

        // Calculation Logic (Ported from cetakslip.blade.php)
        $total_jam_satubulan = 173;
        $d = $datapresensi->first();
        
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
        $upah_premi_shift2_total = 0;
        $upah_premi_shift3_total = 0;
        
        $masakerja = hitungMasakerja($d['tanggal_masuk'], $end_date);

        $privillage_karyawan = [
            '16.11.266', '22.08.339', '19.10.142', '17.03.025', '00.12.062', 
            '08.07.092', '16.05.259', '17.08.023', '15.10.043', '17.07.302', 
            '15.10.143', '03.03.065', '23.12.337'
        ];

        while (strtotime($tanggal_presensi) <= strtotime($end_date)) {
            $search = ['nik' => $d['nik'], 'tanggal' => $tanggal_presensi];
            $cekdirumahkan = ceklibur($datadirumahkan, $search);
            $cekliburnasional = ceklibur($dataliburnasional, $search);
            $cektanggallimajam = ceklibur($datatanggallimajam, $search);
            $cekliburpengganti = ceklibur($dataliburpengganti, $search);
            $cekminggumasuk = ceklibur($dataminggumasuk, $search);
            $ceklembur = ceklembur($datalembur, $search);
            $ceklemburharilibur = ceklembur($datalemburharilibur, $search);

            $lembur = presensiHitunglembur($ceklembur);
            $lembur_libur = presensiHitunglembur($ceklemburharilibur);
            $total_overtime_1 += $lembur['overtime_1'];
            $total_overtime_2 += $lembur['overtime_2'];

            if (!empty($cekliburnasional)) {
                $overtime_libur = ($d['kode_jabatan'] == 'J20') ? $lembur_libur['overtime_libur'] * 2 : $lembur_libur['overtime_libur'];
                $total_overtime_libur_nasional += $overtime_libur;
            } else {
                $overtime_libur = $lembur_libur['overtime_libur'];
                $total_overtime_libur_reguler += $overtime_libur;
            }
            $total_overtime_libur += $overtime_libur;

            $premi_shift2_rate = ($tanggal_presensi >= '2026-02-25') ? 7500 : 5000;
            $premi_shift3_rate = ($tanggal_presensi >= '2026-02-25') ? 10000 : 6000;

            if ($tanggal_presensi < '2026-03-21') {
                $jml_premi_s2 = $lembur['jmlharilembur_shift_2'] + $lembur_libur['jmlharilembur_shift_2'];
                $jml_premi_s3 = $lembur['jmlharilembur_shift_3'] + $lembur_libur['jmlharilembur_shift_3'];
                $upah_premi_shift2_total += $jml_premi_s2 * $premi_shift2_rate;
                $upah_premi_shift3_total += $jml_premi_s3 * $premi_shift3_rate;
            }

            $pot_jam_t = 0; $pot_jam_d = 0; $pot_jam_i = 0; $pot_jam_p = 0; $pot_jam_th = 0; $pot_jam_iz = 0; $pot_jam_s = 0; $denda_hari = 0;
            $jam_kerja_hari = 0;

            if (isset($d[$tanggal_presensi])) {
                $row = $d[$tanggal_presensi];
                if ($row['status'] == 'h') {
                    $istirahat = $row['istirahat'];
                    $jam_mulai = (in_array($d['kode_jabatan'], ['J22', 'J23']) || (in_array($d['kode_jabatan'], ['J31', 'J32']) && $tanggal_presensi >= '2026-02-21')) || (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk)) ? $row['jam_in'] : date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $row['jam_mulai']));
                    $jam_selesai = (in_array($d['kode_jabatan'], ['J22', 'J23']) || (in_array($d['kode_jabatan'], ['J31', 'J32']) && $tanggal_presensi >= '2026-02-21')) || (getNamahari($tanggal_presensi) == 'Minggu' && empty($cekminggumasuk)) ? $row['jam_out'] : date(($row['lintashari'] == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi) . ' ' . $row['jam_selesai']);

                    $terlambat = presensiHitungJamTerlambat($row['jam_in'], $jam_mulai);
                    $denda = presensiHitungDenda($terlambat['jamterlambat'], $terlambat['menitterlambat'], $row['kode_izin_terlambat'], $d['kode_dept'], $d['kode_jabatan']);
                    $denda_hari = $denda['denda'];

                    $jam_awal_ist = null; $jam_akhir_ist = null;
                    if ($istirahat == '1') {
                        $base_tgl = ($row['lintashari'] == '1') ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi;
                        $jam_awal_ist = date('Y-m-d H:i', strtotime($base_tgl . ' ' . $row['jam_awal_istirahat']));
                        $jam_akhir_ist = date('Y-m-d H:i', strtotime($base_tgl . ' ' . $row['jam_akhir_istirahat']));
                    }

                    $pc = presensiHitungPulangCepat($row['jam_out'], $jam_selesai, $jam_awal_ist, $jam_akhir_ist);
                    $ik = presensiHitungJamKeluarKantor($row['jam_keluar'], $row['jam_kembali'], $jam_selesai, $row['jam_out'], $row['total_jam'], $istirahat, $jam_awal_ist, $jam_akhir_ist, $row['keperluan']);

                    $pot_jam_th = (empty($row['jam_in']) || empty($row['jam_out'])) ? $row['total_jam'] : 0;
                    $pot_jam_p = ($row['izin_pulang_direktur'] == '1' || ($tanggal_presensi >= '2026-03-21' && $pc['desimal'] < 1 && !empty($row['kode_izin_pulang']))) ? 0 : $pc['desimal'];
                    $pot_jam_iz = ($row['izin_keluar_direktur'] == '1' || $ik['desimal'] <= 1) ? 0 : $ik['desimal'];
                    $pot_jam_t = ($row['izin_terlambat_direktur'] == '1') ? 0 : $terlambat['desimal'];

                    $total_pot_hari = $pot_jam_s + $pot_jam_p + $pot_jam_iz + $pot_jam_t + $pot_jam_d + $pot_jam_th + $pot_jam_i;
                    $jam_kerja_hari = (!empty($row['jam_in']) && !empty($row['jam_out'])) ? $row['total_jam'] - $total_pot_hari : 0;

                    if ($row['kode_jadwal'] == 'JD003' && $jam_kerja_hari >= 5 && empty($cekliburnasional) && getNamahari($tanggal_presensi) != 'Minggu') {
                        $total_premi_shift2++; $upah_premi_shift2_total += $premi_shift2_rate;
                    }
                    if ($row['kode_jadwal'] == 'JD004' && $jam_kerja_hari >= 5 && empty($cekliburnasional) && getNamahari($tanggal_presensi) != 'Minggu') {
                        $total_premi_shift3++; $upah_premi_shift3_total += $premi_shift3_rate;
                    }
                } else if ($row['status'] == 's') {
                    if (!empty($row['doc_sid']) || $row['izin_sakit_direktur'] == '1') {
                        $jam_kerja_hari = !empty($cekdirumahkan) ? $row['total_jam'] / 2 : $row['total_jam'];
                        if (!empty($cekdirumahkan)) $pot_jam_d = $row['total_jam'] == 7 ? 1.75 : 1.25;
                    } else {
                        $jam_kerja_hari = !empty($cekdirumahkan) ? $row['total_jam'] / 2 : $row['total_jam'];
                        $pot_jam_s = $jam_kerja_hari;
                        if (!empty($cekdirumahkan)) $pot_jam_d = $row['total_jam'] == 7 ? 1.75 : 1.25;
                    }
                    if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21' && $tanggal_presensi < '2025-04-21') $pot_jam_s = 0;
                } else if ($row['status'] == 'c') {
                    if ($row['kode_cuti'] != 'C01') {
                        if ($tanggal_presensi >= '2024-11-21') {
                            if (!empty($cekdirumahkan)) {
                                $jam_kerja_hari = round($row['total_jam'] / 1.33, 2);
                                $pot_jam_d = $row['total_jam'] == 7 ? 1.75 : 1.25;
                            } else {
                                $jam_kerja_hari = $row['total_jam'];
                            }
                        } else {
                            $jam_kerja_hari = !empty($cekdirumahkan) ? $row['total_jam'] / 2 : $row['total_jam'];
                        }
                    } else {
                        if (!empty($cekdirumahkan)) $pot_jam_d = $row['total_jam'] == 7 ? 1.75 : 1.25;
                        $jam_kerja_hari = $row['total_jam'];
                    }
                } else if ($row['status'] == 'i') {
                    $jam_kerja_hari = !empty($cekdirumahkan) ? $row['total_jam'] / 2 : $row['total_jam'];
                    $pot_jam_i = ($row['izin_absen_direktur'] == '1') ? (!empty($cekdirumahkan) ? $jam_kerja_hari : 0) : $jam_kerja_hari;
                    if ($d['kode_jabatan'] == 'J19' && $tanggal_presensi >= '2024-10-21' && $tanggal_presensi < '2025-04-21') $pot_jam_i = 0;
                }
            } else {
                if (getNamahari($tanggal_presensi) == 'Minggu') {
                    $jam_kerja_hari = 0;
                } else if (!empty($cekdirumahkan)) {
                    $h_base = (getNamahari($tanggal_presensi) == 'Sabtu') ? (($tanggal_presensi == '2024-10-26') ? 3.5 : 2.5) : (!empty($cektanggallimajam) ? 2.5 : 3.5);
                    if ($tanggal_presensi >= '2024-11-21') {
                        if (getNamahari($tanggal_presensi) == 'Sabtu' || !empty($cektanggallimajam)) {
                            $jam_kerja_hari = 3.75; $pot_jam_d = 1.25;
                        } else {
                            $jam_kerja_hari = 5.25; $pot_jam_d = 1.75;
                        }
                    } else {
                        $jam_kerja_hari = $h_base; $pot_jam_d = $h_base;
                    }
                } else if (!empty($cekliburnasional)) {
                    $jam_kerja_hari = (getNamahari($tanggal_presensi) == 'Sabtu') ? 5 : 7;
                } else if (!empty($cekliburpengganti)) {
                    $jam_kerja_hari = 0;
                } else {
                    $pot_jam_th = (!empty($cekdirumahkan)) ? (getNamahari($tanggal_presensi) == 'Sabtu' ? ($tanggal_presensi == '2024-10-26' ? 3.5 : 2.5) : 3.5) : (getNamahari($tanggal_presensi) == 'Sabtu' ? 5 : 7);
                    $jam_kerja_hari = $pot_jam_th;
                }
            }
            if (in_array($d['nik'], $privillage_karyawan) && $tanggal_presensi >= '2024-11-21') $pot_jam_d = 0;
            
            $total_pot_hari = $pot_jam_s + $pot_jam_p + $pot_jam_iz + $pot_jam_t + $pot_jam_d + $pot_jam_th + $pot_jam_i;
            $total_potongan_jam_terlambat += $pot_jam_t;
            $total_potongan_jam_dirumahkan += $pot_jam_d;
            $total_potongan_jam_izinkeluar += $pot_jam_iz;
            $total_potongan_jam_pulangcepat += $pot_jam_p;
            $total_potongan_jam_tidakhadir += $pot_jam_th;
            $total_potongan_jam_izin += $pot_jam_i;
            $total_potongan_jam_sakit += $pot_jam_s;
            $grand_total_potongan_jam += $total_pot_hari;
            $total_denda += $denda_hari;

            $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
        }

        if ($d['kode_jabatan'] == 'J01') $grand_total_potongan_jam = 0;
        $total_jam_kerja_bulan = $total_jam_satubulan - $grand_total_potongan_jam;
        $upah_perjam = $upah / $total_jam_satubulan;

        if ($d['kode_jabatan'] == 'J20') {
            $u_ot1 = 1.5 * 6597 * $total_overtime_1;
            $u_ot2 = 1.5 * 6597 * $total_overtime_2;
            $u_ot_l = (13194 * $total_overtime_libur_reguler) + (13143 * $total_overtime_libur_nasional);
        } else {
            $u_ot1 = $upah_perjam * 1.5 * $total_overtime_1;
            $u_ot2 = $upah_perjam * 2 * $total_overtime_2;
            $u_ot_l = floor($upah_perjam * 2 * $total_overtime_libur);
        }
        $total_upah_overtime = $u_ot1 + $u_ot2 + $u_ot_l;
        $bruto = ($upah_perjam * $total_jam_kerja_bulan) + $total_upah_overtime + $upah_premi_shift2_total + $upah_premi_shift3_total;

        $totalbulanmasakerja = $masakerja['tahun'] * 12 + $masakerja['bulan'];
        $spip = (($d['kode_cabang'] == 'PST' && $totalbulanmasakerja >= 3) || ($d['kode_cabang'] == 'TSM' && $totalbulanmasakerja >= 3) || $d['spip'] == 1) ? 5000 : 0;
        
        $jml_potongan_upah = $d['iuran_bpjs_kesehatan'] + $d['iuran_bpjs_tenagakerja'] + $total_denda + $d['cicilan_pjp'] + $d['cicilan_kasbon'] + $d['cicilan_piutang'] + $d['jml_pengurang'] + $spip;
        $jmlbersih = $bruto - $jml_potongan_upah + $d['jml_penambah'];

        $summary = [
            'upah' => $upah,
            'gaji_pokok' => $d['gaji_pokok'],
            'tunjangan' => [
                'jabatan' => $d['t_jabatan'],
                'masa_kerja' => $d['t_masakerja'],
                'tanggung_jawab' => $d['t_tanggungjawab'],
                'makan' => $d['t_makan'],
                'istri' => $d['t_istri'],
                'skill' => $d['t_skill'],
            ],
            'insentif' => [
                'umum' => $insentif,
                'manager' => $insentif_manager,
                'total' => $jumlah_insentif
            ],
            'overtime' => [
                'jam_1' => $total_overtime_1,
                'upah_1' => $u_ot1,
                'jam_2' => $total_overtime_2,
                'upah_2' => $u_ot2,
                'jam_libur' => $total_overtime_libur,
                'upah_libur' => $u_ot_l,
                'total_upah' => $total_upah_overtime
            ],
            'premi_shift' => [
                'shift_2_hari' => $total_premi_shift2,
                'shift_2_upah' => $upah_premi_shift2_total,
                'shift_3_hari' => $total_premi_shift3,
                'shift_3_upah' => $upah_premi_shift3_total
            ],
            'potongan' => [
                'jam_absensi' => $grand_total_potongan_jam,
                'upah_absensi' => $upah_perjam * $grand_total_potongan_jam,
                'denda' => $total_denda,
                'bpjs_kesehatan' => $d['iuran_bpjs_kesehatan'],
                'bpjs_tk' => $d['iuran_bpjs_tenagakerja'],
                'pjp' => $d['cicilan_pjp'],
                'kasbon' => $d['cicilan_kasbon'],
                'piutang' => $d['cicilan_piutang'],
                'spip' => $spip,
                'pengurang' => $d['jml_pengurang'],
                'total' => $jml_potongan_upah
            ],
            'penambah' => $d['jml_penambah'],
            'bruto' => $bruto,
            'netto' => $jmlbersih,
            'upah_perjam' => $upah_perjam,
            'total_jam_kerja' => $total_jam_kerja_bulan,
            'masakerja' => $masakerja
        ];

        return response()->json(
            [
                'success' => true,
                'message' => 'Detail Slip Gaji!',
                'summary' => $summary,
                'employee' => $d,
                'start_date'  => $start_date,
                'end_date'    => $end_date,
            ]
        );
    }

    public function index()
    {
        return new SlipgajiResource(true, 'List Slip Gaji!', null);
    }
}
