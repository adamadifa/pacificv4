<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Piutangkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PiutangkaryawanController extends Controller
{
    public function index(Request $request)
    {
        $pk = new Piutangkaryawan();
        $piutangkaryawan = $pk->getPiutangkaryawan(request: $request)->paginate(15);
        $piutangkaryawan->appends(request()->all());
        $data['piutangkaryawan'] = $piutangkaryawan;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.piutangkaryawan.index', $data);
    }


    public function create()
    {
        return view('keuangan.piutangkaryawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $lastpiutang = Piutangkaryawan::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("no_pinjaman", "desc")
                ->first();

            $last_no_pinjaman = $lastpiutang != null ? $lastpiutang->no_pinjaman : '';
            $no_pinjaman  = buatkode($last_no_pinjaman, "NPJ" . date('y', strtotime($request->tanggal)), 3);

            Piutangkaryawan::create([
                'no_pinjaman' => $no_pinjaman,
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'jumlah' => toNumber($request->jumlah),
                'status' => isset($request->status) ? $request->status : 0,
                'id_user' => auth()->user()->id,
                'kategori' => $request->kategori
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));            //throw $th;
        }
    }


    public function show($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pk = new Piutangkaryawan();
        $data['piutangkaryawan'] = $pk->getPiutangkaryawan(no_pinjaman: $no_pinjaman)->first();
        return view('keuangan.piutangkaryawan.show', $data);
    }


    public function getpiutangkaryawan($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pk = new Piutangkaryawan();
        $data['piutangkaryawan'] = $pk->getPiutangkaryawan(no_pinjaman: $no_pinjaman)->first();
        return view('keuangan.piutangkaryawan.getpiutangkaryawan', $data);
    }


    public function destroy($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $piutangkaryawan = Piutangkaryawan::find($no_pinjaman);
        if (!$piutangkaryawan) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $piutangkaryawan->delete();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
