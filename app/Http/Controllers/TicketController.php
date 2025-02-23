<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();
        $query->select('tickets.*', 'users.name', 'admin.name as admin', 'users.kode_cabang');
        $query->join('users', 'tickets.id_user', '=', 'users.id');
        $query->leftJoin('users as admin', 'tickets.id_admin', '=', 'admin.id');
        $query->orderBy('status', 'desc');
        $query->orderBy('kode_pengajuan', 'asc');
        $ticket = $query->get();
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
                'id_user' => auth()->user()->id
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
}
