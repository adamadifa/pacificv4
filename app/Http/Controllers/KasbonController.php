<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Historibayarkasbon;
use App\Models\Kasbon;
use App\Models\Ledger;
use App\Models\Ledgerkasbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KasbonController extends Controller
{
    public function index(Request $request)
    {

        $kb = new Kasbon();
        $kasbon = $kb->getKasbon(request: $request)->paginate(15);
        $kasbon->appends(request()->all());
        $data['kasbon'] = $kasbon;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kasbon.index', $data);
    }


    public function create()
    {
        return view('keuangan.kasbon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
            'mulai_cicilan' => 'required'
        ]);

        $karyawancontroller = new KaryawanController();
        $getkaryawan = $karyawancontroller->getkaryawan(Crypt::encrypt($request->nik))->getContent();
        $karyawan = json_decode($getkaryawan, true);
        $datakaryawan = $karyawan['data'];
        $jumlahbulankerja = calculateMonths($datakaryawan['tanggal_masuk'], date('Y-m-d'));
        $jumlahkasbon = toNumber($request->jumlah);
        if ($jumlahbulankerja < 9) {
            $max_kasbon = 200000;
        } else if ($jumlahbulankerja <= 15) {
            $max_kasbon = 400000;
        } else {
            $max_kasbon = 600000;
        }

        if ($datakaryawan['kasbon_max'] !== 0) {
            if ($datakaryawan['kasbon_max'] > $max_kasbon) {
                $max_kasbon = $max_kasbon;
            } else {
                $max_kasbon = $datakaryawan['kasbon_max'];
            }
        }

        $bulan_cicilan = date("m", strtotime($request->mulai_cicilan));
        $tahun_cicilan = date("Y", strtotime($request->mulai_cicilan));

        if ($bulan_cicilan == 1) {
            $bulan_cicilan = 12;
            $tahun_cicilan = $tahun_cicilan - 1;
        } else {
            $bulan_cicilan = $bulan_cicilan - 1;
            $tahun_cicilan = $tahun_cicilan;
        }


        DB::beginTransaction();
        try {
            //Ccek jika Masih Memiliki Piutang Kasbon
            if ($datakaryawan['cekkasbon'] > 0) {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan Kasbon, Karena Masih Memiliki Ajuan Kasbon Yang Belum Lunas'));
            }

            //Cek Jika Jumlah Kasbon Melebihi Batas Maksimal

            if ($jumlahkasbon > $max_kasbon) {
                return Redirect::back()->with(messageError('Jumlah Ajuan Kasbon Melebihi Batas Maksimal'));
            }


            $kode_potongan = "GJ" . $bulan_cicilan . $tahun_cicilan;
            $cekpembayaran = Historibayarkasbon::where('kode_potongan', $kode_potongan)->count();
            if ($cekpembayaran > 0) {
                return Redirect::back()->with(messageError('Ajuan Kasbon Pada Periode ini Sudah Ditutup'));
            }


            $lastkasbon = Kasbon::select('no_kasbon')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->whereRaw('LENGTH(no_kasbon)=9')
                ->orderBy("no_kasbon", "desc")
                ->first();
            $last_nokasbon = $lastkasbon != null ? $lastkasbon->no_kasbon : '';
            $no_kasbon  = buatkode($last_nokasbon, "KB" . date('y', strtotime($request->tanggal)) . date('m', strtotime($request->tanggal)), 3);

            Kasbon::create([
                'no_kasbon' => $no_kasbon,
                'tanggal' => $request->tanggal,
                'nik' => $request->nik,
                'status_karyawan' => $datakaryawan['status_karyawan'],
                'akhir_kontrak' => $datakaryawan['akhir_kontrak'],
                'kode_jabatan' => $datakaryawan['kode_jabatan'],
                'jumlah' => toNumber($request->jumlah),
                'jatuh_tempo' => $request->mulai_cicilan,
                'id_user' => auth()->user()->id
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($no_kasbon)
    {
        $no_kasbon = Crypt::decrypt($no_kasbon);
        $kb = new Kasbon();
        $data['kasbon'] = $kb->getKasbon($no_kasbon)->first();
        $data['bank'] = Bank::where('kode_cabang', 'PST')->orderBy('kode_bank')->get();
        return view('keuangan.kasbon.approve', $data);
    }

    public function approvestore($no_kasbon, Request $request)
    {
        $no_kasbon = Crypt::decrypt($no_kasbon);
        $kb = new Kasbon();
        $kasbon = $kb->getKasbon($no_kasbon)->first();

        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LENGTH(no_bukti)=12')
                ->whereRaw('LEFT(no_bukti,7) ="LRPST' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ? $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LRPST'  . date('y', strtotime($request->tanggal)), 5);

            Ledger::create([
                'no_bukti' => $no_bukti,
                'kode_bank' => $request->kode_bank,
                'tanggal' => $request->tanggal,
                'pelanggan' => $kasbon->nama_karyawan,
                'keterangan' => "Piutang Karyawan " . $kasbon->nama_karyawan,
                'kode_akun' => '1-1451',
                'jumlah' => $kasbon->jumlah,
                'debet_kredit' => 'D',
            ]);

            Ledgerkasbon::create([
                'no_bukti' => $no_bukti,
                'no_kasbon' => $no_kasbon
            ]);

            Kasbon::where('no_kasbon', $no_kasbon)->update(['status' => 1]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancel($no_kasbon)
    {
        $no_kasbon = Crypt::decrypt($no_kasbon);
        DB::beginTransaction();
        try {
            $kasbon = Kasbon::find($no_kasbon);
            if (!$kasbon) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }

            $cektutuplaporan = cektutupLaporan($kasbon->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $cekpembayaran = Historibayarkasbon::where('no_kasbon', $no_kasbon)->count();

            if ($cekpembayaran > 0) {
                return Redirect::back()->with(messageError('Tidak Dapat di Batalkan Karena PJP sudah ada Pembayaran'));
            }

            $ledgerkasbon = Ledgerkasbon::where('no_kasbon', $no_kasbon)->first();
            Ledger::where('no_bukti', $ledgerkasbon->no_bukti)->delete();
            $kasbon->update(['status' => 0]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data berhasil dibatalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_kasbon)
    {
        $no_kasbon = Crypt::decrypt($no_kasbon);
        $kasbon = Kasbon::find($no_kasbon);
        if (!$kasbon) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $kasbon->delete();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak($no_kasbon)
    {
        $no_kasbon = Crypt::decrypt($no_kasbon);
        $kb = new Kasbon();
        $data['kasbon'] = $kb->getKasbon($no_kasbon)->first();

        return view('keuangan.kasbon.cetak', $data);
    }
}
