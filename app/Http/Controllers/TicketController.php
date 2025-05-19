<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Ticketmessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ticket::query();
        $query->select('tickets.*', 'users.name', 'admin.name as admin', 'users.kode_cabang');
        $query->join('users', 'tickets.id_user', '=', 'users.id');
        $query->leftJoin('users as admin', 'tickets.id_admin', '=', 'admin.id');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $query->where('users.kode_cabang', auth()->user()->kode_cabang);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('users.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->status_search)) {
            if ($request->status_search == "pending") {
                $query->where('tickets.status', 0);
            } else {
                $query->where('tickets.status', 1);
            }
        }
        $query->orderBy('status');
        $query->orderBy('kode_pengajuan', 'asc');
        $ticket = $query->paginate(10);
        $ticket->appends($request->all());

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['ticket'] = $ticket;
        return view('utilities.ticket.index', $data);
    }

    public function create()
    {
        return view('utilities.ticket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
        ]);

        $bulan = date("m", strtotime($request->tanggal));
        $tahun = substr(date("Y", strtotime($request->tanggal)), 2, 2);
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $status = 0;
        $ticket = Ticket::whereBetween('tanggal', [$dari, $sampai])->orderBy('kode_pengajuan', 'desc')->first();
        $lastkode_pengajuan = $ticket != null ? $ticket->kode_pengajuan : '';
        $kode_pengajuan = buatkode($lastkode_pengajuan, "MT" . $bulan . $tahun, 4);

        try {
            Ticket::create([
                'kode_pengajuan' => $kode_pengajuan,
                'tanggal' => $request->tanggal,
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
        $ticket = Ticket::where('kode_pengajuan', $kode_pengajuan)->first();
        return view('utilities.ticket.edit', compact('ticket'));
    }

    public function update($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',

        ]);

        try {
            Ticket::where('kode_pengajuan', $kode_pengajuan)->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
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
            Ticket::where('kode_pengajuan', $kode_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }


    public function approve($kode_pengajuan)
    {
        $ticket = Ticket::where('kode_pengajuan', $kode_pengajuan)
            ->select('tickets.*', 'users.name')
            ->join('users', 'tickets.id_user', '=', 'users.id')
            ->first();
        return view('utilities.ticket.approve', compact('ticket'));
    }

    public function storeapprove($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $user = User::where('id', auth()->user()->id)->first();
        $status = 0;
        if ($user->hasRole(['gm administrasi', 'regional operation manager'])) {
            $field = 'gm';
            if (isset($_POST['decline'])) {
                $status = 2;
            }
        }

        if ($user->hasRole('super admin')) {
            if (isset($_POST['decline'])) {
                $status = 2;
            } else {
                $status = 1;
            }
        }

        try {
            if ($user->hasRole('super admin')) {
                Ticket::where('kode_pengajuan', $kode_pengajuan)->update([
                    'status' => $status,
                    'id_admin' => auth()->user()->id,
                    'tanggal_selesai' => date('Y-m-d')
                ]);
            } else {
                Ticket::where('kode_pengajuan', $kode_pengajuan)->update([
                    'status' => $status,
                    $field => auth()->user()->id
                ]);
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function message($kode_pengajuan)
    {
        $ticketmessage = Ticketmessage::where('kode_pengajuan', $kode_pengajuan)
        ->select('tickets_messages.*', 'users.name')
        ->join('users', 'tickets_messages.id_user', '=', 'users.id')
        ->get();
        $data['ticketmessage'] = $ticketmessage;
        $data['kode_pengajuan'] = $kode_pengajuan;
        return view('utilities.ticket.message', $data);
    }

    public function storemessage($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $request->validate([
            'message' => 'required',    
        ]);
        try {
            Ticketmessage::create([
                'kode_pengajuan' => $kode_pengajuan,
                'message' => $request->message,
                'id_user' => auth()->user()->id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
