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
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_approve_ajuanlimitkredit = config('global.roles_aprove_ajuanlimitkredit');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        //$roles_maker_ajuanlimitkredit = config('global.roles_maker_ajuanlimitkredit');
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
                'status_ajuan',
                'disposisi.id_pengirim'
            );
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

            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->where('marketing_ajuan_limitkredit_disposisi.id_penerima', auth()->user()->id);
            $query->orderBy('marketing_ajuan_limitkredit.created_at', 'desc');
        } else {
            $query = Ajuanlimitkredit::query();
            $query->select(
                'marketing_ajuan_limitkredit.*',
                'nama_pelanggan',
                'nama_salesman',
                'nama_cabang',
                'disposisi.id_pengirim',
                'roles.name as role',
                'status_ajuan'
            );
            $query->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
            $query->join('salesman', 'marketing_ajuan_limitkredit.kode_salesman', '=', 'salesman.kode_salesman');
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
            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->orderBy('marketing_ajuan_limitkredit.created_at', 'desc');
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
            $query->where('marketing_ajuan_limitkredit.status', $request->status);
        } else {
            if (!empty($request->status)) {
                $query->where('marketing_ajuan_limitkredit.status', $request->status);
            }
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_ajuan_limitkredit.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_ajuan_limitkredit.tanggal', [$start_date, $end_date]);
        }


        // dd($query->get());
        $ajuanlimit = $query->cursorPaginate(15);
        $ajuanlimit->appends(request()->all());
        $data['ajuanlimit'] = $ajuanlimit;
        $data['roles_approve_ajuanlimitkredit'] = $roles_approve_ajuanlimitkredit;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('marketing.ajuanlimit.index', $data);
    }

    public function create()
    {
        return view('marketing.ajuanlimit.create');
    }

    // public function edit($no_pengajuan)
    // {
    //     $no_pengajuan = Crypt::decrypt($no_pengajuan);
    //     return view('marketing.ajuanlimit.create');
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            $last_ajuan_limit = Ajuanlimitkredit::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $pelanggan->kode_cabang . '"')
                ->whereRaw('MID(no_pengajuan,7,2) = "' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();

            //dd($last_ajuan_limit);
            if ($last_ajuan_limit == null) {
                $last_no_pengajuan = 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2) . '00000';
            } else {
                $last_no_pengajuan = $last_ajuan_limit->no_pengajuan;
            }
            $no_pengajuan = buatkode($last_no_pengajuan, 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2), 5);

            //dd($no_pengajuan);
            $lokasi = explode(",", $request->lokasi);

            // dd($pelanggan);
            if (empty($pelanggan->foto) && empty($pelanggan->foto_owner) && toNumber($request->jumlah) > 15000000) {
                return Redirect::back()->with('message', 'Ajuan lebih dari Rp. 15.000.000, foto toko dan foto owner wajib diisi.');
            }
            // if (toNumber($request->jumlah) > 15000000 && empty($request->foto) || toNumber($request->jumlah) > 15000000 && empty($request->foto_owner)) {
            //     return Redirect::back()->with(messageSuccess('Ajuan Limit Melebihi 15jt Foto Toko dan Owner Wajib Ada'));
            // }
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
                'limit_sebelumnya' => !empty($pelanggan->limit_pelanggan) ? $pelanggan->limit_pelanggan : 0,
                'omset_sebelumnya' => !empty($pelanggan->omset_toko) ? $pelanggan->omset_toko : 0,
                'jumlah'  => toNumber($request->jumlah),
                'ljt' => $request->ljt,
                'topup_terakhir' => $request->topup_terakhir,
                'lama_topup' => 1,
                'jml_faktur' => $request->jml_faktur,
                'histori_transaksi' => $request->histori_transaksi,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'cara_pembayaran' => $request->cara_pembayaran,
                'kepemilikan' => $request->kepemilikan,
                'lama_langganan' => $request->lama_langganan,
                'lama_berjualan' => $request->lama_berjualan,
                'jaminan' => $request->jaminan,
                'omset_toko' => toNumber($request->omset_toko),
                'status' => 0,
                'skor' => $request->skor,
                'kode_salesman' => $pelanggan->kode_salesman,
                'id_user' => auth()->user()->id,
                'referensi' => !empty($request->referensi) ? implode(",", $request->referensi) : '',
                'ket_referensi' => $request->ket_referensi
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

            if ($smm != null) {
                $id_penerima = $smm->id;
            } else {
                $rsm = User::role('regional sales manager')->where('kode_regional', $regional->kode_regional)
                    ->where('status', 1)
                    ->first();
                if ($rsm != null) {
                    $id_penerima = $rsm->id;
                } else {
                    $gm = User::role('gm marketing')
                        ->where('status', 1)
                        ->first();
                    if ($gm != null) {
                        $id_penerima = $gm->id;
                    } else {
                        $id_penerima = 22;
                        //return Redirect::back()->with(messageError('User GM Marketing Tidak Ditemukan'));
                    }
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
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::select(
            'marketing_ajuan_limitkredit.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.nik',
            'pelanggan.alamat_pelanggan',
            'pelanggan.no_hp_pelanggan',
            'salesman.nama_salesman',
            'cabang.nama_cabang',
            'pelanggan.hari',
            'pelanggan.latitude',
            'pelanggan.longitude',
            'pelanggan.foto',
            'pelanggan.foto_owner',

        )
            ->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_ajuan_limitkredit.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanlimit'] = $ajuanlimit;
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');

        $data['disposisi'] = Disposisiajuanlimitkredit::select('marketing_ajuan_limitkredit_disposisi.*', 'users.name as username', 'roles.name as role')
            ->join('users', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_limitkredit_disposisi.created_at')
            ->get();

        $data['lastdisposisi'] = Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->orderBy('created_at', 'desc')->first();
        return view('marketing.ajuanlimit.approve', $data);
    }


    public function show($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajl = new Ajuanlimitkredit();
        $ajuanlimit = $ajl->getAjuanlimitkredit($no_pengajuan);
        $data['ajuanlimit'] = $ajuanlimit;
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');

        $data['disposisi'] = Disposisiajuanlimitkredit::select('marketing_ajuan_limitkredit_disposisi.*', 'users.name as username', 'roles.name as role')
            ->join('users', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_limitkredit_disposisi.created_at')
            ->get();
        return view('marketing.ajuanlimit.show', $data);
    }


    public function cetak($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajl = new Ajuanlimitkredit();
        $ajuanlimit = $ajl->getAjuanlimitkredit($no_pengajuan);
        $data['ajuanlimit'] = $ajuanlimit;
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');
        $data['histori_transaksi'] = config('pelanggan.histori_transaksi');

        $data['disposisi'] = Disposisiajuanlimitkredit::select('marketing_ajuan_limitkredit_disposisi.*', 'users.name as username', 'roles.name as role')
            ->join('users', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_limitkredit_disposisi.created_at')
            ->get();
        return view('marketing.ajuanlimit.cetak', $data);
    }


    public function updateLimitpelanggan($kode_pelanggan, $jumlah)
    {
        Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update([
            'limit_pelanggan' => $jumlah
        ]);
    }

    public function approvestore($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
            ->select('marketing_ajuan_limitkredit.*', 'kode_regional')
            ->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        DB::beginTransaction();
        try {


            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            if (isset($_POST['decline'])) {
                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_limitkredit_disposisi.status' => 2
                        ]);
                } else {
                    Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 2
                        ]);
                }

                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 2]);
                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Ditolak'));
            } else {

                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_limitkredit_disposisi.status' => 1
                        ]);
                } else {
                    Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 1
                        ]);
                }




                if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager' || auth()->user()->roles->pluck('name')[0] == "operation manager") {
                    if ($ajuanlimit->jumlah <= 5000000) {
                        Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->jumlah);
                        DB::commit();
                        return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                    } else {
                        $rsm = User::role('regional sales manager')
                            ->where('kode_regional', $ajuanlimit->kode_regional)
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
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->jumlah);
                        DB::commit();
                        return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                    } else {
                        $gm = User::role('gm marketing')
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
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->jumlah);
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
                            'uraian_analisa' => $request->uraian_analisa,
                            'status' => 0
                        ]);
                        DB::commit();
                        return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
                    }
                } else if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 1]);
                    $jumlah = !empty($ajuanlimit->jumlah_rekomendasi) ? $ajuanlimit->jumlah_rekomendasi : $ajuanlimit->jumlah;
                    //Update Limit Pelanggan
                    $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $jumlah);
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
        $ajuanlimit = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->first();


        DB::beginTransaction();
        try {
            if ($ajuanlimit->status == '2') {
                if (auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_limitkredit_disposisi.status' => 0
                        ]);
                } else {
                    Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)->update([
                            'status' => 0
                        ]);
                }


                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
            } else {
                if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager' || auth()->user()->roles->pluck('name')[0] == 'operation manager') {
                    if ($ajuanlimit->jumlah <= 5000000) {
                        Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->limit_sebelumnya);
                    }
                } else if (auth()->user()->roles->pluck('name')[0] == 'regional sales manager') {
                    if ($ajuanlimit->jumlah <= 10000000) {
                        Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->limit_sebelumnya);
                    }
                } else if (auth()->user()->roles->pluck('name')[0] == 'gm marketing') {
                    if ($ajuanlimit->jumlah <= 15000000) {
                        Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);
                        //Update Limit Pelanggan
                        $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->limit_sebelumnya);
                    }
                } else if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 0]);

                    //Update Limit Pelanggan
                    $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->limit_sebelumnya);
                }
                if (auth()->user()->roles->pluck('name')[0] != 'operation manager') {

                    if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager') {
                        Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
                            ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('no_pengajuan', $no_pengajuan)
                            ->where('roles.name', 'regional sales manager')
                            ->delete();
                    } else {
                        Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                            ->where('id_pengirim', auth()->user()->id)
                            ->whereRaw('id_pengirim != id_penerima')
                            ->delete();
                    }

                    Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
                        ->where('id_penerima', auth()->user()->id)
                        ->update(['status' => 0]);
                } else {
                    $disposisi_om = Disposisiajuanlimitkredit::where('id_pengirim', auth()->user()->id)->where('no_pengajuan', $no_pengajuan);
                    $cek_pengirim_om = $disposisi_om->count();
                    if ($cek_pengirim_om > 1) {
                        $last_pengirim_om = $disposisi_om->orderBy('created_at', 'desc')->first();
                        Disposisiajuanlimitkredit::where('kode_disposisi', $last_pengirim_om->kode_disposisi)->delete();
                        Disposisiajuanlimitkredit::leftjoin('users as pengirim', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'pengirim.id')
                            ->leftjoin('model_has_roles', 'pengirim.id', '=', 'model_has_roles.model_id')
                            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('no_pengajuan', $no_pengajuan)
                            ->where('roles.name', 'sales marketing manager')
                            ->delete();
                    }


                    Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
                        ->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('no_pengajuan', $no_pengajuan)
                        ->where('roles.name', 'sales marketing manager')
                        ->update([
                            'marketing_ajuan_limitkredit_disposisi.status' => 0
                        ]);

                    Disposisiajuanlimitkredit::leftjoin('users as penerima', 'marketing_ajuan_limitkredit_disposisi.id_penerima', '=', 'penerima.id')
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


    public function adjust($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanlimit'] = $ajuanlimit;

        return view('marketing.ajuanlimit.adjust', $data);
    }

    public function adjuststore($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->first();
        DB::beginTransaction();
        try {
            Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                'jumlah_rekomendasi' => toNumber($request->jumlah_rekomendasi),
                'ljt_rekomendasi' => $request->ljt
            ]);
            if ($ajuanlimit->status == '1') {
                $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, toNumber($request->jumlah_rekomendasi));
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Penyesuaian Berhasil Di Simpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $targetkomisi = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->first();
        $tanggal = $targetkomisi->tahun . "-" . $targetkomisi->bulan . "-01";
        try {
            Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
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
