<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Jasamasakerja;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\Kategorijmk;
use App\Models\Rencanacicilanpjp;
use App\Models\Resign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ResignController extends Controller
{
    public function index(Request $request)
    {

        $query = Resign::query();
        $query->select('hrd_resign.*', 'nama_karyawan', 'nama_jabatan', 'kode_dept', 'kode_cabang', 'nama_kategori');
        $query->join('hrd_karyawan', 'hrd_resign.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_kategorijmk', 'hrd_resign.kode_kategori', '=', 'hrd_kategorijmk.kode_kategori');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_resign.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('nik', $request->nik);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }
        $query->orderBy('hrd_resign.tanggal', 'desc');
        $resign = $query->paginate(15);
        $resign->appends($request->all());
        $data['resign'] = $resign;
        return view('hrd.resign.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        $data['kategori_jmk'] = Kategorijmk::all();
        return view('hrd.resign.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'kode_kategori' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lastresign = Resign::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_resign", "desc")
                ->first();
            $lastnoresign = $lastresign != null ? $lastresign->kode_resign : '';
            $kode_resign = buatkode($lastnoresign, "RES" . date('y', strtotime($request->tanggal)), 3);
            Resign::create([
                'kode_resign' => $kode_resign,
                'nik' => $request->nik,
                'kode_kategori' => $request->kode_kategori,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'pjp' => $request->pjp ?? 0,
                'kasbon' => $request->kasbon ?? 0,
                'piutang' => $request->piutang_lainnya ?? 0,
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_resign)
    {
        $kode_resign = Crypt::decrypt($kode_resign);
        $data['resign'] = Resign::where('kode_resign', $kode_resign)->first();
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        $data['kategori_jmk'] = Kategorijmk::all();
        return view('hrd.resign.edit', $data);
    }

    public function update(Request $request, $kode_resign)
    {

        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'kode_kategori' => 'required',
        ]);

        $kode_resign = Crypt::decrypt($kode_resign);
        try {
            Resign::where('kode_resign', $kode_resign)->update([
                'nik' => $request->nik,
                'kode_kategori' => $request->kode_kategori,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'pjp' => $request->pjp ?? 0,
                'kasbon' => $request->kasbon ?? 0,
                'piutang' => $request->piutang_lainnya ?? 0,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak($kode_resign)
    {
        $kode_resign = Crypt::decrypt($kode_resign);
        $data['resign'] =  Resign::select(
            'hrd_resign.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_karyawan.tanggal_masuk',
            'hrd_karyawan.no_ktp',
            'hrd_karyawan.alamat as alamat_karyawan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'nama_cabang',
            'alamat_cabang',
            'nama_pt'
        )
            ->join('hrd_karyawan', 'hrd_resign.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('cabang', 'hrd_karyawan.kode_cabang', 'cabang.kode_cabang')
            ->where('kode_resign', $kode_resign)
            ->first();


        $data['gaji'] = Gaji::where('nik', $data['resign']->nik)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();
        $data['pjp'] = Rencanacicilanpjp::join('keuangan_pjp', 'keuangan_pjp_rencanacicilan.no_pinjaman', '=', 'keuangan_pjp.no_pinjaman')
            ->select(DB::raw('SUM(keuangan_pjp_rencanacicilan.jumlah) as sisa_pjp'))
            ->where('keuangan_pjp.nik', $data['resign']->nik)
            ->where('keuangan_pjp_rencanacicilan.bayar', 0)
            ->first();
        $data['kasbon'] = Kasbon::select(DB::raw('SUM(jumlah) as total_kasbon'))
            ->where('nik', $data['resign']->nik)
            ->where('status', 0)
            ->first();
        $data['jmk_sudahbayar'] = Jasamasakerja::where('nik', $data['resign']->nik)
            ->select(DB::raw('SUM(jumlah) as jmk_sudahbayar'))
            ->first();
        $data['pihak_satu'] = config('hrd.pihak_satu');
        // $data['potongan'] = Potongankesepakatanbersama::where('no_kb', $no_kb)->get();
        return view('hrd.resign.cetak', $data);
    }

    public function destroy($kode_resign)
    {
        $kode_resign = Crypt::decrypt($kode_resign);
        try {
            Resign::where('kode_resign', $kode_resign)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
