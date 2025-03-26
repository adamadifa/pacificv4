<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Dpb;
use App\Models\Historibayarpenjualan;
use App\Models\Mutasigudangcabang;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Ticket;
use App\Models\Ticketupdatedata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TicketupdateController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ticketupdatedata::query();
        $query->select('tickets_update_data.*', 'users.name', 'approval.name as approval', 'users.kode_cabang');
        $query->join('users', 'tickets_update_data.id_user', '=', 'users.id');
        $query->leftJoin('users as approval', 'tickets_update_data.id_approval', '=', 'approval.id');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $query->where('users.kode_cabang', auth()->user()->kode_cabang);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('users.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->status_search)) {
            if ($request->status_search == "pending") {
                $query->where('tickets_update_data.status', 0);
            } else {
                $query->where('tickets_update_data.status', 1);
            }
        }
        $query->orderBy('status');
        $query->orderBy('kode_pengajuan', 'asc');
        $ticket = $query->paginate(10);
        $ticket->appends($request->all());

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['ticket'] = $ticket;
        return view('utilities.tickets_update.index', $data);
    }

    public function create()
    {
        return view('utilities.tickets_update.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
            'no_bukti' => 'required',
        ]);

        $bulan = date("m", strtotime($request->tanggal));
        $tahun = substr(date("Y", strtotime($request->tanggal)), 2, 2);
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $status = 0;
        $ticket = Ticket::whereBetween('tanggal', [$dari, $sampai])->orderBy('kode_pengajuan', 'desc')->first();
        $lastkode_pengajuan = $ticket != null ? $ticket->kode_pengajuan : '';
        $kode_pengajuan = buatkode($lastkode_pengajuan, "PD" . $bulan . $tahun, 4);


        $kategoriOptions = [
            '1' => 'Penjualan',
            '2' => 'Pembayaran',
            '3' => 'Retur',
            '4' => 'DPB',
            '5' => 'Mutasi Persediaan',
        ];

        if ($request->kategori == 1) {
            $cek = Penjualan::where('no_faktur', $request->no_bukti)->count();
        } else if ($request->kategori == 2) {
            $cek = Historibayarpenjualan::where('no_bukti', $request->no_bukti)->count();
        } else if ($request->kategori == 3) {
            $cek = Retur::where('no_retur', $request->no_bukti)->count();
        } else if ($request->kategori == 4) {
            $cek = Dpb::where('no_dpb', $request->no_bukti)->count();
        } else if ($request->kategori == 5) {
            $cek = Mutasigudangcabang::where('no_mutasi', $request->no_bukti)->count();
        }

        if ($cek == 0) {
            return Redirect::back()->with(messageError('No Bukti Tidak Ditemukan'));
        }
        try {
            Ticketupdatedata::create([
                'kode_pengajuan' => $kode_pengajuan,
                'tanggal' => $request->tanggal,
                'kategori' => $request->kategori,
                'no_bukti' => $request->no_bukti,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'id_user' => auth()->user()->id,
                'link' => $request->link
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function edit($kode_pengajuan)
    {
        $ticket = Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)->first();
        return view('utilities.tickets_update.edit', compact('ticket'));
    }

    public function update($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'no_bukti' => 'required',
            'kategori' => 'required',

        ]);

        try {
            Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'kategori' => $request->kategori,
                'no_bukti' => $request->no_bukti,
                'link' => $request->link
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function destroy($kode_pengajuan)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        try {
            Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function approve($kode_pengajuan)
    {
        $ticket = Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)
            ->select('tickets_update_data.*', 'users.name')
            ->join('users', 'tickets_update_data.id_user', '=', 'users.id')
            ->first();
        return view('utilities.tickets_update.approve', compact('ticket'));
    }

    public function storeapprove($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $user = User::where('id', auth()->user()->id)->first();
        $status = 1;
        if ($user->hasRole(['gm administrasi', 'regional operation manager', 'super admin'])) {
            $field = 'gm';
            if (isset($_POST['decline'])) {
                $status = 2;
            }
        }



        try {
            Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)->update([
                'status' => $status,
                'gm' => auth()->user()->id,
                'id_approval' => auth()->user()->id
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function cancel($kode_pengajuan)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        try {
            Ticketupdatedata::where('kode_pengajuan', $kode_pengajuan)->update([
                'status' => 0,
                'gm' => null,
                'id_approval' => null
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }
}
