<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\TicketApprovalConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class TicketApprovalConfigController extends Controller
{
    public function index()
    {
        $config = TicketApprovalConfig::select(
            'ticket_approval_configs.*',
            'nama_dept',
            'nama_cabang'
        )
            ->leftJoin('hrd_departemen', 'ticket_approval_configs.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('cabang', 'ticket_approval_configs.kode_cabang', '=', 'cabang.kode_cabang')
            ->get();
        $data['config'] = $config;
        return view('utilities.ticket.config.index', $data);
    }

    public function create()
    {
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('utilities.ticket.config.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            TicketApprovalConfig::create([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'roles' => $request->roles
            ]);
            return redirect()->route('ticketconfig.index')->with(messageSuccess('Config Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function edit(string $id)
    {
        $data['config'] = TicketApprovalConfig::find($id);
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('utilities.ticket.config.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            TicketApprovalConfig::find($id)->update([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'roles' => $request->roles
            ]);
            return redirect()->route('ticketconfig.index')->with(messageSuccess('Config Berhasil Diupdate'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy(string $id)
    {
        try {
            TicketApprovalConfig::find($id)->delete();
            return redirect()->route('ticketconfig.index')->with(messageSuccess('Config Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
