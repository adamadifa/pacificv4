<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Historibayarpjp;
use App\Models\Ledger;
use App\Models\Ledgerpjp;
use App\Models\Pjp;
use App\Models\Pjppotonggaji;
use App\Models\Rencanacicilanpjp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PjpController extends Controller
{
    public function index(Request $request)
    {

        $pj = new Pjp();
        $pjp = $pj->getPjp(request: $request)->paginate(15);
        $pjp->appends(request()->all());
        $data['pjp'] = $pjp;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.pjp.index', $data);
    }


    public function create()
    {

        return view('keuangan.pjp.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'nik' => 'required',
            'jumlah_pinjaman' => 'required',
            'angsuran' => 'required',
            'jumlah_angsuran' => 'required',
            'mulai_cicilan' => 'required'
        ]);

        $karyawancontroller = new KaryawanController();
        $getkaryawan = $karyawancontroller->getkaryawan(Crypt::encrypt($request->nik))->getContent();
        $karyawan = json_decode($getkaryawan, true);
        $datakaryawan = $karyawan['data'];
        $jumlahbulankerja = calculateMonths($datakaryawan['tanggal_masuk'], date('Y-m-d'));
        $sp_pusat = ['SP2', 'SP3'];
        $sp_cabang = ['SP1', 'SP2', 'SP3'];

        $minimal_bayar = 75 / 100 * $datakaryawan['total_pinjaman'];
        $persentase_bayar = !empty($datakaryawan['total_pinjaman']) ?  ROUND($datakaryawan['total_pembayaran'] / $datakaryawan['total_pinjaman'] * 100) : 0;
        if ($datakaryawan['status_karyawan'] == 'T') {
            $tenor_max = 20;
        } else {
            $tenor_max = calculateMonths(date('Y-m-d'), $datakaryawan['akhir_kontrak']);
            $tenor_max = $tenor_max > 0 ? $tenor_max : 0;
        }

        // dd($request->angsuran . "-" . $tenor_max);
        $masakerja = hitungMasakerja($datakaryawan['tanggal_masuk'], date('Y-m-d'));
        //dd($masakerja);
        $jmlkali_jmk = hitungJmk($masakerja['tahun']);
        if ($masakerja['tahun'] < 2) {
            $jmk = $jmlkali_jmk * $datakaryawan['gaji_pokok'];
        } else {
            $jmk = $jmlkali_jmk * $datakaryawan['gapok_tunjangan'];
        }

        $sisa_jmk = $jmk - $datakaryawan['total_jmk_dibayar'];
        $angsuran_max = ROUND(40 / 100 * $datakaryawan['gapok_tunjangan']);
        $plafon = $angsuran_max * $tenor_max;

        $plafon_max = $plafon < $sisa_jmk ? $plafon : $sisa_jmk;

        // dd($angsuran_max);
        $jumlah_pinjaman = toNumber($request->jumlah_pinjaman);
        $jumlah_angsuran = toNumber($request->jumlah_angsuran);

        //dd($masakerja);
        DB::beginTransaction();
        try {
            if ($datakaryawan['status_karyawan'] == 'O') {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan PJP, Karena Status Karyawan Sebagai Karyawan Outsourcing'));
            }


            if ($jumlahbulankerja < 15) {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan PJP, Masa Kerja Karyawan Kurang dari 1,3 Tahun atau 15 Bulan, Masa Kerja Karyawan Saat Ini ' . $jumlahbulankerja . ' Bulan'));
            }

            if ($tenor_max <= 0) {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan PJP, Karena Kontrak Karyawan Habis pada Tanggal ' . DateToIndo($datakaryawan['akhir_kontrak']) . ', Silahkan Hubungi Departemen HRD'));
            }

            if ($datakaryawan['kode_cabang'] == 'PST' && in_array($datakaryawan['jenis_sp'], $sp_pusat) || $datakaryawan['kode_cabang'] != 'PST' && in_array($datakaryawan['jenis_sp'], $sp_cabang)) {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan PJP, Karena Kontrak Karyawan Masih Dalam Masa' . $datakaryawan['jenis_sp'] . ', Yang Berakhir Pada' . $datakaryawan['tanggal_berakhir_sp']));
            }

            if ($datakaryawan['total_pembayaran'] < $minimal_bayar) {
                return Redirect::back()->with(messageError('Tidak Dapat Melakukan Ajuan PJP, Karana Karyawan Masih Memiliki Pinjaman, Untuk Melakukan Ajuan PJP kembali, Karyawan harus sudah membayar 75% dari Pinjaman Sebelumnya, Total Pinjaman Sebesar ' . formatAngka($datakaryawan['total_pinjaman']) . ' dan Total Yang Sudah Dibayarkan Sebesar ' . formatAngka($datakaryawan['total_pembayaran']) . '(' . $persentase_bayar . '%)'));
            }

            if ($jumlah_pinjaman > $plafon_max) {
                return Redirect::back()->with(messageError('Jumlah Pinjaman Melebihi Plafon Maksimal'));
            }

            if ($request->angsuran > $tenor_max) {
                return Redirect::back()->with(messageError('Melebihi Jumlah Cicilan Maksimal'));
            }

            if ($jumlah_angsuran > $angsuran_max) {
                return Redirect::back()->with(messageError('Melebihi Jumlah Maksimal Angsuran'));
            }

            $bulan_cicilan = date("m", strtotime($request->mulai_cicilan));
            $tahun_cicilan = date("Y", strtotime($request->mulai_cicilan));

            // if ($bulan_cicilan == 1) {
            //     $bulan_cicilan = 12;
            //     $tahun_cicilan = $tahun_cicilan - 1;
            // } else {
            //     $bulan_cicilan = $bulan_cicilan - 1;
            //     $tahun_cicilan = $tahun_cicilan;
            // }


            $lastpinjaman = Pjp::select('no_pinjaman')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("no_pinjaman", "desc")
                ->first();

            $last_no_pinjaman = $lastpinjaman != null ? $lastpinjaman->no_pinjaman : '';
            $no_pinjaman  = buatkode($last_no_pinjaman, "PJK" . date('y', strtotime($request->tanggal)), 3);

            $cicilan_terakhir = $jumlah_angsuran + ($jumlah_pinjaman - ($jumlah_angsuran * $request->angsuran));

            $kode_potongan = "GJ" . $bulan_cicilan . $tahun_cicilan;
            $cekpembayaran = Historibayarpjp::where('kode_potongan', $kode_potongan)->count();
            if ($cekpembayaran > 0) {
                return Redirect::back()->with(messageError('Pinjaman Pada Periode ini Sudah Ditutup'));
            }

            Pjp::create([
                'no_pinjaman' => $no_pinjaman,
                'tanggal' => $request->tanggal,
                'nik' => $request->nik,
                'status_karyawan' => $datakaryawan['status_karyawan'],
                'akhir_kontrak' => $datakaryawan['status_karyawan'] == 'T' ? null : $datakaryawan['akhir_kontrak'],
                'gapok_tunjangan' => $datakaryawan['gapok_tunjangan'],
                'tenor_max' => $tenor_max,
                'angsuran_max' => $angsuran_max,
                'jmk' => $jmk,
                'jmk_sudahbayar' => $datakaryawan['total_jmk_dibayar'],
                'plafon_max' => $plafon_max,
                'jumlah_pinjaman' => $jumlah_pinjaman,
                'angsuran' => $request->angsuran,
                'jumlah_angsuran' => $jumlah_angsuran,
                'mulai_cicilan' => $request->mulai_cicilan,
                'id_user' => auth()->user()->id
            ]);

            $thncicilan = $tahun_cicilan;
            for ($i = 1; $i <= $request->angsuran; $i++) {
                if ($bulan_cicilan > 12) {
                    $blncicilan = $bulan_cicilan - 12;
                    $thncicilan = $thncicilan + 1;
                    $bulan_cicilan = 1;
                } else {
                    $blncicilan = $bulan_cicilan;
                    $thncicilan = $thncicilan;
                }

                if ($i == $request->angsuran) {
                    $cicilan = $cicilan_terakhir;
                } else {
                    $cicilan = $jumlah_angsuran;
                }


                Rencanacicilanpjp::create([
                    'no_pinjaman' => $no_pinjaman,
                    'cicilan_ke' => $i,
                    'bulan' => $blncicilan,
                    'tahun' => $thncicilan,
                    'jumlah' => $cicilan
                ]);

                $bulan_cicilan++;
            }

            DB::commit();

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {

            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }


    public function show($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pj = new Pjp();
        $data['pjp'] = $pj->getPjp(no_pinjaman: $no_pinjaman)->first();
        return view('keuangan.pjp.show', $data);
    }

    public function getrencanacicilan(Request $request)
    {
        $data['rencanacicilan'] = Rencanacicilanpjp::where('no_pinjaman', $request->no_pinjaman)->orderBy('cicilan_ke')->get();
        return view('keuangan.pjp.getrencanacicilan', $data);
    }


    public function cetak($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pj = new Pjp();
        $data['pjp'] = $pj->getPjp($no_pinjaman)->first();

        return view('keuangan.pjp.cetak', $data);
    }

    public function approve($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pj = new Pjp();
        $data['pjp'] = $pj->getPjp($no_pinjaman)->first();
        $data['bank'] = Bank::where('kode_cabang', 'PST')->orderBy('kode_bank')->get();
        return view('keuangan.pjp.approve', $data);
    }


    public function approvestore($no_pinjaman, Request $request)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pj = new Pjp();
        $pjp = $pj->getPjp($no_pinjaman)->first();

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
                'pelanggan' => $pjp->nama_karyawan,
                'keterangan' => "Piutang Karyawan " . $pjp->nama_karyawan,
                'kode_akun' => '1-1451',
                'jumlah' => $pjp->jumlah_pinjaman,
                'debet_kredit' => 'D',
            ]);

            Ledgerpjp::create([
                'no_bukti' => $no_bukti,
                'no_pinjaman' => $no_pinjaman
            ]);

            Pjp::where('no_pinjaman', $no_pinjaman)->update(['status' => 1]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancel($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        DB::beginTransaction();
        try {
            $pjp = Pjp::find($no_pinjaman);
            if (!$pjp) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }

            $cektutuplaporan = cektutupLaporan($pjp->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $cekpembayaran = Historibayarpjp::where('no_pinjaman', $no_pinjaman)->count();

            if ($cekpembayaran > 0) {
                return Redirect::back()->with(messageError('Tidak Dapat di Batalkan Karena PJP sudah ada Pembayaran'));
            }

            $ledgerpjp = Ledgerpjp::where('no_pinjaman', $no_pinjaman)->first();
            Ledger::where('no_bukti', $ledgerpjp->no_bukti)->delete();
            $pjp->update(['status' => 0]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data berhasil dibatalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_pinjaman)
    {
        $no_pinjaman = Crypt::decrypt($no_pinjaman);
        $pjp = Pjp::find($no_pinjaman);
        if (!$pjp) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $pjp->delete();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
