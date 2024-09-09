<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Detailtransfer;
use App\Models\Historibayarpenjualan;
use App\Models\Historibayarpenjualantransfer;
use App\Models\Ledger;
use App\Models\Ledgersetoranpusat;
use App\Models\Ledgertransfer;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\Setoranpusat;
use App\Models\Setoranpusattransfer;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayarantransferController extends Controller
{

    public function index(Request $request)
    {

        // $user = User::findorfail(auth()->user()->id);
        // $roles_access_all_cabang = config('global.roles_access_all_cabang');
        // dd($roles_access_all_cabang);
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $trf = new Transfer();
        $transfer = $trf->getTransfer(request: $request);
        $transfer = $transfer->paginate(15);
        $transfer->appends(request()->all());
        $data['transfer'] = $transfer;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('marketing.pembayarantransfer.index', $data);
    }
    public function create($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();
        $data['no_faktur'] = $no_faktur;
        return view('marketing.pembayarantransfer.create', $data);
    }

    public function creategroup()
    {
        return view('marketing.pembayarantransfer.creategroup');
    }


    public function store(Request $request, $no_faktur)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $tahun = date('Y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            $lastransfer = Transfer::select('kode_transfer')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_transfer", "desc")
                ->first();

            $last_kode_transfer = $lastransfer != null ? $lastransfer->kode_transfer : '';
            $kode_transfer  = buatkode($last_kode_transfer, "TR" . $tahun, 4);
            Transfer::create([
                'kode_transfer' => $kode_transfer,
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);

            Detailtransfer::create([
                'kode_transfer' => $kode_transfer,
                'no_faktur' => $no_faktur,
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function storegroup(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required',
            'tanggal' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required'
        ]);
        $no_faktur = $request->no_faktur;
        $jumlah = $request->jml;
        $tahun = date('Y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if (empty($no_faktur)) {
                return Redirect::back()->with(messageError('Detail Faktur Masih Kosong'));
            }

            $lastransfer = Transfer::select('kode_transfer')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_transfer", "desc")
                ->first();

            $last_kode_transfer = $lastransfer != null ? $lastransfer->kode_transfer : '';
            $kode_transfer  = buatkode($last_kode_transfer, "TR" . $tahun, 4);

            Transfer::create([
                'kode_transfer' => $kode_transfer,
                'kode_pelanggan' => $request->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);

            for ($i = 0; $i < count($no_faktur); $i++) {
                $detail[] = [
                    'kode_transfer' => $kode_transfer,
                    'no_faktur' => $no_faktur[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }

            Detailtransfer::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function edit($no_faktur, $kode_transfer)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer_detail.kode_transfer',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'kode_salesman',
            'marketing_penjualan_transfer_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->where('marketing_penjualan_transfer_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_transfer_detail.kode_transfer', $kode_transfer)
            ->first();
        $data['no_faktur'] = $no_faktur;
        $data['kode_transfer'] = $kode_transfer;
        return view('marketing.pembayarantransfer.edit', $data);
    }

    public function update(Request $request, $no_faktur, $kode_transfer)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        DB::beginTransaction();
        try {

            $transfer = Transfer::where('kode_transfer', $kode_transfer)->first();
            $cektutuplaporantransfer = cektutupLaporan($transfer->tanggal, "penjualan");


            $ceksetorantransfer = Setoranpusattransfer::where('kode_transfer', $kode_transfer)->count();
            if ($ceksetorantransfer > 0) {
                return Redirect::back()->with(messageError('Data Transfer Tidak Bisa Di Ubah Karena Sudah Disetorkan'));
            }


            if ($cektutuplaporantransfer > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            Transfer::where('kode_transfer', $kode_transfer)->update([
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            Detailtransfer::where('kode_transfer', $kode_transfer)->where('no_faktur', $no_faktur)->update([
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_faktur, $kode_transfer)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $transfer = Transfer::where('kode_transfer', $kode_transfer)->first();
        DB::beginTransaction();
        try {


            $ceksetorantransfer = Setoranpusattransfer::where('kode_transfer', $kode_transfer)->count();
            if ($ceksetorantransfer > 0) {
                return Redirect::back()->with(messageError('Data Transfer Tidak Bisa Di Ubah Karena Sudah Disetorkan'));
            }

            $cektutuplaporan = cektutupLaporan($transfer->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Detailtransfer::where('no_faktur', $no_faktur)->where('kode_transfer', $kode_transfer)->delete();
            $cekdetailtransfer = Detailtransfer::where('kode_transfer', $kode_transfer)->count();
            if (empty($cekdetailtransfer)) {
                Transfer::where('kode_transfer', $kode_transfer)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_transfer)
    {
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $trf = new Transfer();
        $transfer = $trf->getTransfer(kode_transfer: $kode_transfer)->first();
        $data['transfer'] = $transfer;
        $data['detail'] = $trf->getDetailtransfer($kode_transfer)->get();
        return view('marketing.pembayarantransfer.show', $data);
    }


    public function approve($kode_transfer)
    {
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $trf = new Transfer();
        $transfer = $trf->getTransfer(kode_transfer: $kode_transfer)->first();
        $data['transfer'] = $transfer;
        $data['detail'] = $trf->getDetailtransfer($kode_transfer)->get();
        $bnk = new Bank();
        $bank = $bnk->getbankCabang()->get();
        $data['bank'] = $bank;

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('marketing.pembayarantransfer.approve', $data);
    }

    public function approvestore($kode_transfer, Request $request)
    {
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $tahun = date('y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $trf = new Transfer();
            $transfer = $trf->getTransfer($kode_transfer)->first();
            if (!empty($request->tanggal)) {
                $tanggal_tutup_laporan = $request->tanggal;
            } else {
                if (!empty($transfer->tanggal_diterima)) {
                    $tanggal_tutup_laporan = $transfer->tanggal_diterima;
                } else if (!empty($transfer->tanggal_ditolak)) {
                    $tanggal_tutup_laporan = $transfer->tanggal_ditolak;
                }
            }
            $detail = $trf->getDetailtransfer($kode_transfer)->get();
            $cektutuplaporan = cektutupLaporan($tanggal_tutup_laporan, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            function updatesetoran($kode_transfer, $status, $omset_bulan, $omset_tahun, $no_bukti = null)
            {
                $setorantransfer = Setoranpusattransfer::where('kode_transfer', $kode_transfer)->first();
                if ($setorantransfer != null) {
                    Setoranpusat::where('kode_setoran', $setorantransfer->kode_setoran)->update([
                        'status' => $status,
                        'omset_bulan' => $omset_bulan,
                        'omset_tahun' => $omset_tahun
                    ]);

                    if ($status == '1') {
                        //dd($no_bukti);
                        Ledgersetoranpusat::create([
                            'no_bukti' => $no_bukti,
                            'kode_setoran' => $setorantransfer->kode_setoran
                        ]);
                    } else {
                        Ledgersetoranpusat::where('kode_setoran', $setorantransfer->kode_setoran)->delete();
                    }
                }
            }
            function prosespending($kode_transfer)
            {
                $ledgertransfer = Ledgertransfer::where('kode_transfer', $kode_transfer)->first();
                $historibayartransfer = Historibayarpenjualantransfer::where('kode_transfer', $kode_transfer)->get();
                $no_bukti_ledger = $ledgertransfer != null ? $ledgertransfer->no_bukti : '';

                $no_bukti_pembayaran = [];
                foreach ($historibayartransfer as $d) {
                    $no_bukti_pembayaran[] = $d->no_bukti;
                }


                if ($ledgertransfer != null) {
                    Ledger::where('no_bukti', $no_bukti_ledger)->delete();
                    Historibayarpenjualan::whereIn('no_bukti', $no_bukti_pembayaran)->delete();
                }

                Transfer::where('kode_transfer', $kode_transfer)->update([
                    'status' => 0,
                    'tanggal_ditolak' => NULL,
                    'omset_bulan' => NULL,
                    'omset_tahun' => NULL
                ]);

                updatesetoran($kode_transfer, 0, NULL, NULL);
            }



            //Jika Diterima
            if ($request->status === '1') {
                // $bank = Bank::where('kode_bank', $request->kode_bank)->first();
                // $kode_akun_bank = $bank->kode_akun;
                prosespending($kode_transfer);
                Transfer::where('kode_transfer', $kode_transfer)->update([
                    'status' => 1,
                    'omset_bulan' => $request->omset_bulan,
                    'omset_tahun' => $request->omset_tahun
                ]);



                //Insert Histori Byar
                $totalbayar = 0;
                foreach ($detail as $d) {
                    $lasthistoribayar = Historibayarpenjualan::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,6) = "' . $transfer->kode_cabang . $tahun . '-"')
                        ->orderBy("no_bukti", "desc")
                        ->first();

                    $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                    $no_bukti  = buatkode($last_no_bukti, $transfer->kode_cabang . $tahun . "-", 6);
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'tanggal' => $request->tanggal,
                        'no_faktur' => $d->no_faktur,
                        'jenis_bayar' => 'TR',
                        'jumlah' => toNumber($d->jumlah),
                        'kode_salesman' => $transfer->kode_salesman,
                        'id_user' => auth()->user()->id
                    ]);
                    Historibayarpenjualantransfer::create([
                        'no_bukti' => $no_bukti,
                        'kode_transfer' => $kode_transfer
                    ]);
                    $totalbayar += $d->jumlah;
                    $list_faktur[] = $d->no_faktur;
                }

                $datafaktur = implode(",", $list_faktur);
                //Insert Ledger
                //Generate Ledger

                if ($transfer->kode_cabang == 'PST') {
                    $lastledger = Ledger::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,7) ="LR' . $transfer->kode_cabang . $tahun . '"')
                        ->whereRaw('LENGTH(no_bukti)=12')
                        ->orderBy('no_bukti', 'desc')
                        ->first();
                    $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                    $no_bukti = buatkode($last_no_bukti, 'LR' . $transfer->kode_cabang . $tahun, 5);
                } else {
                    $lastledger = Ledger::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,7) ="LR' . $transfer->kode_cabang . $tahun . '"')
                        ->orderBy('no_bukti', 'desc')
                        ->first();
                    $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                    $no_bukti = buatkode($last_no_bukti, 'LR' . $transfer->kode_cabang . $tahun, 4);
                }

                Ledger::create([
                    'no_bukti' => $no_bukti,
                    'tanggal' => $request->tanggal,
                    'pelanggan' => $transfer->nama_pelanggan,
                    'kode_bank' => $request->kode_bank,
                    'keterangan' => "INV " . $datafaktur,
                    'kode_akun' => getAkunpiutangcabang($transfer->kode_cabang),
                    'jumlah' => $totalbayar,
                    'debet_kredit' => 'K'
                ]);

                Ledgertransfer::create([
                    'no_bukti' => $no_bukti,
                    'kode_transfer' => $kode_transfer
                ]);
                updatesetoran($kode_transfer, 1, $request->omset_bulan, $request->omset_tahun, $no_bukti);
            } elseif ($request->status == '2') {
                prosespending($kode_transfer);
                Transfer::where('kode_transfer', $kode_transfer)->update([
                    'tanggal_ditolak' => $request->tanggal,
                    'status' => 2,
                    'omset_bulan' => date('m', strtotime($transfer->jatuh_tempo)),
                    'omset_tahun' => date('Y', strtotime($transfer->jatuh_tempo)),
                ]);
                updatesetoran($kode_transfer, 2, date('m', strtotime($transfer->jatuh_tempo)), date('Y', strtotime($transfer->jatuh_tempo)));
            } else if ($request->status === '0') {

                prosespending($kode_transfer);
            }


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            // dd($e);
        }
    }

    public function destroytransfer($kode_transfer)
    {

        $kode_transfer = Crypt::decrypt($kode_transfer);
        DB::beginTransaction();
        try {
            $transfer = Transfer::where('kode_transfer', $kode_transfer)->first();
            $ceksetorantransfer = Setoranpusattransfer::where('kode_transfer', $kode_transfer)->count();

            if ($ceksetorantransfer > 0) {
                return Redirect::back()->with(messageError('Data Transfer Tidak Bisa Di Hapus Karena Sudah Disetorkan'));
            }
            $cektutuplaporan = cektutupLaporan($transfer->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Transfer::where('kode_transfer', $kode_transfer)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
