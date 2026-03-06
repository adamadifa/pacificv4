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
        $user = User::findOrFail(auth()->user()->id);
        $user_role = $user->roles->pluck('name')[0];

        $pf = new Pengajuanfaktur();
        $query = $pf->getPengajuanfaktur(request: $request);
        $ajuanfaktur = $query->paginate(15);
        $ajuanfaktur->appends(request()->all());
        $data['ajuanfaktur'] = $ajuanfaktur;
        $config = \App\Models\AjuanfakturkreditConfig::first();
        $roles_approve_ajuanfakturkredit = $config ? (is_string($config->roles) ? json_decode($config->roles) : $config->roles) : [];
        $data['roles_approve_ajuanfakturkredit'] = $roles_approve_ajuanfakturkredit;

        $data['level_user'] = $user_role;

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
                // Tentukan Posisi Ajuan awal
                $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
                $kode_regional = $regional ? $regional->kode_regional : '';

                $config = \App\Models\AjuanfakturkreditConfig::first();
                if (!$config) {
                    return Redirect::back()->with(messageError('Konfigurasi Approval Belum Diatur'));
                }
                $roles = is_string($config->roles) ? json_decode($config->roles) : $config->roles;
                
                $id_penerima = null;
                $posisi_ajuan = null;

                foreach ($roles as $role) {
                    $id_penerima = $this->getPenerimaByRole($role, $pelanggan->kode_cabang, $kode_regional);
                    if ($id_penerima != null) {
                        $posisi_ajuan = $role;
                        break;
                    }
                }

                if ($posisi_ajuan == null) {
                    return Redirect::back()->with(messageError('Tidak ada user penerima approval awal yang ditemukan'));
                }

                Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update([
                    'posisi_ajuan' => $posisi_ajuan
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
            if (isset($_POST['decline'])) {
                Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 2]);
                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Ditolak'));
            } else {
                $config = \App\Models\AjuanfakturkreditConfig::first();
                if (!$config) {
                    return Redirect::back()->with(messageError('Konfigurasi Approval Belum Diatur'));
                }
                $roles = is_string($config->roles) ? json_decode($config->roles) : $config->roles;
                
                $current_role = auth()->user()->roles->pluck('name')[0];
                if ($current_role == "operation manager") {
                    $current_role = "sales marketing manager";
                }
                $current_index = array_search($current_role, $roles);
                $is_last_role = ($current_index !== false && $current_index == count($roles) - 1);

                if ($is_last_role) {
                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update([
                        'status' => 1,
                        // Ensure it stays at current position to mark completion boundary accurately
                        'posisi_ajuan' => $current_role
                    ]);
                    DB::commit();
                    return Redirect::back()->with(messageSuccess('Data Ajuan Berhasil Disetujui'));
                } else {
                    $next_role = null;
                    if ($current_index !== false && $current_index + 1 < count($roles)) {
                        $next_role = $roles[$current_index + 1];
                    }

                    if ($next_role == null) {
                        DB::rollBack();
                        return Redirect::back()->with(messageError('Konfigurasi Approval berikutnya tidak valid'));
                    }

                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update([
                        'posisi_ajuan' => $next_role
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
        $ajuanlimit = Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->first();

        DB::beginTransaction();
        try {
            $config = \App\Models\AjuanfakturkreditConfig::first();
            if (!$config) {
                return Redirect::back()->with(messageError('Konfigurasi Approval Belum Diatur'));
            }
            $roles = is_string($config->roles) ? json_decode($config->roles) : $config->roles;
            
            $current_role = auth()->user()->roles->pluck('name')[0];
            if ($current_role == "operation manager") {
                $current_role = "sales marketing manager";
            }
            $current_index = array_search($current_role, $roles);


            if ($ajuanlimit->status == '2') {
                // Jika ditolak, kembalikan ke status 0 (menunggu persetujuan saat ini)
                Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 0, 'posisi_ajuan' => $current_role]);
            } else {
                
                $is_last_role = ($current_index !== false && $current_index == count($roles) - 1);

                if ($is_last_role && $ajuanlimit->status == '1') {
                    // Jika role terakhir membatalkan persetujuan akhir
                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update(['status' => 0, 'posisi_ajuan' => $current_role]);
                } else {
                     // Jika role bukan terakhir membatalkan penerusan ke role selanjutnya
                    Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update([
                        'posisi_ajuan' => $current_role,
                        'status' => 0
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

    public function edit($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanfaktur = Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanfaktur'] = $ajuanfaktur;

        $config = \App\Models\AjuanfakturkreditConfig::first();
        $roles_approve = $config ? (is_string($config->roles) ? json_decode($config->roles) : $config->roles) : [];
        $data['roles_approve'] = $roles_approve;

        return view('marketing.ajuanfaktur.edit', $data);
    }

    public function update($no_pengajuan, Request $request)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        DB::beginTransaction();
        try {
            $posisi_ajuan = empty($request->posisi_ajuan) ? null : $request->posisi_ajuan;
            Pengajuanfaktur::where('no_pengajuan', $no_pengajuan)->update([
                'posisi_ajuan' => $posisi_ajuan
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Posisi Ajuan Berhasil Diubah'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    private function getPenerimaByRole($role_name, $kode_cabang, $kode_regional)
    {
        if ($role_name == 'sales marketing manager') {
            $user = User::role('sales marketing manager')
                ->where('kode_cabang', $kode_cabang)
                ->where('status', 1)
                ->first();
        } else if ($role_name == 'regional sales manager') {
            $user = User::role('regional sales manager')
                ->where('kode_regional', $kode_regional)
                ->where('status', 1)
                ->first();
        } else {
            $user = User::role($role_name)
                ->where('status', 1)
                ->first();
        }

        return $user ? $user->id : null;
    }
}
