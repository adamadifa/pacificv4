<?php

namespace App\Http\Controllers;

use App\Models\Itempenilaian;
use App\Models\Karyawan;
use App\Models\Kontrakkaryawan;
use App\Models\Penilaiankaryawan;
use Illuminate\Http\Request;

class PenilaiankaryawanController extends Controller
{
    public function index(Request $request)
    {
        $pk = new Penilaiankaryawan();
        $penilaiankaryawan = $pk->getPenilaiankaryawan(request: $request)->paginate(15);
        $penilaiankaryawan->appends(request()->all());
        $data['penilaiankaryawan'] = $penilaiankaryawan;
        return view('hrd.penilaiankaryawan.index', $data);
    }


    public function create()
    {
        $k = new Karyawan();
        $karyawan = $k->getKaryawanpenilaian()->get();
        $data['karyawan'] = $karyawan;
        return view('hrd.penilaiankaryawan.create', $data);
    }

    public function createpenilaian(Request $request)
    {
        $k = new Karyawan();
        $karyawan = $k->getKaryawan($request->nik);
        $data['karyawan'] = $karyawan;
        //dd($request->no_kontrak);
        //Kontrak Karyawan
        $kk = new Kontrakkaryawan();
        $data['kontrak'] = $kk->getKontrak($request->no_kontrak)->first();

        $doc_2 = ['J15', 'J16', 'J18', 'J19', 'J21', 'J22', 'J23', 'J24'];
        $doc = in_array($karyawan->kode_jabatan, $doc_2) ?  2 : 1;
        // dd($karyawan->kode_jabatan);
        // dd($doc);

        $data['penilaian_item'] = Itempenilaian::where('kode_doc', $doc)
            ->select('hrd_penilaian_item.*', 'hrd_penilaian_kategori.nama_kategori')
            ->join('hrd_penilaian_kategori', 'hrd_penilaian_item.kode_kategori', '=', 'hrd_penilaian_kategori.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_item')
            ->get();
        return view('hrd.penilaiankaryawan.create_penilaian_1', $data);
    }
}
