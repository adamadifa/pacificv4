<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Detailgiro;
use App\Models\Giro;
use App\Models\Historibayarpenjualan;
use App\Models\Historibayarpenjualangiro;
use App\Models\Ledger;
use App\Models\Ledgergiro;
use App\Models\Ledgersetoranpusat;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\Setoranpusat;
use App\Models\Setoranpusatgiro;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayarangiroController extends Controller
{

    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $gr = new Giro();
        $giro = $gr->getGiro(request: $request);
        $giro = $giro->paginate(15);
        $giro->appends(request()->all());
        $data['giro'] = $giro;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('marketing.pembayarangiro.index', $data);
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
        return view('marketing.pembayarangiro.create', $data);
    }


    public function creategroup()
    {
        return view('marketing.pembayarangiro.creategroup');
    }

    public function store(Request $request, $no_faktur)
    {

        $request->validate([
            'no_giro' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
            'jatuh_tempo' => 'required',

        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $tahun = date('Y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {


            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cekgiro = Giro::where('no_giro', $request->no_giro)->count();
            if ($cekgiro > 0) {
                return Redirect::back()->with(messageError("No. Giro Sudah Ada"));
            }

            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            $lastgiro = Giro::select('kode_giro')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_giro", "desc")
                ->first();

            $last_kode_giro = $lastgiro != null ? $lastgiro->kode_giro : '';
            $kode_giro  = buatkode($last_kode_giro, "GR" . $tahun, 4);
            Giro::create([
                'kode_giro' => $kode_giro,
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'no_giro' => $request->no_giro,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->jatuh_tempo,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);

            Detailgiro::create([
                'kode_giro' => $kode_giro,
                'no_faktur' => $no_faktur,
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
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
            'bank_pengirim' => 'required',
            'no_giro' => 'required',
            'jatuh_tempo' => 'required'
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

            $lastgiro = Giro::select('kode_giro')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_giro", "desc")
                ->first();

            $last_kode_giro = $lastgiro != null ? $lastgiro->kode_giro : '';
            $kode_giro  = buatkode($last_kode_giro, "GR" . $tahun, 4);

            // dd($kode_giro);
            Giro::create([
                'kode_giro' => $kode_giro,
                'kode_pelanggan' => $request->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'no_giro' => $request->no_giro,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->jatuh_tempo,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);




            for ($i = 0; $i < count($no_faktur); $i++) {
                $detail[] = [
                    'kode_giro' => $kode_giro,
                    'no_faktur' => $no_faktur[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }
            Detailgiro::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function edit($no_faktur, $kode_giro)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();

        $data['giro'] = Detailgiro::select(
            'no_giro',
            'marketing_penjualan_giro.tanggal',
            'bank_pengirim',
            'kode_salesman',
            'marketing_penjualan_giro_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_giro_detail.kode_giro', $kode_giro)
            ->first();
        $data['no_faktur'] = $no_faktur;
        $data['kode_giro'] = $kode_giro;
        return view('marketing.pembayarangiro.edit', $data);
    }



    public function update(Request $request, $no_faktur, $kode_giro)
    {

        $request->validate([
            'no_giro' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
            'jatuh_tempo' => 'required',
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        DB::beginTransaction();
        try {

            $giro = Giro::where('kode_giro', $kode_giro)->first();

            $cektutuplaporangiro = cektutupLaporan($giro->tanggal, "penjualan");
            if ($cektutuplaporangiro > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cekgiro = Giro::where('no_giro', $request->no_giro)
                ->where('no_giro', '!=', $giro->no_giro)
                ->count();
            if ($cekgiro > 0) {
                return Redirect::back()->with(messageError("No. Giro Sudah Ada"));
            }

            $ceksetoran = Setoranpusatgiro::where('kode_giro', $kode_giro)->count();
            if ($ceksetoran > 0) {
                return Redirect::back()->with(messageError("No. Giro Sudah Di Setor"));
            }
            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            Giro::where('kode_giro', $kode_giro)->update([
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'no_giro' => $request->no_giro,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->jatuh_tempo,
                'keterangan' => $request->keterangan,
            ]);

            Detailgiro::where('kode_giro', $kode_giro)->where('no_faktur', $no_faktur)->update([
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_faktur, $kode_giro)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        $giro = Giro::where('kode_giro', $kode_giro)->first();
        DB::beginTransaction();
        try {

            $ceksetorangiro = Setoranpusatgiro::where('kode_giro', $kode_giro)->count();
            if ($ceksetorangiro > 0) {
                return Redirect::back()->with(messageError('Data Giro Tidak Bisa Di Hapus Karena Sudah Disetorkan'));
            }


            $cektutuplaporan = cektutupLaporan($giro->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            //Hapus Surat Jalan
            Detailgiro::where('no_faktur', $no_faktur)->where('kode_giro', $kode_giro)->delete();
            $cekdetailgiro = Detailgiro::where('kode_giro', $kode_giro)->count();
            if (empty($cekdetailgiro)) {
                Giro::where('kode_giro', $kode_giro)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_giro)
    {
        $kode_giro = Crypt::decrypt($kode_giro);
        $gr = new Giro();
        $giro = $gr->getGiro(kode_giro: $kode_giro)->first();
        $data['giro'] = $giro;
        $data['detail'] = $gr->getDetailgiro($kode_giro)->get();
        $bnk = new Bank();
        $bank = $bnk->getbankCabang()->get();
        $data['bank'] = $bank;

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('marketing.pembayarangiro.approve', $data);
    }


    public function approvestore($kode_giro, Request $request)
    {
        $kode_giro = Crypt::decrypt($kode_giro);
        $tahun = date('y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $gr = new Giro();
            $giro = $gr->getGiro($kode_giro)->first();
            if (!empty($request->tanggal)) {
                $tanggal_tutup_laporan = $request->tanggal;
            } else {
                if (!empty($giro->tanggal_diterima)) {
                    $tanggal_tutup_laporan = $giro->tanggal_diterima;
                } else if (!empty($giro->tanggal_ditolak)) {
                    $tanggal_tutup_laporan = $giro->tanggal_ditolak;
                }
            }
            $detail = $gr->getDetailgiro($kode_giro)->get();
            $cektutuplaporan = cektutupLaporan($tanggal_tutup_laporan, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            function updatesetoran($kode_giro, $status, $omset_bulan, $omset_tahun, $no_bukti = null, $tanggal = null)
            {
                $setorangiro = Setoranpusatgiro::where('kode_giro', $kode_giro)->first();
                if ($setorangiro != null) {
                    Setoranpusat::where('kode_setoran', $setorangiro->kode_setoran)->update([
                        'status' => $status,
                        'omset_bulan' => $omset_bulan,
                        'omset_tahun' => $omset_tahun
                    ]);

                    if ($status == '1') {
                        //dd($status);
                        //dd($no_bukti);
                        Setoranpusat::where('kode_setoran', $setorangiro->kode_setoran)->update([
                            'tanggal' => $tanggal
                        ]);
                        Ledgersetoranpusat::create([
                            'no_bukti' => $no_bukti,
                            'kode_setoran' => $setorangiro->kode_setoran
                        ]);
                    } else {
                        Ledgersetoranpusat::where('kode_setoran', $setorangiro->kode_setoran)->delete();
                    }
                }
            }

            function prosespending($kode_giro)
            {

                $ledgergiro = Ledgergiro::where('kode_giro', $kode_giro)->first();
                $historibayargiro = Historibayarpenjualangiro::where('kode_giro', $kode_giro)->get();
                $no_bukti_ledger = $ledgergiro != null ? $ledgergiro->no_bukti : '';
                $no_bukti_pembayaran = [];
                foreach ($historibayargiro as $d) {
                    $no_bukti_pembayaran[] = $d->no_bukti;
                }
                if ($ledgergiro != null) {
                    Ledger::where('no_bukti', $no_bukti_ledger)->delete();
                    Historibayarpenjualan::whereIn('no_bukti', $no_bukti_pembayaran)->delete();
                }
                Giro::where('kode_giro', $kode_giro)->update([
                    'status' => 0,
                    'tanggal_ditolak' => NULL,
                    'omset_bulan' => NULL,
                    'omset_tahun' => NULL

                ]);

                updatesetoran($kode_giro, 0, NULL, NULL, NULL, NULL);
            }
            //Jika Diterima
            if ($request->status === '1') {
                // $bank = Bank::where('kode_bank', $request->kode_bank)->first();
                // $kode_akun_bank = $bank->kode_akun;
                prosespending($kode_giro);
                Giro::where('kode_giro', $kode_giro)->update([
                    'status' => 1,
                    'omset_bulan' => $request->omset_bulan,
                    'omset_tahun' => $request->omset_tahun
                ]);

                // updatesetoran($kode_giro, 1, $request->omset_bulan, $request->omset_tahun);
                //Insert Histori Byar
                $totalbayar = 0;
                foreach ($detail as $d) {
                    $lasthistoribayar = Historibayarpenjualan::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,6) = "' . $giro->kode_cabang . $tahun . '-"')
                        ->orderBy("no_bukti", "desc")
                        ->first();

                    $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                    $no_bukti  = buatkode($last_no_bukti, $giro->kode_cabang . $tahun . "-", 6);
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'tanggal' => $request->tanggal,
                        'no_faktur' => $d->no_faktur,
                        'jenis_bayar' => 'TR',
                        'jumlah' => toNumber($d->jumlah),
                        'kode_salesman' => $giro->kode_salesman,
                        'id_user' => auth()->user()->id
                    ]);
                    Historibayarpenjualangiro::create([
                        'no_bukti' => $no_bukti,
                        'kode_giro' => $kode_giro
                    ]);
                    $totalbayar += $d->jumlah;
                    $list_faktur[] = $d->no_faktur;
                }

                $datafaktur = implode(",", $list_faktur);
                //Insert Ledger
                //Generate Ledger

                if ($giro->kode_cabang == 'PST') {
                    $lastledger = Ledger::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,7) ="LR' . $giro->kode_cabang . $tahun . '"')
                        ->whereRaw('LENGTH(no_bukti)=12')
                        ->orderBy('no_bukti', 'desc')
                        ->first();
                    $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                    $no_bukti = buatkode($last_no_bukti, 'LR' . $giro->kode_cabang . $tahun, 5);
                } else {
                    $lastledger = Ledger::select('no_bukti')
                        ->whereRaw('LEFT(no_bukti,7) ="LR' . $giro->kode_cabang . $tahun . '"')
                        ->orderBy('no_bukti', 'desc')
                        ->first();
                    $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                    $no_bukti = buatkode($last_no_bukti, 'LR' . $giro->kode_cabang . $tahun, 4);
                }

                Ledger::create([
                    'no_bukti' => $no_bukti,
                    'tanggal' => $request->tanggal,
                    'pelanggan' => $giro->nama_pelanggan,
                    'kode_bank' => $request->kode_bank,
                    'keterangan' => "INV " . $datafaktur,
                    'kode_akun' => getAkunpiutangcabang($giro->kode_cabang),
                    'jumlah' => $totalbayar,
                    'debet_kredit' => 'K'
                ]);

                Ledgergiro::create([
                    'no_bukti' => $no_bukti,
                    'kode_giro' => $kode_giro
                ]);

                updatesetoran($kode_giro, 1, $request->omset_bulan, $request->omset_tahun, $no_bukti, $request->tanggal);
            } elseif ($request->status == '2') {
                prosespending($kode_giro);
                Giro::where('kode_giro', $kode_giro)->update([
                    'tanggal_ditolak' => $request->tanggal,
                    'status' => 2,
                    'omset_bulan' => date('m', strtotime($giro->jatuh_tempo)),
                    'omset_tahun' => date('Y', strtotime($giro->jatuh_tempo)),
                ]);

                updatesetoran($kode_giro, 2, date('m', strtotime($giro->jatuh_tempo)), date('Y', strtotime($giro->jatuh_tempo)), NULL, $request->tanggal);
            } else if ($request->status === '0') {

                prosespending($kode_giro);
            }


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_giro)
    {
        $kode_giro = Crypt::decrypt($kode_giro);
        $gr = new Giro();
        $giro = $gr->getGiro(kode_giro: $kode_giro)->first();
        $data['giro'] = $giro;
        $data['detail'] = $gr->getDetailgiro($kode_giro)->get();
        return view('marketing.pembayarangiro.show', $data);
    }


    public function destroygiro($kode_giro)
    {

        $kode_giro = Crypt::decrypt($kode_giro);

        DB::beginTransaction();
        try {
            $giro = Giro::where('kode_giro', $kode_giro)->first();
            $ceksetorangiro = Setoranpusatgiro::where('kode_giro', $kode_giro)->count();
            if ($ceksetorangiro > 0) {
                return Redirect::back()->with(messageError('Data Giro Tidak Bisa Di Hapus Karena Sudah Disetorkan'));
            }
            $cektutuplaporan = cektutupLaporan($giro->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Giro::where('kode_giro', $kode_giro)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
