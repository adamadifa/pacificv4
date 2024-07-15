<?php

namespace App\Http\Controllers;

use App\Models\Detailpenilaiankaryawan;
use App\Models\Itempenilaian;
use App\Models\Karyawan;
use App\Models\Kontrakkaryawan;
use App\Models\Penilaiankaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        $data['doc'] = $doc;
        $data['tanggal'] = $request->tanggal;
        // dd($karyawan->kode_jabatan);
        // dd($doc);
        $cekpenilaian = Penilaiankaryawan::where('no_kontrak', $request->no_kontrak)
            ->orWhere('kontrak_dari', $data['kontrak']->kontrak_dari)
            ->count();

        if ($cekpenilaian > 0) {
            return Redirect::back()->with('error', 'Penilaian Karyawan sudah pernah dilakukan');
        }
        $data['penilaian_item'] = Itempenilaian::where('kode_doc', $doc)
            ->select('hrd_penilaian_item.*', 'hrd_penilaian_kategori.nama_kategori')
            ->join('hrd_penilaian_kategori', 'hrd_penilaian_item.kode_kategori', '=', 'hrd_penilaian_kategori.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_item')
            ->get();
        if ($doc == 1) {
            return view('hrd.penilaiankaryawan.create_penilaian_1', $data);
        } else {
            return view('hrd.penilaiankaryawan.create_penilaian_2', $data);
        }
    }


    public function store(Request $request, $no_kontrak)
    {
        $no_kontrak = Crypt::decrypt($no_kontrak);
        $request->validate([
            'skor.*' => 'required',
            'rekomendasi' => 'required',
            'evaluasi' => 'required',

        ]);

        DB::beginTransaction();
        try {
            $lastpenilaian = Penilaiankaryawan::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_penilaian", "desc")
                ->first();
            $last_kode_penilaian = $lastpenilaian != null ? $lastpenilaian->kode_penilaian : '';
            $kode_penilaian = buatkode($last_kode_penilaian, "PK" . date('my', strtotime($request->tanggal)), 2);

            $kk = new Kontrakkaryawan();
            $kontrak = $kk->getKontrak($no_kontrak)->first();

            $k = new Karyawan();
            $karyawan = $k->getKaryawan($kontrak->nik);

            $sid = !empty($request->sid) ? $request->sid : 0;
            $sakit = !empty($request->sakit) ? $request->sakit : 0;
            $izin = !empty($request->izin) ? $request->izin : 0;
            $alfa = !empty($request->alfa) ? $request->alfa : 0;
            Penilaiankaryawan::create([
                'kode_penilaian' => $kode_penilaian,
                'nik' => $karyawan->nik,
                'tanggal' => $request->tanggal,
                'kontrak_dari' => $kontrak->dari,
                'kontrak_sampai' => $kontrak->sampai,
                'rekomendasi' => $request->rekomendasi,
                'evaluasi' => $request->evaluasi,
                'masa_kontrak' => $request->masa_kontrak,
                'kode_perusahaan' => $karyawan->kode_perusahaan,
                'kode_cabang' => $karyawan->kode_cabang,
                'kode_dept' => $karyawan->kode_dept,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_doc' => $request->kode_doc,
                'sid' => $sid,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'izin' => $izin,
                'status' => 0,
                'status_pemutihan' => 0,
                'no_kontrak' => $no_kontrak
            ]);

            foreach ($request->skor as $kode_item => $skor) {
                Detailpenilaiankaryawan::create([
                    'kode_penilaian' => $kode_penilaian,
                    'kode_item' => $kode_item,
                    'nilai' => $skor
                ]);
            }

            DB::commit();
            return redirect('/penilaiankaryawan')->with(messageSuccess('Data Berhasil Ditambah'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/penilaiankaryawan')->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_penilaian)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        try {
            Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
