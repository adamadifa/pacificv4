<?php

namespace App\Http\Controllers;

use App\Models\Historibayarpjp;
use App\Models\Pjp;
use App\Models\Pjppotonggaji;
use App\Models\Rencanacicilanpjp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PembayaranpjpController extends Controller
{

    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $query = Pjppotonggaji::query();
        $query->select('keuangan_pjp_potonggaji.kode_potongan', 'bulan', 'tahun', 'totalpembayaran');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!$user->hasRole($roles_access_all_pjp)) {
            $whereKategorijabatan = "WHERE hrd_jabatan.kategori != 'MJ'";
        } else {
            $whereKategorijabatan = "";
        }

        $query->leftJoin(
            DB::raw("(
            SELECT kode_potongan,SUM(jumlah) as totalpembayaran
            FROM keuangan_pjp_historibayar
            INNER JOIN keuangan_pjp ON keuangan_pjp_historibayar.no_pinjaman = keuangan_pjp.no_pinjaman
            INNER JOIN hrd_karyawan ON keuangan_pjp.nik = hrd_karyawan.nik
            INNER JOIN hrd_jabatan ON hrd_karyawan.kode_jabatan = hrd_jabatan.kode_jabatan
            $whereKategorijabatan
            GROUP BY kode_potongan
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_pjp_potonggaji.kode_potongan', '=', 'historibayar.kode_potongan');
            }
        );
        $query->orderBy('tahun');
        $query->orderBy('bulan');
        $data['historibayar'] = $query->get();
        return view('keuangan.pembayaranpjp.index', $data);
    }
    public function create($no_pinjaman)
    {
        $data['no_pinjaman'] = Crypt::decrypt($no_pinjaman);
        return view('keuangan.pembayaranpjp.create', $data);
    }

    public function store(Request $request)
    {
        $no_pinjaman = $request->no_pinjaman;
        $tanggal = $request->tanggal;
        $jumlah = toNumber($request->jumlah);
        $id_user = auth()->user()->id;





        DB::beginTransaction();
        try {
            $pj = new Pjp();
            $pjp = $pj->getPjp(no_pinjaman: $no_pinjaman)->first();
            $sisa_tagihan = $pjp->jumlah_pinjaman - $pjp->totalpembayaran;
            if ($jumlah > $sisa_tagihan) {
                return 2;
            }
            //Generate No. Bukti
            $lasthistoribayar = Historibayarpjp::select('no_bukti')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($tanggal)) . '"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_nobukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
            $no_bukti  = buatkode($last_nobukti, "PJ" . date('y', strtotime($tanggal)), 4);




            $rencana = Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                ->whereRaw('jumlah != bayar')
                ->orderBy('cicilan_ke', 'asc')
                ->get();

            $mulaicicilan = Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                ->whereRaw('jumlah != bayar')
                ->orderBy('cicilan_ke', 'asc')
                ->first();
            $sisa = $jumlah;
            $cicilan = "";
            $i = $mulaicicilan->cicilan_ke;
            foreach ($rencana as $d) {
                if ($sisa >= $d->jumlah) {
                    Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                        ->where('cicilan_ke', $i)
                        ->update([
                            'bayar' => $d->jumlah
                        ]);
                    //$cicilan .=  $d->cicilan_ke . ",";
                    $sisapercicilan = $d->jumlah - $d->bayar;
                    $sisa = $sisa - $sisapercicilan;

                    if ($sisa == 0) {
                        $cicilan .=  $d->cicilan_ke;
                    } else {
                        $cicilan .=  $d->cicilan_ke . ",";
                    }

                    $coba = $cicilan;
                } else {
                    if ($sisa != 0) {
                        $sisapercicilan = $d->jumlah - $d->bayar;
                        if ($d->bayar != 0) {
                            if ($sisa >= $sisapercicilan) {
                                Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                    ->where('cicilan_ke', $i)
                                    ->update([
                                        'bayar' =>  DB::raw('bayar +' . $sisapercicilan)
                                    ]);
                                $cicilan .= $d->cicilan_ke . ",";
                                $sisa = $sisa - $sisapercicilan;
                            } else {
                                Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                    ->where('cicilan_ke', $i)
                                    ->update([
                                        'bayar' =>  DB::raw('bayar +' . $sisa)
                                    ]);
                                //$cicilan .= $d->cicilan_ke . ",";
                                $sisa = $sisa - $sisa;
                                if ($sisa == 0) {
                                    $cicilan .=  $d->cicilan_ke;
                                } else {
                                    $cicilan .=  $d->cicilan_ke . ",";
                                }
                            }
                        } else {
                            Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                ->where('cicilan_ke', $i)
                                ->update([
                                    'bayar' =>  DB::raw('bayar +' . $sisa)
                                ]);
                            //$cicilan .= $d->cicilan_ke;
                            $sisa = $sisa - $sisa;
                            if ($sisa == 0) {
                                $cicilan .=  $d->cicilan_ke;
                            } else {
                                $cicilan .=  $d->cicilan_ke . ",";
                            }
                        }
                    }
                }
                $i++;
            }

            $data = [
                'no_bukti' => $no_bukti,
                'tanggal' => $tanggal,
                'no_pinjaman' => $no_pinjaman,
                'jumlah' => $jumlah,
                'cicilan_ke' => $cicilan,
                'id_user' => $id_user
            ];
            Historibayarpjp::create($data);
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            return 1;
            dd($e);
        }
    }



    function destroy(Request $request)
    {
        $no_bukti = Crypt::decrypt($request->no_bukti);
        $trans = Historibayarpjp::where('no_bukti', $no_bukti)->first();
        $cicilan_ke = array_map('intval', explode(',', $trans->cicilan_ke));
        $rencana = Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
            ->whereIn('cicilan_ke', $cicilan_ke)
            ->orderBy('cicilan_ke', 'desc')
            ->get();
        //dd($rencana);
        $mulaicicilan = Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
            ->whereIn('cicilan_ke', $cicilan_ke)
            ->orderBy('cicilan_ke', 'desc')
            ->first();
        //dd($mulaicicilan);
        DB::beginTransaction();
        try {
            $sisa = $trans->jumlah;
            $i = $mulaicicilan->cicilan_ke;
            foreach ($rencana as $d) {
                if ($sisa >= $d->bayar) {
                    Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
                        ->where('cicilan_ke', $i)
                        ->update([
                            'bayar' => DB::raw('bayar -' . $d->bayar)
                        ]);
                    $sisa = $sisa - $d->bayar;
                } else {
                    if ($sisa != 0) {
                        Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
                            ->where('cicilan_ke', $i)
                            ->update([
                                'bayar' =>  DB::raw('bayar -' . $sisa)
                            ]);
                        $sisa = $sisa - $sisa;
                    }
                }

                $i--;
            }
            Historibayarpjp::where('no_bukti', $no_bukti)
                ->delete();


            DB::commit();

            echo 0;
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e);
            echo 1;
        }
    }

    public function gethistoribayar(Request $request)
    {
        $data['historibayar'] = Historibayarpjp::where('no_pinjaman', $request->no_pinjaman)->orderBy('tanggal')->get();
        return view('keuangan.pembayaranpjp.gethistoribayar', $data);
    }
}
