<?php

namespace App\Http\Controllers;

use App\Models\Detailjadwalshift;
use App\Models\Gantishift;
use App\Models\Group;
use App\Models\Jadwalshift;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JadwalshiftController extends Controller
{
    public function index()
    {
        $data['jadwalshift'] = Jadwalshift::orderBy('dari', 'desc')->paginate(15);
        return view('hrd.jadwalshift.index', $data);
    }

    public function create()
    {
        return view('hrd.jadwalshift.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
        ]);

        $tahun = substr(date('Y'), 2, 2);
        $lastjadwalshift = Jadwalshift::select('kode_jadwalshift')
            ->whereRaw('MID(kode_jadwalshift,3,2)="' . $tahun . '"')
            ->orderBy('kode_jadwalshift', 'desc')->first();
        $last_kode_jadwalshift = $lastjadwalshift != null ? $lastjadwalshift->kode_jadwalshift : '';
        $kode_jadwalshift = buatkode($last_kode_jadwalshift, "SJ" . $tahun, 4);
        $dari = $request->dari;
        $sampai = $request->sampai;
        $cekjadwal = Jadwalshift::whereRaw('"' . $dari . '" >= dari')
            ->whereRaw('"' . $dari . '" <= sampai')
            ->count();

        if (!empty($cekjadwal)) {
            return Redirect::back()->with(messageError('Jadwal Shift ' . $dari . ' - ' . $sampai . ' sudah ada.'));
        }

        try {
            Jadwalshift::create([
                'kode_jadwalshift' => $kode_jadwalshift,
                'dari' => $dari,
                'sampai' => $sampai
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jadwalshift)
    {
        $kode_jadwalshift = Crypt::decrypt($kode_jadwalshift);
        $data['jadwalshift'] = Jadwalshift::where('kode_jadwalshift', $kode_jadwalshift)->first();
        return view('hrd.jadwalshift.edit', $data);
    }

    public function update(Request $request, $kode_jadwalshift)
    {
        $kode_jadwalshift = Crypt::decrypt($kode_jadwalshift);
        try {
            Jadwalshift::where('kode_jadwalshift', $kode_jadwalshift)->update([
                'dari' => $request->dari,
                'sampai' => $request->sampai
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function aturjadwal($kode_jadwalshift)
    {
        $kode_jadwalshift = Crypt::decrypt($kode_jadwalshift);
        $data['jadwalshift'] = Jadwalshift::where('kode_jadwalshift', $kode_jadwalshift)->first();
        return view('hrd.jadwalshift.aturjadwal', $data);
    }

    public function getshift(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        $query = Detailjadwalshift::query();
        $query->where('kode_jadwalshift', $request->kode_jadwalshift);
        $query->join('hrd_karyawan', 'hrd_jadwalshift_detail.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->where('hrd_jadwalshift_detail.kode_jadwal', $request->kode_jadwal);
        $query->orderBy('hrd_karyawan.kode_group', 'asc');
        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        $shift = $query->get();

        $data['shift'] =  $shift;

        return view('hrd.jadwalshift.getshift', $data);
    }

    public function aturshift($shift, $kode_jadwalshift)
    {
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        $data['kode_jadwalshift'] = Crypt::decrypt($kode_jadwalshift);
        $data['shift'] = $shift;
        $data['group'] = Group::orderBy('kode_group')
            ->whereIn('kode_dept', $dept_access)
            ->get();
        return view('hrd.jadwalshift.aturshift', $data);
    }

    public function getgroup($kode_group, $kode_jadwalshift)
    {
        $data['karyawan'] = Karyawan::where('kode_group', $kode_group)
            ->select('hrd_karyawan.*', 'jadwalshift.kode_jadwal', 'nama_jadwal')
            //left join ke detail jadwal shift dengan kode jadwal shift sesuai parameter
            ->leftJoin(
                DB::raw("(
                    SELECT nik,kode_jadwal FROM hrd_jadwalshift_detail
                    WHERE kode_jadwalshift = '$kode_jadwalshift'
                ) jadwalshift"),
                function ($join) {
                    $join->on('hrd_karyawan.nik', '=', 'jadwalshift.nik');
                }
            )
            //Left Join Ke Tabel Jadwal
            ->leftJoin('hrd_jadwalkerja', function ($join) {
                $join->on('jadwalshift.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal');
            })
            ->where('status_aktif_karyawan', 1)
            ->orderBy('nama_karyawan')
            ->get();
        return view('hrd.jadwalshift.getgroup', $data);
    }


    public function updatejadwal(Request $request)
    {
        try {
            if ($request->shift == 1) {
                $kode_jadwal = "JD002";
            } else if ($request->shift == 2) {
                $kode_jadwal = "JD003";
            } else if ($request->shift == 3) {
                $kode_jadwal = "JD004";
            }
            $cek = Detailjadwalshift::where('nik', $request->nik)->where('kode_jadwalshift', $request->kode_jadwalshift)->first();
            if ($cek != null) {
                Detailjadwalshift::where('nik', $request->nik)->where('kode_jadwalshift', $request->kode_jadwalshift)->update([
                    'kode_jadwal' => $kode_jadwal
                ]);
            } else {
                Detailjadwalshift::create([
                    'nik' => $request->nik,
                    'kode_jadwalshift' => $request->kode_jadwalshift,
                    'kode_jadwal' => $kode_jadwal
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function tambahkansemua(Request $request)
    {
        try {
            if ($request->shift == 1) {
                $kode_jadwal = "JD002";
            } else if ($request->shift == 2) {
                $kode_jadwal = "JD003";
            } else if ($request->shift == 3) {
                $kode_jadwal = "JD004";
            }

            $karyawan = Karyawan::where('status_aktif_karyawan', 1)->where('kode_group', $request->kode_group)->get();
            foreach ($karyawan as $d) {
                $cek = Detailjadwalshift::where('nik', $d->nik)->where('kode_jadwalshift', $request->kode_jadwalshift)->first();
                if ($cek != null) {
                    Detailjadwalshift::where('nik', $d->nik)->where('kode_jadwalshift', $request->kode_jadwalshift)->update([
                        'kode_jadwal' => $kode_jadwal
                    ]);
                } else {
                    Detailjadwalshift::create([
                        'nik' => $d->nik,
                        'kode_jadwalshift' => $request->kode_jadwalshift,
                        'kode_jadwal' => $kode_jadwal
                    ]);
                }
            }


            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function batalkansemua(Request $request)
    {
        try {
            if ($request->shift == 1) {
                $kode_jadwal = "JD002";
            } else if ($request->shift == 2) {
                $kode_jadwal = "JD003";
            } else if ($request->shift == 3) {
                $kode_jadwal = "JD004";
            }

            // Delete Semua Karyawan di Detail Jadwal Shift Sesuai Kode Group
            Detailjadwalshift::join('hrd_karyawan', 'hrd_jadwalshift_detail.nik', '=', 'hrd_karyawan.nik')
                ->where('kode_group', $request->kode_group)
                ->where('hrd_jadwalshift_detail.kode_jadwalshift', $request->kode_jadwalshift)
                ->delete();


            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteshift(Request $request)
    {
        try {
            Detailjadwalshift::where('nik', $request->nik)->where('kode_jadwalshift', $request->kode_jadwalshift)->delete();
            return response()->json(['success' => true, 'message' => 'Delete Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function gantishift($kode_jadwalshift)
    {
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        $data['kode_jadwalshift'] = $kode_jadwalshift;
        $data['karyawan'] = Detailjadwalshift::select('hrd_jadwalshift_detail.nik', 'nama_karyawan')
            ->join('hrd_karyawan', 'hrd_jadwalshift_detail.nik', '=', 'hrd_karyawan.nik')
            ->where('kode_jadwalshift', $kode_jadwalshift)
            ->whereIn('hrd_karyawan.kode_dept', $dept_access)
            ->get();
        $data['jadwalshift'] = Jadwalshift::where('kode_jadwalshift', $kode_jadwalshift)->first();
        return view('hrd.jadwalshift.gantishift', $data);
    }

    public function storegantishift(Request $request)
    {

        try {
            $cek = Gantishift::where('nik', $request->nik)
                ->where('tanggal', $request->tanggal)
                ->first();
            if ($cek != null) {
                return response()->json(['success' => false, 'message' => 'Data Sudah Ada']);
            }

            $lastgantishift = Gantishift::select('kode_gs')->whereRaw('MID(kode_gs,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_gs', 'desc')->first();
            $last_kode_gs = $lastgantishift != null ? $lastgantishift->kode_gs : '';
            $kode_gs = buatkode($last_kode_gs, "GS" . date('y', strtotime($request->tanggal)), 3);


            Gantishift::create([
                'kode_gs' => $kode_gs,
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'kode_jadwal' => $request->kode_jadwal,
                'kode_jadwalshift' => $request->kode_jadwalshift,
            ]);

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getgantishift($kode_jadwalshift)
    {
        $kode_jadwalshift = Crypt::decrypt($kode_jadwalshift);
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        $data['gantishift'] = Gantishift::where('kode_jadwalshift', $kode_jadwalshift)
            ->join('hrd_karyawan', 'hrd_gantishift.nik', '=', 'hrd_karyawan.nik')
            ->whereIn('hrd_karyawan.kode_dept', $dept_access)

            ->join('hrd_jadwalkerja', 'hrd_gantishift.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->get();

        return view('hrd.jadwalshift.getgantishift', $data);
    }

    public function deletegantishift(Request $request)
    {
        try {
            Gantishift::where('kode_gs', $request->kode_gs)
                ->delete();
            return response()->json(['success' => true, 'message' => 'Delete Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
