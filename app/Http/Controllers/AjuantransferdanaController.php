<?php

namespace App\Http\Controllers;

use App\Models\Ajuantransferdana;
use App\Models\Cabang;
use App\Models\Setoranpusat;
use App\Models\Setoranpusatajuantransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AjuantransferdanaController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $atd = new Ajuantransferdana();
        $ajuantrasfer = $atd->getAjuantransferdana(request: $request)->paginate(15);
        $ajuantrasfer->appends(request()->all());
        $data['ajuantransfer'] = $ajuantrasfer;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.ajuantransferdana.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.ajuantransferdana.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'nama' => 'required',
                'nama_bank' => 'required',
                'jumlah' => 'required',
                'keterangan' => 'required',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'nama' => 'required',
                'nama_bank' => 'required',
                'jumlah' => 'required',
                'keterangan' => 'required',
            ]);
        }



        DB::beginTransaction();
        try {
            $lastajuan = Ajuantransferdana::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y') . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $kode_cabang . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();


            if ($lastajuan == null) {
                echo 1;
                $last_no_pengajuan = 'PTD' . $kode_cabang . substr(date('Y'), 2, 2) . '00000';
            } else {
                echo 2;
                $last_no_pengajuan = $lastajuan->no_pengajuan;
            }

            //dd($last_no_pengajuan);
            $no_pengajuan = buatkode($last_no_pengajuan, 'PTD' . $kode_cabang . substr(date('Y'), 2, 2), 5);

            Ajuantransferdana::create([
                'no_pengajuan' => $no_pengajuan,
                'tanggal' => $request->tanggal,
                'nama' => $request->nama,
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'jumlah' => toNumber($request->jumlah),
                'keterangan' => $request->keterangan,
                'status' => 0,
                'kode_cabang' => $kode_cabang
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_pengajuan)
    {
        $data['ajuantransfer'] = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->firstOrFail();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.ajuantransferdana.edit', $data);
    }

    public function update(Request $request, $no_pengajuan)
    {

        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required|date',
                'nama' => 'required|string|max:255',
                'nama_bank' => 'required|string|max:255',
                'jumlah' => 'required',
                'keterangan' => 'required|string',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required|date',
                'nama' => 'required|string|max:255',
                'nama_bank' => 'required|string|max:255',
                'jumlah' => 'required',
                'keterangan' => 'required|string',
            ]);
        }

        DB::beginTransaction();
        try {
            $ajuantransferdana = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->firstOrFail();
            $ajuantransferdana->update([
                'tanggal' => $request->tanggal,
                'nama' => $request->nama,
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'jumlah' => toNumber($request->jumlah),
                'keterangan' => $request->keterangan,
                'kode_cabang' => $kode_cabang
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diperbarui'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function delete($no_pengajuan)
    {
        DB::beginTransaction();
        try {
            $no_pengajuan = Crypt::decrypt($no_pengajuan);
            $ajuantransferdana = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->firstOrFail();
            $ajuantransferdana->delete();

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($no_pengajuan)
    {
        DB::beginTransaction();
        try {
            $no_pengajuan = Crypt::decrypt($no_pengajuan);
            $ajuantransferdana = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->firstOrFail();
            $ajuantransferdana->update([
                'status' => 1, // Mengubah status menjadi 1 untuk menandakan bahwa pengajuan telah disetujui
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Pengajuan Berhasil DiValidasi'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelApprove($no_pengajuan)
    {
        DB::beginTransaction();
        try {
            $no_pengajuan = Crypt::decrypt($no_pengajuan);
            $ajuantransferdana = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->firstOrFail();
            $ajuantransferdana->update([
                'status' => 0, // Mengubah status kembali menjadi 0 untuk menandakan bahwa pengajuan tidak disetujui
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Pengajuan Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function proses($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $atd = new Ajuantransferdana();
        $ajuantransfer = $atd->getAjuantransferdana($no_pengajuan)->first();
        $data['ajuantransfer'] = $ajuantransfer;
        return view('keuangan.ajuantransferdana.proses', $data);
    }


    public function prosesstore($no_pengajuan, Request $request)
    {

        $request->validate([
            'bukti' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $no_pengajuan = Crypt::decrypt($no_pengajuan);
            $ajuantransfer = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->first();

            $cektutuplaporan = cektutupLaporan($ajuantransfer->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $setoranpusat = Setoranpusat::select('kode_setoran')
                ->whereRaw('LEFT(kode_setoran,4)="SB' . date('y') . '"')
                ->orderBy('kode_setoran', 'desc')
                ->first();
            $last_kode_setoran = $setoranpusat != null ? $setoranpusat->kode_setoran : '';
            $kode_setoran   = buatkode($last_kode_setoran, 'SB' . date('y'), 5);

            Setoranpusat::create([
                'kode_setoran' => $kode_setoran,
                'tanggal' => $ajuantransfer->tanggal,
                'kode_cabang' => $ajuantransfer->kode_cabang,
                'setoran_kertas' => $ajuantransfer->jumlah,
                'keterangan' => 'Transfer ke Pihak ke 3 ' . $ajuantransfer->nama,
                'status' => 0
            ]);

            Setoranpusatajuantransfer::create([
                'kode_setoran' => $kode_setoran,
                'no_pengajuan' => $no_pengajuan
            ]);

            Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->update([
                'bukti' => $request->bukti
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with($e->getMessage());
        }
    }

    public function cancelProses($no_pengajuan)
    {

        DB::beginTransaction();
        try {
            $no_pengajuan = Crypt::decrypt($no_pengajuan);
            $ajuantransfer = Ajuantransferdana::where('no_pengajuan', $no_pengajuan)->first();

            if (!$ajuantransfer) {
                return Redirect::back()->with(messageError('Data Pengajuan Tidak Ditemukan'));
            }


            $setoranpusat = Setoranpusatajuantransfer::where('no_pengajuan', $no_pengajuan)->first();
            if ($setoranpusat) {
                Setoranpusat::where('kode_setoran', $setoranpusat->kode_setoran)->delete();
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Proses Pengajuan Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError('Error: ' . $e->getMessage()));
        }
    }

    public function cetak(Request $request)
    {
        $atd = new Ajuantransferdana();
        $data['ajuantransfer'] = $atd->getAjuantransferdana(request: $request)->get();
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_search)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Ajuan Transfer Dana $request->dari-$request->sampai.xls");
        }
        return view('keuangan.ajuantransferdana.cetak', $data);
    }
}
