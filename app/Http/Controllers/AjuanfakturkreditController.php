<?php

namespace App\Http\Controllers;

use App\Models\Ajuanfakturkredit;
use App\Models\Cabang;
use App\Models\Disposisiajuanfaktur;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AjuanfakturkreditController extends Controller
{
    public function index(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_approve_ajuanfakturkredit = config('global.roles_aprove_ajuanfakturkredit');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $user = User::findorfail(auth()->user()->id);
        if ($user->hasRole($roles_approve_ajuanfakturkredit)) {
            $query = Disposisiajuanfaktur::select(
                'marketing_ajuan_faktur.*',
                'nama_pelanggan',
                'nama_salesman',
                'nama_cabang',
                'pelanggan.limit_pelanggan',
                'roles.name as role',
                'marketing_ajuan_faktur.status',
                'marketing_ajuan_faktur_disposisi.status as status_disposisi',
                'status_ajuan',
                'disposisi.id_pengirim',
            );
            $query->join('marketing_ajuan_faktur', 'marketing_ajuan_faktur_disposisi.no_pengajuan', '=', 'marketing_ajuan_faktur.no_pengajuan');
            $query->join('pelanggan', 'marketing_ajuan_faktur.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_ajuan_faktur_disposisi.no_pengajuan,id_pengirim,id_penerima,catatan,status as status_ajuan
                FROM marketing_ajuan_faktur_disposisi
				WHERE marketing_ajuan_faktur_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_ajuan_faktur_disposisi
                    GROUP BY no_pengajuan)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_ajuan_faktur.no_pengajuan', '=', 'disposisi.no_pengajuan');
                }
            );

            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->where('marketing_ajuan_faktur_disposisi.id_penerima', auth()->user()->id);
            $query->orderBy('marketing_ajuan_faktur.created_at', 'desc');
        } else {
            $query = Pengajuanfaktur::query();
            $query->select(
                'marketing_ajuan_faktur.*',
                'nama_pelanggan',
                'nama_salesman',
                'nama_cabang',
                'pelanggan.limit_pelanggan',
                'disposisi.id_pengirim',
                'roles.name as role',
                'status_ajuan'
            );
            $query->join('pelanggan', 'marketing_ajuan_faktur.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
            $query->join('salesman', 'marketing_ajuan_faktur.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_ajuan_faktur_disposisi.no_pengajuan,id_pengirim,id_penerima,catatan,status as status_ajuan
                FROM marketing_ajuan_faktur_disposisi
				WHERE marketing_ajuan_faktur_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_ajuan_faktur_disposisi
                    GROUP BY no_pengajuan)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_ajuan_faktur.no_pengajuan', '=', 'disposisi.no_pengajuan');
                }
            );
            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->orderBy('marketing_ajuan_faktur.created_at', 'desc');
        }
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('salesman.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->posisi_ajuan)) {
            $query->where('roles.name', $request->posisi_ajuan);
        }

        if ($request->status === '0') {
            $query->where('marketing_ajuan_faktur.status', $request->status);
        } else {
            if (!empty($request->status)) {
                $query->where('marketing_ajuan_faktur.status', $request->status);
            }
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_ajuan_faktur.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_ajuan_faktur.tanggal', [$start_date, $end_date]);
        }


        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        // dd($query->get());
        $ajuanfaktur = $query->paginate(15);
        $ajuanfaktur->appends(request()->all());
        $data['ajuanfaktur'] = $ajuanfaktur;


        $data['roles_approve_ajuanfakturkredit'] = $roles_approve_ajuanfakturkredit;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.ajuanfaktur.index', $data);
    }

    public function create()
    {
        return view('marketing.ajuanfaktur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required',
            'tanggal' => 'required',
            'jumlah_faktur' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();

            //Generate No. Pengajuan
            $lastajuan = Pengajuanfaktur::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $pelanggan->kode_cabang . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();

            $last_no_pengajuan = $lastajuan != null ? $lastajuan->no_pengajuan : '';
            $no_pengajuan = buatkode($last_no_pengajuan, 'PJF' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2), 5);


            if ($pelanggan->limit_pelanggan <= 10000000 && $request->cod == '1' && $request->jumlah_faktur <= 2) {
                Pengajuanfaktur::create([
                    'no_pengajuan' => $no_pengajuan,
                    'tanggal' => $request->tanggal,
                    'kode_pelanggan' => $request->kode_pelanggan,
                    'kode_salesman' => $pelanggan->kode_salesman,
                    'jumlah_faktur' => toNumber($request->jumlah_faktur),
                    'siklus_pembayaran' => isset($request->cod) ? $request->cod : 0,
                    'status' => 1,
                    'keterangan' => $request->keterangan
                ]);
            } else {
                Pengajuanfaktur::create([
                    'no_pengajuan' => $no_pengajuan,
                    'tanggal' => $request->tanggal,
                    'kode_pelanggan' => $request->kode_pelanggan,
                    'kode_salesman' => $pelanggan->kode_salesman,
                    'jumlah_faktur' => toNumber($request->jumlah_faktur),
                    'siklus_pembayaran' => isset($request->cod) ? $request->cod : 0,
                    'status' => 0,
                    'keterangan' => $request->keterangan
                ]);
                //Disposisi

                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiajuanfaktur::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPFK" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


                $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
                $smm = User::role('sales marketing manager')->where('kode_cabang', $pelanggan->kode_cabang)
                    ->where('status', 1)
                    ->first();

                if ($smm != null) {
                    $id_penerima = $smm->id;
                } else {
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


                Disposisiajuanfaktur::create([
                    'kode_disposisi' => $kode_disposisi,
                    'no_pengajuan' => $no_pengajuan,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => $id_penerima,
                    'catatan' => $request->keterangan,
                    'status' => 0
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


    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanfaktur = Pengajuanfaktur::select(
            'marketing_ajuan_faktur.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.alamat_pelanggan',
            'pelanggan.no_hp_pelanggan',
            'salesman.nama_salesman',
            'cabang.nama_cabang',
            'pelanggan.limit_pelanggan'

        )
            ->join('pelanggan', 'marketing_ajuan_faktur.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_ajuan_faktur.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanfaktur'] = $ajuanfaktur;


        $data['disposisi'] = Disposisiajuanfaktur::select('marketing_ajuan_faktur_disposisi.*', 'users.name as username', 'roles.name as role')
            ->join('users', 'marketing_ajuan_faktur_disposisi.id_pengirim', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_faktur_disposisi.created_at')
            ->get();

        $data['lastdisposisi'] = Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)->orderBy('created_at', 'desc')->first();
        return view('marketing.ajuanfaktur.approve', $data);
    }


    public function approvestore($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanfaktur = Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)
            ->join('salesman', 'marketing_ajuan_faktur.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->first();

        DB::beginTransaction();
        try {


            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanfaktur::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPFK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            if (isset($_POST['decline'])) {
                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_faktur_disposisi.status' => 2
                        ]);
                } else {
                    Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 2
                        ]);
                }

                Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 2]);
                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Ditolak'));
            } else {

                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_faktur_disposisi.status' => 1
                        ]);
                } else {
                    Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 1
                        ]);
                }

                if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager' || auth()->user()->roles->pluck('name')[0] == "operation manager") {
                    $rsm = User::role('regional sales manager')
                        ->where('kode_regional', $ajuanfaktur->kode_regional)
                        ->where('status', 1)
                        ->first();
                    if ($rsm != NULL) {
                        $id_penerima = $rsm->id;
                    } else {
                        $gm = User::role('gm marketing')
                            ->where('status', 1)
                            ->first();
                        $id_penerima = $gm->id;
                    }
                    Disposisiajuanfaktur::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'catatan' => $request->catatan,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                } else if (auth()->user()->roles->pluck('name')[0] == 'regional sales manager') {
                    $gm = User::role('gm marketing')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $gm->id;
                    Disposisiajuanfaktur::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'catatan' => $request->catatan,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                } else if (auth()->user()->roles->pluck('name')[0] == 'gm marketing') {
                    $dirut = User::role('direktur')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $dirut->id;
                    Disposisiajuanfaktur::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $id_penerima,
                        'catatan' => $request->catatan,
                        'status' => 0
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                } else if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cancel($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->first();


        DB::beginTransaction();
        try {
            if ($ajuanlimit->status == '2') {
                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_faktur_disposisi.status' => 0
                        ]);
                } else {
                    Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 0
                        ]);
                }


                Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
            } else {
                if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
                }
                if (auth()->user()->roles->pluck('name')[0] != 'operation manager') {
                    if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager') {
                        Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                            ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('no_pengajuan', $no_pengajuan)
                            ->where('roles.name', 'regional sales manager')
                            ->delete();
                    } else {
                        Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)
                            ->where('id_pengirim', auth()->user()->id)
                            ->whereRaw('id_pengirim != id_penerima')
                            ->delete();
                    }

                    Disposisiajuanfaktur::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)
                        ->update(['status' => 0]);
                } else {
                    $disposisi_om = Disposisiajuanfaktur::where('id_pengirim', auth()->user()->id)->where('no_pengajuan', $no_pengajuan);
                    $cek_pengirim_om = $disposisi_om->count();
                    if ($cek_pengirim_om > 1) {
                        $last_pengirim_om = $disposisi_om->orderBy('created_at', 'desc')->first();
                        Disposisiajuanfaktur::where('kode_disposisi', $last_pengirim_om->kode_disposisi)->delete();
                        Disposisiajuanfaktur::leftjoin('users as pengirim', 'marketing_ajuan_faktur_disposisi.id_pengirim', '=', 'pengirim.id')
                            ->leftjoin('model_has_roles', 'pengirim.id', '=', 'model_has_roles.model_id')
                            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('no_pengajuan', $no_pengajuan)
                            ->where('roles.name', 'sales marketing manager')
                            ->delete();
                    }


                    Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_faktur_disposisi.status' => 0
                        ]);

                    Disposisiajuanfaktur::leftjoin('users as penerima', 'marketing_ajuan_faktur_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'regional sales manager')
                        ->delete();
                }
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
