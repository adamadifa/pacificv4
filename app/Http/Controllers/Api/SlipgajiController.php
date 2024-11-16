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
    public function show($bulangaji, $tahungaji, $nik)
    {
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
        return response()->json(
            [
                'success' => true,
                'message' => 'List Slip Gaji!',
                'presensi'    => $datapresensi,
                'start_date'  => $start_date,
                'end_date'    => $end_date,
                'dataliburnasional' => $dataliburnasional,
                'datadirumahkan' => $datadirumahkan,
                'dataliburpengganti' => $dataliburpengganti,
                'dataminggumasuk' => $dataminggumasuk,
                'datatanggallimajam' => $datatanggallimajam,
                'datalembur' => $datalembur,
                'datalemburharilibur' => $datalemburharilibur,
                'jmlhari' => $jmlhari
            ]
        );
    }

    public function index()
    {
        return new SlipgajiResource(true, 'List Slip Gaji!', null);
    }
}
