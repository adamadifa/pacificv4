<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function index()
    {
        return view('utilities.ticket.index');
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
}
