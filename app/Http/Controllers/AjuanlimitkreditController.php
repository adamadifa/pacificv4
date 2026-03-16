<?php

namespace App\Http\Controllers;

use App\Models\Ajuanlimitkredit;
use App\Models\AjuanlimitkreditConfig;
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

        $ajl = new Ajuanlimitkredit();
        $query = $ajl->getAjuanlimitkredit(request: $request);

        $ajuanlimit = $query->paginate(15);
        $ajuanlimit->appends(request()->all());

        $all_configs = AjuanlimitkreditConfig::orderBy('min_limit', 'asc')->get();
        $roles_filter = [];
        foreach ($all_configs as $c) {
            $roles_filter = array_merge($roles_filter, $c->roles);
        }
        $roles_filter = array_unique($roles_filter);

        if (empty($roles_filter)) {
            $roles_filter = config('global.roles_aprove_ajuanlimitkredit');
        }

        $data['ajuanlimit'] = $ajuanlimit;
        $data['all_configs'] = $all_configs;
        $data['roles_approve_ajuanlimitkredit'] = $roles_filter;
        $role = auth()->user()->getRoleNames()->first();
        if ($role == 'operation manager') {
            $role = 'sales marketing manager';
        }
        $data['level_user'] = $role;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        return view('marketing.ajuanlimit.index', $data);
    }


    public function create()
    {
        return view('marketing.ajuanlimit.create');
    }

    public function edit($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanlimit = Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanlimit'] = $ajuanlimit;

        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $data['level_user'] = $role;
        $data['roles_approve'] = [];
        if ($role == 'super admin') {
            $config = \App\Models\AjuanlimitkreditConfig::where('min_limit', '<=', $ajuanlimit->jumlah)
                ->where('max_limit', '>=', $ajuanlimit->jumlah)
                ->first();
            $data['roles_approve'] = $config ? $config->roles : ['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur'];
        }

        return view('marketing.ajuanlimit.edit', $data);
    }

    public function update($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        try {
            Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                'posisi_ajuan' => $request->posisi_ajuan
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

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

            $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
            $jumlah_ajuan = toNumber($request->jumlah);
            $config = AjuanlimitkreditConfig::where('min_limit', '<=', $jumlah_ajuan)
                ->where('max_limit', '>=', $jumlah_ajuan)
                ->first();

            $roles = $config ? $config->roles : ['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur'];


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
                'ket_referensi' => $request->ket_referensi,
                'posisi_ajuan' => $roles[0] ?? null
            ]);


            //Disposisi (Optional: keep for history or remove if fully replaced. User said "abaikan", but usually history is good. However, if following "lembur" exactly, it might be removed.)
            // For now, let's keep the history logic if needed but primarily use posisi_ajuan in index.
            // Actually, if I follow "lembur" exactly, I should remove the disposisi creation here if it's no longer the source of truth.
            
            /* 
            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiajuanlimitkredit::create([
                'kode_disposisi' => $kode_disposisi,
                'no_pengajuan' => $no_pengajuan,
                'id_pengirim' => auth()->user()->id,
                'id_penerima' => 22, // Fallback
                'uraian_analisa' => $request->uraian_analisa,
                'status' => 0
            ]);
            */

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

        // Added Range-Based Logic
        $jumlah = $ajuanlimit->jumlah;
        $config = AjuanlimitkreditConfig::where('min_limit', '<=', $jumlah)
            ->where('max_limit', '>=', $jumlah)
            ->first();
        $roles = $config ? $config->roles : ['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur'];
        $current_role = auth()->user()->roles->pluck('name')[0];
        if ($current_role == 'operation manager') $current_role = 'sales marketing manager';
        $current_index = array_search($current_role, $roles);

        $next_role = null;
        if ($current_index !== false && isset($roles[$current_index + 1])) {
            $next_role = $roles[$current_index + 1];
        }

        $data['config'] = $config;
        $data['roles'] = $roles;
        $data['next_role'] = $next_role;
        $data['is_final_approver'] = ($current_index !== false && $current_index === count($roles) - 1) || $current_role == 'direktur';

        $data['lastdisposisi'] = Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)
            ->orderBy('created_at', 'desc')
            ->first();

        $data['disposisi'] = Disposisiajuanlimitkredit::select('marketing_ajuan_limitkredit_disposisi.*', 'users.name as username', 'roles.name as role')
            ->join('users', 'marketing_ajuan_limitkredit_disposisi.id_pengirim', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('no_pengajuan', $no_pengajuan)
            ->orderBy('marketing_ajuan_limitkredit_disposisi.created_at')
            ->get();
        return view('marketing.ajuanlimit.approve', $data);
    }


    public function show($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajl = new Ajuanlimitkredit();
        $ajuanlimit = $ajl->getAjuanlimitkredit($no_pengajuan)->first();
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
        $ajuanlimit = $ajl->getAjuanlimitkredit($no_pengajuan)->first();
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


            $user = auth()->user();
            $user_roles = $user->roles->pluck('name')->toArray();

            if (isset($_POST['decline'])) {
                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update(['status' => 2]);

                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPLK" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                Disposisiajuanlimitkredit::create([
                    'kode_disposisi' => $kode_disposisi,
                    'no_pengajuan' => $no_pengajuan,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => 0,
                    'uraian_analisa' => $request->uraian_analisa,
                    'status' => 2
                ]);

                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Ditolak'));
            } else {

                if (in_array('operation manager', $user_roles)) {
                    $current_role = 'sales marketing manager';
                } else if (in_array('super admin', $user_roles)) {
                    $current_role = !empty($ajuanlimit->posisi_ajuan) ? $ajuanlimit->posisi_ajuan : 'sales marketing manager';
                } else {
                    $current_role = $user_roles[0];
                }

                $jumlah = $ajuanlimit->jumlah;
                $config = AjuanlimitkreditConfig::where('min_limit', '<=', $jumlah)
                    ->where('max_limit', '>=', $jumlah)
                    ->first();

                $roles = $config ? $config->roles : ['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur'];
                $current_index = array_search($current_role, $roles);

                // Final Approval if it's the last role in the sequence
                $is_approved = ($current_index !== false && $current_index === count($roles) - 1);

                // Special case for Direktur (always final approval even if not in sequence)
                if (in_array('direktur', $user_roles)) {
                    $is_approved = true;
                }

                if ($is_approved) {
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                        'status' => 1,
                        'posisi_ajuan' => $current_role
                    ]);
                    $jumlah = !empty($ajuanlimit->jumlah_rekomendasi) ? $ajuanlimit->jumlah_rekomendasi : $ajuanlimit->jumlah;
                    $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $jumlah);
                    
                    $tanggal_hariini = date('Y-m-d');
                    $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                        ->orderBy('kode_disposisi', 'desc')
                        ->first();
                    $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                    $format = "DPLK" . date('Ymd');
                    $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                    Disposisiajuanlimitkredit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => 0,
                        'uraian_analisa' => $request->uraian_analisa,
                        'status' => 1
                    ]);

                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                } else {
                    $next_role = null;
                    if ($current_index !== false && isset($roles[$current_index + 1])) {
                        $next_role = $roles[$current_index + 1];
                    } else if (!empty($ajuanlimit->posisi_ajuan)) {
                        $next_role = $ajuanlimit->posisi_ajuan;
                    } else {
                        $next_role = $roles[0];
                    }

                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                        'posisi_ajuan' => $next_role
                    ]);

                    $tanggal_hariini = date('Y-m-d');
                    $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                        ->orderBy('kode_disposisi', 'desc')
                        ->first();
                    $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                    $format = "DPLK" . date('Ymd');
                    $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                    Disposisiajuanlimitkredit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'no_pengajuan' => $no_pengajuan,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => 0,
                        'uraian_analisa' => $request->uraian_analisa,
                        'status' => 1
                    ]);

                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Diteruskan'));
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
            $user = User::findorfail(auth()->user()->id);
            $role = $user->getRoleNames()->first();

            $jumlah = $ajuanlimit->jumlah;
            $config = AjuanlimitkreditConfig::where('min_limit', '<=', $jumlah)
                ->where('max_limit', '>=', $jumlah)
                ->first();

            $roles = $config ? $config->roles : ['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur'];

            if ($ajuanlimit->status == '2') {
                // If rejected, reset to pending at the first role
                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                    'status' => '0',
                    'posisi_ajuan' => $roles[0]
                ]);
            } else if ($ajuanlimit->status == '1') {
                // If approved, reset to pending at the last role
                Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                    'status' => '0',
                    'posisi_ajuan' => end($roles)
                ]);
                $this->updateLimitpelanggan($ajuanlimit->kode_pelanggan, $ajuanlimit->limit_sebelumnya);
            } else {
                // If pending, go back to previous role
                $current_index = array_search($ajuanlimit->posisi_ajuan, $roles);
                if ($current_index !== false && $current_index > 0) {
                    $prev_role = $roles[$current_index - 1];
                    Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->update([
                        'posisi_ajuan' => $prev_role
                    ]);
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
        DB::beginTransaction();
        try {
            Disposisiajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->delete();
            Ajuanlimitkredit::where('no_pengajuan', $no_pengajuan)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
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
