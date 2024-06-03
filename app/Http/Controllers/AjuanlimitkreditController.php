<?php

namespace App\Http\Controllers;

use App\Models\Ajuanlimitkredit;
use App\Models\Cabang;
use App\Models\Disposisiajuanlimitkredit;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AjuanlimitkreditController extends Controller
{
    public function index()
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_approve_ajuanlimitkredit = config('global.roles_aprove_ajuanlimitkredit');
        $user = User::findorfail(auth()->user()->id);
        if ($user->hasRole($roles_approve_ajuanlimitkredit)) {

            $query = Disposisiajuanlimitkredit::select(
                'marketing_ajuan_limitkredit.*',
                'nama_pelanggan',
                'nama_salesman',
                'nama_cabang',
                'roles.name as role',
                'marketing_ajuan_limitkredit.status',
                'marketing_ajuan_limitkredit_disposisi.status as status_disposisi',
                'status_ajuan'
            );
            $query->where('marketing_ajuan_limitkredit_disposisi.id_penerima', auth()->user()->id);
            $query->join('marketing_ajuan_limitkredit', 'marketing_ajuan_limitkredit_disposisi.no_pengajuan', '=', 'marketing_ajuan_limitkredit.no_pengajuan');
            $query->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_ajuan_limitkredit_disposisi.no_pengajuan,id_pengirim,id_penerima,uraian_analisa,status as status_ajuan
                FROM marketing_ajuan_limitkredit_disposisi
				WHERE marketing_ajuan_limitkredit_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_ajuan_limitkredit_disposisi
                    GROUP BY no_pengajuan)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_ajuan_limitkredit.no_pengajuan', '=', 'disposisi.no_pengajuan');
                }
            );
            $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
        } else {
            $query = Ajuanlimitkredit::query();
            $query->select(
                'marketing_ajuan_limitkredit.*',
                'nama_pelanggan',
                'nama_salesman',
                'nama_cabang',
                'roles.name as role',
                'disposisi.id_pengirim'
            );
            $query->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_ajuan_limitkredit_disposisi.no_pengajuan,id_pengirim,id_penerima,uraian_analisa,status
                FROM marketing_ajuan_limitkredit_disposisi
				WHERE marketing_ajuan_limitkredit_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_ajuan_limitkredit_disposisi
                    GROUP BY no_pengajuan)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_ajuan_limitkredit.no_pengajuan', '=', 'disposisi.no_pengajuan');
                }
            );
            $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->orderBy('marketing_ajuan_limitkredit.tanggal', 'desc');
        }

        $ajuanlimit = $query->cursorPaginate(15);
        $ajuanlimit->appends(request()->all());
        $data['ajuanlimit'] = $ajuanlimit;

        $data['roles_approve_ajuanlimitkredit'] = $roles_approve_ajuanlimitkredit;
        return view('marketing.ajuanlimit.index', $data);
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
                'lama_berjualan' => $request->lama_berjualan,
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
                'uraian_analisa' => $request->uraian_analisa,
                'status' => 0
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanlimit'] = $ajuanlimit;
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');

        $data['disposisi'] = Disposisiajuanlimitkredit::join('users', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'users.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_limitkredit_disposisi.created_at')
            ->get();
        return view('marketing.ajuanlimit.approve', $data);
    }

    public function approvestore($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->first();

        DB::beginTransaction();
        try {

            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                ->where('id_penerima', auth()->user()->id)->update([
                    'status' => 1
                ]);

            if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager') {
                if ($ajuanlimit->jumlah <= 5000000) {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                } else {
                    $rsm = User::role('regional sales manager')
                        ->where('status', 1)
                        ->first();
                    if ($rsm != NULL) {
                        $id_penerima = $rsm->id;
                    } else {
                        $gm = User::role('general marketing')
                            ->where('status', 1)
                            ->first();
                        $id_penerima = $gm->id;
                    }
                    Disposisiajuanlimitkredit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'uraian_analisa' => $request->uraian_analisa,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                }
            } else if (auth()->user()->roles->pluck('name')[0] == 'regional sales manager') {
                if ($ajuanlimit->jumlah <= 10000000) {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                } else {
                    $gm = User::role('general marketing')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $gm->id;
                    Disposisiajuanlimitkredit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'uraian_analisa' => $request->uraian_analisa,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                }
            } else if (auth()->user()->roles->pluck('name')[0] == 'gm marketing') {
                if ($ajuanlimit->jumlah <= 15000000) {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                } else {
                    $dirut = User::role('direktur')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $dirut->id;
                    Disposisiajuanlimitkredit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                }
            } else if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
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
