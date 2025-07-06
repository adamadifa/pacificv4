<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Jasamasakerja;
use App\Models\Karyawan;
use App\Models\Kesepakatanbersama;
use App\Models\Kontrakgaji;
use App\Models\Kontrakkaryawan;
use App\Models\Kontrakpenilaian;
use App\Models\Penilaiankaryawan;
use App\Models\Potongankesepakatanbersama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KesepakatanbersamaController extends Controller
{
    public function index(Request $request)
    {

        $query = Kesepakatanbersama::query();
        $query->select(
            'hrd_kesepakatanbersama.*',
            'nama_karyawan',
            'hrd_penilaian.kode_jabatan',
            'nama_jabatan',
            'hrd_penilaian.kode_dept',
            'hrd_penilaian.kode_cabang',
            'hrd_kontrak_penilaian.no_kontrak as no_kontrak_baru'
        );
        $query->join('hrd_karyawan', 'hrd_kesepakatanbersama.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_penilaian', 'hrd_kesepakatanbersama.kode_penilaian', '=', 'hrd_penilaian.kode_penilaian');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('hrd_kontrak_penilaian', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kontrak_penilaian.kode_penilaian');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_kesepakatanbersama.dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('nik', $request->nik);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }
        $query->orderBy('hrd_kesepakatanbersama.tanggal', 'desc');
        $query->orderBy('no_kontrak_baru', 'asc');
        $kesepakatanbersama = $query->paginate(15);
        $kesepakatanbersama->appends($request->all());
        $data['kesepakatanbersama'] = $kesepakatanbersama;
        return view('hrd.kesepakatanbersama.index', $data);
    }

    public function create($kode_penialaian)
    {
        $kode_penialaian = Crypt::decrypt($kode_penialaian);
        $data['kode_penilaian'] = $kode_penialaian;
        return view('hrd.kesepakatanbersama.create', $data);
    }

    public function store(Request $request, $kode_penialaian)
    {
        $kode_penialaian = Crypt::decrypt($kode_penialaian);
        $penialaiankaryawan = Penilaiankaryawan::where('kode_penilaian', $kode_penialaian)->first();
        $request->validate([
            'tanggal' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lastkb = Kesepakatanbersama::select('no_kb')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("no_kb", "desc")
                ->first();
            $last_no_kb = $lastkb != null ? $lastkb->no_kb : '';
            $no_kb  = buatkode($last_no_kb, "KB" . date('my', strtotime($request->tanggal)), 3);

            //$last Kontrak
            $kontrak = Kontrakkaryawan::where('nik', $penialaiankaryawan->nik)
                ->where('status_kontrak', 1)
                ->first();

            $gaji = Gaji::where('nik', $penialaiankaryawan->nik)
                ->where('tanggal_berlaku', '<=', $request->tanggal)
                ->orderBy('tanggal_berlaku', 'desc')
                ->first();


            Kesepakatanbersama::create([
                'no_kb' => $no_kb,
                'tanggal' => $request->tanggal,
                'nik' => $penialaiankaryawan->nik,
                'kode_penilaian' => $penialaiankaryawan->kode_penilaian,
                'no_kontrak' => $kontrak->no_kontrak,
                'kode_gaji' => $gaji->kode_gaji,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Ditambahkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function potongan($no_kb)
    {
        $no_kb = Crypt::decrypt($no_kb);
        $data['no_kb'] = $no_kb;
        $data['potongan'] = Potongankesepakatanbersama::where('no_kb', $no_kb)->get();
        return view('hrd.kesepakatanbersama.potongan', $data);
    }

    public function storepotongan(Request $request, $no_kb)
    {

        $no_kb = Crypt::decrypt($no_kb);
        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        DB::beginTransaction();
        try {
            if (empty($keterangan)) {
                return Redirect::back()->with(messageError('Data Potongan Tidak Boleh Kosong'));
            }

            //Hapus Data Sebelumnya
            Potongankesepakatanbersama::where('no_kb', $no_kb)->delete();

            //Simpan Data
            for ($i = 0; $i < count($keterangan); $i++) {
                $detail[] = [
                    'no_kb' => $no_kb,
                    'keterangan' => $keterangan[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                ];
            }

            Potongankesepakatanbersama::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak($no_kb)
    {
        $no_kb = Crypt::decrypt($no_kb);
        $data['kesepakatanbersama'] =  Kesepakatanbersama::select(
            'hrd_kesepakatanbersama.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_karyawan.tanggal_masuk',
            'hrd_karyawan.no_ktp',
            'hrd_karyawan.alamat as alamat_karyawan',
            'hrd_penilaian.kode_dept',
            'hrd_penilaian.kode_cabang',
            'nama_cabang',
            'alamat_cabang',
            'nama_pt',
            'hrd_gaji.gaji_pokok',
            'hrd_gaji.t_jabatan',
            'hrd_gaji.t_tanggungjawab',
            'hrd_gaji.t_makan',
            'hrd_gaji.t_skill',
            'hrd_kontrak_penilaian.no_kontrak as no_kontrak_baru'
        )
            ->join('hrd_karyawan', 'hrd_kesepakatanbersama.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_penilaian', 'hrd_kesepakatanbersama.kode_penilaian', '=', 'hrd_penilaian.kode_penilaian')
            ->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('cabang', 'hrd_penilaian.kode_cabang', 'cabang.kode_cabang')
            ->leftJoin('hrd_kontrak_penilaian', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kontrak_penilaian.kode_penilaian')
            ->leftJoin('hrd_gaji', 'hrd_kesepakatanbersama.kode_gaji', 'hrd_gaji.kode_gaji')
            ->where('no_kb', $no_kb)
            ->first();

        $data['kontrak'] = Kontrakkaryawan::where('no_kontrak', $data['kesepakatanbersama']->no_kontrak)->first();
        $data['jmk'] = Jasamasakerja::where('nik', $data['kesepakatanbersama']->nik)
            ->where('tanggal', '<', $data['kesepakatanbersama']->tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();
        $data['pihak_satu'] = config('hrd.pihak_satu');
        $data['potongan'] = Potongankesepakatanbersama::where('no_kb', $no_kb)->get();
        return view('hrd.kesepakatanbersama.cetak', $data);
    }


    public function createkontrak($kode_penialaian)
    {
        $kode_penialaian = Crypt::decrypt($kode_penialaian);
        $data['jabatan'] = Jabatan::orderBy('kode_jabatan')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $pk = new Penilaiankaryawan();
        $penialaiankaryawan = $pk->getPenilaianKaryawan($kode_penialaian)->first();
        $data['penilaiankaryawan'] = $penialaiankaryawan;
        $data['gaji'] = Gaji::where('nik', $penialaiankaryawan->nik)->orderBy('tanggal_berlaku', 'desc')->first();
        return view('hrd.kesepakatanbersama.createkontrak', $data);
    }


    public function storekontrak(Request $request, $kode_penialaian)
    {
        $kode_penialaian = Crypt::decrypt($kode_penialaian);
        $request->validate([
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_jabatan' => 'required',
            'dari' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //Generate No. Kontrak
            $penialaiankaryawan = Penilaiankaryawan::where('kode_penilaian', $kode_penialaian)->first();
            $lastkontrak = Kontrakkaryawan::select('no_kontrak')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->orderBy("no_kontrak", "desc")
                ->first();
            $last_no_kontrak = $lastkontrak != null ? $lastkontrak->no_kontrak : '';
            $no_kontrak  = buatkode($last_no_kontrak, "K" . date('my', strtotime($request->dari)), 3);

            //Generate Kode Gaji

            $lastgaji = Gaji::select('kode_gaji')
                ->whereRaw('YEAR(tanggal_berlaku)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('LEFT(kode_gaji,3)="S' . date('y', strtotime($request->dari)) . '"')
                ->orderBy("kode_gaji", "desc")
                ->first();

            $last_kode_gaji = $lastgaji != null ? $lastgaji->kode_gaji : '';
            $kode_gaji  = buatkode($last_kode_gaji, "S" . date('y', strtotime($request->dari)), 4);


            //Cek Kontrak Terakhir Karyawan
            $lastkontrakkaryawan = Kontrakkaryawan::where('nik', $penialaiankaryawan->nik)
                ->orderBy('tanggal', 'desc')
                ->orderBy('no_kontrak', 'desc')
                ->first();
            Kontrakkaryawan::create([
                'no_kontrak' => $no_kontrak,
                'nik' => $penialaiankaryawan->nik,
                'tanggal' => $request->dari,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'status_kontrak' => 1,
                'status_pemutihan' => 0,
            ]);

            Gaji::create([
                'nik' => $penialaiankaryawan->nik,
                'kode_gaji' => $kode_gaji,
                'gaji_pokok' => toNumber($request->gaji_pokok),
                't_jabatan' => toNumber($request->t_jabatan),
                't_masakerja' => toNumber($request->t_masakerja),
                't_tanggungjawab' => toNumber($request->t_tanggungjawab),
                't_makan' => toNumber($request->t_makan),
                't_istri' => toNumber($request->t_istri),
                't_skill' => toNumber($request->t_skill),
                'tanggal_berlaku' => $request->dari
            ]);

            Kontrakgaji::create([
                'no_kontrak' => $no_kontrak,
                'kode_gaji' => $kode_gaji,
            ]);

            Kontrakpenilaian::create([
                'no_kontrak' => $no_kontrak,
                'kode_penilaian' => $kode_penialaian,
            ]);

            Karyawan::where('nik', $penialaiankaryawan->nik)->update([
                'kode_jabatan' => $request->kode_jabatan,
                'kode_cabang' => $request->kode_cabang,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_dept' => $request->kode_dept
            ]);

            if ($lastkontrakkaryawan != null) {
                $lastkontrakkaryawan->update([
                    'status_kontrak' => 0
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_kb)
    {

        $no_kb = Crypt::decrypt($no_kb);
        try {
            $kesepakatanbersama = Kesepakatanbersama::where('no_kb', $no_kb);
            if (!$kesepakatanbersama) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }

            $kesepakatanbersama->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
