<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Gaji;
use App\Models\Group;
use App\Models\Jabatan;
use App\Models\Jadwalkerja;
use App\Models\Jasamasakerja;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\Klasifikasikaryawan;
use App\Models\Kontrakkaryawan;
use App\Models\Pjp;
use App\Models\Rencanacicilanpjp;
use App\Models\Statusperkawinan;
use App\Models\Suratperingatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null ? json_decode($user->dept_access, true) : [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        //Tampilkan Departemen dan Group
        if (!$user->hasRole($roles_access_all_karyawan)) {
            if (auth()->user()->kode_cabang != 'PST') {
                $departemen = Karyawan::select('hrd_karyawan.kode_dept', 'nama_dept')
                    ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
                    ->where('kode_cabang', auth()->user()->kode_cabang)
                    ->groupBy('hrd_karyawan.kode_dept')
                    ->orderBy('hrd_karyawan.kode_dept')->get();
                $group = Karyawan::select('hrd_karyawan.kode_group', 'nama_group')
                    ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                    ->where('kode_cabang', auth()->user()->kode_cabang)
                    ->groupBy('hrd_karyawan.kode_group')
                    ->orderBy('hrd_karyawan.kode_group')->get();
            } else {
                $departemen = Karyawan::select('hrd_karyawan.kode_dept', 'nama_dept')
                    ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
                    ->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept)
                    ->groupBy('hrd_karyawan.kode_dept')
                    ->orderBy('hrd_karyawan.kode_dept')->get();
                $group = Karyawan::select('hrd_karyawan.kode_group', 'nama_group')
                    ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                    ->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept)
                    ->groupBy('hrd_karyawan.kode_group')
                    ->orderBy('hrd_karyawan.kode_group')->get();
            }
        } else {
            $departemen = Departemen::orderBy('kode_dept')->get();
            $group = Group::orderBy('kode_group')->get();
        }




        // $departemen = Departemen::orderBy('kode_dept')->get();
        // $group = Group::orderBy('kode_group')->get();
        $query = Karyawan::query();
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_karyawan)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                }
            }
        }




        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        $query->orderBy('nama_karyawan', 'asc');
        $karyawan = $query->paginate(15);
        $karyawan->appends($request->all());
        return view('datamaster.karyawan.index', compact('cabang', 'karyawan', 'departemen', 'group'));
    }

    public function create()
    {
        $status_perkawinan = Statusperkawinan::orderBy('kode_status_kawin')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();
        $jabatan = Jabatan::orderBy('kode_jabatan')->get();
        $klasifikasi = Klasifikasikaryawan::orderBy('kode_klasifikasi')->get();
        return view('datamaster.karyawan.create', compact(
            'status_perkawinan',
            'cabang',
            'departemen',
            'group',
            'jabatan',
            'klasifikasi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_group' => 'required',
            'kode_jabatan' => 'required',
            'kode_klasifikasi' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required'
        ]);

        try {
            Karyawan::create([
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_group' => $request->kode_group,
                'kode_jabatan' => $request->kode_jabatan,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'lock_location' => 1,
                'status_aktif_karyawan' => 1,
                'password' => Hash::make('12345')
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($nik)
    {

        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        $status_perkawinan = Statusperkawinan::orderBy('kode_status_kawin')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();
        $jabatan = Jabatan::orderBy('kode_jabatan')->get();
        $klasifikasi = Klasifikasikaryawan::orderBy('kode_klasifikasi')->get();
        $jadwalkerja = Jadwalkerja::orderBy('kode_jadwal')->get();
        return view('datamaster.karyawan.edit', compact(
            'status_perkawinan',
            'cabang',
            'departemen',
            'group',
            'jabatan',
            'klasifikasi',
            'karyawan',
            'jadwalkerja'
        ));
    }


    public function update(Request $request, $nik)
    {
        $nik = Crypt::decrypt($nik);
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_group' => 'required',
            'kode_jabatan' => 'required',
            'kode_klasifikasi' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required',
            'status_aktif_karyawan' => 'required',
            'kode_jadwal' => 'required'
        ]);

        try {
            Karyawan::where('nik', $nik)->update([
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_group' => $request->kode_group,
                'kode_jabatan' => $request->kode_jabatan,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'status_aktif_karyawan' => $request->status_aktif_karyawan,
                'tanggal_nonaktif' => $request->status_aktif_karyawan === "0" ? $request->tanggal_nonaktif : NULL,
                'tanggal_off_gaji' => $request->status_aktif_karyawan === "0" ? $request->tanggal_off_gaji : NULL,
                'kode_jadwal' => $request->kode_jadwal,
                'pin' => $request->pin
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi')
            ->join('hrd_status_kawin', 'hrd_karyawan.kode_status_kawin', '=', 'hrd_karyawan.kode_status_kawin')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')

            ->first();
        return view('datamaster.karyawan.show', compact('karyawan'));
    }


    public function unlocklocation($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        try {
            if ($karyawan->lock_location === '0') {
                Karyawan::where('nik', $nik)->update([
                    'lock_location' => 1,
                ]);
                return Redirect::back()->with(messageSuccess('Lokasi Berhasil Di Unlock'));
            } else {
                Karyawan::where('nik', $nik)->update([
                    'lock_location' => 0,
                ]);
                return Redirect::back()->with(messageSuccess('Lokasi Berhasil Di Lock'));
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getkaryawanjson(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $role_access_all_pjp = config('global.roles_access_all_pjp');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        if ($request->ajax()) {
            $query = Karyawan::query();
            $query->select(
                'hrd_karyawan.nik',
                DB::raw("UPPER(nama_karyawan) as nama_karyawan"),
                'hrd_jabatan.nama_jabatan',
                'hrd_departemen.nama_dept',
                DB::raw('UPPER(cabang.nama_cabang) as nama_cabang'),
                DB::raw("CASE
                    WHEN status_karyawan = 'T' THEN 'TETAP'
                    WHEN status_karyawan = 'K' THEN 'KONTRAK'
                    WHEN status_karyawan = 'O' THEN 'OUTSOURCING'
                    ELSE 'Undifined'
                END AS statuskaryawan")
            );
            $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
            $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
            $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', $user->kode_regional);
                    $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                } else {
                    if ($user->hasRole('sales marketing manager')) {
                        $query->where('hrd_karyawan.kode_jabatan', '!=', 'J07');
                    } else {
                        $query->where('hrd_jabatan.kategori', 'NM');
                    }
                    $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                }
            } else {
                if (!$user->hasRole($role_access_all_pjp)) {
                    if (!$user->hasRole('regional operation manager')) {
                        $query->where('hrd_jabatan.kategori', 'NM');
                    } else {
                        $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
                    }
                } else {
                    if (!$user->hasRole(['super admin', 'manager keuangan', 'gm administrasi'])) {
                        $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
                    }
                }
            }
            $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
            $query->where('status_aktif_karyawan', 1);
            $karyawan = $query;
            return DataTables::of($karyawan)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" nik="' . Crypt::encrypt($row->nik) . '" class="pilihkaryawan"><i class="ti ti-external-link"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getkaryawanpiutangkaryawanjson(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $role_access_all_pjp = config('global.roles_access_all_pjp');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        if ($request->ajax()) {
            $query = Karyawan::query();
            $query->select(
                'hrd_karyawan.nik',
                DB::raw("UPPER(nama_karyawan) as nama_karyawan"),
                'hrd_jabatan.nama_jabatan',
                'hrd_departemen.nama_dept',
                DB::raw('UPPER(cabang.nama_cabang) as nama_cabang'),
                DB::raw("CASE
                    WHEN status_karyawan = 'T' THEN 'TETAP'
                    WHEN status_karyawan = 'K' THEN 'KONTRAK'
                    WHEN status_karyawan = 'O' THEN 'OUTSOURCING'
                    ELSE 'Undifined'
                END AS statuskaryawan")
            );
            $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
            $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
            $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
            // if (!$user->hasRole($roles_access_all_cabang)) {
            //     if ($user->hasRole('regional sales manager')) {
            //         $query->where('cabang.kode_regional', $user->kode_regional);
            //         $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
            //     } else {
            //         $query->where('hrd_jabatan.kategori', 'NM');
            //         $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
            //     }
            // } else {
            //     if (!$user->hasRole($role_access_all_pjp)) {
            //         if (!$user->hasRole('regional operation manager')) {
            //             $query->where('hrd_jabatan.kategori', 'NM');
            //         } else {
            //             $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
            //         }
            //     } else {
            //         if (!$user->hasRole(['super admin', 'manager keuangan', 'gm administrasi'])) {
            //             $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
            //         }
            //     }
            // }
            $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
            $query->where('status_aktif_karyawan', 1);
            $karyawan = $query;
            return DataTables::of($karyawan)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" nik="' . Crypt::encrypt($row->nik) . '" class="pilihkaryawan"><i class="ti ti-external-link"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getkaryawan($nik)
    {

        $nik = Crypt::decrypt($nik);
        $query = Karyawan::query();
        $query->select(
            'hrd_karyawan.*',
            'hrd_jabatan.nama_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang',
            DB::raw("CASE
                WHEN status_karyawan = 'T' THEN 'TETAP'
                WHEN status_karyawan = 'K' THEN 'KONTRAK'
                WHEN status_karyawan = 'O' THEN 'OUTSOURCING'
                ELSE 'Undifined'
            END AS statuskaryawan")
        );
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->where('nik', $nik);
        $karyawan = $query->first()->toArray();

        $gaji = Gaji::select('hrd_gaji.nik', DB::raw("gaji_pokok + t_jabatan + t_masakerja + t_tanggungjawab + t_makan + t_istri + t_skill as gapok_tunjangan, gaji_pokok"))
            ->where('nik', $nik)->orderBy('tanggal_berlaku', 'desc')->first();
        $gajiArray = $gaji != null ? $gaji->toArray() : ['gapok_tunjangan' => 0, 'gaji_pokok' => 0];

        $kontrak = Kontrakkaryawan::select('sampai as akhir_kontrak')->where('nik', $nik)->orderBy('tanggal', 'desc')->first();
        $kontrakArray = $kontrak != null ? $kontrak->toArray() :  ['akhir_kontrak' => date("Y-m-d")];

        $jmk = Jasamasakerja::select(DB::raw('SUM(jumlah) as total_jmk_dibayar'))->where('nik', $nik)->groupBy('nik')->first();
        $jmkArray = $jmk != null ? $jmk->toArray() : ['total_jmk_dibayar' => 0];

        $sp = Suratperingatan::select('jenis_sp', 'sampai as tanggal_berakhir_sp')->where('nik', $nik)->where('sampai', '>', date('Y-m-d'))->orderBy('tanggal', 'desc')->first();
        $spArray = $sp != null ? $sp->toArray() : ['jenis_sp' => null, 'tanggal_berakhir_sp' => null];


        $cekpinjaman = Pjp::select(
            'keuangan_pjp.nik',
            DB::raw("SUM(jumlah_pinjaman) as total_pinjaman"),
            DB::raw("SUM(totalpembayaran) as total_pembayaran"),
        )
            ->leftJoin(
                DB::raw("(
                SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
            ) historibayar"),
                function ($join) {
                    $join->on('keuangan_pjp.no_pinjaman', '=', 'historibayar.no_pinjaman');
                }
            )
            ->whereRaw('IFNULL(jumlah_pinjaman,0) - IFNULL(totalpembayaran,0) != 0')
            ->where('keuangan_pjp.nik', $nik)
            ->groupBy('keuangan_pjp.nik')
            ->first();

        $cekpinjamanArray = $cekpinjaman != null ? $cekpinjaman->toArray() : ['total_pinjaman' => 0, 'total_pembayaran' => 0];



        $lastpjp = Pjp::select('keuangan_pjp.*', 'totalpembayaran')
            ->where('nik', $nik)
            ->leftJoin(
                DB::raw("(
                SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
            ) historibayar"),
                function ($join) {
                    $join->on('keuangan_pjp.no_pinjaman', '=', 'historibayar.no_pinjaman');
                }
            )
            ->whereRaw('IFNULL(jumlah_pinjaman,0) - IFNULL(totalpembayaran,0) != 0')
            ->orderBy('keuangan_pjp.tanggal', 'desc')
            ->first();

        if ($lastpjp != null) {
            $angsuran_max = $lastpjp->angsuran_max;
            $cicilan = Rencanacicilanpjp::where('no_pinjaman', $lastpjp->no_pinjaman)->first();
            $kasbon_max = $angsuran_max - $cicilan->jumlah;
        } else {
            $kasbon_max = 0;
        }

        $kasbonmaxArray = ['kasbon_max' => $kasbon_max];


        $cekkasbon = Kasbon::leftJoin(
            DB::raw("(
                SELECT no_kasbon,jumlah as totalpembayaran FROM keuangan_kasbon_historibayar
            ) historibayar"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'historibayar.no_kasbon');
            }
        )
            ->where('nik', $nik)
            ->whereNull('totalpembayaran')
            ->count();

        $kasbonArray = ['cekkasbon' => $cekkasbon];
        $data = array_merge($karyawan, $gajiArray, $kontrakArray, $jmkArray, $spArray, $cekpinjamanArray, $kasbonmaxArray, $kasbonArray);



        return response()->json([
            'success' => true,
            'message' => 'Detail Karyawan',
            'data'    => $data
        ]);
    }
}
