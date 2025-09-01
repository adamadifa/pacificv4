<?php

namespace App\Http\Controllers;

use App\Models\Bpjskesehatan;
use App\Models\Bpjstenagakerja;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailpenyesuaianupah;
use App\Models\Gaji;
use App\Models\Historibayarkasbon;
use App\Models\Historibayarpiutangkaryawan;
use App\Models\Historibayarpjp;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizincuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanhrdController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['roles_access_all_karyawan'] = config('global.roles_access_all_karyawan');
        $data['roles_access_all_pjp'] = config('global.roles_access_all_pjp');
        return view('hrd.laporan.index', $data);
    }


    public function getdepartemen(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        // $kode_cabang = $request->kode_cabang;
        $query = Karyawan::query();
        $query->join('hrd_departemen', 'hrd_departemen.kode_dept', '=', 'hrd_karyawan.kode_dept');
        $query->select('hrd_karyawan.kode_dept', 'nama_dept');
        $query->distinct();
        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan', 'manager keuangan', 'gm administrasi'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }
        if (!empty($kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', $kode_cabang);
        }
        $departemen = $query->get();


        // $departemen = Karyawan::where('kode_cabang', $kode_cabang)
        //     ->join('hrd_departemen', 'hrd_departemen.kode_dept', '=', 'hrd_karyawan.kode_dept')
        //     ->select('hrd_karyawan.kode_dept', 'nama_dept')->distinct()->get();

        $html = '<option value="">Semua Departemen</option>';
        foreach ($departemen as $d) {
            $html .= '<option value="' . $d->kode_dept . '">' . $d->nama_dept . '</option>';
        }

        return $html;
    }

    public function getgroup(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        // $kode_cabang = $request->kode_cabang;


        $query = Karyawan::query();
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->select('hrd_karyawan.kode_group', 'nama_group');
        $query->distinct();
        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan', 'manager keuangan', 'gm administrasi'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }
        if (!empty($kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', $kode_cabang);
        }
        $group = $query->get();


        $html = '<option value="">Semua Group</option>';
        foreach ($group as $d) {
            $html .= '<option value="' . $d->kode_group . '">' . $d->nama_group . '</option>';
        }

        return $html;
    }

    public function cetakpresensi(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");

        $kode_potongan = "GJ" . $request->bulan . $request->tahun;

        $lastbulan = $lastbulan < 10 ? '0' . $lastbulan : $lastbulan;
        $bulan = $request->bulan < 10 ? '0' . $request->bulan : $request->bulan;
        if ($request->periode_laporan == 2) {
            $dari = $request->tahun . "-" . $bulan  . "-01";
            $sampai = date("Y-m-t", strtotime($dari));
        } else {
            $dari = $lasttahun . "-" . $lastbulan . "-21";
            $sampai = $request->tahun . "-" . $bulan . "-20";
        }

        //dd($dari);
        $start_date = $dari;
        $end_date = $sampai;

        $daribulangaji = $dari;
        $berlakugaji =  in_array($request->format_laporan, [4, 5]) ? date('Y-m-t', strtotime(date('Y-m', strtotime($dari)) . '-01')) : $sampai;


        $karyawan_phk_maret = [
            '15.03.371',
            '15.05.373',
            '19.07.419',
            '22.01.450',
            '24.07.315'
        ];

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
                $query->select(DB::raw('MAX(kode_gaji)'))
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
                $query->select(DB::raw('MAX(kode_insentif)'))
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

        if (!empty($kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        // $query->whereBetween('hrd_presensi.tanggal', [$start_date, $end_date]);

        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }

        if (request()->is('laporanhrd/cetakgaji')) {
            if (!$user->hasRole($roles_access_all_pjp)) {
                if ($request->format_laporan != 3) {
                    $query->where('hrd_jabatan.kategori', 'NM');
                }
            } else {
                if (!empty($request->kategori_laporan)) {
                    $query->where('hrd_jabatan.kategori', $request->kategori_laporan);
                }
            }
        } else {
            if (!empty($request->kategori_laporan)) {
                $query->where('hrd_jabatan.kategori', $request->kategori_laporan);
            }
        }
        // $qpresensi->where('hrd_karyawan.nik', '15.08.376');
        $query->where('status_aktif_karyawan', 1);
        $query->where('tanggal_masuk', '<=', $end_date);
        if (in_array($request->format_laporan, [4, 5])) {
            $query->where('hrd_karyawan.status_karyawan', '!=', 'O');
            $query->whereNotIn('hrd_karyawan.nik', $karyawan_phk_maret);
        }
        $query->orWhere('status_aktif_karyawan', 0);
        $query->where('tanggal_off_gaji', '>=', $start_date);
        $query->where('tanggal_masuk', '<=', $end_date);

        if (in_array($request->format_laporan, [4, 5])) {
            $query->where('hrd_karyawan.status_karyawan', '!=', 'O');
            $query->whereNotIn('hrd_karyawan.nik', $karyawan_phk_maret);
        }

        if (!empty($kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        $query->whereBetween('hrd_presensi.tanggal', [$start_date, $end_date]);
        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }

        if (request()->is('laporanhrd/cetakgaji')) {
            if (!$user->hasRole($roles_access_all_pjp)) {
                if ($request->format_laporan != 3) {
                    $query->where('hrd_jabatan.kategori', 'NM');
                }
            } else {
                if (!empty($request->kategori_laporan)) {
                    $query->where('hrd_jabatan.kategori', $request->kategori_laporan);
                }
            }
        } else {
            if (!empty($request->kategori_laporan)) {
                $query->where('hrd_jabatan.kategori', $request->kategori_laporan);
            }
        }
        $query->orderBy('nik', 'asc');
        $query->orderBy('tanggal', 'asc');
        $presensi = $query->get();



        $data['presensi'] = $presensi->groupBy('nik')->map(function ($rows) {
            $data = [
                'nik' => $rows->first()->nik,
                'nama_karyawan' => $rows->first()->nama_karyawan,
                'kode_jabatan' => $rows->first()->kode_jabatan,
                'nama_jabatan' => $rows->first()->nama_jabatan,
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

        $data['dataliburnasional'] = getdataliburnasional($start_date, $end_date);
        $data['datadirumahkan'] = getdirumahkan($start_date, $end_date);
        $data['dataliburpengganti'] = getliburpengganti($start_date, $end_date);
        $data['dataminggumasuk'] = getminggumasuk($start_date, $end_date);
        $data['datatanggallimajam'] = gettanggallimajam($start_date, $end_date);
        $data['datalembur'] = getlembur($start_date, $end_date, 1);
        $data['datalemburharilibur'] = getlembur($start_date, $end_date, 2);
        $data['jmlhari'] = hitungJumlahHari($start_date, $end_date) + 1;
        $data['roles_access_all_pjp'] = $roles_access_all_pjp;
        $data['format_laporan'] = $request->format_laporan;
        $privillage_karyawan = [
            '16.11.266',
            '22.08.339',
            '19.10.142',
            '17.03.025',
            '00.12.062',
            '08.07.092',
            '16.05.259',
            '17.08.023',
            '15.10.043',
            '17.07.302',
            '15.10.143',
            '03.03.065',
            '23.12.337',
        ];

        $data['bulan'] = $bulan;
        $data['tahun'] = $request->tahun;
        $data['privillage_karyawan'] = $privillage_karyawan;
        if (request()->is('laporanhrd/cetakgaji')) {
            if ($request->format_laporan == 1 || $request->format_laporan == 3) {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=Laporan Gaji.xls");
                }
                return view('hrd.laporan.gaji_cetak', $data);
            } else if ($request->format_laporan == 2) {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=Rekap Gaji.xls");
                }
                $data['cabang'] = Cabang::orderby('kode_cabang')->get();
                return view('hrd.laporan.rekap_gaji_cetak', $data);
            } else if ($request->format_laporan == 4) {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=Laporan THR.xls");
                }
                return view('hrd.laporan.thr_cetak', $data);
            } else if ($request->format_laporan == 5) {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=Rekap THR.xls");
                }
                return view('hrd.laporan.rekap_thr_cetak', $data);
            }
        } else {
            if ($request->format_laporan == 1) {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=Laporan Presensi.xls");
                }
                return view('hrd.laporan.presensi_cetak', $data);
            } else {
                if (isset($_POST['exportButton'])) {
                    header("Content-type: application/vnd-ms-excel");
                    // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                    header("Content-Disposition: attachment; filename=PSM.xls");
                }
                return view('hrd.laporan.psm_cetak', $data);
            }
        }
    }


    public function cetakcuti(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $selectColumnBulan = [];
        $selectBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $selectColumnBulan[] = DB::raw('SUM(IF(MONTH(hrd_presensi.tanggal)="' . $i . '",1,0)) as `bulan_' . $i . '`');
            $selectBulan[] = 'bulan_' . $i;
        }


        $rekapcuti = Presensiizincuti::query();
        $rekapcuti->join('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti');
        $rekapcuti->join('hrd_presensi', 'hrd_presensi_izincuti.id_presensi', '=', 'hrd_presensi.id');
        $rekapcuti->select('hrd_presensi.nik', ...$selectColumnBulan);
        $rekapcuti->where('kode_cuti', 'C01');
        $rekapcuti->whereRaw('YEAR(hrd_presensi.tanggal)="' . $request->tahun . '"');
        $rekapcuti->groupBy('hrd_presensi.nik');




        $query = Karyawan::query();
        $query->select('hrd_karyawan.*', 'nama_cabang', 'nama_dept', 'nama_group', 'nama_jabatan', ...$selectBulan);
        $query->leftJoin('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->leftJoinSub($rekapcuti, 'rekapcuti', function ($join) {
            $join->on('hrd_karyawan.nik', '=', 'rekapcuti.nik');
        });
        $query->where('hrd_karyawan.status_aktif_karyawan', '=', '1');
        if (!empty($request->kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', '=', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', '=', $request->kode_dept);
        }

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', '=', $request->kode_group);
        }

        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }

        $data['cuti'] = $query->get();
        $data['tahun'] = $request->tahun;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Cuti.xls");
        }
        return view('hrd.laporan.cuti_cetak', $data);
    }
}
