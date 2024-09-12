<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Bank;
use App\Models\Detailkontrabonangkutan;
use App\Models\Kontrabonangkutan;
use App\Models\Ledger;
use App\Models\Ledgerkontrabonangkutan;
use App\Models\Ledgerkontrabonangkutanhutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KontrabonangkutanController extends Controller
{
    public function index(Request $request)
    {
        $kb = new Kontrabonangkutan();
        $kontrabon = $kb->getKontrabonangkutan(request: $request)->paginate(15);
        $kontrabon->appends(request()->all());
        $data['kontrabon'] = $kontrabon;

        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        if (request()->is('kontrabonangkutan')) {
            return view('gudangjadi.kontrabon.index', $data);
        } else if (request()->is('kontrabonkeuangan/angkutan')) {
            return view('keuangan.kontrabon.angkutan', $data);
        }
    }

    public function create()
    {
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->where('kode_angkutan', '!=', 'A00')->get();
        return view('gudangjadi.kontrabon.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'kode_angkutan' => 'required'
        ]);

        $no_dok = $request->no_dok_item;
        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if (empty($no_dok)) {
                return Redirect::back()->with(messageError('Detail Kontrabon Masih Kosong'));
            }

            //Generate No. Kontrabon
            $lastkontrabon = Kontrabonangkutan::select('no_kontrabon')
                ->whereRaw('MID(no_kontrabon,4,4)=' . date('my', strtotime($request->tanggal)))
                ->orderBy('no_kontrabon', 'desc')
                ->first();
            $last_no_kontrabon = $lastkontrabon != null ? $lastkontrabon->no_kontrabon : '';
            $no_kontrabon = buatkode($last_no_kontrabon, 'KA/' . date('my', strtotime($request->tanggal)) . "/", 3);

            Kontrabonangkutan::create([
                'no_kontrabon' => $no_kontrabon,
                'tanggal' => $request->tanggal,
                'kode_angkutan' => $request->kode_angkutan
            ]);

            for ($i = 0; $i < count($no_dok); $i++) {
                $detail[] = [
                    'no_kontrabon' => $no_kontrabon,
                    'no_dok' => $no_dok[$i]
                ];
            }

            Detailkontrabonangkutan::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonangkutan();
        $data['kontrabon'] = $kb->getKontrabonangkutan($no_kontrabon)->first();
        $data['detail'] = Detailkontrabonangkutan::where('no_kontrabon', $no_kontrabon)
            ->select('gudang_jadi_angkutan_suratjalan.*', 'tujuan', 'gudang_jadi_mutasi.tanggal')
            ->join('gudang_jadi_angkutan_suratjalan', 'gudang_jadi_angkutan_kontrabon_detail.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok')
            ->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan')
            ->leftjoin('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok')
            ->orderBy('gudang_jadi_mutasi.tanggal', 'desc')
            ->get();
        return view('gudangjadi.kontrabon.show', $data);
    }

    public function proses($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonangkutan();
        $data['kontrabon'] = $kb->getKontrabonangkutan($no_kontrabon)->first();
        $data['detail'] = Detailkontrabonangkutan::where('no_kontrabon', $no_kontrabon)
            ->select('gudang_jadi_angkutan_suratjalan.*', 'tujuan', 'gudang_jadi_mutasi.tanggal')
            ->join('gudang_jadi_angkutan_suratjalan', 'gudang_jadi_angkutan_kontrabon_detail.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok')
            ->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan')
            ->leftjoin('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok')
            ->orderBy('gudang_jadi_mutasi.tanggal', 'desc')
            ->get();
        $bank = new Bank();
        $data['bank'] = $bank->getBank()->get();
        return view('gudangjadi.kontrabon.proses', $data);
    }

    public function storeproses(Request $request, $no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            //Generate No. Bukti Ledger
            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,7) ="LRPST' . date('y', strtotime($request->tanggal)) . '"')
                ->whereRaw('LENGTH(no_bukti)=12')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LRPST' . date('y', strtotime($request->tanggal)), 5);


            $start_date = date('Y-m-', strtotime($request->tanggal)) . "01";
            $end_date = date('Y-m-t', strtotime($start_date));

            $kontrabon = Kontrabonangkutan::select('gudang_jadi_angkutan_kontrabon.*', 'nama_angkutan')
                ->join('angkutan', 'gudang_jadi_angkutan_kontrabon.kode_angkutan', '=', 'angkutan.kode_angkutan')
                ->where('no_kontrabon', $no_kontrabon)
                ->first();

            $detailkontrabon = Detailkontrabonangkutan::select(
                DB::raw("SUM(IF(gudang_jadi_mutasi.tanggal BETWEEN '$start_date' AND '$end_date',(tarif+bs+tepung),0)) as jumlah_angkutan"),
                DB::raw("SUM(IF(gudang_jadi_mutasi.tanggal < '$start_date',(tarif+bs+tepung),0)) as jumlah_hutang"),
            )
                ->join('gudang_jadi_angkutan_suratjalan', 'gudang_jadi_angkutan_kontrabon_detail.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok')
                ->join('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok')
                ->where('no_kontrabon', $no_kontrabon)->first();

            //dd($detailkontrabon);
            if ($detailkontrabon == null) {
                return Redirect::back()->with(messageError('Detail Kontrabon Kosong'));;
            } else {
                $jumlah_angkutan = $detailkontrabon->jumlah_angkutan;
                $jumlah_hutang = $detailkontrabon->jumlah_hutang;
            }

            if (!empty($jumlah_angkutan)) {
                $cek1 = 1;
                Ledger::create([
                    'no_bukti' => $no_bukti,
                    'tanggal' => $request->tanggal,
                    'pelanggan' => $kontrabon->nama_angkutan,
                    'keterangan' => $request->keterangan,
                    'kode_bank' => $request->kode_bank,
                    'kode_akun' => '6-1114',
                    'jumlah' => $jumlah_angkutan,
                    'debet_kredit' => 'D',
                    'kode_peruntukan' => 'MP',
                    'keterangan_peruntukan' => 'PST'
                ]);

                Ledgerkontrabonangkutan::create([
                    'no_bukti' => $no_bukti,
                    'no_kontrabon' => $no_kontrabon
                ]);
            }

            if (!empty($jumlah_hutang)) {
                echo $cek2 = 1;
                $no_bukti_hutang = !empty($jumlah_angkutan) ?  buatkode($no_bukti, 'LRPST' . date('y', strtotime($request->tanggal)), 5) : $no_bukti;
                Ledger::create([
                    'no_bukti' => $no_bukti_hutang,
                    'tanggal' => $request->tanggal,
                    'pelanggan' => $kontrabon->nama_angkutan,
                    'keterangan' => $request->keterangan,
                    'kode_bank' => $request->kode_bank,
                    'kode_akun' => '2-1800',
                    'jumlah' => $jumlah_hutang,
                    'debet_kredit' => 'D',
                    'kode_peruntukan' => 'MP',
                    'keterangan_peruntukan' => 'PST'
                ]);

                Ledgerkontrabonangkutanhutang::create([
                    'no_bukti' => $no_bukti_hutang,
                    'no_kontrabon' => $no_kontrabon
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

    public function cancelproses($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        DB::beginTransaction();
        try {
            $kontrabon = Kontrabonangkutan::where('no_kontrabon', $no_kontrabon)->first();
            // dd($kontrabon->tanggal);
            $cektutuplaporan = cektutupLaporan($kontrabon->tanggal, "ledger");
            // dd($cektutuplaporan);
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            //Cek no Bukti Ledger
            $ledgerkontrabon = Ledgerkontrabonangkutan::where('no_kontrabon', $no_kontrabon)->first();
            $ledgerkontrabonhutang = Ledgerkontrabonangkutanhutang::where('no_kontrabon', $no_kontrabon)->first();
            if ($ledgerkontrabon) {
                Ledger::where('no_bukti', $ledgerkontrabon->no_bukti)->delete();
            }
            if ($ledgerkontrabonhutang) {
                Ledger::where('no_bukti', $ledgerkontrabonhutang->no_bukti)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil di Batalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageSuccess($e->getMessage()));
        }
    }


    public function destroy($no_kontrabon)
    {
        DB::beginTransaction();
        try {
            $no_kontrabon = Crypt::decrypt($no_kontrabon);
            $kontrabonpembelian = Kontrabonangkutan::where('no_kontrabon', $no_kontrabon)->firstOrFail();
            $kontrabonpembelian->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
