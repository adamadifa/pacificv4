<?php

namespace App\Http\Controllers;

use App\Models\Ajuanlimitkredit;
use App\Models\Cabang;
use App\Models\Disposisiajuanlimitkredit;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AjuanlimitkreditController extends Controller
{
    public function index()
    {
        return view('marketing.ajuanlimit.index');
    }

    public function create()
    {

        return view('marketing.ajuanlimit.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            $last_ajuan_limit = Ajuanlimitkredit::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $pelanggan->kode_cabang . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();

            if ($last_ajuan_limit == null) {
                $last_no_pengajuan = 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2) . '00000';
            } else {
                $last_no_pengajuan = $last_ajuan_limit->no_pengajuan;
            }
            $no_pengajuan = buatkode($last_no_pengajuan, 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2), 5);

            //dd($no_pengajuan);
            $lokasi = explode(",", $request->lokasi);
            //Update Data Pelanggan
            Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->update([
                'nik' => $request->nik,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'alamat_toko' => $request->alamat_toko,
                'latitude' => $lokasi[0],
                'longitude' => $lokasi[1],
                'no_hp_pelanggan' => $request->no_hp_pelanggan,
                'hari'  => $request->hari,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'cara_pembayaran' => $request->cara_pembayaran,
                'kepemilikan' => $request->kepemilikan,
                'lama_langganan' => $request->lama_langganan,
                'lama_berjualan' => $request->lama_usaha,
                'jaminan' => $request->jaminan,
                'omset_toko' => toNumber($request->omset_toko)
            ]);

            //Insert Pengajuan
            Ajuanlimitkredit::create([
                'no_pengajuan' => $no_pengajuan,
                'tanggal' => $request->tanggal,
                'kode_pelanggan' => $request->kode_pelanggan,
                'limit_sebelumnya' => $pelanggan->limit_pelanggan,
                'omset_sebelumnya' => $pelanggan->omset_toko,
                'jumlah'  => toNumber($request->jumlah),
                'jumlah_rekomendasi'  => toNumber($request->jumlah),
                'ljt' => $request->ljt,
                'ljt_rekomendasi' => $request->ljt,
                'topup_terakhir' => $request->topup_terakhir,
                'lama_topup' => 1,
                'jml_faktur' => $request->jml_faktur,
                'histori_transaksi' => $request->histori_transaksi,
                'status' => 0,
                'skor' => $request->skor,
                'id_user' => auth()->user()->id
            ]);


            //Disposisi

            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


            $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
            $smm = User::role('sales marketing manager')->where('kode_cabang', $pelanggan->kode_cabang)
                ->where('status', 1)
                ->first();
            $id_penerima = $smm->id;
            if ($smm == NULL) {
                $rsm = User::role('regional sales manager')->where('kode_regional', $regional->kode_regional)
                    ->where('status', 1)
                    ->first();
                $id_penerima = $rsm->id;
                if ($rsm == NULL) {
                    $gm = User::role('gm marketing')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $gm->id;
                }
            }


            Disposisiajuanlimitkredit::create([
                'kode_disposisi' => $kode_disposisi,
                'no_pengajuan' => $no_pengajuan,
                'id_pengirim' => auth()->user()->id,
                'id_penerima' => $id_penerima,
                'uraian_analisa' => 0,
                'status' => 0
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
    //AJAX REQUEST
    public function gettopupTerakhir(Request $request)
    {
        $tgl1 = new DateTime($request->tanggal);
        $tgl2 = new DateTime(date('Y-m-d'));
        $lama_topup = $tgl2->diff($tgl1)->days + 1;

        // tahun
        $y = $tgl2->diff($tgl1)->y;

        // bulan
        $m = $tgl2->diff($tgl1)->m;

        // hari
        $d = $tgl2->diff($tgl1)->d;

        $usia_topup = $y . " tahun " . $m . " bulan " . $d . " hari";

        $data = [
            'lama_topup' => $lama_topup,
            'usia_topup' => $usia_topup
        ];
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $data
        ]);
    }
}
