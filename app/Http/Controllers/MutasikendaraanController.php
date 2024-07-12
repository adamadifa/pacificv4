<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kendaraan;
use App\Models\Mutasikendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MutasikendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasikendaraan::query();
        $query->select('ga_kendaraan_mutasi.*', 'no_polisi', 'cabang_asal.nama_cabang as cabang_asal', 'cabang_tujuan.nama_cabang as cabang_tujuan');
        $query->join('kendaraan', 'ga_kendaraan_mutasi.kode_kendaraan', 'kendaraan.kode_kendaraan');
        $query->join('cabang as cabang_asal', 'ga_kendaraan_mutasi.kode_cabang_asal', 'cabang_asal.kode_cabang');
        $query->join('cabang as cabang_tujuan', 'ga_kendaraan_mutasi.kode_cabang_tujuan', 'cabang_tujuan.kode_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Request::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        if (!empty($request->no_polisi)) {
            $query->where('kendaraan.no_polisi', 'like', '%' . $request->no_polisi . '%');
        }
        $query->orderBy('ga_kendaraan_mutasi.tanggal', 'desc');
        $mutasikendaraan = $query->paginate(10);
        $mutasikendaraan->appends(request()->all());
        $data['mutasikendaraan'] = $mutasikendaraan;
        return view('generalaffair.mutasikendaraan.index', $data);
    }

    public function create()
    {
        $data['kendaraan'] = Kendaraan::all();
        $data['cabang'] = Cabang::all();
        return view('generalaffair.mutasikendaraan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kendaraan' => 'required',
            'kode_cabang' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $lastmutasikendaraan = Mutasikendaraan::select('no_mutasi')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("no_mutasi", "desc")
                ->first();

            $lastnomutasi = $lastmutasikendaraan != null ? $lastmutasikendaraan->no_mutasi : '';

            $no_mutasi  = buatkode($lastnomutasi, "M" . date('my', strtotime($request->tanggal)), 2);


            $kendaraan = Kendaraan::where('kode_kendaraan', $request->kode_kendaraan)->first();
            Mutasikendaraan::create([
                'no_mutasi' => $no_mutasi,
                'kode_kendaraan' => $request->kode_kendaraan,
                'tanggal' => $request->tanggal,
                'kode_cabang_asal' => $kendaraan->kode_cabang,
                'kode_cabang_tujuan' => $request->kode_cabang,
                'keterangan' => $request->keterangan
            ]);

            Kendaraan::where('kode_kendaraan', $request->kode_kendaraan)->update([
                'kode_cabang' => $request->kode_cabang
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);

        DB::beginTransaction();
        try {
            $mutasikendaraan = Mutasikendaraan::where('no_mutasi', $no_mutasi)->first();
            $cekmutasikendaraan = Mutasikendaraan::where('kode_kendaraan', $mutasikendaraan->kode_kendaraan)->where('tanggal', '>', $mutasikendaraan->tanggal)->count();
            if ($cekmutasikendaraan > 0) {
                return Redirect::back()->with(messageError('Data Mutasi Kendaraan Tidak Bisa Dihapus  !'));
            }

            Mutasikendaraan::where('no_mutasi', $no_mutasi)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
